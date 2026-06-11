import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/color-modes.js',
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/guest.css',
                'resources/js/app.js',
                'resources/js/guest.js',
                'resources/js/admin.js',
                'resources/js/tom-select.js',
                'resources/js/datatable-manager.js',
                'resources/js/custom-select.js',
                ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    
});
