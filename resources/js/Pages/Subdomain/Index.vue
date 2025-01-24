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
import InputNumber from 'primevue/inputnumber';
import InputIcon from "primevue/inputicon";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import Tag from "primevue/tag";
//import { usePrimeVue } from "primevue/config";
import FileUpload from "primevue/fileupload";
import Message from "primevue/message";
import FloatLabel from "primevue/floatlabel";
import { watch } from "vue";
import { Checkbox } from "primevue";

const props = defineProps({
    countries: {type: Object, default: () => {},},
    regions: {type: Object, default: () => {},},
    states: {type: Object, default: () => {},},
});

const getBools = () => {
    return [
        { label: trans("no"), value: 0, },
        { label: trans("yes"), value: 1, },
    ];
};

const toast = useToast();
const loading = ref(true);
const dt = ref();
const filters = ref({});

const subdomains = ref();
const defaultSubdomain = {
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
};

// Tároló kulcsok
const local_storage_subdomains = 'subdomains';
const local_storage_column_key = 'ln_subdomains_grid_columns';

const subdomain = ref({ ...defaultSubdomain });

const initialSubdomain = () => {
    return {...defaultSubdomain};
};

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

    name: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },

    db_host: {
        required: helpers.withMessage(trans('validate_field_required', {field: 'db_host'}), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
    },

    db_port: {
        required: helpers.withMessage(trans('validate_field_required', {field: 'db_port'}), required),
        min: helpers.withMessage( ({ $params }) => trans('validate_min.numeric', { min: $params.min }), minLength(validationRules.mysql_port_min)),
        max: helpers.withMessage( ({ $params }) => trans('validate_max.numeric', { max: $params.max }), minLength(validationRules.mysql_port_max)),
    },

    db_name: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
    },

    db_user: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
    },

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

const state = reactive({
    columns: {
        'subdomain': { field: 'subdomain', is_visible: true, is_sortable: true, is_filterable: true },
        'url': { field: 'url', is_visible: true, is_sortable: true, is_filterable: true },
        'name': { field: 'name', is_visible: true, is_sortable: true, is_filterable: true },
        'db_host': { field: 'db_host', is_visible: true, is_sortable: true, is_filterable: true },
        'db_port': { field: 'db_port', is_visible: true, is_sortable: true, is_filterable: true },
        'db_name': { field: 'db_name', is_visible: true, is_sortable: true, is_filterable: true },
        'db_user': { field: 'db_user', is_visible: true, is_sortable: true, is_filterable: true },
        'db_password': { field: 'db_password', is_visible: true, is_sortable: true, is_filterable: true },
        'notification': { field: 'notification', is_visible: true, is_sortable: true, is_filterable: true },
        'state_id': { field: 'state_id', is_visible: true, is_sortable: true, is_filterable: true },
        'is_mirror': { field: 'is_mirror', is_visible: true, is_sortable: true, is_filterable: true },
        'sso': { field: 'sso', is_visible: true, is_sortable: true, is_filterable: true },
        'acs_id': { field: 'acs_id', is_visible: true, is_sortable: true, is_filterable: true },
        'active': { field: 'active', is_visible: true, is_sortable: true, is_filterable: true }
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
}, { deep: true });

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
 * ===========================================
 * DIALOGOK
 * ===========================================
 */
// táblázat beállításai
const settingsDialog = ref(false);
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
// ======================================================

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

    await SubdomainService.getSubdomains()
        .then((response) => {
            subdomains.value = response.data;
        })
        .catch((error) => {
            console.error("getSubdomains API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch Subdomain",
                additionalInfo: "Failed to retrieve the subdomain",
                category: "Error",
                priority: "high",
                data: null,
            });
        }).finally(() => {
            loading.value = false;
        });
};

/**
 * Lekéri a városok listáját az API-ból, amikor a komponens létrejön.
 *
 * Ez a funkció a subdomainek listáját lekéri az API-ból, amikor a komponens létrejön.
 * A városok listája a subdomains változóban lesz elmentve.
 *
 * @return {void}
 */
onMounted(() => {
    fetchItems();

    let columns = localStorage.getItem(local_storage_column_key);
    if( columns ) {
        columns = JSON.parse(columns);
        for(const column_name in columns) {
            state.columns[column_name] = columns[column_name];
        }
    }
});

