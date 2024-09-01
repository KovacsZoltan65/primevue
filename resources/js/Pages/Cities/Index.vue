<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { CityService } from '../services/CityService';

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
 * @type {Ref<boolean>}
 */
const submitted = ref(false);

onMounted(() => {
    CityService.getCities().then((data) => {
        cities.value = data;
    });
});

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Városok kezelés" />

        <div class="card">

            <Toolbar>
                <template #start></template>
                <template #end></template>
            </Toolbar>

            <DataTable
                :value="cities.value"
                :selection="selectedCities.value"
                :row-selection="true"
                :paginator="true">
            </DataTable>
        </div>

    </AuthenticatedLayout>
</template>