<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from "../../../Validation/ValidationRules.json";

import CityService from "@/service/CityService";

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
import { createId } from "@/helpers/functions";

/**
 * Szerver felöl jövő adatok
 */
const props = defineProps({
    countries: { type: Object, default: () => {}, },
    regions: { type: Object, default: () => {}, },
});

/**
 * Az állapotmező logikai értékeit adja vissza.
 *
 * @returns {Array<Object>} objektumok tömbje címke és érték tulajdonságokkal.
 */
const getBools = () => {
    return [
        { label: trans("inactive"), value: 0, },
        { label: trans("active"), value: 1, },
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
const deleteSelectedCitiesDialog = ref(false);

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
 * @property {string} name - A város neve.
 * @property {number} country_id - Az ország azonosítója.
 * @property {number} region_id - A régió azonosítója.
 * @property {number} active - A város státusza.
 * @property {number} id - A város azonosítója.
 */
const city = ref({
    id: null,
    name: "",
    country_id: null,
    region_id: null,
    active: 1,
});

const initialCity = () => {
    return {...city};
};

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
const filters = ref({});

const initFilters = () => {
    filters.value = {
        // A globális szűrőobjektum.
        // Van egy érték tulajdonsága a keresési lekérdezés tárolására
        // és egy matchMode tulajdonsága a keresés típusának megadásához.
        global: { value: null, matchMode: FilterMatchMode.CONTAINS, },
    }
};

initFilters();

/**
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 *
 * @type {ref<boolean>}
 */
const submitted = ref(false);

const rules = {
    name: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
    country_id: {
        required: helpers.withMessage( trans("validate_country_id") , required),
    },
    region_id: {
        required: helpers.withMessage( trans("validate_region_id") , required),
    },
};
/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 *
 * @type {Object}
 */
const v$ = useVuelidate(rules, city);

// ======================================================

/**
 * Lekéri a városok listáját az API-ból.
 *
 * Ez a funkció a városok listáját lekéri az API-ból.
 * A városok listája a cities változóban lesz elmentve.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megoldódik.
 */
const fetchItems = () => {
    CityService.getCities()
        .then((response) => {
            console.log(response.data.data);
            // A városok listája a cities változóban lesz elmentve
            cities.value = response.data.data;
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("getCities API Error:", error);
        });
};

/**
 * Eseménykezelő, amely a komponens létrejöttekor hívódik meg.
 *
 * Ez a funkció a városok listáját lekéri az API-ból, amikor a komponens létrejön.
 * A városok listája a cities változóban lesz elmentve.
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
 * A deleteCitysDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott termékek törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    deleteSelectedCitiesDialog.value = true;
}

/**
 * Nyitja meg az új város dialógusablakot.
 *
 * Ez a függvény a city változó értékét alaphelyzetbe állítja, a submitted változó értékét False-ra állítja,
 * és a cityDialog változó értékét igazra állítja, amely megnyitja az új város dialógusablakot.
 *
 * @return {void}
 */
function openNew() {
    city.value = { ...initialCity };
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
};

/**
 * Szerkeszti a kiválasztott várost.
 *
 * Ez a funkció a kiválasztott város adatait másolja a city változóba,
 * és megnyitja a dialógusablakot a város szerkesztéséhez.
 *
 * @param {object} data - A kiválasztott város adatai.
 * @return {void}
 */
const editCity = (data) => {
    // Másolja a kiválasztott város adatait a city változóba.
    city.value = { ...data };

    // Nyissa meg a dialógusablakot a város szerkesztéséhez.
    cityDialog.value = true;
};

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
    // Másolja a kiválasztott város adatait a city változóba.
    city.value = { ...data };

    // Nyissa meg a dialógusablakot a város törléséhez.
    deleteCityDialog.value = true;
};

const saveCity = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (city.value.id) {
            updateCity();
        } else {
            createCity();
        }
    } else {
        alert("FAIL");
    }
};

/**
 * Hozzon létre új várost.
 *
 * A metódus létrehoz egy új várost a szerveren a CityService.createCity() függvénnyel,
 * és a választ megjeleníti a datatable-ben.
 *
 * Ha a város létrehozása sikeres, akkor a dialógusablakot bezárja,
 * és egy sikerüzenetet jelenít meg a felhasználónak.
 *
 * Ha a város létrehozása sikertelen, akkor a dialógusablakot bezárja,
 * és egy hibaüzenetet jelenít meg a felhasználónak.
 *
 * @return {void}
 */
