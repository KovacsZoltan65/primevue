<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";

import { trans } from "laravel-vue-i18n";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from "../../Validation/validationRules.json";

import SubdomainService from "@/service/SubdomainService";

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
//import { usePrimeVue } from "primevue/config";
import FileUpload from "primevue/fileupload";

const loading = ref(true);

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

const getBools = () => {
    return [
        {
            label: trans("inactive"),
            value: 0,
        },
        {
            label: trans("active"),
            value: 1,
        },
    ];
};


/**
 * Reaktív hivatkozás a PrimeVue toast komponensre.
 *
 * @type {Object}
 */
const toast = useToast();

/**
 * Reaktív hivatkozás a PrimeVue DataTable komponensre.
 *
 * @type {Object}
 */
const dt = ref();

/**
 * Reaktív hivatkozás a subdomainek listájára.
 *
 * A subdomains változóban lesznek tárolva a subdomainek,
 * amelyek a DataTable komponensben lesznek megjelenítve.
 *
 * @type {ref<Array>}
 */
const subdomains = ref([]);

/**
 * Reaktív hivatkozás a létrehozandó / módosítandó subdomain objektumra.
 *
 * A subdomain változóban lesz tárolva a létrehozandó / módosítandó subdomain,
 * amelyet a PrimeVue dialog komponensben lesz megjelenítve.
 *
 * @type {ref<Object>}
 */
const subdomain = ref({
    id: null,
    subdomain: "",
    url: "",
    name: "",
    db_host: "",
    db_port: 3306,
    db_name: "",
    db_user: "",
    db_password: "",
    notification: 1,
    state_id: 1,
    is_mirror: 0,
    sso: 0,
    acs_id: 0,
    active: 1,
    last_export: null,
});

/**
 * Reaktív hivatkozás a subdomain dialógus ablak állapotára.
 *
 * A subdomainDialog változóban lesz tárolva a subdomain dialógus ablak
 * megnyitott (true) vagy bezárt (false) állapota.
 *
 * @type {ref<boolean>}
 */
const subdomainDialog = ref(false);

/**
 * Reaktív hivatkozás a kiválasztott subdomainek törléséhez használt párbeszédpanel megnyitásához.
 *
 * A deleteSelectedSubdomainsDialog változóban lesz tárolva a kiválasztott subdomainek törléséhez használt párbeszédpanel
 * megnyitott (true) vagy bezárt (false) állapota.
 *
 * @type {ref<boolean>}
 */
const deleteSelectedSubdomainsDialog = ref(false);

/**
 * Reaktív hivatkozás a kiválasztott subdomain törléséhez használt párbeszédpanel megnyitásához.
 *
 * A deleteSubdomainDialog változóban lesz tárolva a kiválasztott subdomain törléséhez használt párbeszédpanel
 * megnyitott (true) vagy bezárt (false) állapota.
 *
 * @type {ref<boolean>}
 */
const deleteSubdomainDialog = ref(false);

/**
 * Reaktív hivatkozás a kiválasztott subdomainekre.
 *
 * A selectedSubdomains változóban lesz tárolva a kiválasztott subdomainek
 * listája, amelyet a DataTable komponensben lesz megjelenítve.
 *
 * @type {ref<Array>}
 */
const selectedSubdomains = ref([]);

/**
 * Reaktív hivatkozás a globális keresés szűrőinek tárolására az adattáblában.
 *
 * A filters változóban lesz tárolva a globális keresés szűrőinek
 * objektuma, amelyet a DataTable komponensben lesz megjelenítve.
 *
 * @type {Object}
 */
const filters = ref({});

/**
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 *
 * @type {ref<boolean>}
 */
const submitted = ref(false);

/**
 * A validációs szabályokat tartalmazó objektum.
 *
 * A kulcsok a mezők nevei, az értékek pedig a validációs szabályok.
 *
 * @type {Object}
 */
