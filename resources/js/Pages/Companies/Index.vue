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

import CompanyService from "@/service/CompanyService";
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
    companies: {
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
const companies = ref();

/**
 * Reaktív hivatkozás a város adatainak megjelenítő párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const companyDialog = ref(false);

/**
 * Reaktív hivatkozás a városok törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteSelectedCompaniesDialog = ref(false);

/**
 * Reaktív hivatkozás a kijelölt város(ok) törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteCompanyDialog = ref(false);

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
const company = ref({
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
const selectedCompanies = ref();

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
const v$ = useVuelidate(rules, company);

// ======================================================

/**
 * Lekéri a városok listáját az API-ból.
 *
 * Ez a funkció a városok listáját lekéri az API-ból.
 * A városok listája a companies változóban lesz elmentve.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megoldódik.
 */
const fetchItems = () => {
    CompanyService.getCompanies()
        .then((response) => {
            // A városok listája a companies változóban lesz elmentve
            companies.value = response.data.data;
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("getCompanies API Error:", error);
        });
};

/**
 * Eseménykezelő, amely a komponens létrejöttekor hívódik meg.
 *
 * Ez a funkció a városok listáját lekéri az API-ból, amikor a komponens létrejön.
 * A városok listája a companies változóban lesz elmentve.
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
 * A deleteCompanysDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott termékek törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    // Állítsa a deleteCompaniessDialog változó értékét igazra,
    // amely megnyitja a megerősítő párbeszédablakot a kiválasztott termékek törléséhez.
    deleteSelectedCompaniesDialog.value = true;
}

/**
 * Nyitja meg az új város dialógusablakot.
 *
 * Ez a függvény a company változó értékét alaphelyzetbe állítja, a submitted változó értékét False-ra állítja,
 * és a companyDialog változó értékét igazra állítja, amely megnyitja az új város dialógusablakot.
 *
 * @return {void}
 */
function openNew() {
    company.value = { ...initialCompany };
    submitted.value = false;
    companyDialog.value = true;
}

/**
 * Az új város objektum alapértelmezett értékei.
 *
 * A companyDialog változó értékét igazra állítva, ez az objektum lesz a dialógusablakban
 * megjelenő új város formban.
 *
 * @type {Object}
 * @property {string} name - A város neve.
 * @property {number} country_id - Az ország azonosítója.
 * @property {number} region_id - A régió azonosítója.
 * @property {number} active - A város aktív-e? (1 igen, 0 nem).
 * @property {number} id - A város azonosítója.
 */
const initialCompany = {
    name: "",
    country_id: null,
    region_id: null,
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
    companyDialog.value = false;
    submitted.value = false;

    // Visszaállítja a validációs objektumot az alapértelmezett állapotába.
    v$.value.$reset();
};

/**
 * Szerkeszti a kiválasztott várost.
 *
 * Ez a funkció a kiválasztott város adatait másolja a company változóba,
 * és megnyitja a dialógusablakot a város szerkesztéséhez.
 *
 * @param {object} data - A kiválasztott város adatai.
 * @return {void}
 */
const editCompany = (data) => {
    // Másolja a kiválasztott város adatait a company változóba.
    company.value = { ...data };

    // Nyissa meg a dialógusablakot a város szerkesztéséhez.
    companyDialog.value = true;
};

/**
 * Megerősítés a város törléséhez.
 *
 * Ez a funkció a company változóba másolja a kiválasztott város adatait,
 * és megnyitja a dialógusablakot a város törléséhez.
 *
 * @param {object} data - A kiválasztott város adatai.
 * @return {void}
 */
const confirmDeleteCompany = (data) => {
    // Másolja a kiválasztott város adatait a company változóba.
    company.value = { ...data };

    // Nyissa meg a dialógusablakot a város törléséhez.
    deleteCompanyDialog.value = true;
};

const saveCompany = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (company.value.id) {
            updateCompany();
        } else {
            createCompany();
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
const createCompany = () => {
    CompanyService.createCompany(company.value)
        .then((response) => {
            //console.log('response', response);
            companies.values.push(response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Company Created",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("createCompany API Error:", error);
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
const updateCompany = () => {
    CompanyService.updateCompany(company.value.id, company.value)
        .then(() => {
            // Megkeresi a város indexét a városok tömbjében az azonosítója alapján
            const index = findIndexById(company.value.id);
            // A város adatait frissíti a városok tömbjében
            companies.value.splice(index, 1, company.value);

            // Bezárja a dialógus ablakot
            hideDialog();

            // Siker-értesítést jelenít meg
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Company Updated",
                life: 3000,
            });
        })
        .catch(error => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error('updateCompany API Error:', error);
        });
};

/**
 * Megkeresi egy város indexét a városok tömbjében az azonosítója alapján.
 *
 * @param {number} id - A keresendő város azonosítója.
 * @returns {number} A város indexe a városok tömbjében, vagy -1, ha nem található.
 */
const findIndexById = (id) => {
    return companies.value.findIndex((company) => company.id === id);
};

const deleteCompany = () => {
    CompanyService.deleteCompany(company.value.id)
        .then((response) => {
            //
        })
        .catch((error) => {
            console.error("deleteCompany API Error:", error);
        });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const deleteSelectedCompanies = () => {
    console.log(selectedCompanies.value);
};

const getCountryName = (id) => {
    return props.countries.find((country) => country.id === id).name;
};

const getRegionName = (id) => {
    return props.regions.find((region) => region.id === id).name;
};

const getActiveLabel = (company) =>
    ["danger", "success", "warning"][company.active || 2];

const getActiveValue = (company) =>
    ["inactive", "active", "pending"][company.active] || "pending";
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
                <Column
                    field="company_id"
                    :header="$t('company')"
                    sortable
                    style="min-width: 16rem"
                >
                    <template #body="slotProps">
                        {{ getCompanyName(slotProps.data.company_id) }}
                    </template>
                </Column>

                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            class="mr-2"
                            @click="editCompany(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteCompany(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
