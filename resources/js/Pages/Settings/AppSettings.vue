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

import AppSettingsService from "@/service/AppSettingsService";

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

const defaultSetting = {
    id: null,
    key: "",
    value: "",
    active: 1,
};

const app_setting = ref({ ...defaultSetting });

const initialSetting = () => {
    return { ...defaultSetting };
};

const submitted = ref(false);

const rules = {
    key: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
    value: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
};

const state = reactive({
    columns: {
        id: { field: 'id', is_visible: true, is_sortable: true, is_filterable: true },
        key: { field: 'key', is_visible: true, is_sortable: true, is_filterable: true },
        value: { field: 'value', is_visible: true, is_sortable: true, is_filterable: true },
        active:  { field: 'active', is_visible: true, is_sortable: true, is_filterable: true },
    }
});

const local_storage_column_key = 'ln_app_settins_grid_columns';
watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
});

const selectedSettings = ref([]);
const settingDialog = ref(false);
const settingsDialog = ref(false);
const deleteSelectedSettingsDialog = ref(false);
const deleteSettingDialog = ref(false);

const v$ = useVuelidate(rules, app_setting);

const fetchItems = async () => {
    loading.value = true;

    await AppSettingsService.getSettings()
        .then((response) => {
            app_settings.value = response.data;
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

    let columns = localStorage.getItem(local_storage_column_key);
    if (columns) {
        columns = JSON.parse(columns);
        for(const column_name in columns) {
            state.columns[column_name] = columns[column_name];
        }
    }
});

const hideDialog = () => {
    app_setting.value = initialSetting();
    settingDialog.value = false;
    deleteSettingDialog.value = false;
    deleteSelectedSettingsDialog.value = false;
    submitted.value = false;

    v$.value.$reset();
};

const openNew = () => {
    app_setting.value = initialSetting();
    submitted.value = false;
    settingDialog.value = true;
};

const editSetting = (data) => {
    app_setting.value = {...data};
    settingDialog.value = true;
}

const saveSetting = async () => {
    const result = await v$.value.$validate();

    if ( result ) {
        submitted.value = true;

        if( app_setting.value.id ) {
            updateSetting();
        } else {
            createSetting();
        }
    } else {
        // Validációs hibák összegyűjtése
        const validationErrors = v$.value.$errors.map((error) => ({
                field: error.$property,
                message: trans(error.$message),
            }));
        // Adatok előkészítése logoláshoz
        const data = {
            componentName: "saveAppSetting",
            additionalInfo: "Client-side validation failed during app_setting update",
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

const createSetting = async () => {

    const newSetting = {...app_setting.value, id: createId() };

    app_settings.value.push(newSetting);

    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "App Setting creation in progress",
        life: 3000,
    });

    await AppSettingsService.createSetting(app_setting.value)
        .then((response) => {
            // Lokális adat frissítése a szerver válasza alapján
            const index = findIndexById(newSetting.id);
            if (index !== -1) {
                app_settings.value.splice(index, 1, response.data);
            }
            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "App Setting Created",
                life: 3000,
            });
        })
        .catch((error) => {
            if( error.response && error.response.status === 422) {
                const validationErrors = error.response.data.details;

                toast.add({
                    severity: "warn",
                    summary: "Validation Error",
                    detail: "Please check your inputs",
                    life: 4000,
                });

                // Validációs hibák logolása
                ErrorService.logClientError(error, {
                    componentName: "CreateAppSettingDialog",
                    additionalInfo: "Validation errors occurred during app setting creation",
                    category: "Validation Error",
                    priority: "medium",
                    validationErrors: validationErrors,
                });
            } else {
                // Hibás esetben a lokális adat törlése
                const index = findIndexById(newSetting.id);
                if (index !== -1) {
                    app_settings.value.splice(index, 1);
                }

                // Toast hibaüzenet
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: trans('error_app_setting_create'),
                });

                // Hiba naplózása
                ErrorService.logClientError(error, {
                    componentName: "CreateAppSettingDialog",
                    additionalInfo: "Failed to create a app setting in the backend",
                    category: "Error",
                    priority: "high",
                    data: app_setting.value,
                });
            }
        });
};

const updateSetting = async () => {
    const index = findIndexById(company.value.id);
    if (index === -1) {
        console.error(`App Setting with id ${app_setting.value.id} not found`);
        return;
    }

    // Eredeti adat mentése az optimista frissítéshez
    const originalAppSetting = { ...app_settings.value[index] };

    // Lokális frissítés az optimista visszacsatoláshoz
    app_settings.value.splice(index, 1, { ...app_setting.value });
    hideDialog();

    // "Frissítés folyamatban" visszajelzés
    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "App Setting update in progress",
        life: 2000,
    });

    await AppSettingsService.updateSetting(app_setting.value.id, app_setting.value)
        .then((response) => {})
        .catch((error) => {
            // Sikertelen frissítés esetén az eredeti adat visszaállítása
            app_settings.value.splice(index, 1, originalAppSetting);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to app setting",
            });

            // Hiba naplózása a szerver felé
            ErrorService.logClientError(error, {
                componentName: "UpdateAppSettingDialog",
                additionalInfo: "Failed to update app setting in the backend",
                category: "Error",
                priority: "medium",
                data: app_setting.value,
            });
        });
};

