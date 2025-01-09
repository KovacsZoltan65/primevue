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

import ACSService from "@/service/ACSService";

import CompanyService from "@/service/CompanyService";

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
import Checkbox from "primevue/checkbox";

const props = defineProps({
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

const getBools = () => {
    return [
        {label: trans("inactive"),value: 0,},
        {label: trans("active"),value: 1,},
    ];
};

const toast = useToast();
const loading = ref(true);
const dt = ref();
const filters = ref({});

const acss = ref();
const defaultAcs = {
    id: null,
    name: "",
    active: 1,
};

// Tároló kulcsok
const local_storage_acs = 'acss';
const local_storage_column_key = 'ln_companies_grid_columns';

const acs = ref({ ...defaultAcs });

const initialACS = () => {
    return { ...defaultAcs };
};

const submitted = ref(false);

const rules = {
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
};

const v$ = useVuelidate(rules, acs);

const state = reactive({
    columns: {
        'id': { field: 'id', is_visible: true, is_sortable: true, is_filterable: true },
        'name': { field: 'name', is_visible: true, is_sortable: true, is_filterable: true },
        'active': { field: 'active', is_visible: true, is_sortable: true, is_filterable: true }
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
}, { deep: true });

const selectedACSs = ref([]);

/**
 * ===========================================
 * DIALOGOK
 * ===========================================
 */
// táblázat beállításai
const settingsDialog = ref(false);
// új cég készítéséhez, vagy meglevő szerkesztéshez
const acsDialog = ref(false);
// kiválasztott cégekek törléséhez
const deleteSelectedACSsDialog = ref(false);
// cég törléséhez
const deleteACSDialog = ref(false);
// ======================================================

const fetchItems = async () => {
    loading.value = true;

    await ACSService.getACSS()
        .then((response) => {
            acss.value = response.data;

            localStorage.setItem(local_storage_acs, JSON.stringify(response.data));
        })
        .catch((error) => {
            console.error("getACSS API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch ACS Systems",
                additionalInfo: "Failed to retrieve the ACS Systems",
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

    let columns = localStorage.getItem(local_storage_column_key);
    if( columns ) {
        columns = JSON.parse(columns);
        for(const column_name in columns) {
            state.columns[column_name] = columns[column_name];
        }
    }
});

const hideDialog = () => {
    acs.value = initialACS(); // Visszaáll az alapértelemezett állapotra
    settingsDialog.value = false;
    acsDialog.value = false;
    deleteACSDialog.value = false;
    deleteSelectedACSsDialog.value = false;
    submitted.value = false;

    v$.value.$reset();
};

function openNew() {
    company.value = initialCompany();
    submitted.value = false;
    acsDialog.value = true;
};

const editACS = (data) => {
    acs.value = { ...data };
    acsDialog.value = true;
};

const saveACS = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (acs.value.id) {
            updateACS();
        } else {
            createACS();
        }

        localStorage.removeItem(local_storage_acs);
    } else {
        // Validációs hibák összegyűjtése
        const validationErrors = v$.value.$errors.map((error) => ({
                field: error.$property,
                message: trans(error.$message),
            }));
        // Adatok előkészítése logoláshoz
        const data = {
            componentName: "saveACS",
            additionalInfo: "Client-side validation failed during acs system update",
            category: "Validation Error",
            priority: "low",
            validationErrors: validationErrors,
        };
        // Validációs hibák logolása
        ErrorService.logValidationError(new Error('Client-side validation error'), data);

        // Hibaüzenet megjelenítése a felhasználónak
        toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: "Please fix the highlighted errors before submitting.",
        });
    }
};

const createACS = async () => {

    const newACS = {...acs.value, id: createId() };
    acs.value.push(newACS);

    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "ACS creation in progress",
        life: 3000,
    });

    await ACSService.createACS(acs.value)
        .then(() => {

            const index = findIndexById(newACS.id);
            if (index !== -1) {
                acss.value.splice(index, 1, response.data);
            }

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "ACS Created",
                life: 3000,
            });
        })
        .catch((error) => {});
};

const updateACS = async () => {};

</script>

<template>
    <AppLayout>
        <Head :title="$t('acs_systems')" />

        <Toast />

        <div class="card">
            <Toolbar class="md-6">
                <template #start>

                    <!-- Settings -->
                    <Button
                        icon="pi pi-cog"
                        severity="secondary"
                        class="mr-2"
                        @click="openSettingsDialog"
                    />

                    <!-- ERROR -->
                    <Button
                        label="ERROR"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="throwError"
                    />

                    <!-- New Button -->
                    <Button
                        :label="$t('add_new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                        :disabled="!props.can.companies_create"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete_selected')"
                        icon="pi pi-trash"
                        severity="secondary"
                        class="mr-2"
                        @click="confirmDeleteSelected"
                        :disabled="!props.can.companies_delete ||
                            !selectedCompanies ||
                            !selectedCompanies.length"
                    />
                </template>

                <template #end>
                    <FileUpload
                        mode="basic"
                        accept="image/*"
                        :maxFileSize="1000000"
                        label="Import"
                        customUpload auto
                        chooseLabel="Import"
                        class="mr-2"
                        :chooseButtonProps="{ severity: 'secondary' }"
                        @upload="onUpload"
                    />
                    <Button
                        :label="$t('export')"
                        icon="pi pi-upload"
                        severity="secondary"
                        @click="exportCSV($event)"
                    />
                </template>
            </Toolbar>

            <DataTable></DataTable>

        </div>
    </AppLayout>
</template>