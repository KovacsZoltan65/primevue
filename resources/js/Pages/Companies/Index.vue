<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Toolbar from 'primevue/toolbar';
import { useToast } from 'primevue/usetoast';
import { FilterMatchMode } from '@primevue/core/api';
import { CompanyService } from '@/service/CompanyService';
import Column from 'primevue/column';

//const toast = useToast();
const companies = ref();
//const company = ref({});
const selectedCompanies = ref();
const dt = ref();
const filters = ref({
    global: {
        value: null,
        matchMode: FilterMatchMode.CONTAINS
    }
});

const props = defineProps({
    companies: {
        type: Object,
        default: () => ({})
    }
});

function openNew(){}

function exportCSV(){}

function confirmDeleteSelected() {}

onMounted(() => {
    //console.log( 'props.companies.data', props.companies.data );
    //companies.value = CompanyService.getData();
    //console.log('comapnies', companies);
    
    CompanyService.getCompanies()
        .then((data) => {
            companies.value = data;
        })
    
});

</script>
<template>
    <AppLayout>
        <div>
            <div class="card">
                
                <Toolbar class="md-6">
                    <template #start>
                        <Button :label="$t('new')" icon="pi pi-plus" 
                                severity="secondary" class="mr-2" @click="openNew" />
                        <Button :label="$t('delete')" icon="pi pi-trash" 
                                severity="secondary" 
                                @click="confirmDeleteSelected"
                            :disabled="!selectedCompanies || !selectedCompanies.length" />
                    </template>

                    <template #end>
                        <Button :label="$t('export')" icon="pi pi-upload" 
                                severity="secondary" 
                                @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable 
                    ref="dt" 
                    v-model:selection="selectedCompanies" 
                    :value="props.companies.data" 
                    dataKey="id" 
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products">
                    
                    <template #header>
                        <div class="flex flex-wrap gap-2 items-center justify-between">
                            <h4 class="m-0">{{ $t('manage_products') }}</h4>
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="filters['global'].value" :placeholder="$t('search')" />
                            </IconField>
                        </div>
                    </template>

                    <Column selectionMode="multiple" style="width: 3rem" :exportable="false" />
                    <Column field="name" :header="$t('name')" sortable style="min-width: 16rem" />
                    <Column field="country" :header="$t('country')" sortable style="min-width: 16rem" />
                    <Column field="city" :header="$t('city')" sortable style="min-width: 16rem" />

                    <Column :exportable="false" style="min-width: 12rem">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" outlined rounded class="mr-2" 
                                    @click="editProduct(slotProps.data)" />
                            <Button icon="pi pi-trash" outlined rounded severity="danger" 
                                    @click="confirmDeleteProduct(slotProps.data)" />
                        </template>
                    </Column>
                    
                </DataTable>

            </div>
        </div>
    </AppLayout>
</template>