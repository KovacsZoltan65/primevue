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
import Message from "primevue/message";
import FloatLabel from "primevue/floatlabel";

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
        { label: trans("inactive"), value: 0, },
        { label: trans("active"), value: 1, },
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
    last_export: null,
};

const local_storage_companies = 'subdomains';
const local_storage_column_key = 'ln_subdomains_grid_columns';

const subdomain = ref({ ...defaultSubdomain });

const initialSubdomain = () => {
    return {...defaultSubdomain};
};

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

/**
 * Beállítja az alapértelmezett értékeket a szűrő mezők számára.
 *
 * A globális szűrő és a name, subdomain szűrők kezdőértékei.
 */
const initFilters = () => {
    filters.value = {
        // Globális szűrő
        global: {
            value: null,
            matchMode: FilterMatchMode.CONTAINS,
        },
        // Név szűrő
        name: {
            operator: FilterOperator.AND,
            constraints: [
                { value: null, matchMode: FilterMatchMode.STARTS_WITH, },
            ],
        },
        // Alomain szűrő
        subdomain: {
            operator: FilterOperator.AND,
            constraints: [
                { value: null, matchMode: FilterMatchMode.STARTS_WITH, },
            ],
        },
        // URL szűrő
        url: {
            operator: FilterOperator.AND,
            constraints: [
                { value: null, matchMode: FilterMatchMode.STARTS_WITH, },
            ],
        },
        // db_name szűrő
        db_name: {
            operator: FilterOperator.AND,
            constraints: [
                { value: null, matchMode: FilterMatchMode.STARTS_WITH, },
            ],
        },
        db_user: {
            operator: FilterOperator.AND,
            constraints: [
                { value: null, matchMode: FilterMatchMode.STARTS_WITH, },
            ],
        },
        active: {
            operator: FilterOperator.AND,
            constraints: [
                { value: null, matchMode: FilterMatchMode.STARTS_WITH, },
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
    subdomain.value = initialSubdomain();

    submitted.value = false;
    subdomainDialog.value = true;
}

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

    const newSubdomain = {...subdomain.value, id: createId() };

    subdomains.value.push(newSubdomain);

    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "Subdomain creation in progress",
        life: 3000,
    });

    await SubdomainService.createSubdomain(newSubdomain)
        .then((response) => {
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

    /*
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
    */
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
            :header="getModalTitle()"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
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
    </AppLayout>
</template>
