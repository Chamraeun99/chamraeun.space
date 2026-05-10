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

        return null;
    }
}
