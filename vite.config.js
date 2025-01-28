import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    server: {
        cors: true, // Engedélyezi a CORS-t
        host: '127.0.0.1', // IPv4-re állítás
        port: 5173
    },
    plugins: [
        laravel({
            input: "resources/js/app.js",
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
    css: {
        processorOptions: {
            scss: {
                api: "modern",
            },
        },
    },
});
