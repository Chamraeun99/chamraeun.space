<?php

$origins = ['https://chamraeun-space-frontend.onrender.com'];

$frontend = env('FRONTEND_URL');
if (is_string($frontend) && $frontend !== '') {
    $origins[] = rtrim($frontend, '/');
}

$extra = env('CORS_ALLOWED_ORIGINS');
if (is_string($extra) && $extra !== '') {
    foreach (explode(',', $extra) as $o) {
        $t = trim($o);
        if ($t !== '') {
            $origins[] = rtrim($t, '/');
        }
    }
}

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => array_values(array_unique($origins)),
    /** Render preview deploys and alternate hostnames (matched against request Origin). */
    'allowed_origins_patterns' => ['#^https://.*\.onrender\.com$#'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
