import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            protocol: "wss",
            // if your DDEV project serves multiple hosts, update the "host" as required.
            host: `${process.env.DDEV_HOSTNAME}`,
        }
    }

});