/**
 * Bezárja a dialógusablakot.
 *
 * Ez a függvény a dialógusablakot bezárja, és a submitted változó értékét False-ra állítja.
 * A v$.value.$reset() függvénnyel visszaállítja a validációs objektumot az alapértelmezett állapotába.
 *
 * @return {void}
 */
const hideDialog = () => {
    subdomain.value = initialSubdomain();
    subdomainDialog.value = false;
    submitted.value = false;
    v$.value.$reset();
};

/**
 * Beállítja az alapértelmezett értékeket a szűrő mezők számára.
 *
 * A globális szűrő és a name, subdomain szűrők kezdőértékei.
 */
const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS,},
        name: {operator: FilterOperator.AND, constraints: [ { value: null, matchMode: FilterMatchMode.STARTS_WITH, },],},
        subdomain: {operator: FilterOperator.AND, constraints: [ { value: null, matchMode: FilterMatchMode.STARTS_WITH, }, ], },
        url: {operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH, },],},
        db_name: {operator: FilterOperator.AND,constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH, },],},
        db_user: {operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH, },],},
        active: {operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH, },],}
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
    subdomain.value = initialSubdomain();
    submitted.value = false;
    subdomainDialog.value = true;
}

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

    if( result ) {
        submitted.value = true;

        if( subdomain.value.id ) {
            updateSubdomain();
        } else {
            createSubdomain();
        }
    } else {
        const validationErrors = v$.value.$errors.map((error) => ({
            field: error.$property,
            message: trans(error.$message),
        }));

        const data = {
            componentName: "saveSubdomain",
            additionalInfo: "Client-side validation failed during subdomain update",
            category: "Validation Error",
            priority: "low",
            validationErrors: validationErrors,
        };

        ErrorService.logValidationError(new Error('Client-side validation error'), data);

        toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: "Please fix the highlighted errors before submitting.",
        });
    }
};

const createSubdomain = async () => {

    // Lokálisan hozzunk létre egy ideiglenes azonosítót az új céghez
    const newSubdomain = {...subdomain.value, id: createId() };

    subdomains.value.push(newSubdomain);
    // Optimista visszajelzés a felhasználónak
    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "Subdomain creation in progress",
        life: 3000,
    });

    // Szerver kérés
    await SubdomainService.createSubdomain(subdomain.value)
        .then((response) => {

            // Lokális adat frissítése a szerver válasza alapján
            const index = findIndexById(newSubdomain.id);
            if (index !== -1) {
                subdomains.value.splice(index, 1, response.data);
            }

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Subdomain Created",
                life: 3000,
            });

        })
        .catch((error) => {

            if( error.response && error.response.status === 422){
                const validationErrors = error.response.data.details;

                toast.add({
                    severity: "warn",
                    summary: "Validation Error",
                    detail: "Please check your inputs",
                    life: 4000,
                });

                // Validációs hibák logolása
                ErrorService.logClientError(error, {
                    componentName: "CreateSubdomainDialog",
                    additionalInfo: "Validation errors occurred during subdomain creation",
                    category: "Validation Error",
                    priority: "medium",
                    validationErrors: validationErrors,
                });
            }else{

                // Hibás esetben a lokális adat törlése
                const index = findIndexById(newSubdomain.id);
                if (index !== -1) {
                    subdomains.value.splice(index, 1);
                }

                // Toast hibaüzenet
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: trans('error_subdomain_create'),
                });

                // Hiba naplózása
                ErrorService.logClientError(error, {
                    componentName: "CreateSubdomainDialog",
                    additionalInfo: "Failed to create a subdomain in the backend",
                    category: "Error",
                    priority: "high",
                    data: subdomain.value,
                });

            }

        });
};

