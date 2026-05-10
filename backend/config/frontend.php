<?php

return [
    // Read via config('frontend.url'); do not call env('FRONTEND_URL') outside config (breaks config:cache on servers).
    'url' => env('FRONTEND_URL', ''),
];