const createCity = () => {
    /**
     * Létrehoz egy új város objektumot az aktuális város adataival és egy új azonosítóval.
     * 
     * A city.value objektum másolatát hozza létre, és hozzáad egy új egyedi azonosítót 
     * az új városhoz.
     * 
     * @type {Object}
     * @property {number} id - Az új város egyedi azonosítója.
     */
    const newCity = { ...city.value, id: createId() };

    // Add hozzá az új várost a városok tömbjéhez.
    cities.values.push(newCity);

    // Zárja be a dialógusablakot.
    hideDialog();

    // Sikerüzenetet jelenít meg a felhasználónak.
    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "City creation in progress",
        life: 3000,
    });

    // Küldjön egy API kérést az új város létrehozására.
    CityService.createCity(city.value)
        .then((response) => {
            // Frissítse az új város objektumot a szervertől kapott válasszal.
            const index = cities.value.findIndex(cit => cit.id === newCity.id);
            cities.value.splice(index, 1, response.data);
        })
        .catch((error) => {
            // Ha hiba történik, távolítsa el az új várost a városok tömbjéből.
            const index = cities.values.indexOf(newCity);
            if (index !== -1) {
                cities.values.splice(index, 1);
            }

            // Jelenítse meg a hibaüzenetet a konzolon.
            console.error("createCity API Error:", error);

            // Hibaüzenetet jelenít meg a felhasználónak.
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to create city",
                life: 3000,
            });
        });
};

const updateCity = () => {
    /**
     * Keresse meg a város indexét a városok tömbjében az azonosítója alapján.
     * -1-et ad vissza, ha a város nem található.
     *
     * @param {number} id A keresendő város azonosítója.
     * @returns {number} A város indexe a városok tömbjében, vagy -1, ha nem található.
     */
    const index = findIndexById(city.value.id);

    /**
     * Ellenőrzi, hogy létezik-e a város a városok tömbjében az azonosítója alapján.
     * Ha nem található, akkor vége a metódusnak.
     */
    if (index === -1) {
        console.error(`City with id ${city.value.id} not found`);
        return;
    }

    /**
     * Tárolja el a jelenlegi város adatait, hogy vissza tudjuk állítani,
     * ha a város frissítése sikertelen.
     *
     * @type {Object}
     */
    const originalCity = { ...cities.value[index] };

    // Tárolja el a módosított város adatait a városok tömbjében.
    // A ...city.value szintaxissal másoljuk a city.value objektumot,
    // hogy ne legyen referencia a régi város adatokra.
    cities.value.splice(index, 1, { ...city.value });

    // Bezárja a dialógusablakot, miután a városok tömbjében elmentette a módosított város adatait.
    // A hideDialog() függvényt a submitCity() függvényben is használjuk,
    // azért hívjuk meg itt is, hogy a dialógusablakot bezárjuk,
    // miután a város adatait elmentettük a szerveren.
    hideDialog();

    // Jelenít meg egy információs üzenetet, hogy a város frissítése folyamatban van.
    // A life tulajdonsággal beállítjuk, hogy az üzenet 2 másodperc múlva eltűnjön.
    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "City update in progress",
        life: 2000,
    });

    /**
     * Hívja meg az API-t a város frissítésére az azonosító alapján.
     *
     * A sikeres API hívás esetén frissíti a város adatait a szerver válasza alapján.
     * Hiba esetén visszaállítja az eredeti város adatait.
     */
    CityService.updateCity(city.value.id, city.value)
        .then((response) => {
            // Frissítse a város adatait a szerver válaszával
            cities.value.splice(index, 1, response.data);

            // Sikerüzenet megjelenítése a felhasználónak
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "City Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            // Állítsa vissza az eredeti város adatokat hiba esetén
            cities.value.splice(index, 1, originalCity);

            // Hibaüzenet megjelenítése a konzolon
            console.error("updateCity API Error:", error);

            // Hibaüzenet megjelenítése a felhasználónak
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to update city",
                life: 3000,
            });
        });
};

const deleteSelectedCities = () => {
    const originalCity = [...cities.value];

    const selectedCityIds = new Set(selectedCities.value.map(city => city.id));

    cities.value = cities.value.filter(city => !selectedCityIds.has(city.id));

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Deleting selected cities...",
        life: 2000,
    });

    CityService.deleteSelectedCities(selectedCityIds)
        .then(() => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Deleted selected cities",
                life: 3000,
            });
        })
        .catch(error => {
            cities.value = originalCity;
            
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete selected cities",
                life: 3000,
            });
            
            console.error("deleteSelectedCities API Error:", error);
        });
};

/**
 * Törli a kiválasztott várost a szerverről.
 *
 * A metódus elküld egy API kérést a város azonosítójával,
 * és eltávolítja a várost a cities változóból.
 *
 * Ha a törlés sikeres, akkor egy sikerüzenetet jelenít meg a felhasználónak.
 * Hiba esetén visszaállítja az eredeti várost, és egy hibaüzenetet jelenít meg 
 * a felhasználónak.
 *
 * @return {void}
 */
