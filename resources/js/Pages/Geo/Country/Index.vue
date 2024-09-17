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

import CountryService from "@/service/CountryService";
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
    /**
     * Régiók adatai.
     */
    regions: {
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
const countries = ref();

/**
 * Reaktív hivatkozás a város adatainak megjelenítő párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const countryDialog = ref(false);

/**
 * Reaktív hivatkozás a városok törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteSelectedCountriesDialog = ref(false);

/**
 * Reaktív hivatkozás a kijelölt város(ok) törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteCountryDialog = ref(false);

/**
 * Reaktív hivatkozás a város adatainak tárolására.
 *
 * @type {Object}
 * @property {string} name - A város neve.
 * @property {number} country_id - Az ország azonosítója.
 * @property {number} region_id - A régió azonosítója.
 * @property {number} active - A város státusza.
 * @property {number} id - A város azonosítója.
 */
const country = ref({
    name: "",
    country_id: null,
    region_id: null,
    active: 1,
    id: null,
});

/**
 * Reaktív hivatkozás a kijelölt városok tárolására.
 *
 * @type {Array}
 */
const selectedCountries = ref();

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
    region_id: {
        required: helpers.withMessage("validate_region_id", required),
    },
};

/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 *
 * @type {Object}
 */
const v$ = useVuelidate(rules, country);

// ======================================================

/**
 * Lekéri a városok listáját az API-ból.
 *
 * Ez a funkció a városok listáját lekéri az API-ból.
 * A városok listája a countries változóban lesz elmentve.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megoldódik.
 */
const fetchItems = () => {
    CountryService.getCountries()
        .then((response) => {
            //console.log(response);
            // A városok listája a countries változóban lesz elmentve
            countries.value = response.data.data;
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("getCountries API Error:", error);
        });
};

/**
 * Eseménykezelő, amely a komponens létrejöttekor hívódik meg.
 *
 * Ez a funkció a városok listáját lekéri az API-ból, amikor a komponens létrejön.
 * A városok listája a countries változóban lesz elmentve.
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
 * A deleteCountrysDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott termékek törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    // Állítsa a deleteCountriessDialog változó értékét igazra,
    // amely megnyitja a megerősítő párbeszédablakot a kiválasztott termékek törléséhez.
    deleteSelectedCountriesDialog.value = true;
}

/**
 * Nyitja meg az új város dialógusablakot.
 *
 * Ez a függvény a country változó értékét alaphelyzetbe állítja, a submitted változó értékét False-ra állítja,
 * és a countryDialog változó értékét igazra állítja, amely megnyitja az új város dialógusablakot.
 *
 * @return {void}
 */
function openNew() {
    country.value = { ...initialCountry };
    submitted.value = false;
    countryDialog.value = true;
}

/**
 * Az új város objektum alapértelmezett értékei.
 *
 * A countryDialog változó értékét igazra állítva, ez az objektum lesz a dialógusablakban
 * megjelenő új város formban.
 *
 * @type {Object}
 * @property {string} name - A város neve.
 * @property {number} country_id - Az ország azonosítója.
 * @property {number} region_id - A régió azonosítója.
 * @property {number} active - A város aktív-e? (1 igen, 0 nem).
 * @property {number} id - A város azonosítója.
 */
const initialCountry = {
    name: "",
    //country_id: null,
    //region_id: null,
    code: '',
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
    countryDialog.value = false;
    submitted.value = false;

    // Visszaállítja a validációs objektumot az alapértelmezett állapotába.
    v$.value.$reset();
};

/**
 * Szerkeszti a kiválasztott várost.
 *
 * Ez a funkció a kiválasztott város adatait másolja a country változóba,
 * és megnyitja a dialógusablakot a város szerkesztéséhez.
 *
 * @param {object} data - A kiválasztott város adatai.
 * @return {void}
 */
const editCountry = (data) => {
    // Másolja a kiválasztott város adatait a country változóba.
    country.value = { ...data };

    // Nyissa meg a dialógusablakot a város szerkesztéséhez.
    countryDialog.value = true;
};

/**
 * Megerősítés a város törléséhez.
 *
 * Ez a funkció a country változóba másolja a kiválasztott város adatait,
 * és megnyitja a dialógusablakot a város törléséhez.
 *
 * @param {object} data - A kiválasztott város adatai.
 * @return {void}
 */
const confirmDeleteCountry = (data) => {
    // Másolja a kiválasztott város adatait a country változóba.
    country.value = { ...data };

    // Nyissa meg a dialógusablakot a város törléséhez.
    deleteCountryDialog.value = true;
};

