import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/js/bootstrap.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    environmentVariables: {
        VITE_PUSHER_APP_KEY: process.env.PUSHER_APP_KEY,
        VITE_PUSHER_HOST: process.env.PUSHER_HOST,
        VITE_PUSHER_PORT: process.env.PUSHER_PORT,
        VITE_PUSHER_SCHEME: process.env.PUSHER_SCHEME,
    },
});