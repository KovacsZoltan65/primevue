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

    await ACSService.getACSs()
        .then((response) => {
            console.log(response);
            acss.value = response.data;

            localStorage.setItem(local_storage_acs, JSON.stringify(response.data));
        })
        .catch((error) => {
            console.error("getACSs API Error:", error);

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
        .catch((error) => {
            if(error.response && error.response.status === 422) {
                const validationErrors = error.response.data.details;

                toast.add({
                    severity: "warn",
                    summary: "Validation Error",
                    detail: "Please check your inputs",
                    life: 4000,
                });

                // Validációs hibák logolása
                ErrorService.logClientError(error, {
                    componentName: "CreateCompanyDialog",
                    additionalInfo: "Validation errors occurred during ACS creation",
                    category: "Validation Error",
                    priority: "medium",
                    validationErrors: validationErrors,
                });
            } else {
                const index = findIndexById(newACS.id);
                if (index !== -1) {
                    acss.value.splice(index, 1);
                }

                // Toast hibaüzenet
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: trans('error_acs_create'),
                });

                // Hiba naplózása
                ErrorService.logClientError(error, {
                    componentName: "CreateACSDialog",
                    additionalInfo: "Failed to create a ACS in the backend",
                    category: "Error",
                    priority: "high",
                    data: acs.value,
                });
            }
        });
};

const updateACS = async () => {
    const index = findIndexById(acs.value.id);
    if (index === -1) {
        console.error(`ACS with id ${acs.value.id} not found`);
        return;
    }

    const originalACS = { ...acss.value[index] };

    acss.value.splice(index, 1, { ...acs.value });

    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "ACS update in progress",
        life: 2000,
    });

    await ACSService.updateACS(acs.value.id, acs.value)
        .then((response) => {
            //
        })
        .catch((error) => {
            acss.value.splice(index, 1, originalACS);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to update acs",
            });

            ErrorService.logClientError(error, {
                componentName: "UpdateACSDialog",
                additionalInfo: "Failed to update a acs in the backend",
                category: "Error",
                priority: "medium",
                data: acs.value,
            });
        });
};

const deleteSelectedACSs = async () => {
    const originalACSs = [...acss.value];

    selectedACSs.value.forEach(selectedACS => {
        const index = acss.value.findIndex(acs => acs.id === selectedACS.id);
        if (index !== -1) {
            acss.value.splice(index, 1);
        }
    });

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Deleting selected acss...",
        life: 2000,
    });

    await ACSService.deleteACSs(selectedACSs.value.map(acs => acs.id))
        .then((response) => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Selected ACSs deleted",
                life: 3000,
            });

            selectedACSs.value = [];
        })
        .catch((error) => {
            acss.value = originalACSs;

            const errorMessage = error.response?.data?.error || "Failed to delete selected ACSs";

            toast.add({
                severity: "error",
                summary: "Error",
                detail: errorMessage,
                life: 3000,
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteACSsDialog",
                additionalInfo: "Failed to delete a ACSs in the backend",
                category: "Error",
                priority: "low",
                data: acss.value
            });
        });
};

const deleteACS = async () => {
    const index = findIndexById(acs.value.id);
    if (index === -1) {
        console.warn("No acs found with the given id:", acs.value.id);
        return;
    }

    const originalACS = { ...acss.value[index] };

    acss.value.splice(index, 1);

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "ACS deletion in progress",
        life: 2000,
    });

    await ACSService.deleteACS(id)
        .then((response) => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "ACS Deleted",
                life: 3000,
            });
        })
        .catch((error) => {
            acss.value.splice(index, 0, originalACS);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete acs",
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteACSDialog",
                additionalInfo: "Failed to delete a ACS in the backend",
                category: "Error",
                priority: "medium",
                data: acs.value,
            });
        });
};

const confirmDeleteACS = (data) => {
    acs.value = { ...data };
    deleteACSDialog.value = true;
};

function confirmDeleteSelected() {
    deleteSelectedACSsDialog.value = true;
};

const openSettingsDialog = () => {
    settingsDialog.value = true;
};

const findIndexById = (id) => {
    return acss.value.findIndex((acs) => acs.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const getModalTitle = () => {
    return acs.value.id
        ? trans("acss_edit_title")
        : trans("acss_new_title");
};

const getModalDetails = () => {
    return acs.value.id
        ? trans("acss_edit_details")
        : trans("acss_new_details");
};

const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS},
        name: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
    }
}

const clearFilter = () => {
    initFilters();
};

initFilters();

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

            <DataTable
                ref="dt"
                v-model:selection="selectedACSs"
                v-model:filters="filters"
                :value="acss"
                dataKey="id" :paginator="true" :rows="10" sortMode="multiple"
                :filters="filters" filterDisplay="menu"
                :loading="loading" stripedRows removableSort
                :globalFilterFields="['name']"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} subdomains"
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <!-- SZŰRÉS TÖRLÉSE -->
                        <Button
                            type="button"
                            icon="pi pi-filter-slash"
                            :label="$t('clear')"
                            outlined
                            @click="clearFilter()"
                        />

                        <!-- FELIRAT -->
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("acs_state_title") }}
                        </div>

                        <!-- KERESÉS -->
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText
                                v-model="filters['global'].value"
                                :placeholder="$t('search')"
                            />
                        </IconField>
                    </div>
                </template>

                <template #paginatorstart>
                    <Button
                        type="button"
                        icon="pi pi-refresh"
                        text
                        @click="fetchItems"
                    />
                </template>

                <template #empty>
                    {{ $t('data_not_found', {data: 'acss'} ) }}
                </template>

                <template #loading>
                    {{ $t('loader', {data: 'ACS Systems'}) }}
                </template>

                <Column
                    selectionMode="multiple"
                    style="min-width: 3rem"
                    :exportable="false"
                />

                <Column
                    field="name"
                    :header="$t('name')"
                    sortable
                    style="min-width: 16rem"
                >
                    <template #body="{ data }">
                        {{ data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'name'})"
                        />
                    </template>
                </Column>

                <Column
                    field="active"
                    :header="$t('active')"
                    sortable
                    style="min-width: 6rem"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="$t(getActiveValue(slotProps.data))"
                            :severity="getActiveLabel(slotProps.data)"
                        />
                    </template>
                </Column>

                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            class="mr-2"
                            @click="editState(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteACS(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>

        </div>
    </AppLayout>
</template>
