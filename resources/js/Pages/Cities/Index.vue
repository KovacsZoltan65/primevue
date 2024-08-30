<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

import { Functions } from '../../helpers/Functions';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { CityService } from '../services/CityService';
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
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 * 
 * @type {ref<boolean>}
 */
 const submitted = ref(false);

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

onMounted(() => {
    CityService.getCities().then((data) => {
        cities.value = data;
    });
});

/**
 * Ez a funkció megnyitja az új város párbeszédpanelt.
 * A város objektumot üres objektummá inicializálja,
 * a beküldött jelzőt false értékre, a cityDialog jelzőt pedig igazra állítja.
 *
 * @return {void}
 */
function openNew() {
    // Inicializálja a városobjektumot egy üres objektummá.
    city.value = {};

    // Állítsa be a beküldött jelzőt false értékre.
    submitted.value = false;

    // A párbeszédpanel megnyitásához állítsa a cityDialog jelzőt true értékre.
    cityDialog.value = true;
}

function hideDialog() {
    cityDialog.value = false;
    submitted.value = false;
}

function saveCity() {
    // Állítsa a beküldött értéket igazra.
    submitted.value = true;

    // Ellenőrizze, hogy a város neve nem üres-e, vagy csak szóközt tartalmaz-e.
    if ( city?.value.name?.trim() ) {

        // Ellenőrizze, hogy a város rendelkezik-e azonosítóval.
        if( city.value.id ) {
            // Cserélje ki a várost a várostömbben a frissített termékre.
            cities.value[findIndexById(city.value.id)] = city.value;

            // Jelenítse meg a sikeres toast üzenetet, amely jelzi, hogy a termék frissítve lett.
            toast.add({ severity: 'success', summary: 'Successful', detail: 'City Updated', life: 3000 });
        } else {
            //city.value.id = Functions.createId();
            //city.value.name = '';
            //city.value.region_id = 0;
            //city.value.country_id = 0;
            //city.value.latitude = 0.0;
            //city.value.longitude = 0.0;
            
            const fetchItem = CityService.update(city.value.id, city.value)
            .then(response => {
                // Adja hozzá az új terméket a terméktömbhöz.
                cities.value.push(city.value);

                // Jelenítsen meg egy sikeres toast üzenetet, amely jelzi, hogy a termék elkészült.
                toast.add({ severity: 'success', summary: 'Successful', detail: 'City Created', life: 3000 });
            })
            .catch(error => {
                if (error.response) {
                    errorMessage.value = `Error ${error.response.status}: ${error.response.data.message || 'Failed to load items.'}`;
                } else {
                    errorMessage.value = 'Network error or server did not respond.';
                }
            });            
        }

        // A termék párbeszédpanel elrejtése és a termékobjektum törlése.
        cityDialog.value = false;
        city.value = {};
    }
}

function editCity(prod) {
    city.value = {...prod };
    cityDialog.value = true;
}

function confirmDeleteCity(cit) {
    //
}

function deleteCity() {
    //
}

function findIndexById(id) {
    //
}



function confirmDeleteSelected() {
    //
}

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Városok kezelés" />

        <div class="card">

            <Toolbar>
                
                <template #start>
                    
                    <Button label="New" 
                            icon="pi pi-plus" 
                            severity="secondary" 
                            class="mr-2" 
                            @click="openNew" />
                    
                    <Button label="Delete" 
                            icon="pi pi-trash" 
                            severity="secondary" 
                            @click="confirmDeleteSelected" 
                            :disabled="!selectedCities || !selectedCities.length" />

                </template>

                <template #end></template>

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
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
                >
            </DataTable>
        </div>

    </AuthenticatedLayout>
</template>