<script setup>
import { ref, onMounted } from "vue";
import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import CompanyDialog from "./CompanyDialog.vue";
import CompanyService from "@/service/CompanyService";

const companies = ref([]);
const isDialogVisible = ref(false);
const selectedCompany = ref({});
const dialogTitle = ref("");
const loading = ref(false);

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
    console.log('Index openDialog');
    selectedCompany.value = { name: "" };
    dialogTitle.value = "Add Company";
    isDialogVisible.value = true;
};

const editCompany = (company) => {
    console.log('editCompany');
    selectedCompany.value = { ...company };
    dialogTitle.value = "Edit Company";
    isDialogVisible.value = true;
};

const saveCompany = (company) => {
    console.log('Index.vue saveCompany');
    if (!company.id) {
        company.id = Date.now(); // Temporary ID for new entries
        companies.value.push(company);
    } else {
        const index = companies.value.findIndex(c => c.id === company.id);
        if (index > -1) {
            companies.value[index] = company;
        }
    }
    isDialogVisible.value = false;
};
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