const saveCountry = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (country.value.id) {
            updateCountry();
        } else {
            createCountry();
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
const createCountry = () => {
    CountryService.createCountry(country.value)
        .then((response) => {
            //console.log('response', response);
            countries.values.push(response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Country Created",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("createCountry API Error:", error);
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
const updateCountry = () => {
    CountryService.updateCountry(country.value.id, country.value)
        .then(() => {
            // Megkeresi a város indexét a városok tömbjében az azonosítója alapján
            const index = findIndexById(country.value.id);
            // A város adatait frissíti a városok tömbjében
            countries.value.splice(index, 1, country.value);

            // Bezárja a dialógus ablakot
            hideDialog();

            // Siker-értesítést jelenít meg
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Country Updated",
                life: 3000,
            });
        })
        .catch(error => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error('updateCountry API Error:', error);
        });
};

/**
 * Megkeresi egy város indexét a városok tömbjében az azonosítója alapján.
 *
 * @param {number} id - A keresendő város azonosítója.
 * @returns {number} A város indexe a városok tömbjében, vagy -1, ha nem található.
 */
const findIndexById = (id) => {
    return countries.value.findIndex((country) => country.id === id);
};

const deleteCountry = () => {
    CountryService.deleteCountry(country.value.id)
        .then((response) => {
            //
        })
        .catch((error) => {
            console.error("deleteCountry API Error:", error);
        });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const deleteSelectedCountries = () => {
    console.log(selectedCountries.value);
};

//const getCountryName = (id) => {
//    return props.countries.find((country) => country.id === id).name;
//};

//const getRegionName = (id) => {
    //console.log('props.regions', props.regions);
    //console.log('id', id);

//    let region = props.regions.find((region) => region.country_id === id);

//    if( region )
//    {
//        console.log('region', region);
//    }

    //return props.regions.find((region) => region.id === id).name;
//};

const getActiveLabel = (country) =>
    ["danger", "success", "warning"][country.active || 2];

const getActiveValue = (country) =>
    ["inactive", "active", "pending"][country.active] || "pending";
</script>

<template>
    <AppLayout>
        <Head :title="$t('countries')" />

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
                        :disabled="!selectedCountries || !selectedCountries.length"
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
                v-model:selection="selectedCountries"
                :value="countries"
                dataKey="id"
                :paginator="true"
                :rows="10"
                :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} countries"
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <!-- FELIRAT -->
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("countries_title") }}
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
<!--               <Column
                    field="country_id"
                    :header="$t('country')"
                    sortable
                    style="min-width: 12rem"
                >
                    <template #body="slotProps">
                        {{ getCountryName(slotProps.data.country_id) }}
                    </template>
                </Column>
            -->
                <!-- Region -->
<!--                <Column
                    :header="$t('region')"
                    sortable
                    style="min-width: 12rem"
                >
                    <template #body="slotProps">
                        {{ getRegionName(slotProps.data.id) }}
                    </template>
                </Column>
-->
                <!-- Country -->
<!--                <Column
                    field="latitude"
                    :header="$t('latitude')"
                    style="min-width: 12rem"
                    sortable
                >
                    <template #body="slotProps">
                        {{ getCountryName(slotProps.data.country_id) }}
                    </template>
                </Column>
-->
                <!-- Longitude -->
<!--                <Column
                    field="longitude"
                    :header="$t('longitude')"
                    style="min-width: 12rem"
                    sortable
                />
-->
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
                            @click="editCountry(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteCountry(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Város szerkesztése -->
        <Dialog
            v-model:visible="countryDialog"
            :style="{ width: '550px' }"
            :header="$t('countries_details')"
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
                            v-model="country.name"
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
                            v-model="country.active"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Countries"
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
                            v-model="country.country_id"
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
                            v-model="country.region_id"
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
                            v-model="country.latitude"
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
                            v-model="country.longitude"
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
                    @click="saveCountry"
                />
            </template>
        </Dialog>

        <!-- Város törlése -->
        <!-- Egy megerősítő párbeszédpanel, amely megjelenik, ha a felhasználó törölni szeretne egy várost. -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést. -->
        <Dialog
            v-model:visible="deleteCountryDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <!-- A párbeszédpanel tartalma -->
            <div class="flex items-center gap-4">
                <!-- A figyelmeztető ikon -->
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <!-- A szöveg, amely megjelenik a párbeszédpanelen -->
                <span v-if="country"
                    >{{ $t("confirm_delete_2") }} <b>{{ country.name }}</b
                    >?</span
                >
            </div>
            <!-- A párbeszédpanel lábléc, amely tartalmazza a gombokat -->
            <template #footer>
                <!-- A "Nem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteCountryDialog = false"
                    text
                />
                <!-- A "Igen" gomb, amely törli a várost -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteCountry"
                />
            </template>
        </Dialog>

        <!-- Erősítse meg a kiválasztott városok törlését párbeszédpanelen -->
        <!-- Ez a párbeszédpanel akkor jelenik meg, ha a felhasználó több várost szeretne törölni -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést -->
        <Dialog
            v-model:visible="deleteSelectedCountriesDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="country">{{ $t("confirm_delete") }}</span>
            </div>
            <template #footer>
                <!-- "Mégsem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSelectedCountriesDialog = false"
                    text
                />
                <!-- Megerősítés gomb -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSelectedCountries"
                />
            </template>
        </Dialog>
    </AppLayout>
</template>
