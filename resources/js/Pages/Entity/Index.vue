<script setup>
//
import {computed, onMounted, reactive, ref, watch} from 'vue';
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

import {Toolbar,DataTable,Column,IconField,
    InputText,InputIcon,Button,Dialog,
    Select,Tag,FileUpload,FloatLabel,
    Message,Checkbox, DatePicker} from "primevue";

import { format } from 'date-fns';

// TOAST
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from '@/Validation/ValidationRules.json';

//
import EntityService from "@/service/EntityService.js";
import ErrorService from "@/service/ErrorService.js";
import { createId } from "@/helpers/functions.js";
import {trans} from "laravel-vue-i18n";
import {FilterMatchMode} from "@primevue/core/api";
import CompanyService from "@/service/CompanyService.js";

//
const toast = useToast();
const loading = ref(false);
const dt = ref();
const filters = ref({});
const submitted = ref(false);

const local_storage_column_key = 'ln_entities_grid_columns';

//
const props = defineProps({
    users: { type: Object, default: () => {}, },
    companies: { type: Object, default: () => {}, },
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

//
const defaultEntity = {
    id: null,
    name: "",
    email: "",
    start_date: "",
    end_date: "",
    last_export: "",
    user_id: null,
    company_id: null,
    active: 1
};

const entities = ref();
const entity = ref({ ...defaultEntity });
const selectedEntities = ref();

const initialEntity = () => {
    return { ...defaultEntity };
};

const rules = {
    name:        { required: helpers.withMessage(trans("validate_directory"), required), },
    email:       { required: helpers.withMessage(trans("validate_directory"), required), },
    start_date:  { required: helpers.withMessage(trans("validate_directory"), required), },
    end_date:    { required: helpers.withMessage(trans("validate_directory"), required), },
    last_export: { required: helpers.withMessage(trans("validate_directory"), required), },
    user_id:     { required: helpers.withMessage(trans("validate_directory"), required), },
    company_id:  { required: helpers.withMessage(trans("validate_directory"), required), }
};
const v$ = useVuelidate(rules, entity);

const state = reactive({
    columns: {
        "id":          { field: "id", is_visible: true, is_sortable: true, is_filterable: true },
        "name":        { field: "name", is_visible: true, is_sortable: true, is_filterable: true },
        "email":       { field: "email", is_visible: true, is_sortable: true, is_filterable: true },
        "start_date":  { field: "start_date", is_visible: true, is_sortable: true, is_filterable: true },
        "end_date":    { field: "end_date", is_visible: true, is_sortable: true, is_filterable: true },
        "last_export": { field: "last_export", is_visible: true, is_sortable: true, is_filterable: true },
        "user_id":     { field: "user_id", is_visible: true, is_sortable: true, is_filterable: true },
        "company_id":  { field: "company_id", is_visible: true, is_sortable: true, is_filterable: true },
        "active":      { field: "active", is_visible: true, is_sortable: true, is_filterable: true }
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
}, { deep: true });

const fetchItems = async () => {
    loading.value = true;

    await EntityService.getEntities()
        .then((response) => {
            entities.value = response.data;
        })
        .catch((error) => {

            ErrorService.logClientError(error, {
                componentName: "Fetch Entities",
                additionalInfo: "Failed to retrieve the entity",
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

const saveEntity = async () => {

    entity.value.start_date = format(entity.value.start_date, "yyyy-MM-dd");
    //entity.value.end_date = format(entity.value.end_date, "yyyy-MM-dd");
    //entity.value.last_import = format(entity.value.last_import, "yyyy-MM-dd");

    entity.value.end_date = entity.value.end_date
        ? format(new Date(entity.value.end_date), "yyyy-MM-dd")
        : null;
    entity.value.last_import = entity.value.last_export
        ? format(new Date(entity.value.last_export), "yyyy-MM-dd")
        : null;

    console.log("Mentésre kerül:", entity.value);
    /*
    const result = await v$.value.$validate();

    if(result) {
        submitted.value = true;

        if (entity.value.id) {
            await updateEntity();
        } else {
            await createEntity();
        }
    } else {
        const validationErrors = v$.value.$errors.map((error) => ({
            field: error.$property,
            message: trans(error.$message),
        }));

        const data = {
            componentName: "saveEntity",
            additionalInfo: "Client-side validation failed during entity update",
            category: "Validation Error",
            priority: "low",
            validationErrors: validationErrors,
        };

        await ErrorService.logValidationError(new Error('Client-side validation error'), data);

        toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: "Please fix the highlighted errors before submitting.",
        });
    }
    */
};

const createEntity = async () => {
    const newEntity = {...entity.value, id: createId() };

    entities.value.push(newEntity);

    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "Entity creation in progress",
        life: 3000,
    });

    await EntityService.createEntity(entity.value)
        .then((response) => {
            const index = findIndexById(newEntity.id);
            if (index !== -1) {
                entities.value.splice(index, 1, response.data);
            }

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Entity Created",
                life: 3000,
            });
        })
        .catch((error) => {
            if( error.response && error.response.status === 422 ) {
                const validationErrors = error.response.data.details;

                toast.add({
                    severity: "warn",
                    summary: "Validation Error",
                    detail: "Please check your inputs",
                    life: 4000,
                });

                ErrorService.logClientError(error, {
                    componentName: "CreateEntityDialog",
                    additionalInfo: "Validation errors occurred during entity creation",
                    category: "Validation Error",
                    priority: "medium",
                    validationErrors: validationErrors,
                });
            } else {
                const index = findIndexById(newEntity.id);
                if (index !== -1) {
                    entities.value.splice(index, 1);
                }

                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: trans('error_entity_create'),
                });

                ErrorService.logClientError(error, {
                    componentName: "CreateEntityDialog",
                    additionalInfo: "Failed to create a entity in the backend",
                    category: "Error",
                    priority: "high",
                    data: entity.value,
                });
            }
        });
};

const updateEntity = async () => {
    const index = findIndexById(entity.value.id);
    if (index === -1) {
        console.error(`Entity with id ${entity.value.id} not found`);
        return;
    }

    const originalEntity = { ...entities.value[index] };

    entities.value.splice(index, 1, { ...entity.value });

    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "Entity update in progress",
        life: 2000,
    });

    await EntityService.updateEntity(company.value.id, company.value)
        .then((response) => {
            // Sikeres válasz kezelése
            // Frissített adat a válaszból
            entities.value.splice(index, 1, response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Entity Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            // Sikertelen frissítés esetén az eredeti adat visszaállítása
            entities.value.splice(index, 1, originalEntity);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to update entity",
            });

            // Hiba naplózása a szerver felé
            ErrorService.logClientError(error, {
                componentName: "UpdateEntityDialog",
                additionalInfo: "Failed to update a entity in the backend",
                category: "Error",
                priority: "medium",
                data: entity.value,
            });
        });
};

