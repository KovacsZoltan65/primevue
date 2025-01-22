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

import CompSettingsService from "@/service/CompSettingsService";

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
const comp_settings = ref();
const defaultSetting = ref({
    id: null,
    company_id: null,
    key: "",
    value: "",
    active: 1,
});

const comp_setting = ref({ ...defaultSetting });

const submitted = ref(false);

const initialSetting = () => {
    return { ...defaultSetting };
};

const rules = {
    company_id: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
    key: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
    value: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
};



const selectedSettings = ref([]);
const settingDialog = ref(false);
const deleteSelectedSettingsDialog = ref(false);
const deleteSettingDialog = ref(false);

const v$ = useVuelidate(rules, comp_setting);

const state = reactive({
    columns: {
        'id': { field: 'id', is_visible: true, is_sortable: true, is_filterable: true },
        'key': { field: 'key', is_visible: true, is_sortable: true, is_filterable: true },
        'value': { field: 'value', is_visible: true, is_sortable: true, is_filterable: true },
        'active':  { field: 'active', is_visible: true, is_sortable: true, is_filterable: true },
    }
});

const fetchItems = async () => {
    loading.value = true;

    await CompSettingsService.getSettings()
        .then((response) => {
            comp_settings.value = response.data;
        })
        .catch((error) => {
            console.error("getCompSettings API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch CompSettings",
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
        <Head :title="$t('comp_settings')" />

        <Toast />

        <div class="card">
            <Toolbar class="md-6"></Toolbar>

            <DataTable
                ref="dt"
                v-model:selection="selectedSettings"
                v-model:filters="filters"
                filterDisplay="menu"
                :value="comp_settings"
                dataKey="id"
                :paginator="true" :rows="10" sortMode="multiple"
                :loading="loading" stripedRows removableSort
                :globalFilterFields="['name']"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
            >
                <!-- SELECTION -->
                <Column
                    selectionMode="multiple"
                    style="width: 3rem"
                    :exportable="false"
                    :disabled="!props.can.appSettings_delete"
                />

                <!-- ID -->
                <Column
                    :field="state.columns.id.field"
                    :header="$t(state.columns.id.field)"
                    style="min-width: 16rem"
                    :sortable="state.columns.id.is_sortable"
                    :hidden="!state.columns.id.is_visible"
                />

                <!-- KEY -->
                <Column
                    :field="state.columns.key.field"
                    :header="$t(state.columns.key.field)"
                    style="min-width: 16rem"
                    :sortable="state.columns.key.is_sortable"
                    :hidden="!state.columns.key.is_visible"
                />

                <!-- VALUE -->
                <Column
                    :field="state.columns.value.field"
                    :header="$t(state.columns.value.field)"
                    style="min-width: 16rem"
                    :sortable="state.columns.value.is_sortable"
                    :hidden="!state.columns.value.is_visible"
                />
            </DataTable>
        </div>

    </AppLayout>
</template>
