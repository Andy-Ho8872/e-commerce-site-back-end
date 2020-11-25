<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // default

    //'allowed_origins' => ['http://localhost:3000'], // new

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // default

    //'allowed_headers' => ['Content-Type'], // new

    'exposed_headers' => [],

    'max_age' => 0,

    // 'Access-Control-Allow-Origin: http://localhost:3000/'
    'supports_credentials' => false, // default 

    //'supports_credentials' => false, // new

];