const deleteEntity = async () => {
    const index = findIndexById(entity.value.id);
    if (index === -1) {
        console.warn("No entity found with the given id:", entity.value.id);
        return;
    }

    const originalEntity = { ...entities.value[index] };

    entities.value.splice(index, 1);

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Entity deletion in progress",
        life: 2000,
    });

    await CompanyService.deleteCompany(id)
        .then((response) => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Entity Deleted",
                life: 3000,
            });
        })
        .catch((error) => {
            entities.value.splice(index, 0, originalEntity);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete entity",
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteEntityDialog",
                additionalInfo: "Failed to delete a entity in the backend",
                category: "Error",
                priority: "medium",
                data: entity.value,
            });

        });
};

const deleteEntities = async () => {
    const originalEntities = [...entities.value];

    selectedEntities.value.forEach(selectedEntity => {
        const index = entities.value.findIndex(entity => entity.id === selectedEntity.id);
        if (index !== -1) {
            entities.value.splice(index, 1);
        }
    });

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Deleting selected entities...",
        life: 2000,
    });

    await EntityService.deleteEntities(selectedEntities.value.map(entity => entity.id))
        .then((response) => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Selected entities deleted",
                life: 3000,
            });

            selectedEntities.value = [];
        })
        .catch((error) => {
            entities.value = originalEntities;

            const errorMessage = error.response?.data?.error || "Failed to delete selected entities";

            toast.add({
                severity: "error",
                summary: "Error",
                detail: errorMessage,
                life: 3000,
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteEntitiesDialog",
                additionalInfo: "Failed to delete a entities in the backend",
                category: "Error",
                priority: "low",
                data: companies.value
            });
        });
};

