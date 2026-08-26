<?php

return [
    'ollama_url' => 'http://127.0.0.1:11434',

    'models' => [
        'code' => 'qwen2.5-coder:7b',
        'architecture' => 'qwen3:8b',
    ],

    'project_root' => '/var/www/daodes',

    'rules_path' => '/var/www/daodes/ai/rules',

    'max_context_files' => 40,

    'ignore_paths' => [
        'vendor',
        'node_modules',
        'storage',
        '.git',
        'bootstrap/cache',
    ],
];