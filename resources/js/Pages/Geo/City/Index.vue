<script setup>
import { computed, onMounted, ref, reactive } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { FilterMatchMode } from '@primevue/core/api';
import AppLayout from '@/Layouts/AppLayout.vue';

// Validation
import useVuelidate from '@vuelidate/core';
import { required } from '@vuelidate/validators'

import CityService from '@/service/CityService';

import Toolbar from 'primevue/toolbar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';


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
 * @type {ref<boolean>}
 */
 const cityDialog = ref(false);

/**
 * Reaktív hivatkozás a városok törléséhez használt párbeszédpanel megnyitásához.
 * 
 * @type {ref<boolean>}
 */
 const deleteCitiesDialog = ref(false);

 /**
 * Reaktív hivatkozás a kijelölt város(ok) törléséhez használt párbeszédpanel megnyitásához.
 * 
 * @type {ref<boolean>}
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

//const rules = {
//    name: {required},
//    //latitude: {required},
//    //longitude: {required},
//    country_id: {required},
//    region_id: {required},
//};
const rules = computed(() => ({
    name: { required },
    country_id: { required },
    region_id: { required }
}));

const v$ = useVuelidate(rules, city);
// ======================================================

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

const hideDialog = () => {
    cityDialog.value = false;
    submitted.value = false;
}

const editCity = (data) => {
    city.value = { ...data };
    //city.value = data;
    cityDialog.value = true;
}

const confirmDeleteCity = (data) => {
    city.value = { ...data };
    deleteCityDialog.value = true;
}

const saveCity = () => {
    submitted.value = true;

    if (city.value.id) {
        updateCity();
    }else{
        createCity();
    }
}

/**
 * Hozzon létre új várost az API-nak küldött POST-kéréssel.
 *
 * A metódus ellenörzi a város adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor létrehoz egy új várost az API-ban.
 * A választ megjeleníti a konzolon.
 *
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const createCity = async () => {
    // Futassa a validációs ellenörzést a város adataira
    v$.$validate();

    // Ha a validáció sikerült, akkor hozzon létre új várost az API-ban
    if( !v$.$error ){
        alert('SUCCESS');
        /*
        CityService.createCity(city.value)
        .then(response => {
            // Jelenítse meg a válaszban szereplő adatokat a konzolon
            console.log('response', response);
        })
        .catch(error => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error('createCity API Error:', error);
        });
        */
    }else{
        alert('FAIL');
    }
}

const updateCity = () => {
    CityService.updateCity(city.value.id, city.value)
    .then(response => {
        //
    })
    .catch(error => {
        console.error('updateCity API Error:', error);
    });
}

const deleteCity = () => {
    CityService.deleteCity(city.value.id)
    .then(response => {
        //
    })
    .catch(error => {
        console.error('deleteCity API Error:', error);
    });
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
                        <div class="font-semibold text-xl mb-1">{{ $t('manage_cities') }}</div>
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
                                @click="editCity(slotProps.data)" />
                        <Button icon="pi pi-trash" outlined rounded severity="danger" 
                                @click="confirmDeleteCity(slotProps.data)" />
                    </template>
                </Column>

            </DataTable>

        </div>

        <!-- Város szerkesztése -->
        <Dialog v-model:visible="cityDialog" :style="{ width: '450px' }" 
                :header="$t('city_details')" :modal="true">
                <div class="flex flex-col gap-6">

                    <!-- Name -->
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            {{ $t('name') }}
                        </label>
                        <InputText id="name" 
                                   v-model="city.name" 
                                   required="true" autofocus fluid
                        />
                    <!--
                        <small v-if="submitted && !city.name" class="text-red-500">
                            {{ $t('errors_name_required') }}
                        </small>
                    -->
                        <small class="text-red-500" v-if="v$.name.$error">
                            {{ v$.name.$errors[0].$message }}
                        </small>
                    </div>

                    <!-- region -->
                    <div>
                        <label for="region_id" class="block font-bold mb-3">
                            {{ $t('region_id') }}
                        </label>
                        <InputText id="region_id" v-model="city.region_id" 
                                   required="true" fluid
                                   :invalid="submitted && !city.region_id" />
                        <small v-if="submitted && !city.region_id" class="text-red-500">
                            {{ $t('errors_region_id_required') }}
                        </small>
                    </div>

                    <!-- country -->
                    <div>
                        <label for="country_id" class="block font-bold mb-3">
                            {{ $t('country_id') }}
                        </label>
                        <InputText id="country_id" v-model="city.country_id" 
                                   required="true" fluid
                                   :invalid="submitted && !city.country_id" />
                        <small v-if="submitted && !city.country_id" class="text-red-500">
                            {{ $t('errors_country_id_required') }}
                        </small>
                    </div>

                    <!-- latitude -->
                    <div>
                        <label for="latitude" class="block font-bold mb-3">
                            {{ $t('latitude') }}
                        </label>
                        <InputText id="latitude" v-model="city.latitude" fluid
                                   :invalid="submitted && !city.latitude" />
                        <small v-if="submitted && !city.latitude" class="text-red-500">
                            {{ $t('errors_latitude_required') }}
                        </small>
                    </div>

                    <!-- longitude -->
                    <div>
                        <label for="longitude" class="block font-bold mb-3">
                            {{ $t('longitude') }}
                        </label>
                        <InputText id="longitude" v-model="city.longitude" fluid
                                   :invalid="submitted && !city.longitude" />
                        <small v-if="submitted && !city.longitude" class="text-red-500">
                            {{ $t('errors_longitude_required') }}
                        </small>
                    </div>
                </div>
                <template #footer>
                    <Button :label="$t('cancel')" icon="pi pi-times" text @click="hideDialog" />
                    <Button :label="$t('save')" icon="pi pi-check" @click="saveCity" />
                </template>
        </Dialog>

        <!-- Város törlése -->
        <Dialog></Dialog>

        <!-- Kijelölt városok törlése -->
        <Dialog></Dialog>

    </AppLayout>
</template>