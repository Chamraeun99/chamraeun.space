<?php

return [

    'supabase' => [
        /** Project URL, e.g. https://YOUR_REF.supabase.co — used by SupabaseStorage (blog/projects/media/profile images). */
        'url' => env('SUPABASE_URL'),
        /** Service role JWT for server uploads (Dashboard → Settings → API → service_role). */
        'secret_key' => env('SUPABASE_SECRET_KEY') ?: env('SUPABASE_SERVICE_ROLE_KEY'),
        /** Public anon JWT — used when storage_gateway is anon_authorization. */
        'anon_key' => env('SUPABASE_ANON_KEY'),
        /** Storage bucket (Dashboard → Storage). Set SUPABASE_BUCKET in .env / Render to override. */
        'bucket' => env('SUPABASE_BUCKET', 'chamraeun.space-picture'),
        /**
         * Storage REST headers:
         * - service_both — apikey + Authorization both use service_role (default; best for Laravel server uploads).
         * - anon_authorization — apikey=anon, Authorization=Bearer service_role (only if both keys are from the exact same project).
         */
        'storage_gateway' => env('SUPABASE_STORAGE_GATEWAY', 'service_both'),
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
