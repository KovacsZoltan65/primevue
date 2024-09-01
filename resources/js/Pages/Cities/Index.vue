<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

import { Functions } from '../../helpers/Functions';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { CityService } from '../services/CityService';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';

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
    CityService.getCities()
    .then((data) => {
        cities.value = data;
    })
    .catch(error => {
        console.log(error);
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

/**
 * Ez a funkció a város párbeszédpanel elrejtésére szolgál.
 * A cityDialog hivatkozás értékét false értékre állítja,
 * és a beküldött hivatkozás false.
 *
 * @return {void}
 */
function hideDialog() {
    // A város párbeszédpanel elrejtése a cityDialog értékének false értékre állításával.
    cityDialog.value = false;

    // Állítsa be a beküldött értéket false értékre.
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

/**
 * Város szerlesztése
 *
 * @param {Object} prod - Szerkesztendő város.
 * @return {void}
 */
function editCity(prod) {
    // Create a copy of the city.
    city.value = {...prod };

    // Show the city dialog.
    cityDialog.value = true;
}

/**
 * Megnyitja a város törlését megerősítő párbeszédpanelt.
 *
 * @param {Object} ct - A törölni kívánt város.
 * @return {void}
 */
function confirmDeleteCity(ct) {
    // Mutasd a törlési párbeszédpanelt
    deleteCityDialog.value = true;

    // Töltse be a törlendő termék adatait a termékobjektumba.
    city.value = ct;
}

/**
 * Törli a kiválasztott várost.
 *
 * @return {void}
 */
function deleteCity() {
    // Szűrje ki a törölni kívánt várost a városok listájából.
    city.value = cities.value.filter((val) => val.id !== city.value.id);

    // TÖRLÉS
    CityService.delete(city.value.id)

    // A város törlése párbeszédpanel elrejtése.
    deleteCityDialog.value = false;

    // Állítsa vissza a városobjektumot.
    city.value = {};

    // Mutasson egy üzenetet, amely tájékoztatja a felhasználót, hogy a várost törölték.
    toast.add({ 
        severity: 'success', 
        summary: 'Successful', 
        detail: 'City Deleted', 
        life: 3000 
    });
}

/**
 * Megkeresi egy város indexét a városok tömbben az azonosítója alapján.
 *
 * @param {number} id - A keresendő elem azonosítója.
 * @return {number} A város indexe a városok tömbjében, vagy -1, ha nem található.
 */
function findIndexById(id) {
    // Használja a `findIndex` metódust, hogy gyorsan megtalálja a város indexét.
    return cities.value.findIndex((city) => city.id === id);
}

/**
 * Exportálja az adattáblában lévő adatokat egy CSV-fájlba.
 *
 * A függvény meghívja a `exportCSV` metódust a `dt` refben lévő adattábla komponensen,
 * amely felelős az adatok exportálásáért egy CSV-fájlba.
 *
 * @return {void}
 */
function exportCSV() {
    dt.value.exportCSV();
}

/**
 * Megnyitja a törlés megerősítő párbeszédpanelt, 
 * ha a felhasználó törölni szeretné a kijelölt városokat.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    // Állítsa a deleteCityDialog értékét true értékre, 
    // hogy megnyissa a megerősítő párbeszédpanelt.
    deleteCityDialog.value = true;
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
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products">
                
                <template #header>
                    <div class="flex flex-wrap gap-2 items-center justify-between">
                        <h4 class="m-0">Manage Cities</h4>
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" 
                                       placeholder="Search..." />
                        </IconField>
                    </div>
                </template>

                <!-- SELECTION -->
                <Column selectionMode="multiple" style="width: 3rem" :exportable="false" />

                <!-- RÉGIÓ -->
                <Column field="region_id" header="region_id" sortable style="min-width: 12rem" />

                <!-- Ország -->
                <Column field="country_id" header="country_id" sortable style="min-width: 12rem" />

                <!-- Stélesség -->
                <Column field="latitude" header="latitude" sortable style="min-width: 12rem" />

                <!-- Hosszúság -->
                <Column field="longitude" header="longitude" sortable style="min-width: 12rem" />

                <!-- Név -->
                <Column field="name" header="Name" sortable style="min-width: 12rem" />

                <!-- ACTION -->
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button icon="" outlined rounded class="mr-2" 
                                @click="editCity(slotProps.data)" />
                        <Button icon="" outlined rounded severity="danger" 
                                @click="confirmDeleteCity(slotProps.data)" />
                    </template>
                </Column>

            </DataTable>
        </div>

    </AuthenticatedLayout>
</template>