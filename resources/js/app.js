import "./bootstrap";
//import '../css/app.css';
//import '../css/styles.scss';
import "../css/styles.scss";
import "../css/tailwind.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { i18nVue } from "laravel-vue-i18n";

import Aura from "@primevue/themes/aura";
import PrimeVue from "primevue/config";
import ConfirmationService from "primevue/confirmationservice";
import ToastService from "primevue/toastservice";
import StyleClass from "primevue/styleclass";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18nVue, {
                resolve: async (lang) => import(`../../lang/${lang}.json`),
            })
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        darkModaSelector: ".app-dark",
                    },
                },
            })
            .use(ToastService)
            .use(ConfirmationService)
            .directive("styleclass", StyleClass)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