const rules = {
    /**
     * A subdomain mező validációs szabálya.
     *
     * @type {Object}
     */
    subdomain: {},

    /**
     * A url mező validációs szabálya.
     *
     * @type {Object}
     */
    url: {},

    /**
     * A name mező validációs szabálya.
     *
     * A name mezőnek meg kell felelnie a következ  feltételeknek:
     * - a name nem lehet üres
     * - a name hosszának minimum 3 karakternek kell lennie
     * - a name hosszának maximum 255 karakternek kell lennie
     *
     * @type {Object}
     */
    name: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },

    /**
     * A db_host mező validációs szabálya.
     *
     * A db_host mezőnek meg kell felelnie a következ  feltételeknek:
     * - a db_host nem lehet üres
     * - a db_host hosszának minimum 3 karakternek kell lennie
     *
     * @type {Object}
     */
    db_host: {
        required: helpers.withMessage(trans('validate_field_required', {field: 'db_host'}), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
    },

    /**
     * A db_port mező validációs szabálya.
     *
     * A db_port mezőnek meg kell felelnie a következ  feltételeknek:
     * - a db_port nem lehet üres
     * - a db_port minimum 1-nek kell lennie
     * - a db_port maximum 9999-nek kell lennie
     *
     * @type {Object}
     */
    db_port: {
        required: helpers.withMessage(trans('validate_field_required', {field: 'db_port'}), required),
        min: helpers.withMessage( ({ $params }) => trans('validate_min.numeric', { min: $params.min }), minLength(validationRules.mysql_port_min)),
        max: helpers.withMessage( ({ $params }) => trans('validate_max.numeric', { max: $params.max }), minLength(validationRules.mysql_port_max)),
    },

    /**
     * A db_name mező validációs szabálya.
     *
     * A db_name mezőnek meg kell felelnie a következ  feltételeknek:
     * - a db_name nem lehet üres
     * - a db_name hosszának minimum 3 karakternek kell lennie
     *
     * @type {Object}
     */
    db_name: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
    },

    /**
     * A db_user mező validációs szabálya.
     *
     * A db_user mezőnek meg kell felelnie a következ  feltételeknek:
     * - a db_user nem lehet üres
     * - a db_user hosszának minimum 3 karakternek kell lennie
     *
     * @type {Object}
     */
    db_user: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
    },

    /**
     * A db_password mező validációs szabálya.
     *
     * A db_password mezőnek meg kell felelnie a következ  feltételeknek:
     * - a db_password nem lehet üres
     * - a db_password hosszának minimum 3 karakternek kell lennie
     *
     * @type {Object}
     */
    db_password: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
    },
};

/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 *
 * @type {Object}
 */
const v$ = useVuelidate(rules, subdomain);

// ======================================================

/**
 * Beállítja az alapértelmezett értékeket a szűrő mezők számára.
 *
 * A globális szűrő és a name, subdomain szűrők kezdőértékei.
 */
const initFilters = () => {
    filters.value = {
        // Globális szűrő
        global: {
            value: null, // A szűrő értéke
            matchMode: FilterMatchMode.CONTAINS, // A szűrő típusa (tartalmazza, kezdődik, pontosan)
        },
        // Név szűrő
        name: {
            operator: FilterOperator.AND, // Logikai operátor (és, vagy)
            constraints: [
                {
                    value: null, // A szűrő értéke
                    matchMode: FilterMatchMode.STARTS_WITH, // A szűrő típusa (tartalmazza, kezdődik, pontosan)
                },
            ],
        },
        // Alomain szűrő
        subdomain: {
            operator: FilterOperator.AND, // Logikai operátor (és, vagy)
            constraints: [
                {
                    value: null, // A szűrő értéke
                    matchMode: FilterMatchMode.STARTS_WITH, // A szűrő típusa (tartalmazza, kezdődik, pontosan)
                },
            ],
        },
        // URL szűrő
        url: {
            operator: FilterOperator.AND, // Logikai operátor (és, vagy)
            constraints: [
                {
                    value: null, // A szűrő értéke
                    matchMode: FilterMatchMode.STARTS_WITH, // A szűrő típusa (tartalmazza, kezdődik, pontosan)
                },
            ],
        },
        // db_name szűrő
        db_name: {
            operator: FilterOperator.AND, // Logikai operátor (és, vagy)
            constraints: [
                {
                    value: null, // A szűrő értéke
                    matchMode: FilterMatchMode.STARTS_WITH, // A szűrő típusa (tartalmazza, kezdődik, pontosan)
                },
            ],
        },
        db_user: {
            operator: FilterOperator.AND, // Logikai operátor (és, vagy)
            constraints: [
                {
                    value: null, // A szűrő értéke
                    matchMode: FilterMatchMode.STARTS_WITH, // A szűrő típusa (tartalmazza, kezdődik, pontosan)
                },
            ],
        },
        active: {
            operator: FilterOperator.AND, // Logikai operátor (és, vagy)
            constraints: [
                {
                    value: null, // A szűrő értéke
                    matchMode: FilterMatchMode.STARTS_WITH, // A szűrő típusa (tartalmazza, kezdődik, pontosan)
                },
            ],
        }
    }
};

