<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

import { useToast } from "primevue/usetoast";
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import Toolbar from 'primevue/toolbar';
import { DataTable } from 'primevue/datatable';

const toast = useToast();

const dt = ref();

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

            <DataTable></DataTable>

        </div>

    </AppLayout>
</template>
