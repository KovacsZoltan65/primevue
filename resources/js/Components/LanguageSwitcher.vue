<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { LanguageService } from "@/service/LanguageService";
import { loadLanguageAsync } from "laravel-vue-i18n";
import Select from "primevue/select";

const selectedCountry = ref();
const countries = ref();
const responsiveSettingsLanguage = ref(false);

onMounted(() => {
    LanguageService.getLanguages().then((data) => {
        countries.value = data;
    });
});

function setLocale(locale) {
    axios.post(route("language"), { locale: locale }).catch((error) => {
        console.log(error);
    });
}
</script>
<template>
    <Select
        v-model="selectedCountry"
        :options="countries"
        filter
        optionLabel="name"
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