/**
 * Beállítja az alapértelmezett értékeket a szűrő mezők számára.
 */
const clearFilter = () => {
    initFilters();
};

initFilters();

/**
 * Lekéri a subdomainok listáját az API-ból.
 *
 * Ez a funkció a subdomainok listáját lekéri az API-ból.
 * A subdomainok listája a subdomains változóban lesz elmentve.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megoldódik.
 */
const fetchItems = async () => {
    loading.value = true;

    try {
        const response = await SubdomainService.getSubdomains();
        subdomains.value = response.data.data;
    }catch(error) {
        console.error("getSubdomains API Error:", error);
    } finally {
        loading.value = false;
    }
};

/**
 * Lekéri a városok listáját az API-ból, amikor a komponens létrejön.
 *
 * Ez a funkció a városok listáját lekéri az API-ból, amikor a komponens létrejön.
 * A városok listája a subdomains változóban lesz elmentve.
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
 * A deleteSubdomainsDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott termékek törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    deleteSelectedSubdomainsDialog.value = true;
}

/**
 * Nyitja meg az új aldomain dialógusablakot.
 *
 * Ez a függvény a subdomain változó értékét alaphelyzetbe állítja, a submitted változó értékét False-ra állítja,
 * és a subdomainDialog változó értékét igazra állítja, amely megnyitja az új aldomain dialógusablakot.
 *
 * @return {void}
 */
function openNew() {
    subdomain.value = { ...initialSubdomain };
    submitted.value = false;
    subdomainDialog.value = true;
}

/**
 * Az új aldomain objektum alapértelmezett értékei.
 *
 * Ez az objektum az új aldomain dialógusablakban szerepl  mez k alapértelmezett értékeit tartalmazza.
 *
 * @type {Object}
 * @property {number} id - Az aldomain azonosítója.
 * @property {string} subdomain - Az aldomain neve.
 * @property {string} url - Az aldomain URL-je.
 * @property {string} name - Az aldomain neve.
 * @property {string} db_host - Az adatbázis hostja.
 * @property {number} db_port - Az adatbázis portja.
 * @property {string} db_name - Az adatbázis neve.
 * @property {string} db_user - Az adatbázis felhasználója.
 * @property {string} db_password - Az adatbázis jelszava.
 * @property {number} notification - Az értesítés állapota.
 * @property {number} state_id - Az aldomain állapotának azonosítója.
 * @property {number} is_mirror - Az aldomain tükör-e?
 * @property {number} sso - Az aldomain SSO-e?
 * @property {number} acs_id - Az aldomain ACS azonosítója.
 * @property {number} active - Az aldomain aktív-e?
 * @property {Date} last_export - Az utolsó export dátuma.
 */
const initialSubdomain = {
    id: null,
    subdomain: "",
    url: "",
    name: "",
    db_host: "",
    db_port: 3306,
    db_name: "",
    db_user: "",
    db_password: "",
    notification: 1,
    state_id: 1,
    is_mirror: 0,
    sso: 0,
    acs_id: 0,
    active: 1,
    last_export: null,
};

/**
 * Bezárja a dialógusablakot.
 *
 * Ez a függvény a dialógusablakot bezárja, és a submitted változó értékét False-ra állítja.
 * A v$.value.$reset() függvénnyel visszaállítja a validációs objektumot az alapértelmezett állapotába.
 *
 * @return {void}
 */
const hideDialog = () => {
    subdomainDialog.value = false;
    submitted.value = false;
    v$.value.$reset();
};

/**
 * Szerkeszti a kiválasztott aldomainet.
 *
 * Ez a funkció a kiválasztott aldomain adatait másolja a subdomain változóba,
 * és megnyitja a dialógusablakot a város szerkesztéséhez.
 *
 * @param {object} data - A kiválasztott aldomain adatai.
 * @return {void}
 */
const editSubdomain = (data) => {
    subdomain.value = { ...data };
    subdomainDialog.value = true;
};

/**
 * Megerősítés a subdomain törléséhez.
 *
 * Ez a funkció a kiválasztott subdomain adatait másolja a subdomain változóba,
 * és megnyitja a dialógusablakot a subdomain törléséhez.
 *
 * @param {object} data - A kiválasztott subdomain adatai.
 * @return {void}
 */
const confirmDeleteSubdomain = (data) => {
    subdomain.value = { ...data };

    deleteSubdomainDialog.value = true;
};
/**
 * Mentse el a subdomaint.
 *
 * Ez a funkció ellenőrizni fogja a subdomain változóban lévő adatokat a validációs szabályoknak,
 * és ha a validáció sikeres, akkor meghívja a createSubdomain() vagy updateSubdomain() függvényt.
 * A submitted változó értékét igazra állítja, hogy jelezze a beküldött állapotot.
 *
 * @return {void}
 */
