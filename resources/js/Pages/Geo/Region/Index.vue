<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, required } from "@vuelidate/validators";

import RegionService from "@/service/RegionService";
//import functions from '../../../helpers/functions.js';

import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import IconField from "primevue/iconfield";
import InputText from "primevue/inputtext";
import InputIcon from "primevue/inputicon";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import Tag from "primevue/tag";

/**
 * Szerver felöl jövő adatok
 */
const props = defineProps({
    /**
     * Országok adatai.
     */
    countries: {
        type: Object,
        default: () => {},
    },
});

/**
 * Az állapotmező logikai értékeit adja vissza.
 *
 * @returns {Array<Object>} objektumok tömbje címke és érték tulajdonságokkal.
 */
const getBools = () => {
    return [
        {
            /**
             * Az inaktív állapot címkéje.
             */
            label: trans("inactive"),
            /**
             * Az inaktív állapot értéke.
             */
            value: 0,
        },
        {
            /**
             * Az aktív állapot címkéje.
             */
            label: trans("active"),
            /**
             * Az aktív állapot értéke.
             */
            value: 1,
        },
    ];
};

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
const regions = ref();

/**
 * Reaktív hivatkozás a város adatainak megjelenítő párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const regionDialog = ref(false);

/**
 * Reaktív hivatkozás a városok törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteSelectedRegionsDialog = ref(false);

/**
 * Reaktív hivatkozás a kijelölt város(ok) törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteRegionDialog = ref(false);

/**
 * Reaktív hivatkozás a város adatainak tárolására.
 *
 * @type {Object}
 * @property {string} name - A város neve.
 * @property {number} country_id - Az ország azonosítója.

 * @property {number} active - A város státusza.
 * @property {number} id - A város azonosítója.
 */
const region = ref({
    id: null,
    name: "",
    code: 0,
    country_id: null,
    active: 1,
});

/**
 * Reaktív hivatkozás a kijelölt városok tárolására.
 *
 * @type {Array}
 */
const selectedRegions = ref();

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
        matchMode: FilterMatchMode.CONTAINS,
    },
});

/**
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 *
 * @type {ref<boolean>}
 */
const submitted = ref(false);

const rules = {
    name: {
        required: helpers.withMessage("validate_name", required),
    },
    country_id: {
        required: helpers.withMessage("validate_country_id", required),
    },
};

/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 *
 * @type {Object}
 */
const v$ = useVuelidate(rules, region);

// ======================================================

/**
 * Lekéri a városok listáját az API-ból.
 *
 * Ez a funkció a városok listáját lekéri az API-ból.
 * A városok listája a regions változóban lesz elmentve.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megoldódik.
 */
const fetchItems = () => {
    RegionService.getRegions()
        .then((response) => {
            // A városok listája a regions változóban lesz elmentve
            regions.value = response.data.data;
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("getRegions API Error:", error);
        });
};

/**
 * Eseménykezelő, amely a komponens létrejöttekor hívódik meg.
 *
 * Ez a funkció a városok listáját lekéri az API-ból, amikor a komponens létrejön.
 * A városok listája a regions változóban lesz elmentve.
 *
 * @return {void}
 */
onMounted(() => {
    fetchItems();
});

/**
 * Megerősíti a kiválasztott termékek törlését.
 *
 * Ez a funkció akkor hívódik meg, ha a felhasználó törölni szeretné a kiválasztott termékeket.
 * A deleteRegionsDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott termékek törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    // Állítsa a deleteCitiessDialog változó értékét igazra,
    // amely megnyitja a megerősítő párbeszédablakot a kiválasztott termékek törléséhez.
    deleteSelectedRegionsDialog.value = true;
}

/**
 * Nyitja meg az új város dialógusablakot.
 *
 * Ez a függvény a region változó értékét alaphelyzetbe állítja, a submitted változó értékét False-ra állítja,
 * és a regionDialog változó értékét igazra állítja, amely megnyitja az új város dialógusablakot.
 *
 * @return {void}
 */
function openNew() {
    region.value = { ...initialCity };
    submitted.value = false;
    regionDialog.value = true;
}

/**
 * Az új város objektum alapértelmezett értékei.
 *
 * A regionDialog változó értékét igazra állítva, ez az objektum lesz a dialógusablakban
 * megjelenő új város formban.
 *
 * @type {Object}
 * @property {string} name - A város neve.
 * @property {number} country_id - Az ország azonosítója.
 * @property {number} active - A város aktív-e? (1 igen, 0 nem).
 * @property {number} id - A város azonosítója.
 */
const initialCity = {
    name: "",
    country_id: null,
    code: "",
    active: 1,
    id: null,
};

/**
 * Bezárja a dialógusablakot.
 *
 * Ez a függvény a dialógusablakot bezárja, és a submitted változó értékét False-ra állítja.
 * A v$.value.$reset() függvénnyel visszaállítja a validációs objektumot az alapértelmezett állapotába.
 */
