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

    //'paths' => ['api/*'], // default

    //* 這邊可以設定想要的 後端URL 來進行跨網域存取
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // new

    'allowed_methods' => ['*'],

    //'allowed_origins' => ['*'], // default

    //* 設定前端的 URL 可以進行跨網域(CORS)存取
    'allowed_origins' => ['http://localhost:3000'], // new

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // default

    'exposed_headers' => [],

    'max_age' => 0,

    //'supports_credentials' => false, // default 
    'supports_credentials' => true, // new

];