const deleteSelectedSettings = async () => {};

const deleteSetting = async () => {};

const confirmDeleteSelected = () => {
    deleteSelectedSettingsDialog.value = true;
};

const confirmDeleteSetting = (data) => {
    app_setting.value = {...data};
    deleteSettingDialog.value = true;
};

const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const getStatusSeverity = (status) => {

    console.log('getStatusSeverity status', status);

    switch (status) {
        case 0:
            return "danger";
        case 1:
            return "success";
    }
};

const getStatusValue = (status) => {

    console.log('getStatusValue status', status);

    //['', '', ''][] || 'pending';

    switch (status) {
        case 0:
            return "INACTIVE";
        case 1:
            return "ACTIVE";
    }

};

const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS},
        key: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
    };
};

const clearFilters = () => {
    initFilters();
};

initFilters();

const getModalTitle = () => {
    return app_setting.value.id
        ? $t('edit_setting')
        : $t('add_new_setting');
};

</script>

<template>
    <AppLayout>
        <Head :title="$t('application_settings')" />

        <Toast />

        {{ props.can }}<br/>
        {{ state.columns.id }}

        <div class="card">
            <Toolbar class="md-6">
                <template #start>

                    <!-- Settings -->
                    <Button
                        icon="pi pi-cog"
                        severity="secondary"
                        class="mr-2"
                    />

                    <!-- New Button -->
                    <Button
                        :label="$t('add_new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                        :disabled="!props.can.appSettings_create"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete_selected')"
                        icon="pi pi-trash"
                        severity="secondary"
                        class="mr-2"
                        @click="confirmDeleteSelected"
                        :disabled="!props.can.appSettings_delete ||
                            !selectedSettings ||
                            !selectedSettings.length"
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
                v-model:selection="selectedSettings"
                v-model:filters="filters"
                filterDisplay="menu"
                :value="app_settings"
                dataKey="id"
                :paginator="true" :rows="10" sortMode="multiple"
                :loading="loading" stripedRows removableSort
                :globalFilterFields="['name']"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
            >

                <template #header>
                    <div class="flex flex-wrap gap-2 items-center justify-between">
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
                            {{ $t("appFilter_title") }}
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
                        class="p-button-text"
                        @click="fetchItems()"
                    />
                </template>

                <template #empty>
                    {{ $t("data_not_found", { data: "settings" }) }}
                </template>

                <template #loading>
                    {{ $t("loader", { data: "settings" }) }}
                </template>

                <!-- SELECTION -->
                <Column
                    selectionMode="multiple"
                    style="width: 3rem"
                    :exportable="false"
                    :disabled="!props.can.appSettings_delete"
                />

                <!-- KEY -->
                <Column
                    :field="state.columns.key.field"
                    :header="$t(state.columns.key.field)"
                    style="min-width: 16rem"
                    :sortable="state.columns.key.is_sortable"
                />

                <!-- VALUE -->
                <Column
                    :field="state.columns.value.field"
                    :header="$t(state.columns.value.field)"
                    style="min-width: 16rem"
                    :sortable="state.columns.value.is_sortable"
                />

                <!-- ACTIVE -->
                <Column
                    :field="state.columns.active.field"
                    :header="$t(state.columns.active.field)"
                    style="min-width: 16rem"
                    :sortable="state.columns.active.is_sortable"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="getStatusValue(slotProps.data.active)"
                            :severity="getStatusSeverity(slotProps.data.active)"
                        />
                    </template>
                </Column>

                <!-- ACTIONS -->
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            :disabled="!props.can.appSettings_edit"
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            class="mr-2"
                            @click="editSetting(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteSetting(slotProps.data)"
                            :disabled="!props.can.appSettings_delete"
                        />
                    </template>
                </Column>

            </DataTable>

        </div>

        <!-- SETTINGS DIALOG -->
        <Dialog
            v-model:visible="settingsDialog"
            :style="{ width: '550px' }"
            :header="getModalTitle()"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;"></div>
        </Dialog>

        <!-- SETTINGS -->
        <Dialog
            v-model:visible="settingsDialog"
            :style="{ width: '550px' }"
            :header="$t('app_settings_title')"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
                <div
                    v-for="(sonfig, column) in state.columns" :key="column"
                    class="d-flex align-items-center"
                >
                    <input v-model="config.is_visible"
                        :id="column" class="me-3" type="checkbox" />
                    <label :for="column">{{ $t(config.label) }}</label>
                </div>

            </div>
        </Dialog>

    </AppLayout>
</template>
