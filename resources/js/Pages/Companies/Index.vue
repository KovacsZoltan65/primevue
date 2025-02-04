<script setup>
import { ref, onMounted, watch } from "vue";
import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";

import CompanyDialog from "./CompanyDialog.vue";
import CompanyDeleteDialog from "./CompanyDeleteDialog.vue";

import CompanyService from "@/service/CompanyService";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Toast } from "primevue";
import { Head } from "@inertiajs/vue3";
import { FilterMatchMode } from "@primevue/core/api";

const companies = ref([]);
const selectedCompany = ref({});
const isDialogVisible = ref(false);
const isDeleteDialogVisible = ref(false);
const dialogTitle = ref("");
const loading = ref(false);
const filters = ref({});

// Alapértelmezett cégobjektum
const defaultCompany = {
    id: null,
    name: "",
    directory: "",
    country_id: null,
    city_id: null,
    registration_number: null,
    tax_id: null,
    address: null,
    active: 1,
};

const selectedCompanies = ref({ ...defaultCompany });

const initialCompany = () => {
    return { ...defaultCompany };
};

const props = defineProps({
    countries: { type: Object, default: () => {}, },
    cities: { type: Object, default: () => {}, },
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS},
        name: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
    }
};

const clearFilters = () => {
    initFilters();
};

const fetchItems = () => {
    loading.value = true;
    CompanyService.getCompanies()
        .then((response) => {
            companies.value = response.data;
        })
        .catch((error) => {
            console.error("getCompanies API Error:", error);
        })
        .finally(() => {
            loading.value = false;
        });
};

initFilters();

onMounted(() => {
    fetchItems();
});

// Szerkesztés Dialog megnyitása
const openDialog = () => {
    console.log("Index openDialog");
    selectedCompany.value = { ...defaultCompany }; // Üres objektum létrehozása új elemhez
    dialogTitle.value = "Add Company";
    isDialogVisible.value = true;
};

const closeDialog = () => {
    console.log("Index closeDialog");
    isDialogVisible.value = false;
};

// Törlés Dialog megnyitása
const openDeleteDialog = (company) => {
    selectedCompany.value = company;
    isDeleteDialogVisible.value = true;
};

// Törlés Dialog bezárása
const closeDeleteDialog = () => {
    isDeleteDialogVisible.value = false;
    selectedCompany.value = null;
};

// Törlés eseménykezelő
const onCompanyDeleted = () => {
    companies.value = companies.value.filter(c => c.id !== selectedCompany.value.id);
    isDeleteDialogVisible.value = false;
};

const editCompany = (company) => {
    console.log("editCompany");
    selectedCompany.value = { ...company }; // Meglévő adat átmásolása
    dialogTitle.value = "Edit Company";
    isDialogVisible.value = true;
};

const saveCompany = (company) => {
    console.log("Index.vue saveCompany");

    if (!company.id) {
        company.id = Date.now(); // Ideiglenes ID generálása
        companies.value.push(company);
    } else {
        const index = companies.value.findIndex(c => c.id === company.id);
        if (index > -1) {
            companies.value[index] = company;
        }
    }
    isDialogVisible.value = false;
};

// Figyeljük a props.company változását és frissítjük a localCompany-t
watch(
    () => props.company,
    (newCompany) => {
        Object.assign(localCompany, newCompany);
    }
);

// Figyeljük a visible változást, és alaphelyzetbe állítjuk a formot új elem esetén
watch(
    () => props.visible,
    (newVisible) => {
        if (newVisible) {
            Object.assign(localCompany, props.company?.id ? props.company : defaultCompany);
        }
    }
);
</script>

<template>
    <AppLayout>
        <Head :title="$t('companies')" />

        <Toast />

        <div class="card">

            <!-- Cégszerkesztés Dialog -->
            <CompanyDialog
                v-model:visible="isDialogVisible"
                :header="dialogTitle"
                :company="selectedCompany"
                :countries="countries"
                :cities="cities"
                @save-company="saveCompany"
                @hide-dialog="closeDialog"
            />

            <!-- Törlés Dialog -->
            <CompanyDeleteDialog
                v-model:visible="isDeleteDialogVisible"
                :company="selectedCompany"
                @deleted="onCompanyDeleted"
                @close="closeDeleteDialog"
            />

            <Toolbar class="md-6">
                <template #start>
                    <Button
                        label="Add Company"
                        icon="pi pi-plus"
                        @click="openDialog"
                    />
                </template>
            </Toolbar>

            <DataTable
                ref="dt"
                v-model:selection="selectedCompanies"
                v-model:filters="filters"
                filterDisplay="menu"
                :value="companies"
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
                    </div>
                </template>

                <!-- SELECTION -->
                <Column
                    selectionMode="multiple"
                    style="width: 3rem"
                    :exportable="false"
                    :disabled="!props.can.companies_delete"
                />

                <Column field="name" header="Name" />
                <Column header="Actions">
                    <template #body="{ data }">
                        <Button
                            icon="pi pi-pencil"
                            @click="editCompany(data)"
                            class="mr-2"
                        />
                        <Button
                            icon="pi pi-trash"
                            severity="danger"
                            @click="openDeleteDialog(data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Add any custom styles here */
</style>
