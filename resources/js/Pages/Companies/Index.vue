<script setup>
import { ref, onMounted, watch } from "vue";
import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import CompanyDialog from "./CompanyDialog.vue";
import CompanyService from "@/service/CompanyService";

const companies = ref([]);
const isDialogVisible = ref(false);
const dialogTitle = ref("");
const loading = ref(false);

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

const selectedCompany = ref({ ...defaultCompany });

const initialCompany = () => {
    return { ...defaultCompany };
};

const props = defineProps({
    countries: { type: Object, default: () => {}, },
    cities: { type: Object, default: () => {}, },
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

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

onMounted(() => {
    fetchItems();
});

const openDialog = () => {
    console.log("Index openDialog");
    selectedCompany.value = { ...defaultCompany }; // Üres objektum létrehozása új elemhez
    dialogTitle.value = "Add Company";
    isDialogVisible.value = true;
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
    <div>
        <CompanyDialog
            v-model:visible="isDialogVisible"
            :header="dialogTitle"
            :company="selectedCompany"
            :countries="countries"
            :cities="cities"
            @save-company="saveCompany"
            @hide-dialog="isDialogVisible = false"
        />

        <Toolbar>
            <template #start>
                <Button
                    label="Add Company"
                    icon="pi pi-plus"
                    @click="openDialog"
                />
            </template>
        </Toolbar>

        <DataTable :value="companies" ref="dt">
            <Column field="name" header="Name" />
            <Column header="Actions">
                <template #body="{ data }">
                <Button icon="pi pi-pencil" @click="editCompany(data)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>
/* Add any custom styles here */
</style>
