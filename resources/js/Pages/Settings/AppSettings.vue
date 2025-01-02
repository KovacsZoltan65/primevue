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
import Message from "primevue/message";
import Tag from "primevue/tag";
import FileUpload from "primevue/fileupload";
import Checkbox from "primevue/checkbox";
import { createId } from "@/helpers/functions";
import FloatLabel from "primevue/floatlabel";
import ErrorService from "@/service/ErrorService";

/**
 * Szerver felöl jövő adatok
 */
const props = defineProps({
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

const app_settings = ref();
const defaultSetting = {
    id: null,
    key: "",
    value: "",
    active: 1,
};

// Tároló kulcsok
const local_storage_column_key = 'ln_app_settings_grid_columns';
const local_storage_app_settings = 'app_settings';

const app_setting = ref({ ...defaultSetting });

const initialSetting = () => {
    return { ...defaultSetting };
};

/**
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 *
 * @type {ref<boolean>}
 */
const submitted = ref(false);

// Szabályok
const rules = {
    key: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
    value: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
};

/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 *
 * @type {Object}
 */
const v$ = useVuelidate(rules, app_setting);

const state = reactive({
    columns: {
        'id': { field: 'id', is_visible: true, is_sortable: true, is_filterable: true },
        'key': { field: 'key', is_visible: true, is_sortable: true, is_filterable: true },
        'value': { field: 'value', is_visible: true, is_sortable: true, is_filterable: true },
        'active':  { field: 'active', is_visible: true, is_sortable: true, is_filterable: true },
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
}, { deep: true });

/**
 * Reaktív hivatkozás a kijelölt beállítások tárolására.
 *
 * @type {ref<Array>}
 */
const selectedSettings = ref([]);

/**
 * ===========================================
 * DIALOGOK
 * ===========================================
 */
// táblázat beállításai
const settingsDialog = ref(false);
// új beállítás készítéséhez, vagy meglevő szerkesztéshez
const settingDialog = ref(false);
// kiválasztott beállítások törléséhez
const deleteSelectedSettingsDialog = ref(false);
// beállítás törléséhez
const deleteSettingDialog = ref(false);
// ======================================================

const fetchItems = async () => {
    loading.value = true;

    //let _settings = localStorage.getItem(local_storage_app_settings);
    //if( _settings ) {
        
    //    app_settings.value = JSON.parse(_settings);

    //    loading.value = false;
    //} else {
        await AppSettingsService.getSettings()
            .then((response) => {
                app_settings.value = response.data;
console.log('app_settings.value: ', app_settings.value);
                //localStorage.setItem(local_storage_app_settings, JSON.stringify(response.data));
            })
            .catch((error) => {
                console.error("getSettings API Error:", error);

                ErrorService.logClientError(error, {
                    componentName: "Fetch AppSettings",
                    additionalInfo: "Failed to retrieve the app settings",
                    category: "Error",
                    priority: "high",
                    data: null,
                });
            })
            .finally(() => {
                loading.value = false;
            });
    //}
};

onMounted(() => {
    fetchItems();

    let columns = localStorage.getItem(local_storage_column_key);
    if (columns) {
        columns = JSON.parse(columns);
        for (const column_name in columns) {
            if (state.columns[column_name]) {
                state.columns[column_name] = columns[column_name];
            }
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

        //localStorage.removeItem(local_storage_app_settings);
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

const deleteSelectedSettings = async () => {
    const originalAppSettings = [...app_settings.value];

    selectedSettings.value.forEach(selectedSetting => {
        const index = app_settings.value.findIndex(setting => setting.id === selectedSetting.id);
        if (index !== -1) {
            app_settings.value.splice(index, 1);
        }
    });

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Deleting selected settings...",
        life: 2000,
    });

    await AppSettingsService.deleteSettings(selectedSettings.value.map(setting => setting.id))
        .then((response) => {

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Selected app settings deleted",
                life: 3000,
            });

            selectedSettings.value = [];
        })
        .catch((error) => {
            app_settings.value = originalAppSettings;

            const errorMessage = error.response?.data?.error || "Failed to delete selected app settings";

            toast.add({
                severity: "error",
                summary: "Error",
                detail: errorMessage,
                life: 3000,
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteCompaniesDialog",
                additionalInfo: "Failed to delete app settings in the backend",
                category: "Error",
                priority: "low",
                data: app_settings.value
            });
        });
};

const deleteSetting = async () => {
    const index = findIndexById(app_setting.value.id);
    if (index === -1) {
        console.warn("No app setting found with the given id:", app_setting.value.id);
        return;
    }

    const originalAppSetting = { ...app_settings.value[index] };
    app_settings.value.splice(index, 1);
    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "App Setting deletion in progress",
        life: 2000,
    });

    await AppSettingsService.deleteSetting(app_setting.value.id)
        .then((response) => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "App Setting Deleted",
                life: 3000,
            });
        })
        .catch((error) => {
            app_settings.value.splice(index, 0, originalAppSetting);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to app setting company",
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteAppSettingDialog",
                additionalInfo: "Failed to delete app setting in the backend",
                category: "Error",
                priority: "medium",
                data: app_setting.value,
            });
        });
};

