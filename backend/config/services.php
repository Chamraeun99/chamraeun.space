<?php

return [

    'supabase' => [
        /** Project URL, e.g. https://YOUR_REF.supabase.co — used by SupabaseStorage (blog/projects/media/profile images). */
        'url' => env('SUPABASE_URL'),
        /** Service role JWT for server uploads (Dashboard → Settings → API). */
        'secret_key' => env('SUPABASE_SECRET_KEY'),
        /** Public anon JWT — send as `apikey` on Storage REST (avoids gateway “signature verification failed” vs using service_role for both). */
        'anon_key' => env('SUPABASE_ANON_KEY'),
        /** Storage bucket (Dashboard → Storage). Set SUPABASE_BUCKET in .env / Render to override. */
        'bucket' => env('SUPABASE_BUCKET', 'chamraeun.space-picture'),
        /**
         * Storage REST gateway headers:
         * - anon_authorization — apikey=anon JWT, Authorization=Bearer service_role (recommended)
         * - service_both — both headers use service_role JWT (fallback if anon path fails)
         */
        'storage_gateway' => env('SUPABASE_STORAGE_GATEWAY', 'anon_authorization'),
    ],

    'turnstile' => [
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

];