const saveSubdomain = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (subdomain.value.id) {
            // Ha a subdomainnak van ID-ja, akkor frissíti a subdomainot.
            updateSubdomain();
        } else {
            // Ha a subdomainnak nincs ID-ja, akkor létrehozza az új subdomainot.
            createSubdomain();
        }
    } else {
        // Ha a validáció sikertelen, akkor figyelmeztetést jelenít meg.
        alert("FAIL");
    }
};

/**
 * Létrehozza az új aldomaint.
 *
 * A metódus meghívja a SubdomainService.createSubdomain() függvényt,
 * amely létrehozza az új aldomaint az API-ban.
 *
 * @return {Promise<void>} A metódusban visszaadott ígéret.
 */
const createSubdomain = async () => {
    try {
        // Létrehozza az új aldomaint az API-ban
        const response = await SubdomainService.createSubdomain(subdomain.value);

        // Felveszi az új aldomaint a lista végére
        subdomains.value.push(response.data);

        // Bezárja a dialógusablakot
        hideDialog();

        // Jelenít meg egy sikeres értesítést
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Subdomain Created",
            life: 3000,
        });
    } catch (error) {
        // Jelenít meg egy hibaüzenetet a konzolon
        console.error("createSubdomain API Error:", error);
    }
};

const updateSubdomain = async () => {
    const index = findIndexById(subdomain.value.id);
    try {
        const response = await SubdomainService.updateSubdomain(subdomain.value.id, subdomain.value);
        subdomains.value.splice(index, 1, response.data);
        hideDialog();
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Subdomain Updated",
            life: 3000,
        });
    } catch (error) {
        console.error("updateSubdomain API Error:", error);
    }
};

/**
 * Megkeresi a megadott azonosítójú aldomain indexét.
 *
 * @param {number} id Az aldomain azonosítója
 * @return {number} Az aldomain indexe
 */
const findIndexById = (id) => {
    return subdomains.value.findIndex((subdomain) => subdomain.id === id);
};

/**
 * Törli a kiválasztott aldomaint.
 *
 * A metódus meghívja a SubdomainService.deleteSubdomain() függvényt,
 * amely törli a kiválasztott aldomaint az API-ból.
 * A választ megjeleníti a konzolon.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megold dik.
 */
const deleteSubdomain = () => {
    SubdomainService.deleteSubdomain(subdomain.value.id)
        .then((response) => {
            // Megkeresi a város indexét a városok tömbjében az azonosítója alapján
            const index = findIndexById(subdomain.value.id);
            // A város adatait törli a városok tömbjéb l
            subdomains.value.splice(index, 1);

            // Bezárja a dialógus ablakot
            hideDialog();

            // Figyelmeztetést jelenít meg a sikerrel kapcsolatban
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Subdomain Deleted",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("deleteSubdomain API Error:", error);
        });
};