const hideDialog = () => {
    regionDialog.value = false;
    submitted.value = false;

    // Visszaállítja a validációs objektumot az alapértelmezett állapotába.
    v$.value.$reset();
};

/**
 * Szerkeszti a kiválasztott várost.
 *
 * Ez a funkció a kiválasztott város adatait másolja a region változóba,
 * és megnyitja a dialógusablakot a város szerkesztéséhez.
 *
 * @param {object} data - A kiválasztott város adatai.
 * @return {void}
 */
const editRegion = (data) => {
    // Másolja a kiválasztott város adatait a region változóba.
    region.value = { ...data };

    // Nyissa meg a dialógusablakot a város szerkesztéséhez.
    regionDialog.value = true;
};

/**
 * Megerősítés a város törléséhez.
 *
 * Ez a funkció a region változóba másolja a kiválasztott város adatait,
 * és megnyitja a dialógusablakot a város törléséhez.
 *
 * @param {object} data - A kiválasztott város adatai.
 * @return {void}
 */
const confirmDeleteRegion = (data) => {
    // Másolja a kiválasztott város adatait a region változóba.
    region.value = { ...data };

    // Nyissa meg a dialógusablakot a város törléséhez.
    deleteRegionDialog.value = true;
};

const saveRegion = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (region.value.id) {
            updateRegion();
        } else {
            createRegion();
        }
    } else {
        alert("FAIL");
    }
};

/**
 * Hozzon létre új várost az API-nak küldött POST-kéréssel.
 *
 * A metódus ellenörzi a város adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor létrehoz egy új várost az API-ban.
 * A választ megjeleníti a konzolon.
 *
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const createRegion = () => {
    RegionService.createRegion(region.value)
        .then((response) => {
            //console.log('response', response);
            regions.values.push(response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "City Created",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("createRegion API Error:", error);
        });
};

/**
 * Frissít egy várost az API-nak küldött PUT-kéréssel.
 *
 * A metódus ellenörzi a város adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor frissíti a várost az API-ban.
 * A választ megjeleníti a konzolon.
 *
 * @param {number} id - A frissítendő város azonosítója.
 * @param {object} data - A város új adatai.
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const updateRegion = () => {
    RegionService.updateRegion(region.value.id, region.value)
        .then(() => {
            // Megkeresi a város indexét a városok tömbjében az azonosítója alapján
            const index = findIndexById(region.value.id);
            // A város adatait frissíti a városok tömbjében
            regions.value.splice(index, 1, region.value);

            // Bezárja a dialógus ablakot
            hideDialog();

            // Siker-értesítést jelenít meg
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "City Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("updateRegion API Error:", error);
        });
};

/**
 * Megkeresi egy város indexét a városok tömbjében az azonosítója alapján.
 *
 * @param {number} id - A keresendő város azonosítója.
 * @returns {number} A város indexe a városok tömbjében, vagy -1, ha nem található.
 */
const findIndexById = (id) => {
    return regions.value.findIndex((region) => region.id === id);
};

