import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/main.css',
                'resources/css/menu.css',
                'resources/css/page.css',
                'resources/css/offers.css',
                'resources/css/tasks.css',
                'resources/css/wallet.css',
                'resources/css/ckeditor.css',
                'resources/css/category.css',
                
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        
        https: {
            key: fs.readFileSync(path.resolve(__dirname, 'storage/certs/privkey.pem')),
            cert: fs.readFileSync(path.resolve(__dirname, 'storage/certs/fullchain.pem')),
        },
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            protocol: 'wss',
            host: 'daodes.space',
            clientPort: 443,
            path: '/vite-hmr'
        },
        cors: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
            'Access-Control-Allow-Headers': 'X-Requested-With, Content-Type, Authorization'
        },
        // proxy: {
        //     '/api': {
        //         target: 'https://daodes.space',  // Полный URL бэкенда
        //         changeOrigin: true,
        //         secure: false,
        //         ws: true
        //     }
        // },
        watch: {
            usePolling: true,
            interval: 1000
        }
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js')
        }
    },
    optimizeDeps: {
        include: ['lodash', 'axios']
    }
});