import { defineConfig } from 'vite';
import vue from "@vitejs/plugin-vue";
import laravel from 'laravel-vite-plugin';
import collectModuleAssetsPaths from './vite-module-loader.js';

async function getConfig() {
    const paths = [
        'resources/css/app.scss',
        'resources/js/app.js',
    ];
    const allPaths = await collectModuleAssetsPaths(paths, 'Modules');

    return defineConfig({
        server: {
            https: true,
        },
        plugins: [
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            laravel({
                input: allPaths,
                refresh: true,
            })
        ],
        resolve: {
            alias: {
                '@': '/resources/js',
                'ziggy-js': '/vendor/tightenco/ziggy',
                'zora-js': '/vendor/jetstreamlabs/zora/dist/index.js',
                zora: '/vendor/jetstreamlabs/zora/dist/vue.js',
                "@metronic": "/resources/metronic"
            },
            dedupe: ['@inertiajs/vue3']
        }
    });
}

export default getConfig();
