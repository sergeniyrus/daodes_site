import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/bt_top.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
            'Access-Control-Allow-Headers': 'X-Requested-With, Content-Type, Authorization'
        },

        https: {
            key: fs.readFileSync(path.resolve(__dirname, 'storage/certs/privkey.pem')),
            cert: fs.readFileSync(path.resolve(__dirname, 'storage/certs/fullchain.pem')),
        },
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            protocol: 'wss',
            host: 'daodes.space'
        },
    },
});