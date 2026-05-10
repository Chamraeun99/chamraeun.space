<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'supabase' => [
            'driver' => 's3',
            'key' => env('SUPABASE_ACCESS_KEY'),
            'secret' => env('SUPABASE_SECRET_KEY'),
            'region' => env('SUPABASE_REGION', 'ap-southeast-1'),
            'bucket' => env('SUPABASE_BUCKET', 'chamraeun.space-picture'),
            'endpoint' => env('SUPABASE_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
            'scheme' => 'https',
            'throw' => true,
            'url' => rtrim((string) env('SUPABASE_URL'), '/') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET', 'chamraeun.space-picture'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];