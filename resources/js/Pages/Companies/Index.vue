<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import Toolbar from "primevue/toolbar";
import Column from "primevue/column";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
//import CompanyService from '@/service/CompanyService';
import CompanyService from "@/service/CompanyService";
import Select from "primevue/select";

const props = defineProps({
    countries: {
        type: Object,
        default: () => {},
    },
    cities: {
        type: Object,
        default: () => {},
    },
});

const toast = useToast();
const companies = ref();
const company = ref({});
const selectedCompanies = ref();
const companyDialog = ref(false);
const deleteCompanyesDialog = ref(false);
const deleteCompanyDialog = ref(false);

const country_id = ref();

const dt = ref();
const submitted = ref(false);
const filters = ref({
    global: {
        value: null,
        matchMode: FilterMatchMode.CONTAINS,
    },
});

function openNew() {}

function exportCSV() {}

function confirmDeleteSelected() {}

const fetchItems = () => {
    CompanyService.getCompanies()
        .then((response) => {
            companies.value = response.data.data;
        })
        .catch((error) => {
            console.log(error);
        });
};

onMounted(() => {
    fetchItems();
});

const getCountryName = (id) => {
    return props.countries.find((country) => country.id === id).name;
};

const getCityName = (id) => {
    return props.cities.find((city) => city.id === id).name;
};
</script>
<template>
    <AppLayout>
        <Head :title="$t('companies')" />

        <div class="card">
            <Toolbar class="md-6">
                <template #start>
                    <Button
                        :label="$t('new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                    />

                    <Button
                        :label="$t('delete')"
                        icon="pi pi-trash"
                        severity="secondary"
                        class="mr-2"
                        @click="confirmDeleteSelected"
                        :disabled="
                            !selectedCompanies || !selectedCompanies.length
                        "
                    />
                </template>

                <template #end>
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
                v-model:selection="selectedCompanies"
                :value="companies"
                dataKey="id"
                :paginator="true"
                :rows="10"
                :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("companies_title") }}
                        </div>
                        <!--                        
                        <div class="font-semibold text-xl mb-1">
                            <Select id="country_id" class="w-full"  
                                    v-model="country" 
                                    :options="props.countries" 
                                    optionLabel="name" 
                                    optionValue="id" 
                                    :placholder="$t('name')" />
                        </div>

                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" 
                                       :placeholder="$t('search')" />
                        </IconField>
-->
                    </div>
                </template>

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
                />

                <!-- COUNTRY -->
                <!--<Column field="country_id" :header="$t('country')" sortable style="min-width: 16rem" />-->
                <Column
                    field="country_id"
                    :header="$t('country')"
                    sortable
                    style="min-width: 16rem"
                >
                    <template #body="slotProps">
                        {{ getCountryName(slotProps.data.country_id) }}
                    </template>
                </Column>

                <!-- CITY -->
                <!--<Column field="city" :header="$t('city')" sortable style="min-width: 16rem" />-->
                <Column
                    field="city_id"
                    :header="$t('city')"
                    sortable
                    style="min-width: 16rem"
                >
                    <template #body="slotProps">
                        {{ getCityName(slotProps.data.city_id) }}
                    </template>
                </Column>

                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            class="mr-2"
                            @click="editProduct(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteProduct(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