const deleteSelectedSubdomains = () => {
    console.log(selectedSubdomains.value);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const deleteSelectedSubdomain = () => {
    console.log(selectedSubdomains.value);
};

const getActiveLabel = (subdomain) =>
    ["danger", "success", "warning"][subdomain.active || 2];

const getActiveValue = (subdomain) =>
    ["inactive", "active", "pending"][subdomain.active] || "pending";

const fileupload = ref();

const upload = () => {
    fileupload.value.upload();
};

/**
 * A fájl feltöltését kezeli.
 *
 * Amikor a fájl feltöltése befejeződik, egy figyelmeztetést jelenít meg a sikerrel kapcsolatban.
 *
 * @return {void}
 */
const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

</script>

<template>
    <AppLayout>
        <Head :title="$t('subdomains')" />

        {{ $page.props.available_locales }}
        {{ $page.props.supported_locales }}
        {{ $page.props.locale }}

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
                        :disabled="
                            !selectedSubdomains || !selectedSubdomains.length
                        "
                    />
                </template>

                <template #end>
                    <FileUpload 
                        mode="basic" 
                        accept="image/*" 
                        :maxFileSize="1000000" 
                        label="Import" 
                        customUpload auto 
                        chooseLabel="Import" 
                        class="mr-2" 
                        :chooseButtonProps="{ severity: 'secondary' }"
                        @upload="onUpload"
                    />
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
                v-model:selection="selectedSubdomains"
                v-model:filters="filters"
                :value="subdomains"
                dataKey="id" :paginator="true" :rows="10" sortMode="multiple"
                :filters="filters" filterDisplay="menu"
                :globalFilterFields="['name', 'subdomain', 'url', 'db_name', 'db_user', 'active']"
                :loading="loading" stripedRows removableSort
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} subdomains"
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <!-- SZŰRÉS TÖRLÉSE -->
                        <Button
                            type="button"
                            icon="pi pi-filter-slash"
                            :label="$t('clear')"
                            outlined
                            @click="clearFilter()"
                        />

                        <!-- FELIRAT -->
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("subdomains_title") }}
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

                <template #paginatorstart>
                    <Button
                        type="button"
                        icon="pi pi-refresh"
                        text
                        @click="fetchItems"
                    />
                </template>

                <template #empty>
                    {{ $t('data_not_found', {data: 'subdomain'} ) }}
                </template>

                <template #loading>
                    {{ $t('loader', {data: 'Subdomain'}) }}
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
                    style="min-width: 12rem"
                    sortable
                >
                    <template #body="{ data }">
                        {{ data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'name'})"
                        />
                    </template>
                </Column>

                <!-- Subdomain -->
                <Column
                    field="subdomain"
                    :header="$t('subdomain')"
                    style="min-width: 12rem"
                    sortable
                >
                    <template #body="{ data }">
                        {{ data.subdomain }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'subdomain'})"
                        />
                    </template>
                </Column>

                <!-- url -->
                <Column 
                    field="url"
                    :header="$t('url')"
                    style="min-width: 16rem"
                    sortable
                >
                    <template #body="{ data }">{{ data.url }}</template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'url'})"
                        />
                    </template>
                </Column>

                <!-- db_name -->
                <Column
                    field="db_name"
                    :header="$t('db_name')"
                    style="min-width: 16rem"
                    sortable
                >
                    <template #body="{ data }">{{ data.db_name }}</template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'db_name'})"
                        />
                    </template>
                </Column>

                <!-- db_user -->
                <Column
                    field="db_user"
                    :header="$t('db_user')"
                    style="min-width: 16rem"
                    sortable
                >
                    <template #body="{ data }">{{ data.db_user }}</template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'db_user'})"
                        />
                    </template>
                </Column>
                <!--<Column
                    field="db_user"
                    :header="$t('db_user')"
                    style="min-width: 16rem"
                    sortable
                />-->

                <!-- db_password -->
                <Column
                    field="db_password"
                    :header="$t('db_password')"
                    style="min-width: 16rem"
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
                            @click="editSubdomain(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteSubdomain(slotProps.data)"
                        />
                    </template>
                </Column>

            </DataTable>
        </div>

        <!-- Subdomain szerkesztése -->
        <Dialog
            v-model:visible="subdomainDialog"
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
                            v-model="subdomain.name"
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
                        >
                            {{ $t("active") }}
                        </label>
                        <Select
                            id="active"
                            name="active"
                            v-model="subdomain.active"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Countries"
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

        <!-- Subdomain törlése -->
        <!-- Egy megerősítő párbeszédpanel, amely megjelenik, ha a felhasználó törölni szeretne egy várost. -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést. -->
        <Dialog
            v-model:visible="deleteSubdomainDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <!-- A párbeszédpanel tartalma -->
            <div class="flex items-center gap-4">
                <!-- A figyelmeztető ikon -->
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <!-- A szöveg, amely megjelenik a párbeszédpanelen -->
                <span v-if="subdomain"
                    >{{ $t("confirm_delete_2") }} <b>{{ subdomain.name }}</b
                    >?</span
                >
            </div>
            <!-- A párbeszédpanel lábléc, amely tartalmazza a gombokat -->
            <template #footer>
                <!-- A "Nem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSubdomainDialog = false"
                    text
                />
                <!-- A "Igen" gomb, amely törli a várost -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSubdomain"
                />
            </template>
        </Dialog>

        <!-- Erősítse meg a kiválasztott városok törlését párbeszédpanelen -->
        <!-- Ez a párbeszédpanel akkor jelenik meg, ha a felhasználó több várost szeretne törölni -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést -->
        <Dialog
            v-model:visible="deleteSelectedSubdomainsDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="subdomain">{{ $t("confirm_delete") }}</span>
            </div>
            <template #footer>
                <!-- "Mégsem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSelectedSubdomainsDialog = false"
                    text
                />
                <!-- Megerősítés gomb -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSelectedSubdomains"
                />
            </template>
        </Dialog>
    </AppLayout>
</template>
