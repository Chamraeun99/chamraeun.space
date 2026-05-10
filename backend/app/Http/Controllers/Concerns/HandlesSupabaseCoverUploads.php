<?php

namespace App\Http\Controllers\Concerns;

use App\Services\SupabaseStorage;
use Illuminate\Http\JsonResponse;

trait HandlesSupabaseCoverUploads
{
    protected function validateCoverStorageOrFail(string $provider): ?JsonResponse
    {
        if ($provider !== 'supabase') {
            return null;
        }

        if (! app(SupabaseStorage::class)->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Supabase storage is not configured. Set SUPABASE_URL and SUPABASE_SECRET_KEY (service role) plus SUPABASE_BUCKET; create the bucket in Supabase Dashboard → Storage (public bucket/object access if URLs are embedded in the site).',
            ], 422);
        }

        $gateway = config('services.supabase.storage_gateway', 'anon_authorization');
        $anon = config('services.supabase.anon_key');
        $anonTrimmed = is_string($anon) ? trim($anon) : '';
        if ($gateway !== 'service_both' && $anonTrimmed === '') {
            return response()->json([
                'success' => false,
                'message' => 'SUPABASE_ANON_KEY is missing. Set both keys from Supabase → Settings → API (anon “public”, service_role secret). Paste the full JWT (three dot-separated segments). Alternatively set SUPABASE_STORAGE_GATEWAY=service_both to use service_role only—less reliable on some hosts.',
            ], 422);
        }

        return null;
    }
}