const confirmDeleteSelected = () => {
    deleteSelectedSettingsDialog.value = true;
};

const confirmDeleteSetting = (data) => {
    app_setting.value = {...data};
    deleteSettingDialog.value = true;
};

const openSettingsDialog = () => {
    settingsDialog.value = true;
}

const findIndexById = (id) => {
    return app_settings.value.findIndex((setting) => setting.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const getModalTitle = () => {
    return app_setting.value.id
        ? trans('edit_setting')
        : trans('add_new_setting');
};

const getModalDetails = () => {
    return app_setting.value.id
        ? trans("app_settings_edit_details")
        : trans("app_settings_new_details");
};

const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

const getStatusLabel = (setting) => {
    switch (setting.active) {
        case 0:
            return "danger";
        case 1:
            return "success";
        default:
            return "danger";
    }
};

const getStatusValue = (setting) => {
    switch (setting.active) {
        case 0:
            return trans('inactive');
        case 1:
            return trans('active');
        default:
            return trans('unknown');
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
</script>

<template>
    <AppLayout>
        <Head :title="$t('application_settings')" />

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
                            @click="clearFilters()"
                        />

                        <!-- FELIRAT -->
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("app_settings_title") }}
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

                <!-- ACTIVE -->
                <Column
                    field="active"
                    :header="$t('active')"
                    style="min-width: 16rem"
                    :sortable="state.columns.active.is_sortable"
                    :hidden="!state.columns.active.is_visible"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="getStatusValue(slotProps.data)"
                            :severity="getStatusLabel(slotProps.data)"
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

        <!-- SETTING DIALOG -->
        <Dialog
            v-model:visible="settingDialog"
            :style="{ width: '550px' }"
            :header="getModalTitle()"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
                {{ app_setting.active }}
                <!-- KEY -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="key" class="block font-bold mb-3">
                            {{ $t("key") }}
                        </label>
                        <InputText
                            id="key"
                            v-model="app_setting.key"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_key') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.key.$error">
                        {{ $t(v$.key.$errors[0].$message) }}
                    </small>
                </div>

                <!-- VALUE -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="value" class="block font-bold mb-3">
                            {{ $t("value") }}
                        </label>
                        <InputText
                            id="value"
                            v-model="app_setting.value"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_value') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.value.$error">
                        {{ $t(v$.value.$errors[0].$message) }}
                    </small>
                </div>

                <!-- AKTÍV -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <Select
                        id="active"
                        name="active"
                        v-model="app_setting.active"
                        :options="getBools()"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Active"
                    />
                    <label for="active">active</label>
                </div>
            </div>
        </Dialog>

        <!-- 
            SETTINGS DIALOG táblázat
         -->
        <Dialog
            v-model:visible="settingsDialog"
            :style="{ width: '550px' }"
            :header="$t('app_settings_title')"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
            
                <div class="flex flex-col gap-2">

                    <div class="flex flex-wrap gap-4">
                        <div
                            v-for="(config, column) in state.columns" 
                            :key="column"
                            class="flex items-center gap-2">
                            <Checkbox 
                                v-model="config.is_visible" 
                                :inputId="column" 
                                :value="true" binary
                            />
                            <label :for="column">{{ column }}</label>
                        </div>
                    </div>

                </div>

            </div>
        </Dialog>

    </AppLayout>
</template>
