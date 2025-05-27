import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
/*import { ZiggyVue } from '../../vendor/tightenco/ziggy';*/
import { ZiggyVue } from 'ziggy-js';

import { initializeTheme } from './composables/useAppearance';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import AOS from 'aos';
import 'aos/dist/aos.css';

import VCalendar from 'v-calendar';
import 'v-calendar/style.css';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || '';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, {
                position: "top-right",
                timeout: 5000
            })
            .use(VCalendar, {});

        app.mount(el);

        AOS.init({
            duration: 800,
            once: true,
        })
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
