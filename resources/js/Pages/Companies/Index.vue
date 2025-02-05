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

import { createId } from "@/helpers/functions";

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

/**
 * Az alkatrésznek átadott kellékek.
 *
 * @typedef {Object} Props
 * @property {Object} countries - Országok objektum.
 * @property {Object} cities - Városok objektum.
 * @property {Object} search - Kereső beviteli mezők.
 * @property {Object} can - Jogosultságok.
 */
const props = defineProps({
    countries: { type: Object, default: () => {}, },
    cities: { type: Object, default: () => {}, },
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

/**
 * Beállítja az alapértelmezett értékeket a szürőmezők számára.
 */
const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS},
        name: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
    }
};

/**
 * Beállítja az alapértelmezett értékeket a szürőmezők számára.
 */
const clearFilters = () => {
    initFilters();
};

/**
 * Lekéri a cégeket a szerverről, és elmenti a companies változóban.
 * A folyamat során a loading változóban eltárolja a folyamat állapotát.
 *
 * @async
 */
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

// Beállítja az alapértelmezett értékeket a szürőmezők számára.
initFilters();

/**
 * Az alkatrész életciklus-kampója, amelyet az alkatrész felszerelésekor hívnak meg.
 * Lekéri a cégek listáját a szerverről.
 */
onMounted(() => {
    fetchItems();
});

// Szerkesztés Dialog megnyitása
const openDialog = () => {
    console.log("Index openDialog");
    // Üres objektum létrehozása új elemhez
    selectedCompany.value = { ...defaultCompany };
    dialogTitle.value = "Add Company";
    isDialogVisible.value = true;
};

/**
 * Bezárja a vállalati párbeszédpanelt a láthatóságának false értékre állításával.
 */
const closeDialog = () => {
    console.log("Index closeDialog");
    isDialogVisible.value = false;
};

/**
 * Megnyitja egy cég törlési párbeszédpanelt.
 *
 * @param {Object} company - Törlendő cég
 */
const openDeleteDialog = (company) => {
    selectedCompany.value = company;
    isDeleteDialogVisible.value = true;
};

/**
 * Bezárja a cég törlési párbeszédpanelt 
 * a láthatóságának false értékre állításával 
 * és a kiválasztott cég nullázásával.
 */
const closeDeleteDialog = () => {
    isDeleteDialogVisible.value = false;
    selectedCompany.value = null;
};

/**
 * Frissíti a cégek listáját a törlés után.
 * A törlött cég eltávolítása a listából,
 * és a törlési párbeszédpanel zárása.
 */
const onCompanyDeleted = () => {
    companies.value = companies.value.filter(c => c.id !== selectedCompany.value.id);
    isDeleteDialogVisible.value = false;
};

/**
 * Megnyitja a cég szerkesztési párbeszédpanelt.
 *
 * @param {Object} company - A szerkesztendő cég
 */
const editCompany = (company) => {
    console.log("editCompany");
    selectedCompany.value = { ...company };
    dialogTitle.value = "Edit Company";
    isDialogVisible.value = true;
};

/**
 * Vállalat mentése a cégek listájára.
 * Ha a cégnek nincs azonosítója, generál egy azonosítót, és hozzáadja a céget a listához.
 * Ha a cég már szerepel a listában, frissíti a meglévő céget.
 * Mentés után bezárja a vállalati párbeszédpanelt.
 *
 * @param {Object} company - Az elmentendő cég
 */
const saveCompany = (company) => {
    console.log("Index.vue saveCompany");

    if (!company.id) {
        company.id = Date.now();
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
