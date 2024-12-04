<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button, Column, DataTable, FileUpload, IconField, InputIcon, InputText, Toast, Toolbar } from 'primevue';
import { FilterMatchMode } from "@primevue/core/api";

import PermissionService from "@/service/PermissionService";

// TOAST
import { useToast } from "primevue/usetoast";
import { onMounted, ref } from 'vue';

const props = defineProps({
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});
const toast = useToast();
const dt = ref();

const permissions = ref();
const permission = ref({
    id: null,
    name: "",
    guard_name: "web",
});

const submitted = ref(false);
const permissionDialog = ref(false);
const selectedPermissions = ref([]);
const filters = ref({});
const loading = ref(true);

const initialPermission = () => {
    return {...permission};
};

const fetchItems = async () => {
    loading.value = true;

    await PermissionService.getPermissions()
        .then((response) => {
            permissions.value = response.data;
        })
        .catch((error) => {
            console.error("getPermissions API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch Permissions",
                additionalInfo: "Failed to retrieve the permission",
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

const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS},
        name: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
    }
};

const clearFilter = () => {
    initFilters();
};

initFilters();

const openNew = () => {
    permission.value = initialPermission();
    submitted.value = false;
    permissionDialog.value = true;
};

const confirmDeleteSelected = () => {};

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
        <Head :title="$t('permissions')"/>

        <Toast />

        <div class="card">
            <Toolbar class="md-6">
                <template #start>
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
                            !selectedPermissions || !selectedPermissions.length
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
                v-model:selection="selectedPermissions"
                v-model:filters="filters"
                filterDisplay="menu"
                :value="permissions"
                dataKey="id"
                :paginator="true" :rows="10" sortMode="multiple"
                :loading="loading" stripedRows removableSort
                :globalFilterFields="['name']"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
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
                            {{ $t("companies_title") }}
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
                    {{ $t("data_not_found", { data: "permissions" }) }}
                </template>
                <template #loading>
                    {{ $t("loader", { data: "Permissions" }) }}
                </template>

                <!-- SELECTION -->
                <Column
                    selectionMode="multiple"
                    style="width: 3rem"
                    :exportable="false"
                />

                <!-- NAME -->
                <Column
                    field="name"
                    :header="$t('name')"
                    sortable
                    style="min-width: 16rem"
                >
                    <template #body="slotProps">
                        {{ slotProps.data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'name'})"
                        />
                    </template>
                </Column>

            </DataTable>
        </div>

    </AppLayout>
</template>
