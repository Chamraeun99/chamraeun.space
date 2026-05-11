<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class SupabaseStorage
{
    protected string $url;

    protected string $key;

    protected string $bucket;

    /** Optional; REST gateway often expects `apikey` + `Authorization` (use service role server-side). */
    protected ?string $anonKey;

    /** Trim whitespace and strip one pair of wrapping quotes (common copy/paste from Render/dashboard). */
    protected static function normalizeEnvString(?string $value): string
    {
        $v = trim((string) $value);
        if ($v === '') {
            return '';
        }
        if (
            (str_starts_with($v, '"') && str_ends_with($v, '"'))
            || (str_starts_with($v, "'") && str_ends_with($v, "'"))
        ) {
            $v = substr($v, 1, -1);
        }

        return trim($v);
    }

    public function __construct()
    {
        $this->url = rtrim(self::normalizeEnvString(config('services.supabase.url')), '/');
        $this->key = self::normalizeEnvString(config('services.supabase.secret_key'));
        $this->bucket = self::normalizeEnvString(config('services.supabase.bucket'));
        $anon = config('services.supabase.anon_key');
        $anonTrimmed = is_string($anon) ? self::normalizeEnvString($anon) : '';
        $this->anonKey = $anonTrimmed !== '' ? $anonTrimmed : null;
    }

    /**
     * Headers Supabase Storage REST expects (see config services.supabase.storage_gateway).
     */
    protected function storageHeaders(array $extra = []): array
    {
        $gateway = config('services.supabase.storage_gateway', 'service_both');

        $serviceJwt = $this->key;

        // service_both: standard for server-side uploads. anon_authorization only when anon + service JWT match same project.
        if ($gateway === 'service_both') {
            $apikey = $serviceJwt;
            $bearer = $serviceJwt;
        } else {
            $apikey = $this->anonKey ?? $serviceJwt;
            $bearer = $serviceJwt;
        }

        return array_merge([
            'Authorization' => 'Bearer ' . $bearer,
            'apikey' => $apikey,
        ], $extra);
    }

    /**
     * Upload a file to Supabase Storage.
     */
    public function upload(UploadedFile $file, string $folder = ''): string|false
    {
        $ext = strtolower((string) $file->getClientOriginalExtension());
        if ($ext === '') {
            $ext = strtolower((string) ($file->guessExtension() ?: 'bin'));
        }

        $filename = $folder . '/' . uniqid() . '_' . time() . '.' . $ext;
        $filename = ltrim(str_replace('\\', '/', $filename), '/');
        $encodedKey = implode('/', array_map('rawurlencode', explode('/', $filename)));

        $mime = $file->getMimeType() ?: 'application/octet-stream';
        $path = $file->getRealPath() ?: $file->getPathname();
        $binary = file_get_contents($path);
        if ($binary === false) {
            throw new \RuntimeException('Could not read uploaded file.');
        }

        $response = Http::timeout(120)
            ->withHeaders($this->storageHeaders([
                'Content-Type' => $mime,
                'x-upsert' => 'true',
            ]))
            ->withBody($binary, $mime)
            ->post("{$this->url}/storage/v1/object/{$this->bucket}/{$encodedKey}");

        if ($response->successful()) {
            return $filename;
        }

        $gateway = config('services.supabase.storage_gateway') ?? 'service_both';
        $gatewayHint = $gateway === 'anon_authorization'
            ? 'SUPABASE_ANON_KEY and SUPABASE_SECRET_KEY must be JWTs from the same Supabase project as SUPABASE_URL, or set SUPABASE_STORAGE_GATEWAY=service_both and use only service_role in SUPABASE_SECRET_KEY.'
            : 'Use SUPABASE_SECRET_KEY = full service_role JWT from Dashboard → Settings → API (same project as SUPABASE_URL). Trim quotes/newlines. Legacy keys look like eyJ... ; ensure you did not paste the anon key by mistake.';

        throw new \RuntimeException(
            'Supabase storage upload failed (HTTP '
            . $response->status() . "). {$gatewayHint} Trim any quotes/newlines from env vars. Bucket must exist ("
            . $this->bucket . '). Response: '
            . $response->body()
        );
    }

    /**
     * Delete a file from Supabase Storage.
     */
    public function delete(string $path): bool
    {
        $path = str_replace('\\', '/', ltrim($path, '/'));
        $path = implode('/', array_map('rawurlencode', explode('/', $path)));

        $response = Http::timeout(30)
            ->withHeaders($this->storageHeaders([
                'Content-Type' => 'application/json',
            ]))
            ->delete("{$this->url}/storage/v1/object/{$this->bucket}/{$path}");

        return $response->successful();
    }

    /**
     * Returns true when Supabase is configured with real credentials.
     */
    public function isConfigured(): bool
    {
        if (empty($this->url) || empty($this->key)) {
            return false;
        }

        $urlLower = strtolower($this->url);
        $keyLower = strtolower($this->key);
        $host = strtolower((string) parse_url($this->url, PHP_URL_HOST));

        if (
            str_contains($urlLower, 'your_project_id') ||
            str_contains($host, 'your_project_id') ||
            $keyLower === 'your-anon-key' ||
            $keyLower === 'your-service-role-key'
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get the public URL for a file, or null when Supabase is not configured.
     */
    public function url(string $path): ?string
    {
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        if (!$this->isConfigured()) {
            return null;
        }
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";
    }

    /**
     * Test the connection by uploading and deleting a test file.
     */
    public function test(): array
    {
        $result = [
            'url' => $this->url,
            'bucket' => $this->bucket,
            'key_set' => !empty($this->key),
        ];

        try {
            $testPath = '_test_' . time() . '.txt';
            $response = Http::timeout(30)
                ->withHeaders($this->storageHeaders([
                    'Content-Type' => 'text/plain',
                    'x-upsert' => 'true',
                ]))
                ->withBody('test', 'text/plain')
                ->post("{$this->url}/storage/v1/object/{$this->bucket}/{$testPath}");

            if ($response->successful()) {
                $this->delete($testPath);
                $result['upload_test'] = 'SUCCESS';
            } else {
                $result['upload_test'] = 'FAILED';
                $result['error'] = $response->body();
                $result['status_code'] = $response->status();
            }
        } catch (\Exception $e) {
            $result['upload_test'] = 'FAILED';
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}