/**
 * ========================================================
 * Dialog kezelés
 * ========================================================
 */
const settingsDialog = ref(false);
const entityDialog = ref(false);
const deleteEntityDialog = ref(false);
const deleteEntitiesDialog = ref(false);

const openSettingsDialog = () => {
    settingsDialog.value = true;
};

const confirmDeleteSelected = () => {};

const confirmDeleteEntity = (data) => {}

const openNew = () => {
    //console.log('openNew');
    entity.value = initialEntity();
    entityDialog.value = true;
    submitted.value = false;
};

const openEdit = (data) => {
    //console.log('data', data);
    entity.value = {...data};

    //entity.value.start_date = new Date(data.start_date); // Átalakítás
    //entity.value.end_date = new Date(data.end_date); // Átalakítás
    //entity.value.last_export = new Date(data.last_export); // Átalakítás

    //console.log('entity', entity.value);

    entityDialog.value = true;
};

const startDate = computed({
    get: () => entity.value.start_date ? new Date(entity.value.start_date) : null,
    set: (value) => entity.value.start_date = value,
});

const endDate = computed({
    get: () => entity.value.end_date ? new Date(entity.value.end_date) : null,
    set: (value) => entity.value.end_date = value,
});

const lastExport = computed({
    get: () => entity.value.last_export ? new Date(entity.value.last_export) : null,
    set: (value) => entity.value.last_export = value,
});

const hideDialog = () => {
    settingsDialog.value = false;
    entityDialog.value = false;
    deleteEntityDialog.value = false;
    deleteEntitiesDialog.value = false;
};

/**
 * ========================================================
 */

/**
 * ========================================================
 * Filter kezelés
 * ========================================================
 */
const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS},
        name: {value: null, matchMode: FilterMatchMode.CONTAINS},
        email: {value: null, matchMode: FilterMatchMode.CONTAINS},
        //start_date: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
        //end_date: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
        //last_export: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
        //user_id: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
        //company_id: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
        //active: {value: null, matchMode: FilterMatchMode.STARTS_WITH}
    };
}
const clearFilter = () => {
    initFilters();
};

initFilters();
/**
 * ========================================================
 */

const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

