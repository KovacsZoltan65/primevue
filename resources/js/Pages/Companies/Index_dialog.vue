<script setup>
import { onMounted, ref } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

import { Button, Column, DataTable, Toolbar } from 'primevue';

import CompanyDialog from './CompanyDialog.vue';
import CompanyService from '@/service/CompanyService';
import { createId } from "@/helpers/functions";

/* =======================================================
 * TOAST
 * =======================================================
 */
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import ErrorService from '@/service/ErrorService';
const toast = useToast();
// =======================================================

/* =======================================================
 * VALIDATION
 * =======================================================
 */
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from '@/Validation/ValidationRules.json';

const rules = {
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
    country_id: { required: helpers.withMessage(trans("validate_country_id"), required), },
    city_id: { required: helpers.withMessage(trans("validate_city_id"), required), },
    directory: { required: helpers.withMessage(trans("validate_directory"), required), },
    tax_id: {
        required: helpers.withMessage(trans("validate_tax_id"), required),
    },
    registration_number: {
        required: helpers.withMessage(trans("validate_registration_number"), required),
    },
    address: {
        required: helpers.withMessage(trans("validate_address"), required),
    },
};

const v$ = useVuelidate(rules, company); // Validation object
// =======================================================

const companies = ref([]); // List of companies
const selectedCompany = ref(null); // Company being edited or added
const isDialogVisible = ref(false); // Dialog visibility
const dialogHeader = ref(''); // Dialog header text
//const countries = ref([]); // List of countries
//const cities = ref([]); // List of cities





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

const saveCompany = async (company) => {
    const result =  await v$.value.$validate();
    if ( result ) {
        submitted.value = true;
        if (company.value.id) {
            updateCompany(company);
        } else {
            createCompany(company);
        }

        isDialogVisible.value = false; // Close dialog
    } else {
        // Validációs hibák összegyűjtése
        const validationErrors = v$.value.$errors.map((error) => ({
                field: error.$property,
                message: trans(error.$message),
            }));
        // Adatok előkészítése logoláshoz
        const data = {
            componentName: "saveCompany",
            additionalInfo: "Client-side validation failed during company update",
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

// Implement company creation logic
const createCompany = async (company) => {
    const newCompany = {...company.value, id: createId()};
    companies.value.push(newCompany);

    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "Company creation in progress",
        life: 3000,
    });

    await CompanyService.createCompany(newCompany)
        .then((response) => {
            // Lokális adat frissítése a szerver válasza alapján
            const index = findIndexById(newCompany.id);
            if (index !== -1) {
                companies.value.splice(index, 1, response.data);
            }

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Company Created",
                life: 3000,
            });
        })
        .catch((error) => {
            if( error.response && error.response.status === 422){
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
                    additionalInfo: "Validation errors occurred during company creation",
                    category: "Validation Error",
                    priority: "medium",
                    validationErrors: validationErrors,
                });
            }else{

                // Hibás esetben a lokális adat törlése
                const index = findIndexById(newCompany.id);
                if (index !== -1) {
                    companies.value.splice(index, 1);
                }

                // Toast hibaüzenet
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: trans('error_company_create'),
                });

                // Hiba naplózása
                ErrorService.logClientError(error, {
                    componentName: "CreateCompanyDialog",
                    additionalInfo: "Failed to create a company in the backend",
                    category: "Error",
                    priority: "high",
                    data: company.value,
                });
            }
        });
}; 

// Implement company update logic
const updateCompany = (company) => {
    console.log(company);

    /*
    const index = findIndexById(company.value.id);
    
    if (index === -1) {
        console.error(`Company with id ${country.value.id} not found`);
        return;
    }
    */
}; 

const findIndexById = (id) => {
    return companies.value.findIndex((company) => company.id === id);
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
                @save-company="saveCompany"
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