const updateSubdomain = async () => {

    const index = findIndexById(subdomain.value.id);
    if (index === -1) {
        console.error(`Subdomain with id ${subdomain.value.id} not found`);
        return;
    }

    const originalSubdomain = { ...subdomains.value[index] };
    subdomains.value.splice(index, 1, { ...subdomain.value });
    hideDialog();

    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "Subdomain update in progress",
        life: 2000,
    });

    await SubdomainService.updateSubdomain(subdomain.value.id, subdomain.value)
        .then((response) => {
            subdomains.value.splice(index, 1, response.data.data);

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Subdomain Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            subdomains.value.splice(index, 1, originalSubdomain);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to update subdomain",
            });

            ErrorService.logClientError(error, {
                componentName: "UpdateSubdomainDialog",
                additionalInfo: "Failed to update a subdomain in the backend",
                category: "Error",
                priority: "medium",
                data: subdomain.value,
            });
        });
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
 * Megkeresi a megadott azonosítójú aldomain indexét.
 *
 * @param {number} id Az aldomain azonosítója
 * @return {number} Az aldomain indexe
 */
const findIndexById = (id) => {
    return subdomains.value.findIndex((subdomain) => subdomain.id === id);
};

const deleteSelectedSubdomains = async () => {
    // Eredeti állapot mentése az összes kiválasztott céghez, hogy visszaállíthassuk hiba esetén
    const originalSubdomains = [...subdomains.value];

    // Optimista törlés: azonnal eltávolítjuk az összes kijelölt céget
    selectedSubdomains.value.forEach(selectedSubdomain => {
        const index = subdomains.value.findIndex(subdomain => subdomain.id === selectedSubdomain.id);
        if (index !== -1) {
            subdomains.value.splice(index, 1);
        }
    });

    // Törlési értesítés optimista frissítés után
    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Deleting selected subdomains...",
        life: 2000,
    });

    await SubdomainService.deleteSubdomains(selectedSubdomains.value.map(subdomain => subdomain.id))
        .then((response) => {
            // Sikeres törlés esetén értesítés
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Selected subdomains deleted",
                life: 3000,
            });
            // Törölt elemek eltávolítása a selectedSubdomains-ből
            selectedSubdomains.value = [];
        })
        .error((error) => {
            // Hiba esetén visszaállítjuk az eredeti állapotot
            subdomains.value = originalSubdomains;

            const errorMessage = error.response?.data?.error || "Failed to delete selected subdomains";

            // Hibaüzenet megjelenítése a felhasználói felületen
            toast.add({
                severity: "error",
                summary: "Error",
                detail: errorMessage,
                life: 3000,
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteSubdomainsDialog",
                additionalInfo: "Failed to delete a subdomains in the backend",
                category: "Error",
                priority: "low",
                data: subdomains.value
            });
        });
};

