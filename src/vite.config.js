import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '127.0.0.1',
        port: 5173,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                    'resources/css/admin.css',
                    'resources/js/app.js',
                    'resources/js/voyage-form.js',
                    'resources/js/admin/order-create.js',
                    'resources/js/admin/order-edit.js',
                    'resources/js/admin/order-item-type-switcher.js',
                    'resources/js/admin/dashboard-chart.js'],
            refresh: true,
        }),
    ],
});
