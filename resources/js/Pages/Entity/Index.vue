<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

import EntityService from '@/service/EntityService';

import { FilterMatchMode, FilterOperator } from "@primevue/core/api";

import { useToast } from "primevue/usetoast";
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import Toolbar from 'primevue/toolbar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import { IconField, InputIcon } from 'primevue';

const toast = useToast();

const dt = ref();
const loading = ref(true);

const entities = ref([]);
const entity = {
    id: null,
    name: '',
    email: '',
    start_date: '',
    end_date: '',
    last_export: '',
    company_id: null,
    active: true
};

const submitted = ref(false);

const selectedEntities = ref([]);

const confirmDeleteSelected = () => {}

const entityDialog = ref(false);

const filters = ref({});

const initFilters = () => {
    filters.value = {
        global: {
            value: null,
            matchMode: FilterMatchMode.CONTAINS
        },
        name: {}
    };
}

const clearFilter = () => {
    initFilters();
};

initFilters();

const fetchItems = async () => {
    loading.value = true;

    try {
        const response = await EntityService.getEntities();
        entities.value = response.data.data;
    } catch(error) {
        console.error("getEntities API Error:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchItems();
});

const initialEntity = () => {
    return {...entity};
};

const openNew = () => {
    EntityService.value = {...initialEntity};
    submitted.value = false;
    entityDialog.value = true;
}

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

</script>

<template>
    <AppLayout>
        <Head :title="$t('entities')" />

        <div class="card">

            <Toolbar class="md-6">
                <template #start>

                    <!-- Add New Button -->
                    <Button
                        :label="$t('add_new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete_selected')"
                        icon="pi pi-trash"
                        severity="secondary"
                        @click="confirmDeleteSelected"
                        :disabled="!selectedEntities || !selectedEntities.length"
                    />
                </template>

                <template #end>

                    <!-- Upload Button -->
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

                    <!-- Export Button -->
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
                v-model:selection="selectedEntities"
                v-model:filters="filters"
                :value="entities"
                dataKey="id" :paginator="true" :rows="10" sortMode="multiple"
                :filters="filters" filterDisplay="menu"
                :globalFilterFields="['name']"
                :loading="loading" stripedRows removableSort
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} subdomains"
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
                            {{ $t("entities_title") }}
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
                        icon="pi pi-refresh" text
                        @click="fetchItems"
                    />
                </template>

                <template #empty>
                    {{ $t('data_not_found', {data: $t('entity') || $t('entities') } ) }}
                </template>

                <template #loading>
                    {{ $t('loader', {data: $t('entity') || $t('entities') }) }}
                </template>

                <!-- Checkbox -->
                <Column
                    selectionMode="multiple"
                    style="min-width: 3rem"
                    :exportable="false"
                />

                <!-- Name -->
                <Column
                    field="name"
                    :header="$t('name')"
                    style="min-width: 12rem"
                    sortable
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

                <!-- Email -->
                <Column 
                    field="email" 
                    :header="$t('email')"
                    style="min-width: 12rem"
                    sortable
                >

                    <template #body="slotProps">
                        {{ slotProps.data.email }}
                    </template>
                    <template #filter="slotProps">
                        <InputText
                            v-model="slotProps.filterValue"
                            type="text"
                            :placeholder="$t('search_by', {data: 'email'})"
                        />
                    </template>
                </Column>

            </DataTable>

        </div>

    </AppLayout>
</template>
