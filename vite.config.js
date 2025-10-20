import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import i18n from 'laravel-vue-i18n/vite';

export default defineConfig({
    plugins: [
        vue({
            template :{
                 transformAssetUrls: {
                     base: null,
                     includeAbsolute: false
                 },
            },
        }),
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        i18n(),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        },
    },

    base: '/build/', // обязательно, если файлы в public/build
    build: {
        outDir: 'public/build',
        rollupOptions: { /* ... */ }
    }
});
