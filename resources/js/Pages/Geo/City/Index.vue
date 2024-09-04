<script setup>
import { computed, onMounted, ref, reactive } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { FilterMatchMode } from '@primevue/core/api';
import AppLayout from '@/Layouts/AppLayout.vue';

// Validation
import useVuelidate from '@vuelidate/core';
import { helpers, required } from '@vuelidate/validators'

import CityService from '@/service/CityService';

import Toolbar from 'primevue/toolbar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Select from 'primevue/select';

const props = defineProps({
    countries: {
        type: Object,
        default: () => {}
    },
    regions: {
        type: Object,
        default: () => {}
    }
});

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

const rules = {
    name: {
        required: helpers.withMessage('validate_name', required),
    },
    country_id: {
        required: helpers.withMessage('validate_country_id', required),
    },
    region_id: {
        required: helpers.withMessage('validate_region_id', required),
    }
};
/*
const rules = computed(() => ({
    name: {
        required: helpers.withMessage('validate_name', required),
    },
    country_id: {
        required: helpers.withMessage('validate_country_id', required),
    },
    region_id: {
        required: helpers.withMessage('validate_region_id', required),
    }
}));
*/
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

/**
 * Megerősíti a kiválasztott termékek törlését.
 *
 * Ez a funkció akkor hívódik meg, ha a felhasználó törölni szeretné a kiválasztott termékeket.
 * A deleteProductsDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott termékek törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    // Állítsa a deleteProductsDialog változó értékét igazra,
    // amely megnyitja a megerősítő párbeszédablakot a kiválasztott termékek törléséhez.
    deleteCitiesDialog.value = true;
}

function openNew() {
    city.value = {};
    submitted.value = false;
    cityDialog.value = true;
}

/**
 * Bezárja a dialógusablakot.
 * 
 * Ez a függvény a dialógusablakot bezárja, és a submitted változó értékét False-ra állítja.
 * A v$.value.$reset() függvénnyel visszaállítja a validációs objektumot az alapértelmezett állapotába.
 */
const hideDialog = () => {
    cityDialog.value = false;
    submitted.value = false;

    // Visszaállítja a validációs objektumot az alapértelmezett állapotába.
    v$.value.$reset();
}

/**
 * Szerkeszti a kiválasztott terméket.
 *
 * Ez a funkció a kiválasztott termék adatait másolja a city változóba,
 * és megnyitja a dialógusablakot a termék szerkesztéséhez.
 *
 * @param {object} data - A kiválasztott termék adatai.
 * @return {void}
 */
const editCity = (data) => {
    city.value = { ...data };
    cityDialog.value = true;
}

/**
 * Megerősítés a város törléséhez.
 *
 * Ez a funkció a city változóba másolja a kiválasztott város adatait,
 * és megnyitja a dialógusablakot a város törléséhez.
 *
 * @param {object} data - A kiválasztott város adatai.
 * @return {void}
 */
const confirmDeleteCity = (data) => {
    city.value = { ...data };
    deleteCityDialog.value = true;
}

const saveCity = async () => {

    const result = await v$.value.$validate();
    if( result ){
        alert('VALID');
        /*
        submitted.value = true;

        if (city.value.id) {
            updateCity();
        }else{
            createCity();
        }
        */
    }else{
        alert('FAIL');
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
const createCity = () => {
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
}

const updateCity = () => {
    /*
    CityService.updateCity(city.value.id, city.value)
    .then(response => {
        //
    })
    .catch(error => {
        console.error('updateCity API Error:', error);
    });
    */
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

const getCountryName = (id) => {
    return props.countries.find((country) => country.id === id).name;
};

const getRegionName = (id) => {
    return props.regions.find((region) => region.id === id).name;
};

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

                <!-- Nev -->
                <Column field="name" :header="$t('name')" sortable 
                        style="min-width: 16rem" />

                <!-- Country -->
                <Column field="country_id" :header="$t('country')" sortable style="min-width: 12rem">
                    <template #body="slotProps">
                        {{ getCountryName(slotProps.data.country_id) }}
                    </template>
                </Column>

                <!-- Region -->
                <Column field="region_id" :header="$t('region')" sortable style="min-width: 12rem">
                    <template #body="slotProps">
                        {{ getRegionName(slotProps.data.region_id) }}
                    </template>
                </Column>

                <!-- Lattitude -->
                <Column field="latitude" :header="$t('latitude')" sortable 
                        style="min-width: 12rem" />

                <!-- Longitude -->
                <Column field="longitude" :header="$t('longitude')" sortable 
                        style="min-width: 12rem" />

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
                        <label for="name" class="block font-bold mb-3">{{ $t('name') }}</label>
                        <InputText id="name" v-model="city.name" autofocus fluid />
                        <small class="text-red-500" v-if="v$.name.$error">
                            {{ $t(v$.name.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- country -->
                    <div>
                        <label for="country_id" class="block font-bold mb-3">
                            {{ $t('country') }}
                        </label>
                        <!--<InputText id="country_id" v-model="city.country_id" fluid :invalid="submitted && !city.country_id" />-->

                        <Select id="country_id" fluid 
                                v-model="city.country_id" 
                                :options="props.countries" 
                                optionLabel="name" 
                                optionValue="id" 
                                :placholder="$t('country')" />

                        <small class="text-red-500" v-if="v$.country_id.$error">
                            {{ $t(v$.country_id.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- region -->
                    <div>
                        <label for="region_id" class="block font-bold mb-3">
                            {{ $t('region') }}
                        </label>
                        <!--<InputText id="region_id" 
                                   v-model="city.region_id" fluid 
                                   :invalid="submitted && !city.region_id" />-->
                        
                        <Select id="region_id" fluid 
                                v-model="city.region_id" 
                                :options="props.regions" 
                                optionLabel="name" 
                                optionValue="id" 
                                :placholder="$t('region')" />

                        <small class="text-red-500" v-if="v$.region_id.$error">
                            {{ $t(v$.region_id.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- latitude -->
                    <div>
                        <label for="latitude" class="block font-bold mb-3">
                            {{ $t('latitude') }}
                        </label>
                        <InputText id="latitude" v-model="city.latitude" fluid />
                    </div>

                    <!-- longitude -->
                    <div>
                        <label for="longitude" class="block font-bold mb-3">
                            {{ $t('longitude') }}
                        </label>
                        <InputText id="longitude" v-model="city.longitude" fluid />
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