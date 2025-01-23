<script setup>
import { onMounted, ref } from 'vue';
import CompanyDialog from './CompanyDialog.vue';
import { Button, Column, DataTable, Toolbar } from 'primevue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import CompanyService from '@/service/CompanyService';

const companies = ref([]); // List of companies
const selectedCompany = ref(null); // Company being edited or added
const isDialogVisible = ref(false); // Dialog visibility
const dialogHeader = ref(''); // Dialog header text
//const countries = ref([]); // List of countries
//const cities = ref([]); // List of cities
const v$ = ref({}); // Validation object

const props = defineProps({
    countries: { type: Object, default: () => {}, },
    cities: { type: Object, default: () => {}, },
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

const fetchItems = async () => {
    await CompanyService.getCompanies()
        .then((response) => {
            console.log('response.data', response.data);
            companies.value = response.data;
        })
        .catch((error) => {
            console.log('Companies Index error', error);
        })
        .finally(() => {});
};

onMounted(() => {
    fetchItems();
});

// Functions
const addNewCompany = () => {
    selectedCompany.value = {};
    dialogHeader.value = 'Add New Company';
    isDialogVisible.value = true;
};

const editCompany = (company) => {
    selectedCompany.value = { ...company };
    dialogHeader.value = 'Edit Company';
    isDialogVisible.value = true;
};

const deleteCompany = (company) => {
    // Logic to delete a company
    companies.value = companies.value.filter(c => c !== company);
};

const handleCompanySaved = (company) => {
    // Update the list or add new company
    const index = companies.value.findIndex(c => c.id === company.id);
    if (index !== -1) {
        companies.value[index] = company; // Update existing
    } else {
        companies.value.push(company); // Add new
    }
    isDialogVisible.value = false; // Close dialog
};
</script>

<template>
    <AppLayout>

        <Head :title="$t('companies')" />

        <!-- Other UI components and layout -->
        <div class="card">
            
            <Toolbar class="md-6">
                <template #start>

                    <!-- Add New Button -->
                    <Button
                        label="Add New Company"
                        icon="pi pi-plus"
                        @click="addNewCompany"
                    />

                </template>
            </Toolbar>

            <!-- Company Dialog -->
            <CompanyDialog
                v-model:visible="isDialogVisible"
                :header="dialogHeader"
                :company="selectedCompany"
                :countries="countries"
                :cities="cities"
                :v$="v$"
                @company-saved="handleCompanySaved"
                @hide-dialog="isDialogVisible = false"
            />

            <!-- Table and actions -->
            <DataTable :value="companies">
                
                <!-- Columns -->
                <Column field="name" header="Name" />
                <Column field="directory" header="Directory" />
                <Column field="tax_id" header="tax_id" />

                <!-- Actions -->
                <Column>
                    <template #body="{ data }">
                        <Button
                            icon="pi pi-pencil"
                            @click="editCompany(data)"
                        />
                        <Button class="ml-2"
                            icon="pi pi-trash"
                            @click="deleteCompany(data)"
                        />
                    </template>
                </Column>
            </DataTable>

        </div>
    </AppLayout>
</template>