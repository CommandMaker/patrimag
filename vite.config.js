import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/js/app.js'
            ],
            refresh: true
        }),
        react()
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: undefined
            }
        }
    }
});