import './bootstrap';
import "../metronic/core/index";
import "../metronic/app/layouts/demo1";

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import { ZoraVue } from 'zora'
import { Zora } from './zora.js'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

function resolvePage(name) {
    const [module, page] = name.split('::');

    const pagePath = page
        ? `../../Modules/${module}/resources/js/Pages/${page}.vue`
        : `./Pages/${module}.vue`;

    const pages = page
        ? import.meta.glob('../../Modules/**/resources/js/Pages/**/*.vue')
        : import.meta.glob('./Pages/**/*.vue');

    if (!pages[pagePath]) {
        const errorMessage = `Page not found: ${pagePath}`;
        console.log(errorMessage);
        throw new Error(errorMessage);
    }

    return typeof pages[pagePath] === 'function' ? pages[pagePath]() : pages[pagePath];
}


createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePage(name),
    setup({ el, App, props, plugin }) {
        let app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ZoraVue, Zora)

        app.config.globalProperties.$route = route

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
