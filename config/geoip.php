<?php
return [
    'cache' => 'redis', // Используйте тот же драйвер кеширования, что и в вашем приложении
    'cache_tags' => ['geoip'], // Теги для кеширования (если используется Redis)
    'cache_expires' => 86400, // Время жизни кеша в секундах (1 день)

    'default' => [
        'driver' => 'maxmind_database', // Используйте базу данных MaxMind
        'database_path' => storage_path('app/geoip/GeoLite2-City.mmdb'), // Путь к базе данных
    ],

    'services' => [
        'maxmind_database' => [
            'class' => \Torann\GeoIP\Services\MaxMindDatabase::class,
            'database_path' => storage_path('app/geoip/GeoLite2-City.mmdb'),
        ],
        'maxmind_api' => [
            'class' => \Torann\GeoIP\Services\MaxMindWebService::class,
            'user_id' => env('MAXMIND_USER_ID'),
            'license_key' => env('MAXMIND_LICENSE_KEY'),
        ],
    ],
];
