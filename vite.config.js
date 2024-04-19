import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/main.css',
                'resources/js/app.js',
            ],
            refresh: true,
            detectTls: 'daodes.space', 
        }),
    ],

    server: { 
        hmr: {
            host: 'localhost',
        },
    }, 
    server: { 
        host, 
        hmr: { host }, 
        https: { 
            key: fs.readFileSync(`/path/to/${host}.key`), 
            cert: fs.readFileSync(`/path/to/${host}.crt`), 
        }, 
    }, 


});

