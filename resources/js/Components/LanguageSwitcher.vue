<script setup>
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

//import { LanguageService } from "@/service/LanguageService";
import { loadLanguageAsync } from "laravel-vue-i18n";
import Select from "primevue/select";
import { usePrimeVue } from "primevue/config";
import { localeEN } from "../../../lang/primevue-en.js";
import { localeHU } from "../../../lang/primevue-hu.js";

const primevue = usePrimeVue();
const selectedCountry = ref("");
const supportedleLocales = ref();
//const availableLocale = ref();
const countries = ref();
const responsiveSettingsLanguage = ref(false);

onMounted(() => {
    const page = usePage();
    const sharedData = page.props;

    /**
     * Kiválasztott nyelv
     */
    selectedCountry.val = sharedData.locale;

    /**
     * Támogatott nyelvek
     */
    supportedleLocales.val = sharedData.supported_locales;

    /**
     * Elérhető nyelvek
     */
    countries.value = sharedData.available_locales;
});

function setLocale(event) {

    var locale = selectedCountry.value.code.toLowerCase();


    /*
    axios.post(route("language"), { locale: locale }).catch((error) => {
        console.log(error);
    });
    */
    const primeLocale = locale == "HU" ? localeHU : localeEN;
    primevue.config.locale = primeLocale;
}

watch(selectedCountry, (newValue) => {
    console.log("newValue", newValue);
    const primeLocale =
        newValue.code.toLowerCase() == "hu" ? localeHU : localeEN;
    primevue.config.locale = primeLocale;
});
</script>
<template>
    <Select
        v-model="selectedCountry"
        :options="countries"
        filter
        optionLabel="name"
        opvionValue="code"
        :placeholder="$t('select_country')"
        class="w-full md:w-56"
    >
        <template #value="slotProps">
            <div v-if="slotProps.value" class="flex items-center">
                <img
                    :alt="slotProps.value.label"
                    src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                    :class="`mr-2 flag flag-${slotProps.value.code.toLowerCase()}`"
                    style="width: 18px"
                />
                <div>{{ slotProps.value.name }}</div>
            </div>
            <span v-else>
                {{ slotProps.placeholder }}
            </span>
        </template>

        <template #option="slotProps">
            <div class="flex items-center">
                <img
                    :alt="slotProps.option.label"
                    src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                    :class="`mr-2 flag flag-${slotProps.option.code.toLowerCase()}`"
                    style="width: 18px"
                />
                <div>{{ slotProps.option.name }}</div>
            </div>
        </template>
    </Select>
</template>
