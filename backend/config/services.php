<?php

return [

    'supabase' => [
        /** Project URL, e.g. https://YOUR_REF.supabase.co — used by SupabaseStorage (blog/projects/media/profile images). */
        'url' => env('SUPABASE_URL'),
        /** Service role JWT for server uploads (Dashboard → Settings → API). */
        'secret_key' => env('SUPABASE_SECRET_KEY'),
        /** Optional anon key for REST `apikey` header; if empty, service key is reused. */
        'anon_key' => env('SUPABASE_ANON_KEY'),
        /** Storage bucket (Dashboard → Storage). Set SUPABASE_BUCKET in .env / Render to override. */
        'bucket' => env('SUPABASE_BUCKET', 'chamraeun.space-picture'),
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
    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'kalapak'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

];
