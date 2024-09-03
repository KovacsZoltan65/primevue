<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { FilterMatchMode } from '@primevue/core/api';
import AppLayout from '@/Layouts/AppLayout.vue';
import CityService from '@/service/CityService';

import Toolbar from 'primevue/toolbar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';


/**
 * Használja a PrimeVue toast összetevőjét.
 *
 * @type {Object}
 */
const toast = useToast();

/**
 * Reaktív hivatkozás a datatable komponensre.
 * 
 * @type {Object}
 */
 const dt = ref();

 /**
 * Reaktív hivatkozás a városok adatainak tárolására.
 * 
 * @type {Array}
 */
const cities = ref();

/**
 * Reaktív hivatkozás a város adatainak megjelenítő párbeszédpanel megnyitásához.
 * 
 * @type {Boolean}
 */
 const cityDialog = ref(false);

/**
 * Reaktív hivatkozás a városok törléséhez használt párbeszédpanel megnyitásához.
 * 
 * @type {Boolean}
 */
 const deleteCitiesDialog = ref(false);

 /**
 * Reaktív hivatkozás a kijelölt város(ok) törléséhez használt párbeszédpanel megnyitásához.
 * 
 * @type {Boolean}
 */
const deleteCityDialog = ref(false);

/**
 * Reaktív hivatkozás a város adatainak tárolására.
 * 
 * @type {Object}
 */
const city = ref({});

/**
 * Reaktív hivatkozás a kijelölt városok tárolására.
 * 
 * @type {Array}
 */
const selectedCities = ref();

/**
 * Reaktív hivatkozás a globális keresés szűrőinek tárolására az adattáblában.
 * 
 * @type {Object}
 */
const filters = ref({
    // A globális szűrőobjektum.
    // Van egy érték tulajdonsága a keresési lekérdezés tárolására 
    // és egy matchMode tulajdonsága a keresés típusának megadásához.
    global: {
        /**
         * A globális szűrő értéke.
         * 
         * @type {string | null}
         */
        value: null,

        /**
         * A globális szűrő illesztési módja.
         * 
         * @type {FilterMatchMode}
         */
        matchMode: FilterMatchMode.CONTAINS
    }
});

/**
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 * 
 * @type {ref<boolean>}
 */
const submitted = ref(false);

const fetchItems = () => {
    CityService.getCities()
    .then(response => {
        cities.value = response.data.data;
    })
    .catch(error => {
        console.error('getCities API Error:', error);
    });
};

onMounted(() => {
    fetchItems();
});

function confirmDeleteSelected() {
    // Állítsa a deleteProductsDialog változó értékét igazra,
    // amely megnyitja a megerősítő párbeszédablakot a kiválasztott termékek törléséhez.
    //deleteProductsDialog.value = true;
}

function openNew() {
    city.value = {};
    submitted.value = false;
    cityDialog.value = true;
}

</script>

<template>
    <AppLayout>
        <Head title="Város kezelés" />

        <div class="card">

            <Toolbar class="md-6">
                <template #start>
                    <Button :label="$t('new')" icon="pi pi-plus" severity="secondary" class="mr-2" 
                            @click="openNew" />
                    <Button :label="$t('delete')" icon="pi pi-trash" severity="secondary" 
                            @click="confirmDeleteSelected"
                        :disabled="!selectedCities || !selectedCities.length" />
                </template>

                <template #end>
                    <Button :label="$t('export')" icon="pi pi-upload" severity="secondary" 
                            @click="exportCSV($event)" />
                </template>
            </Toolbar>

            <DataTable 
                ref="dt" 
                v-model:selection="selectedCities" 
                :value="cities" 
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

                <!-- Region -->
                <Column field="region_id" :header="$t('region_id')" sortable style="min-width: 12rem" />

                <!-- Country -->
                <Column field="country_id" :header="$t('country_id')" sortable style="min-width: 12rem" />

                <!-- Lattitude -->
                <Column field="latitude" :header="$t('latitude')" sortable style="min-width: 12rem" />

                <!-- Longitude -->
                <Column field="longitude" :header="$t('longitude')" sortable style="min-width: 12rem" />

                <!-- Nev -->
                <Column field="name" :header="$t('name')" sortable style="min-width: 16rem" />

                <!-- Actions -->
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

    </AppLayout>
</template>