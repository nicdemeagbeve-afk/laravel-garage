import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'public/welcome.css',
                'public/dashboard-admin.css',
                'public/style.css',
            ],
            refresh: true,
        }),
    ],
});
