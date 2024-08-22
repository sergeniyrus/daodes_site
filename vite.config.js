import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/main.css',
                'resources/js/app.js',
                'resources/js/block.js',
                'resources/js/bt_top.js',
            ],
            refresh: true,
        }),
    ],
});
