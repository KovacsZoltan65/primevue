import "./bootstrap";
import "../css/styles.scss";
import "../css/tailwind.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { i18nVue } from "laravel-vue-i18n";

import Aura from "@primevue/themes/aura";

import PrimeVue from "primevue/config";
//import { localeEN } from "../../lang/primevue-en.js";
import { localeHU } from "../../lang/primevue-hu.js";

import ErrorService from "@/service/ErrorService";
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
        const vueApp = createApp({ render: () => h(App, props) })
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
                locale: localeHU,
            })
            .use(ToastService)
            .use(ConfirmationService)
            .directive("styleclass", StyleClass);

            // Globális hibakezelő beállítása
            vueApp.config.errorHandler = (error, vm, info) => {
                console.error("Globális hiba:", error);

                const errorData = {
                    message: error.message || "Ismeretlen hiba",
                    stack: error.stack || null,
                    component: vm?.$options?.name || "UnknownComponent",
                    info,
                    time: new Date().toISOString(),
                };

                // Hiba továbbítása az ErrorService-nek
                ErrorService.logClientError(errorData).catch((logError) => {
                    console.error("Hiba továbbítása nem sikerült:", logError);
                });
            };

            return vueApp.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