const deleteCity = () => {
    // Keresse meg a kiválasztott város indexét a cities tömbben.
    const index = findIndexById(city.value.id);
    // Ellenőrizze, hogy a kiválasztott város indexe érvényes-e.
    // Ha nem, akkor nem csinál semmit, és kilép a függvényből.
    if (index === -1) {
        console.warn("No city found with the given id:", city.value.id);
        return;
    }

    // Tárolja el a kiválasztott város eredeti értékeit, hogy vissza tudjuk állítani,
    // ha a város törlése sikertelen.
    const originalCity = { ...cities.value[index] };

    // Törli a kiválasztott várost a cities tömbből.
    // A splice() metódus használatával törli a várost a tömbből.
    // A splice() metódus két paramétert vár:
    // 1. Az index, ahol el kell kezdeni a törlést.
    // 2. A törlendő elemek száma.
    // Mivel csak egy elemet akarunk törölni, ezért a második paraméter 1.
    cities.value.splice(index, 1);

    // Jelenítse meg egy figyelmeztető toast üzenetet a felhasználónak,
    // hogy a város törlése folyamatban van.
    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "City deletion in progress",
        life: 2000,
    });

    /**
     * Törli a várost az azonosítója alapján az API-ból.
     *
     * Az API kérés sikeres válasza esetén megjelenít egy sikerüzenetet.
     * Hiba esetén visszaállítja az eredeti várost, és megjelenít egy hibaüzenetet.
     */
    CityService.deleteCity(city.value.id)
        .then(() => {
            // Sikeres törlés esetén megjelenít egy sikerüzenetet toastban.
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "City Deleted",
                life: 3000,
            });
        })
        .catch(error => {
            // Hiba esetén visszaállítja az eredeti várost a tömbben.
            cities.value.splice(index, 0, originalCity);

            // Kiírja a hibát a konzolra.
            console.error("deleteCity API Error:", error);
            
            // Megjelenít egy hibaüzenetet toastban.
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete city",
                life: 3000,
            });
        });
};

/**
 * Megkeresi egy város indexét a városok tömbjében az azonosítója alapján.
 *
 * @param {number} id - A keresendő város azonosítója.
 * @returns {number} A város indexe a városok tömbjében, vagy -1, ha nem található.
 */
 const findIndexById = (id) => {
    return cities.value.findIndex((city) => city.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const getCountryName = (id) => {
    return props.countries.find((country) => country.id === id).name;
};

const getRegionName = (id) => {
    return props.regions.find((region) => region.id === id).name;
};

const getActiveLabel = (city) =>
    ["danger", "success", "warning"][city.active || 2];

const getActiveValue = (city) =>
    ["inactive", "active", "pending"][city.active] || "pending";
</script>

<template>
    <AppLayout>
        <Head :title="$t('cities')" />

        <div class="card">
            <Toolbar class="md-6">
                <template #start>
                    <Button
                        :label="$t('add_new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                    />
                    <Button
                        :label="$t('delete_selected')"
                        icon="pi pi-trash"
                        severity="secondary"
                        @click="confirmDeleteSelected"
                        :disabled="!selectedCities || !selectedCities.length"
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
                v-model:selection="selectedCities"
                :value="cities"
                dataKey="id"
                :paginator="true"
                :rows="10"
                :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} cities"
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <!-- FELIRAT -->
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("cities_title") }}
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
                            @click="editCity(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteCity(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Város szerkesztése -->
        <Dialog
            v-model:visible="cityDialog"
            :style="{ width: '550px' }"
            :header="$t('cities_details')"
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
                            v-model="city.name"
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
                            for="active"
                            class="block font-bold mb-3"
                            >{{ $t("active") }}</label
                        >
                        <Select
                            id="active"
                            name="active"
                            v-model="city.active"
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
                            v-model="city.country_id"
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
                            v-model="city.region_id"
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
                            v-model="city.latitude"
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
                            v-model="city.longitude"
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
                    @click="saveCity"
                />
            </template>
        </Dialog>

        <!-- Város törlése -->
        <!-- Egy megerősítő párbeszédpanel, amely megjelenik, ha a felhasználó törölni szeretne egy várost. -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést. -->
        <Dialog
            v-model:visible="deleteCityDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <!-- A párbeszédpanel tartalma -->
            <div class="flex items-center gap-4">
                <!-- A figyelmeztető ikon -->
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <!-- A szöveg, amely megjelenik a párbeszédpanelen -->
                <span v-if="city"
                    >{{ $t("confirm_delete_2") }} <b>{{ city.name }}</b
                    >?</span
                >
            </div>
            <!-- A párbeszédpanel lábléc, amely tartalmazza a gombokat -->
            <template #footer>
                <!-- A "Nem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteCityDialog = false"
                    text
                />
                <!-- A "Igen" gomb, amely törli a várost -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteCity"
                />
            </template>
        </Dialog>

        <!-- Erősítse meg a kiválasztott városok törlését párbeszédpanelen -->
        <!-- Ez a párbeszédpanel akkor jelenik meg, ha a felhasználó több várost szeretne törölni -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést -->
        <Dialog
            v-model:visible="deleteSelectedCitiesDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="city">{{ $t("confirm_delete") }}</span>
            </div>

            <template #footer>
                <!-- "Mégsem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSelectedCitiesDialog = false"
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
