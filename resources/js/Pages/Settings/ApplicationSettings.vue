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

const state = reactive({
    columns: {
        id: { field: 'id', is_visible: true, is_sortable: true, is_filterable: true },
        key: { field: 'key', is_visible: true, is_sortable: true, is_filterable: true },
        value: { field: 'value', is_visible: true, is_sortable: true, is_filterable: true },
        is_active:  { field: 'is_active', is_visible: true, is_sortable: true, is_filterable: true },
    }
});

const local_storage_column_key = 'ln_app_settins_grid_columns';
watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
});

const initialSetting = () => {
    return {...app_setting.value};
};

const selectedSettings = ref([]);
const settingDialog = ref(false);
const settingsDialog = ref(false);
const deleteSelectedSettingsDialog = ref(false);
const deleteSettingDialog = ref(false);

const v$ = useVuelidate(rules, app_setting);

const fetchItems = async () => {
    loading.value = true;

    await ApplicationSettingsService.getSettings()
        .then((response) => {
            app_settings.value = response.data;
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

    let columns = localStorage.getItem(local_storage_column_key);
    if (columns) {
        columns = JSON.parse(columns);
        for(const column_name in columns) {
            state.columns[column_name] = columns[column_name];
        }
    }
});

const openNew = () => {
    //
};

const editSetting = (data) => {
    app_setting.value = {...data};
    settingDialog.value = true;
}

const confirmDeleteSelected = () => {
    //
};

const confirmDeleteSetting = () => {
    //
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
    switch (status) {
        case 0:
            return "danger";
        case 1:
            return "success";
    }
};

const getStatusValue = (status) => {
    switch (status) {
        case 0:
            return "INACTIVE";
        case 1:
            return "ACTIVE";
    }
};

</script>

<template>
    <AppLayout>
        <Head :title="$t('application_settings')" />

        <Toast />

        {{ app_settings }}

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
                        :disabled="!props.can.create"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete_selected')"
                        icon="pi pi-trash"
                        severity="secondary"
                        class="mr-2"
                        @click="confirmDeleteSelected"
                        :disabled="
                            !selectedSettings || !selectedSettings.length
                        "
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

                <template #header></template>

                <template #paginatorstart></template>

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

                <!-- IS ACTIVE -->
                <Column
                    :field="state.columns.is_active.field"
                    :header="$t(state.columns.is_active.field)"
                    style="min-width: 16rem"
                    :sortable="state.columns.is_active.is_sortable"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="getStatusValue(slotProps.data.is_active)"
                            :severity="getStatusSeverity(slotProps.data.is_active)"
                        />
                    </template>
                </Column>

                <!-- ACTIONS -->
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            :disabled="!props.can.edit"
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
                            :disabled="!props.can.delete"
                        />
                    </template>
                </Column>

            </DataTable>

        </div>

        <!-- SETTINGS -->
        <Dialog
            v-model:visible="settingsDialog"
            :style="{ width: '550px' }"
            :header="SETTINGS"
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
