<?php

return [

    'default' => env('BROADCAST_DRIVER', 'null'),

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_KEY', 'default_key'),
            'secret' => env('PUSHER_SECRET', 'default_secret'),
            'app_id' => env('PUSHER_APP_ID', 'default_app_id'),
            'options' => [
                'cluster' => env('PUSHER_CLUSTER', 'default_cluster'),
                'useTLS' => true,
                'encrypted' => true,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY', 'default_ably_key'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'reverb' => [
            'driver' => 'pusher',
            'key' => env('VITE_REVERB_APP_KEY'),
            'secret' => '', // Reverb может не требовать секрета
            'app_id' => '', // Можно оставить пустым, если Reverb не использует ID приложения
            'options' => [
                'host' => env('VITE_REVERB_HOST', 'localhost'),
                'port' => env('VITE_REVERB_PORT', 6001),
                'scheme' => env('VITE_REVERB_SCHEME', 'https'),
                'useTLS' => env('VITE_REVERB_SCHEME', 'https') === 'https',
                'encrypted' => true,
            ],
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
