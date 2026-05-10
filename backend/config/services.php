<?php

return [

    'supabase' => [
        'url' => env('SUPABASE_URL'),
        /** Service role recommended for server uploads (Dashboard → Settings → API). */
        'secret_key' => env('SUPABASE_SECRET_KEY'),
        /** Optional public anon key sent as `apikey` header; defaults to secret_key when empty. */
        'anon_key' => env('SUPABASE_ANON_KEY'),
        'bucket' => env('SUPABASE_BUCKET', 'kalapak-assets'),
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
