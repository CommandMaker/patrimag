import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.scss',
            'resources/js/app.js'
        ]),
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