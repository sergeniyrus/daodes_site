<?php

return [
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'broadcasting/*',
        'reverb/*',
        'login',
        'logout',
        'register'
    ],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://daodes.space',
        'https://daodes.space:5173',
        
        'http://localhost:5173',
        
        
        'https://localhost:5173'
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];