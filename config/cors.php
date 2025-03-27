<?php

return [
  'paths' => ['*'],
  'allowed_methods' => ['*'],
  'allowed_origins' => [
      'https://daodes.space',
      'http://localhost:5173', // Явно разрешите Vite-сервер
      'http://[::1]:5173'      // Разрешите IPv6-адрес
  ],
  'allowed_headers' => ['*'],
  'exposed_headers' => [],
  'max_age' => 0,
  'supports_credentials' => false,
];