<?php

return [
    'paths' => ['api/*'],

    'allowed_methods' => ['GET','POST'],

    'allowed_origins' => explode(",", env('ALLOWED_ORIGINS', '*')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Accept',
        'Authorization',
        'User-Agent',
    ],

    'exposed_headers' => false,

    'max_age' => 0,

    'supports_credentials' => false,
];
