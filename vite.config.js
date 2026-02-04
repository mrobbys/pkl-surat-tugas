import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // visualizer({
        //     open: true, // Otomatis buka browser setelah build selesai
        //     filename: 'stats.html', // Nama file laporannya
        //     gzipSize: true,
        //     brotliSize: true,
        // }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('chart.js')) {
                            return 'vendor-charts';
                        }
                        if (id.includes('@fullcalendar')) {
                            return 'vendor-calendar';
                        }
                        if (id.includes('sweetalert2') || id.includes('choices.js')) {
                            return 'vendor-ui-libs';
                        }
                        return 'vendor';
                    }
                },
            },
        },
    },
});