const migration = async () => {
    await SubdomainService.migration(selectedSubdomains.value.map(subdomain => subdomain.id))
        .then((response) => {})
        .catch((error) => {})
        .finally(() => {});
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

const getModalTitle = () => {
    return subdomain.value.id
        ? trans("subdomains_edit_title")
        : trans("subdomains_new_title");
}

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

const openSettingsDialog = () => {
    settingsDialog.value = true;
};

</script>

<template>
    <AppLayout>
        <Head :title="$t('subdomains')" />

        {{ props.can }}

        <div class="card">
            <Toolbar class="md-6">
                <template #start>

                    <!-- Settings -->
                    <Button
                        icon="pi pi-cog"
                        severity="secondary"
                        class="mr-2"
                        @click="openSettingsDialog"
                    />

                    <!-- New Button -->
                    <Button
                        :label="$t('new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete')"
                        icon="pi pi-trash"
                        severity="secondary"
                        class="mr-2"
                        @click="confirmDeleteSelected"
                        :disabled=" 
                            !selectedSubdomains || !selectedSubdomains.length
                        "
                    />

                    <!-- MIGRATION -->
                    <Button
                        :label="$t('migration')"
                        icon="pi pi-database"
                        severity="secondary"
                        @click="migration"
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

                <!-- NAME -->
                <Column
                    :field="state.columns.name.field"
                    :header="$t(state.columns.name.field)"
                    :sortable="state.columns.name.is_sortable"
                    :hidden="!state.columns.name.is_visible"
                    style="min-width: 12rem"
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

                <!-- SUBDOMAIN -->
                <Column
                    :field="state.columns.subdomain.field"
                    :header="$t(state.columns.subdomain.field)"
                    :sortable="state.columns.subdomain.is_sortable"
                    :hidden="!state.columns.subdomain.is_visible"
                    style="min-width: 12rem"
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

                <!-- URL -->
                <Column
                    :field="state.columns.url.field"
                    :header="$t(state.columns.url.field)"
                    :sortable="state.columns.url.is_sortable"
                    :hidden="!state.columns.url.is_visible"
                    style="min-width: 16rem"
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

                <!-- DB_NAME -->
                <Column
                    :field="state.columns.db_name.field"
                    :header="$t(state.columns.db_name.field)"
                    :sortable="state.columns.db_name.is_sortable"
                    :hidden="!state.columns.db_name.is_visible"
                    style="min-width: 16rem"
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

                <!-- DB_USER -->
                <Column
                    :field="state.columns.db_user.field"
                    :header="$t(state.columns.db_user.field)"
                    :sortable="state.columns.db_user.is_sortable"
                    :hidden="!state.columns.db_user.is_visible"
                    style="min-width: 16rem"
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

                <!-- DB_PASSWORD -->
                <Column
                    :field="state.columns.db_password.field"
                    :header="$t(state.columns.db_password.field)"
                    :sortable="state.columns.db_password.is_sortable"
                    :hidden="!state.columns.db_password.is_visible"
                    style="min-width: 16rem"
                />

                <!-- ACTIVE -->
                <Column
                    :field="state.columns.active.field"
                    :header="$t(state.columns.active.field)"
                    :sortable="state.columns.active.is_sortable"
                    :hidden="!state.columns.active.is_visible"
                    style="min-width: 6rem"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="$t(getActiveValue(slotProps.data))"
                            :severity="getActiveLabel(slotProps.data)"
                        />
                    </template>
                </Column>

                <!-- ACTIONS -->
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
            :header="getModalTitle()"
            :modal="true"
        >
            <!-- NAME & SUBDOMAIN-->
            <div class="flex flex-col gap-6" style="margin-top: 15px;">
                <!-- NAME -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="name" class="block font-bold mb-3">
                            {{ $t("name") }}
                        </label>
                        <InputText
                            id="name"
                            v-model="subdomain.name"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_name') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.name.$error">
                        {{ $t(v$.name.$errors[0].$message) }}
                    </small>
                </div>

                <!-- SUBDOMAIN -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="subdomain" class="block font-bold mb-3">
                            {{ $t("subdomain") }}
                        </label>
                        <InputText
                            id="subdomain"
                            v-model="subdomain.subdomain"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_subdomain') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.subdomain.$error">
                        {{ $t(v$.subdomain.$errors[0].$message) }}
                    </small>
                </div>

                <!-- URL -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="url" class="block font-bold mb-3">
                            {{ $t("url") }}
                        </label>
                        <InputText
                            id="url"
                            v-model="subdomain.url"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_url') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.url.$error">
                        {{ $t(v$.url.$errors[0].$message) }}
                    </small>
                </div>
            </div>

            <!-- DB_HOST & DB_PORT -->
            <div class="flex flex-wrap gap-8" style="margin-top: 15px;">
                <!-- DB_HOST -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="db_host" class="block font-bold mb-3">
                            {{ $t("db_host") }}
                        </label>
                        <InputText
                            id="db_host"
                            v-model="subdomain.db_host"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_db_host') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.db_host.$error">
                        {{ $t(v$.db_host.$errors[0].$message) }}
                    </small>
                </div>
                <!-- DB_PORT -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="db_port" class="block font-bold mb-3">
                            {{ $t("db_port") }}
                        </label>
                        <InputText
                            id="db_port"
                            v-model="subdomain.db_port"
                            fluid
                            type="number"
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_db_port') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.db_port.$error">
                        {{ $t(v$.db_port.$errors[0].$message) }}
                    </small>
                </div>
            </div>

            <div class="flex flex-wrap gap-8" style="margin-top: 20px;">
                <!-- STATE_ID -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="state_id" class="block font-bold mb-3">
                            {{ $t("state_id") }}
                        </label>
                        <Select
                            id="state_id"
                            v-model="subdomain.state_id"
                            :options="props.states"
                            optionLabel="name"
                            optionValue="id"
                            fluid />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_state_id') }}
                    </Message>
                    <!--<small class="text-red-500" v-if="v$.state_id.$error">
                        {{ $t(v$.state_id.$errors[0].$message) }}
                    </small>-->
                </div>
                <!-- DB_NAME -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="db_name" class="block font-bold mb-3">
                            {{ $t("db_name") }}
                        </label>
                        <InputText
                            id="db_name"
                            v-model="subdomain.db_name"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_db_name') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.db_name.$error">
                        {{ $t(v$.db_name.$errors[0].$message) }}
                    </small>
                </div>
            </div>

            <!-- DB_USER & DB_PASSWORD -->
            <div class="flex flex-wrap gap-8" style="margin-top: 15px;">
                <!-- DB_USER -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="db_user" class="block font-bold mb-3">
                            {{ $t("db_user") }}
                        </label>
                        <InputText
                            id="db_password"
                            v-model="subdomain.db_user"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_db_user') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.db_user.$error">
                        {{ $t(v$.db_user.$errors[0].$message) }}
                    </small>
                </div>
                <!-- DB_PASSWORD -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="db_password" class="block font-bold mb-3">
                            {{ $t("db_password") }}
                        </label>
                        <InputText
                            id="db_password"
                            v-model="subdomain.db_password"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_db_password') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.db_password.$error">
                        {{ $t(v$.db_password.$errors[0].$message) }}
                    </small>
                </div>
            </div>

            <!-- NOTIFICATION & IS_MIRROR -->
            <div class="flex flex-wrap gap-8" style="margin-top: 20px;">
                <!-- NOTIFICATION -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="notification" class="block font-bold mb-3">
                            {{ $t("notification") }}
                        </label>
                        <Select
                            id="notification"
                            v-model="subdomain.notification"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Notification" fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_notification') }}
                    </Message>
                </div>

                <!-- IS_MIRROR -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="is_mirror" class="block font-bold mb-3">
                            {{ $t("is_mirror") }}
                        </label>
                        <Select
                            id="is_mirror"
                            v-model="subdomain.is_mirror"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Is Mirror" fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_is_mirror') }}
                    </Message>
                </div>
            </div>

            <!-- SSO & ACS_ID -->
            <div class="flex flex-wrap gap-8" style="margin-top: 20px;">
                <!-- SSO -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="sso" class="block font-bold mb-3">
                            {{ $t("sso") }}
                        </label>
                        <Select
                            id="sso"
                            v-model="subdomain.sso"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="SSO" fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_sso') }}
                    </Message>
                </div>

                <!-- ACS_ID -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="acs_id" class="block font-bold mb-3">
                            {{ $t("acs_id") }}
                        </label>
                        <Select
                            id="sso"
                            v-model="subdomain.acs_id"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="SSO" fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_acs_id') }}
                    </Message>
                </div>
            </div>

            <!-- ACTIVE -->
            <div class="flex flex-wrap gap-8" style="margin-top: 20px;">
                <!-- ACTIVE -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="active" class="block font-bold mb-3">
                            {{ $t("active") }}
                        </label>
                        <Select
                            id="active"
                            v-model="subdomain.active"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Active" fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_subdomain_active') }}
                    </Message>
                </div>
            </div>

            <template #footer>
                <!--
                <div class="flex items-center gap-2" style="margin-top: 10px;">
                    <Checkbox 
                        v-model="pizza" 
                        inputId="ingredient1" 
                        name="pizza" 
                        value="Cheese"
                    />
                    <label 
                        for="ingredient1"
                    >{{$t('create_db')}}</label>
                </div>
                -->

                <Button class="mt-4"
                    :label="$t('cancel')"
                    icon="pi pi-times"
                    text
                    @click="hideDialog"
                />
                <Button style="margin-top: 10px;"
                    :label="$t('save')"
                    icon="pi pi-check"
                    @click="saveSubdomain"
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

        <!-- SETTINGS DIALOG -->
        <Dialog
            v-model:visible="settingsDialog"
            :style="{ width: '450px' }"
            :header="$t('app_settings_title')"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-wrap gap-4">
                        <div
                            v-for="(config, column) in state.columns" :key="column"
                            class="flex items-center gap-2"
                        >
                            <Checkbox
                                v-model="config.is_visible" 
                                :inputId="column"
                                :value="true" binary
                            />
                            <label :for="column">{{ $t(column) }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </Dialog>

    </AppLayout>
</template>
