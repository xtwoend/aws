import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    define: {
        global: 'globalThis',
    },
    resolve: {
        alias: {
            '~bootstrap': 'bootstrap',
            '~@popperjs/core': '@popperjs/core'
        }
    },
    optimizeDeps: {
        include: [
            'bootstrap',
            '@popperjs/core',
            'jquery',
            'datatables.net-bs5',
            'datatables.net-responsive-bs5',
            'datatables.net-buttons-bs5'
        ]
    }
});