const findIndexById = (id) => {
    return entities.value.findIndex((entity) => entity.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const getModalTitle = () => {
    return entity.value.id
        ? trans("entities_edit_title")
        : trans("entities_new_title");
};

const getModalDetails = () => {
    return entity.value.id
        ? trans("entities_edit_details")
        : trans("entities_new_details");
};

const getBools = () => {
    return [
        {label: trans("inactive"),value: 0,},
        {label: trans("active"),value: 1,},
    ];
};

</script>

<template>
    <AppLayout>
        <Head :title="$t('entities')" />

        <Toast />
        <!--
        <pre>
            {{ $page.props.users }}
        </pre>
        -->
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
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                        :disabled="!props.can.entities_create"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        icon="pi pi-trash"
                        severity="secondary"
                        class="mr-2"
                        @click="confirmDeleteSelected"
                        :disabled="!props.can.entities_delete ||
                            !selectedEntities ||
                            !selectedEntities.length"
                    />

                </template>

                <template #center>
                    <!-- FELIRAT -->
                    <div class="font-semibold text-xl mb-1">
                        {{ $t("entities_title") }}
                    </div>
                </template>

                <template #end>

                    <!-- Feltöltés -->
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

                    <!-- Exportálás -->
                    <Button
                        :label="$t('export')"
                        icon="pi pi-upload"
                        severity="secondary"
                        @click="exportCSV($event)"
                    />

                </template>
            </Toolbar>
        </div>

        <div class="card">
            <DataTable
                ref="dt"
                v-model:selection="selectedEntities"
                v-model:filters="filters"
                filterDisplay="row"
                :value="entities"
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
                        <!--
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("entities_title") }}
                        </div>
                        -->
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
                    {{ $t("data_not_found", { data: "entity" }) }}
                </template>

                <template #loading>
                    {{ $t("loader", { data: "Entity" }) }}
                </template>

                <!-- SELECTION -->
                <Column
                    selectionMode="multiple"
                    style="width: 3rem"
                    :exportable="false"
                    :disabled="!props.can.entities_delete"
                />

                <!-- ID -->
                <Column
                    :field="state.columns.id.field"
                    :header="$t(state.columns.id.field)"
                    :sortable="state.columns.id.is_sortable"
                    :hidden="!state.columns.id.is_visible"
                    style="min-width: 16rem"
                />

                <!-- NAME -->
                <Column
                    :field="state.columns.name.field"
                    :header="$t(state.columns.name.field)"
                    :sortable="state.columns.name.is_sortable"
                    :hidden="!state.columns.name.is_visible"
                    style="min-width: 16rem"
                >
                    <template #body="{ data }">
                        {{ data.name }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            @input="filterCallback()"
                            placeholder="Search by name"
                        />
                    </template>
                </Column>

                <!-- EMAIL -->
                <Column
                    :field="state.columns.email.field"
                    :header="$t(state.columns.email.field)"
                    :sortable="state.columns.email.is_sortable"
                    :hidden="!state.columns.email.is_visible"
                    style="min-width: 16rem"
                >
                    <template #body="{ data }">
                        {{ data.email }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            @input="filterCallback()"
                            placeholder="Search by email"
                        />
                    </template>
                </Column>

                <!-- START DATE -->
                <Column
                    :field="state.columns.start_date.field"
                    :header="$t(state.columns.start_date.field)"
                    :sortable="state.columns.start_date.is_sortable"
                    :hidden="!state.columns.start_date.is_visible"
                    style="min-width: 16rem"
                ></Column>

                <!-- END DATE -->
                <Column
                    :field="state.columns.end_date.field"
                    :header="$t(state.columns.end_date.field)"
                    :sortable="state.columns.end_date.is_sortable"
                    :hidden="!state.columns.end_date.is_visible"
                    style="min-width: 16rem"
                ></Column>

            <!-- ACTIONS -->
            <Column :exportable="false" style="min-width: 12rem">
                <template #body="slotProps">
                    <Button
                        icon="pi pi-pencil"
                        outlined
                        rounded
                        class="mr-2"
                        @click="openEdit(slotProps.data)"
                        :disabled="!props.can.entities_edit"
                    />
                    <Button
                        icon="pi pi-trash"
                        outlined
                        rounded
                        severity="danger"
                        @click="confirmDeleteEntity(slotProps.data)"
                        :disabled="!props.can.entities_delete"
                    />
                </template>
            </Column>

            </DataTable>
        </div>

        <!-- SETTINGS DIALOG -->
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

        <!-- EDIT ENTITY DIALOG -->
        <Dialog
            v-model:visible="entityDialog"
            :style="{ width: '550px' }"
            :header="getModalTitle()"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
                <!-- NAME -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="name" class="block font-bold mb-3">
                            {{ $t("name") }}
                        </label>
                        <InputText
                            id="name"
                            v-model="entity.name"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_company_name') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.name.$error">
                        {{ $t(v$.name.$errors[0].$message) }}
                    </small>
                </div>

                <!-- EMAIL -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="email" class="block font-bold mb-3">
                            {{ $t("email") }}
                        </label>
                        <InputText
                            id="email"
                            v-model="entity.email"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_email') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.email.$error">
                        {{ $t(v$.email.$errors[0].$message) }}
                    </small>
                </div>

                <div class="flex flex-wrap gap-4">

                    <!-- START DATE -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <FloatLabel>
                            <label for="start_date" class="block font-bold mb-3">
                                {{ $t("entities_start_date") }}
                            </label>

                            <DatePicker
                                v-model="startDate"
                                dateFormat="yy-mm-dd"
                                fluid showIcon showButtonBar
                            />
                        </FloatLabel>
                        <Message
                            size="small"
                            severity="secondary"
                            variant="simple"
                        >
                            {{ $t('enter_entity_start_date') }}
                        </Message>
                        <small class="text-red-500" v-if="v$.start_date.$error">
                            {{ $t(v$.start_date.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- END DATE -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <FloatLabel>
                            <label for="end_date" class="block font-bold mb-3">
                                {{ $t("entities_end_date") }}
                            </label>
                            <DatePicker
                                id="end_date"
                                v-model="endDate"
                                dateFormat="yy-mm-dd"
                                fluid showIcon showButtonBar
                            />
                        </FloatLabel>
                        <Message
                            size="small"
                            severity="secondary"
                            variant="simple"
                        >
                            {{ $t('enter_entity_end_date') }}
                        </Message>
                        <small class="text-red-500" v-if="v$.end_date.$error">
                            {{ $t(v$.end_date.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- LAST EXPORT DATE -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <FloatLabel>
                            <label for="last_export" class="block font-bold mb-3">
                                {{ $t("entities_last_export") }}
                            </label>
                            <DatePicker
                                v-model="lastExport"
                                dateFormat="yy-mm-dd"
                                fluid showIcon showButtonBar
                            />
                        </FloatLabel>
                        <Message
                            size="small"
                            severity="secondary"
                            variant="simple"
                        >
                            {{ $t('enter_entity_last_export') }}
                        </Message>
                        <small class="text-red-500" v-if="v$.last_export.$error">
                            {{ $t(v$.last_export.$errors[0].$message) }}
                        </small>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <!-- USER_ID -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <FloatLabel>
                            <label for="user_id" class="block font-bold mb-3">
                                {{ $t("user_id") }}
                            </label>
                            <Select
                                id="user_id"
                                v-model="entity.user_id"
                                :options="props.users"
                                optionLabel="name"
                                optionValue="id"
                                :placeholder="$t('user')"
                                fluid
                            />
                        </FloatLabel>
                        <Message
                            size="small"
                            severity="secondary"
                            variant="simple"
                        >
                            {{ $t('enter_user_id') }}
                        </Message>
                        <small class="text-red-500" v-if="v$.user_id.$error">
                            {{ $t(v$.user_id.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- COMPANY_ID -->
                    <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="company_id" class="block font-bold mb-3">
                            {{ $t("company_id") }}
                        </label>
                        <Select
                            id="company_id"
                            v-model="entity.company_id"
                            :options="props.companies"
                            optionLabel="name"
                            optionValue="id"
                            :placeholder="$t('company')"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_company_id') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.company_id.$error">
                        {{ $t(v$.company_id.$errors[0].$message) }}
                    </small>
                </div>
                </div>
                <!-- ACTIVE -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="active" class="block font-bold mb-3">
                            {{ $t("active") }}
                        </label>
                        <Select
                            id="active"
                            v-model="entity.active"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Active" fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_active') }}
                    </Message>
                </div>

            </div>

            <template #footer>
                <Button
                    :label="$t('cancel')"
                    icon="pi pi-times"
                    text
                    @click="hideDialog"
                />
                <Button
                    :label="$t('save')"
                    icon="pi pi-check"
                    @click="saveEntity"
                />

            </template>

        </Dialog>

        <!-- DELETE ENTITY DIALOG -->
        <Dialog></Dialog>

        <!-- DELETE ENTITIES DIALOG -->
        <Dialog></Dialog>

    </AppLayout>
</template>
