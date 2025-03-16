import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'public/css/main.css',
                'public/css/ckeditor.css',
                'public/css/menu.css',
                'public/css/news.css',
                'public/css/offers.css',
                'public/css/tasks.css',
                'resources/js/app.js',
                'resources/js/bt_top.js',
                'public/js/ckeditor.js',
                'public/js/image-cropper.js',
            ],

            refresh: true,
        }),
    ],
    build: {
        sourcemap: true,
    },
    server: {
        hmr: {
            overlay: false,
        },
        // Настройки CORS
        cors: {
            origin: '*', // Разрешить все источники
            methods: '*', // Разрешить методы
            allowedHeaders: '*', // Разрешить заголовки
        },

        fs: {
            strict: false, // Разрешает доступ к файлам за пределами корневой директории
        },

    },
});
