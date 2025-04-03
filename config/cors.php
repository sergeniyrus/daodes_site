<?php

return [
  'paths' => ['api/*', 'sanctum/csrf-cookie', 'broadcasting/*', 'reverb/*'],
  'allowed_methods' => ['*'],
  'allowed_origins' => [
      'https://daodes.space',
      'http://daodes.space',
      'http://localhost:5173',
      'http://127.0.0.1:5173',
      'http://[::1]:5173'
  ],
  'allowed_headers' => ['*'],
  'exposed_headers' => [],
  'max_age' => 0,
  'supports_credentials' => true, // Для работы с куками и Sanctum
];