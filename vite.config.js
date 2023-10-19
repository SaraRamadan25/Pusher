import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    env: {
        VITE_PUSHER_APP_CLUSTER: process.env.PUSHER_APP_CLUSTER,
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/laravel-echo.js',
            ],
            refresh: true,
        }),
    ],
});
