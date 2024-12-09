<script setup>
import { computed, onMounted, ref, reactive, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from '@/Validation/ValidationRules.json';

// TOAST
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';

import ApplicationSettingsService from "@/service/ApplicationSettingsService";

import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import IconField from "primevue/iconfield";
import InputText from "primevue/inputtext";
import InputIcon from "primevue/inputicon";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import Tag from "primevue/tag";
import FileUpload from "primevue/fileupload";
import { createId } from "@/helpers/functions";
import FloatLabel from "primevue/floatlabel";
import ErrorService from "@/service/ErrorService";
import Message from "primevue/message";


const props = defineProps({
    can: { type: Object, default: () => {}, },
});

const toast = useToast();
const loading = ref(true);
const dt = ref();
const filters = ref({});
const app_settings = ref();
const app_setting = ref({
    id: null,
    key: "",
    value: "",
    active: 1,
});
const submitted = ref(false);

const rules = {
    key: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
    value: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
};

const initialSetting = () => {
    return {...app_setting.value};
};

const selectedSettings = ref([]);
const settingDialog = ref(false);
const deleteSelectedSettingsDialog = ref(false);
const deleteSettingDialog = ref(false);

const v$ = useVuelidate(rules, app_setting);

const fetchItems = async () => {
    loading.value = true;

    await ApplicationSettingsService.getSettings()
        .then((response) => {
            app_settings.value = response.data;
            console.log(app_settings.value);
        })
        .catch((error) => {
            console.error("getCompanySettings API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch CompanySettings",
                additionalInfo: "Failed to retrieve the company",
                category: "Error",
                priority: "high",
                data: null,
            });
        })
        .finally(() => {
            loading.value = false;
        });
};

onMounted(() => {
    fetchItems();
});

</script>

<template>
    <AppLayout>
        <Head :title="$t('application_settings')" />

        <Toast />

        {{ app_settings }}
    </AppLayout>
</template>