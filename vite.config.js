import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    base: process.env.NODE_ENV === "production" ? "https://" : "/",
    build: {
        rollupOptions: {
            output: {
                assetFileNames: `build/assets/[name].[hash][extname]`,
                chunkFileNames: `build/assets/[name].[hash].js`,
                entryFileNames: `build/assets/[name].[hash].js`,
            },
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/calendar.js",
            ],
            refresh: [...refreshPaths, "app/Livewire/**"],
        }),
    ],
});