const deleteRegion = () => {
    RegionService.deleteRegion(region.value.id)
        .then((response) => {
            //
        })
        .catch((error) => {
            console.error("deleteRegion API Error:", error);
        });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const deleteSelectedCities = () => {
    console.log(selectedRegions.value);
};

const getCountryName = (id) => {
    return props.countries.find((country) => country.id === id).name;
};

const getActiveLabel = (region) =>
    ["danger", "success", "warning"][region.active || 2];

const getActiveValue = (region) =>
    ["inactive", "active", "pending"][region.active] || "pending";
</script>

<template>
    <AppLayout>
        <Head :title="$t('regions')" />

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
                        @click="confirmDeleteSelected"
                        :disabled="!selectedRegions || !selectedRegions.length"
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
                v-model:selection="selectedRegions"
                :value="regions"
                dataKey="id"
                :paginator="true"
                :rows="10"
                :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} regions"
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <!-- FELIRAT -->
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("regions_title") }}
                        </div>

                        <!-- KERESÉS -->
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText
                                v-model="filters['global'].value"
                                :placeholder="$t('search')"
                            />
                        </IconField>
                    </div>
                </template>

                <!-- Checkbox -->
                <Column
                    selectionMode="multiple"
                    style="min-width: 3rem"
                    :exportable="false"
                />

                <!-- Nev -->
                <Column
                    field="name"
                    :header="$t('name')"
                    sortable
                    style="min-width: 16rem"
                />

                <!-- Country -->
                <Column
                    field="country_id"
                    :header="$t('country')"
                    sortable
                    style="min-width: 12rem"
                >
                    <template #body="slotProps">
                        {{ getCountryName(slotProps.data.country_id) }}
                    </template>
                </Column>

                <!-- Region -->
                <Column
                    field="region_id"
                    :header="$t('region')"
                    sortable
                    style="min-width: 12rem"
                >
                    <template #body="slotProps">
                        {{ getRegionName(slotProps.data.region_id) }}
                    </template>
                </Column>

                <!-- Lattitude -->
                <Column
                    field="latitude"
                    :header="$t('latitude')"
                    style="min-width: 12rem"
                    sortable
                />

                <!-- Longitude -->
                <Column
                    field="longitude"
                    :header="$t('longitude')"
                    style="min-width: 12rem"
                    sortable
                />

                <!-- Active -->
                <Column
                    field="active"
                    :header="$t('active')"
                    sortable
                    style="min-width: 6rem"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="$t(getActiveValue(slotProps.data))"
                            :severity="getActiveLabel(slotProps.data)"
                        />
                    </template>
                </Column>

                <!-- Actions -->
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            class="mr-2"
                            @click="editRegion(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteRegion(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Város szerkesztése -->
        <Dialog
            v-model:visible="regionDialog"
            :style="{ width: '550px' }"
            :header="$t('regions_details')"
            :modal="true"
        >
            <div class="flex flex-col gap-6">
                <div class="flex flex-wrap gap-4">
                    <!-- Name -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="name" class="block font-bold mb-3">{{
                            $t("name")
                        }}</label>
                        <InputText
                            id="name"
                            v-model="region.name"
                            autofocus
                            fluid
                        />
                        <small class="text-red-500" v-if="v$.name.$error">
                            {{ $t(v$.name.$errors[0].$message) }}
                        </small>
                    </div>

                    <div class="flex flex-col grow basis-0 gap-2">
                        <!-- Active -->
                        <label
                            for="inventoryStatus"
                            class="block font-bold mb-3"
                            >{{ $t("active") }}</label
                        >
                        <Select
                            id="active"
                            name="active"
                            v-model="region.active"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Cities"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <!-- Country -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="country_id" class="block font-bold mb-3">
                            {{ $t("country") }}
                        </label>
                        <Select
                            id="country_id"
                            fluid
                            v-model="region.country_id"
                            :options="props.countries"
                            optionLabel="name"
                            optionValue="id"
                            :placholder="$t('country')"
                        />

                        <small class="text-red-500" v-if="v$.country_id.$error">
                            {{ $t(v$.country_id.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- Region -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="region_id" class="block font-bold mb-3">
                            {{ $t("region") }}
                        </label>
                        <Select
                            id="region_id"
                            name="region_id"
                            fluid
                            v-model="region.region_id"
                            :options="props.regions"
                            optionLabel="name"
                            optionValue="id"
                            :placholder="$t('region')"
                        />

                        <small class="text-red-500" v-if="v$.region_id.$error">
                            {{ $t(v$.region_id.$errors[0].$message) }}
                        </small>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <!-- Latitude -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="latitude" class="block font-bold mb-3">
                            {{ $t("latitude") }}
                        </label>
                        <InputText
                            id="latitude"
                            v-model="region.latitude"
                            fluid
                            disabled
                        />
                    </div>

                    <!-- Longitude -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="longitude" class="block font-bold mb-3">
                            {{ $t("longitude") }}
                        </label>
                        <InputText
                            id="longitude"
                            v-model="region.longitude"
                            fluid
                            disabled
                        />
                    </div>
                </div>
            </div>

            <template #footer>
                <Button
                    :label="$t('cancel')"
                    icon="pi pi-times"
                    text
                    @click="hideDialog"
                />
                <Button
                    :label="$t('save')"
                    icon="pi pi-check"
                    @click="saveRegion"
                />
            </template>
        </Dialog>

        <!-- Város törlése -->
        <!-- Egy megerősítő párbeszédpanel, amely megjelenik, ha a felhasználó törölni szeretne egy várost. -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést. -->
        <Dialog
            v-model:visible="deleteRegionDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <!-- A párbeszédpanel tartalma -->
            <div class="flex items-center gap-4">
                <!-- A figyelmeztető ikon -->
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <!-- A szöveg, amely megjelenik a párbeszédpanelen -->
                <span v-if="region"
                    >{{ $t("confirm_delete_2") }} <b>{{ region.name }}</b
                    >?</span
                >
            </div>
            <!-- A párbeszédpanel lábléc, amely tartalmazza a gombokat -->
            <template #footer>
                <!-- A "Nem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteRegionDialog = false"
                    text
                />
                <!-- A "Igen" gomb, amely törli a várost -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteRegion"
                />
            </template>
        </Dialog>

        <!-- Erősítse meg a kiválasztott városok törlését párbeszédpanelen -->
        <!-- Ez a párbeszédpanel akkor jelenik meg, ha a felhasználó több várost szeretne törölni -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést -->
        <Dialog
            v-model:visible="deleteSelectedRegionsDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="region">{{ $t("confirm_delete") }}</span>
            </div>
            <template #footer>
                <!-- "Mégsem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSelectedRegionsDialog = false"
                    text
                />
                <!-- Megerősítés gomb -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSelectedCities"
                />
            </template>
        </Dialog>
    </AppLayout>
</template>
