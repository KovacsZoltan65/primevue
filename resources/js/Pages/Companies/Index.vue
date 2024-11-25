<script setup>
import { computed, onMounted, ref, reactive, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from '@/Validation/ValidationRules.json';

// TOAST
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';

import CompanyService from "@/service/CompanyService";

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
import FileUpload from "primevue/fileupload";
import { createId } from "@/helpers/functions";
import FloatLabel from "primevue/floatlabel";
import ErrorService from "@/service/ErrorService";
import Message from "primevue/message";

/**
 * Szerver felöl jövő adatok
 */
const props = defineProps({
    countries: { type: Object, default: () => {}, },
    cities: { type: Object, default: () => {}, },
});

/**
 * Az állapotmező logikai értékeit adja vissza.
 *
 * @returns {Array<Object>} objektumok tömbje címke és érték tulajdonságokkal.
 */
const getBools = () => {
    return [
        {label: trans("inactive"),value: 0,},
        {label: trans("active"),value: 1,},
    ];
};

const toast = useToast();

const dt = ref();

const companies = ref();
const company = ref({
    id: null,
    name: "",
    directory: "",
    country_id: null,
    city_id: null,
    registration_number: null,
    tax_id: null,
    address: null,
    active: 1,
});

const initialCompany = () => {
    return {...company};
};

watch(
    () => company.value.name,
    /**
     * Visszahívás funkció a figyelés effektushoz.
     *
     * Ez a funkció frissíti a cég directory tulajdonságát a név változásai alapján.
     * @param {string} newValue - A cég könyvtárának új értéke.
     */
    (newValue) => {
        const trimmedValue = newValue?.trim() || ""; // Győződjön meg arról, hogy az érték létezik,
                                                     // és le van vágva.

        if (trimmedValue !== "") {
            company.value.directory = trimmedValue
                .toLowerCase() // Átalakítás kisbetűsre.
                .replace(/\s+/g, "_") // Cserélje ki a szóközöket aláhúzásjelekkel.
                .replace(/[^a-z0-9._-]/g, "") // Távolítsa el a nem engedélyezett karaktereket.
                .replace(/_+/g, "_") // Több aláhúzás összevonása.
                .replace(/^\_+|\_+$/g, ""); // Távolítsa el a bevezető vagy a záró aláhúzást.
        } else {
            company.value.directory = "";
        }
    }
);

/**
 * Reaktív hivatkozás a kijelölt cégek tárolására.
 *
 * @type {ref<Array>}
 */
const selectedCompanies = ref([]);

/**
 * ===========================================
 * DIALOGOK
 * ===========================================
 */
const companyDialog = ref(false);
const deleteSelectedCompaniesDialog = ref(false);
const deleteCompanyDialog = ref(false);

const loading = ref(true);

/**
 * Reaktív hivatkozás a globális keresés szűrőinek tárolására az adattáblában.
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
 * A validációs szabályok tárolása.
 *
 * A validációs szabályokat a vuelidate csomag segítségével definiáljuk.
 * A szabályok a name, country_id és city_id tulajdonságokra vonatkoznak.
 *
 * @type {Object}
 */
const rules = {
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
    country_id: { required: helpers.withMessage(trans("validate_country_id"), required), },
    city_id: { required: helpers.withMessage(trans("validate_city_id"), required), },
    directory: { required: helpers.withMessage(trans("validate_directory"), required), },
    tax_id: {
        required: helpers.withMessage(trans("validate_tax_id"), required),
    },
    registration_number: {
        required: helpers.withMessage(trans("validate_registration_number"), required),
    },
    address: {
        required: helpers.withMessage(trans("validate_address"), required),
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
    loading.value = true;

    CompanyService.getCompanies()
        .then((response) => {
            // A városok listája a companies változóban lesz elmentve
            companies.value = response.data.data;
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("getCompanies API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch Companies",
                additionalInfo: "Failed to retrieve the company",
                category: "Error",
                priority: "high",
                data: null,
            });

        }).finally(() => {
            loading.value = false;
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
    deleteSelectedCompaniesDialog.value = true;
}

/**
 * Megnyitja az új város dialógusablakot.
 *
 * Ez a függvény a company változó értékét alaphelyzetbe állítja,
 * a submitted változó értékét False-ra állítja, és a companyDialog változó
 * értékét igazra állítja, amely megnyitja az új város dialógusablakot.
 *
 * @return {void}
 */
function openNew() {
    company.value = { ...initialCompany };
    submitted.value = false;
    companyDialog.value = true;
}

/**
 * Bezárja a dialógusablakot.
 *
 * Ez a függvény a dialógusablakot bezárja, és a submitted változó értékét False-ra állítja.
 * A v$.value.$reset() függvénnyel visszaállítja a validációs objektumot az alapértelmezett állapotába.
 */
const hideDialog = () => {
    companyDialog.value = false;
    deleteCompanyDialog.value = false;
    deleteSelectedCompaniesDialog.value = false;
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
    company.value = { ...data };

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
    company.value = { ...data };

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
        const data = {
            componentName: "saveCompany",
            additionalInfo: "Client-side validation failed during company update",
            category: "Validation Error",
            priority: "low",
            validationErrors: v$.value.$errors.map((error) => ({
                field: error.$property,
                message: trans(error.$message),
            })),
        };
        ErrorService.logValidationError(new Error('Client-side validation error'), data);

        // Hibaüzenet megjelenítése a felhasználónak
        toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: "Please fix the highlighted errors before submitting.",
        });
    }
};

const createCompany = () => {
    
    // Lokálisan hozzunk létre egy ideiglenes azonosítót az új céghez
    const newCompany = {...company.value, id:createId() };
    companies.value.push(newCompany);

    // Optimista visszajelzés a felhasználónak
    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "Company creation in progress",
        life: 3000,
    });

    // Szerver kérés
    CompanyService.createCompany(company.value)
        .then((response) => {
            // Lokális adat frissítése a szerver válasza alapján
            const index = findIndexById(newCompany.id);
            if (index !== -1) {
                companies.value.splice(index, 1, response.data);
            }
            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Company Created",
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
                    componentName: "CreateCompanyDialog",
                    additionalInfo: "Validation errors occurred during company creation",
                    category: "Validation Error",
                    priority: "medium",
                    validationErrors: validationErrors,
                });
            }else{
                
                // Hibás esetben a lokális adat törlése
                const index = findIndexById(newCompany.id);
                if (index !== -1) {
                    companies.value.splice(index, 1);
                }

                const message = trans('error_company_create');
                //console.error("createCompany API Error:", error);

                // Toast hibaüzenet
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: message,
                });

                // Hiba naplózása
                ErrorService.logClientError(error, {
                    componentName: "CreateCompanyDialog",
                    additionalInfo: "Failed to create a company in the backend",
                    category: "Error",
                    priority: "high",
                    data: company.value,
                });

            }
            
        });
};

const updateCompany = () => {
    /*
    if( v$.value.$invalid ) {
        ErrorService.logClientError(new Error('Client-side validation error'), {
            componentName: "updateCompany",
            additionalInfo: "Client-side validation failed during company update",
            category: "Validation Error",
            priority: "low",
            validationErrors: v$.value.$errors.map((error) => ({
                field: error.$property,
                message: error.$message,
            })),
        });

        // Hibaüzenet megjelenítése a felhasználónak
        toast.add({
            severity: "warn",
            summary: "Validation Error",
            detail: "Please fix the highlighted errors before submitting.",
            life: 4000,
        });

        return; // Megállítjuk a műveletet, amíg a hibák nem kerülnek kijavításra
    }
    */
    const index = findIndexById(company.value.id);
    if (index === -1) {
        console.error(`Company with id ${country.value.id} not found`);
        return;
    }

    // Eredeti adat mentése az optimista frissítéshez
    const originalCompany = { ...companies.value[index] };

    // Lokális frissítés az optimista visszacsatoláshoz
    companies.value.splice(index, 1, { ...company.value });
    hideDialog();

    // "Frissítés folyamatban" visszajelzés
    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "Company update in progress",
        life: 2000,
    });

    // Hívás a szerver felé
    CompanyService.updateCompany(company.value.id, company.value)
        .then((response) => {
            // Sikeres válasz kezelése
            companies.value.splice(index, 1, response.data); // Frissített adat a válaszból

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Company Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            // Sikertelen frissítés esetén az eredeti adat visszaállítása
            companies.value.splice(index, 1, originalCompany);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to update company",
            });

            // Hiba naplózása a szerver felé
            ErrorService.logClientError(error, {
                componentName: "UpdateCompanyDialog",
                additionalInfo: "Failed to update a company in the backend",
                category: "Error",
                priority: "medium",
                data: company.value,
            });
        });
};

const deleteSelectedCompanies = () => {
    // Eredeti állapot mentése az összes kiválasztott céghez, hogy visszaállíthassuk hiba esetén
    const originalCompanies = [...companies.value];

    // Optimista törlés: azonnal eltávolítjuk az összes kijelölt céget
    selectedCompanies.value.forEach(selectedCompany => {
        const index = companies.value.findIndex(company => company.id === selectedCompany.id);
        if (index !== -1) {
            companies.value.splice(index, 1);
        }
    });

    // Törlési értesítés optimista frissítés után
    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Deleting selected companies...",
        life: 2000,
    });

    CompanyService.deleteCompanies(selectedCompanies.value.map(company => company.id))
        .then((response) => {
            // Sikeres törlés esetén értesítés
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Selected companies deleted",
                life: 3000,
            });
            // Törölt elemek eltávolítása a selectedCompanies-ből
            selectedCompanies.value = [];
        })
        .error((error) => {
            // Hiba esetén visszaállítjuk az eredeti állapotot
            companies.value = originalCompanies;

            const errorMessage = error.response?.data?.error || "Failed to delete selected companies";

            // Hibaüzenet megjelenítése a felhasználói felületen
            toast.add({
                severity: "error",
                summary: "Error",
                detail: errorMessage,
                life: 3000,
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteCompaniesDialog",
                additionalInfo: "Failed to delete a companies in the backend",
                category: "Error",
                priority: "low",
                data: companies.value
            });
        });
};

const deleteCompany = () => {
    const index = findIndexById(company.value.id);
    if (index === -1) {
        console.warn("No company found with the given id:", company.value.id);
        return;
    }

    // Eredeti cégadat mentése, hogy visszaállíthassuk, ha a törlés sikertelen
    const originalCompany = { ...companies.value[index] };

    // Optimista törlés: azonnal eltávolítjuk a céget a listából
    companies.value.splice(index, 1);

    // Törlési értesítés optimista frissítés után
    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Company deletion in progress",
        life: 2000,
    });

    CompanyService.deleteCompany(id)
        .then((response) => {
            // Sikeres törlés esetén értesítés
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Company Deleted",
                life: 3000,
            });
        })
        .error((error) => {
            // Hiba esetén visszaállítjuk a céget az eredeti helyére
            companies.value.splice(index, 0, originalCompany);

            // Hibaüzenet megjelenítése a felhasználói felületen
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete company",
            });

            // A hiba naplózása a szerver oldalon
            // Ez hasznos lesz a hibakereséshez és az esetleges észleléshez
            // lehetséges problémák az API-val
            ErrorService.logClientError(error, {
                componentName: "DeleteCompanyDialog",
                additionalInfo: "Failed to delete a company in the backend",
                category: "Error",
                priority: "medium",
                data: company.value,
            });
        });
};

const findIndexById = (id) => {
    return companies.value.findIndex((company) => company.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const getCountryName = (id) => {
    return props.countries.find((country) => country.id === id).name;
};

const getCityName = (id) => {
    return props.cities.find((city) => city.id === id).name;
};

const getModalTitle = () => {
    return company.value.id
        ? trans("companies_edit_title")
        : trans("companies_new_title");
};

const getModalDetails = () => {
    return company.value.id
        ? trans("companies_edit_details")
        : trans("companies_new_details");
};

const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS},
        name: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
    }
}

const clearFilter = () => {
    initFilters();
};

initFilters();

const throwError = () => {
    throw new Error('Test error');
};

</script>

<template>
    <AppLayout>
        <Head :title="$t('companies')" />

        <Toast />

        <div class="card">
            <Toolbar class="md-6">
                <template #start>
                    <!-- ERROR -->
                    <Button
                        label="ERROR"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="throwError"
                    />

                    <!-- New Button -->
                    <Button
                        :label="$t('add_new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete_selected')"
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
                v-model:selection="selectedCompanies"
                v-model:filters="filters"
                :filters="filters" filterDisplay="menu"
                :value="companies"
                dataKey="id"
                :paginator="true" :rows="10" sortMode="multiple"
                :loading="loading" stripedRows removableSort
                :globalFilterFields="['name']"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
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
                            {{ $t("companies_title") }}
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
                        class="p-button-text"
                        @click="fetchItems()"
                    />
                </template>

                <template #empty>
                    {{ $t("data_not_found", { data: "company" }) }}
                </template>

                <template #loading>
                    {{ $t("loader", { data: "Company" }) }}
                </template>

                <!-- SELECTION -->
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
                >
                    <template #body="slotProps">
                        {{ slotProps.data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'name'})"
                        />
                    </template>
                </Column>

                <!-- DIRECTORY -->
                <Column
                    field="directory"
                    :header="$t('directory')"
                    sortable
                    style="min-width: 16rem"
                >
                    <template #body="slotProps">
                        {{ slotProps.data.directory }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'directory'})"
                        />
                    </template>
                </Column>

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
                    field="city_id"
                    :header="$t('city')"
                    sortable
                    style="min-width: 16rem"
                >
                    <template #body="slotProps">
                        {{ getCityName(slotProps.data.city_id) }}
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

        <!-- COMPANY DETAILS DIALOG -->
        <Dialog
            v-model:visible="companyDialog"
            :style="{ width: '550px' }"
            :header="getModalTitle()"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
                <!-- NAME -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="name" class="block font-bold mb-3">
                            {{ $t("name") }}
                        </label>
                        <InputText
                            id="name"
                            v-model="company.name"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_company_name') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.name.$error">
                        {{ $t(v$.name.$errors[0].$message) }}
                    </small>
                </div>

                <!-- DIRECTORY -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="directory" class="block font-bold mb-3">
                            {{ $t("directory") }}
                        </label>
                        <InputText
                            id="directory"
                            v-model="company.directory"
                            fluid disabled
                        />
                    </FloatLabel>
                    <small class="text-red-500" v-if="v$.directory.$error">
                        {{ $t(v$.directory.$errors[0].$message) }}
                    </small>
                </div>

                <!-- TAX ID -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="tax_id" class="block font-bold mb-3">
                            {{ $t("tax_id") }}
                        </label>
                        <InputText
                            id="tax_id"
                            v-model="company.tax_id"
                            fluid
                        />
                    </FloatLabel>

                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_tax_id') }}
                    </Message>

                    <small class="text-red-500" v-if="v$.tax_id.$error">
                        {{ $t(v$.tax_id.$errors[0].$message) }}
                    </small>
                </div>

                <!-- REGISTRATION NUMBER -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="registration_number" class="block font-bold mb-3">
                            {{ $t("registration_number") }}
                        </label>
                        <InputText
                            id="registration_number"
                            v-model="company.registration_number"
                            fluid
                        />
                    </FloatLabel>

                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_registration_number') }}
                    </Message>

                    <small class="text-red-500" v-if="v$.registration_number.$error">
                        {{ $t(v$.registration_number.$errors[0].$message) }}
                    </small>
                </div>

                <div class="flex flex-wrap gap-4">
                    <!-- COUNTRY -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <FloatLabel>
                            <label for="country_id" class="block font-bold mb-3">
                                {{ $t("country") }}
                            </label>
                            <Select
                                id="country_id"
                                v-model="company.country_id"
                                :options="props.countries"
                                optionLabel="name"
                                optionValue="id"
                                :placeholder="$t('country')"
                                fluid
                            />
                        </FloatLabel>

                        <Message
                            size="small"
                            severity="secondary"
                            variant="simple"
                        >
                            {{ $t('select_country') }}
                        </Message>

                        <small class="text-red-500" v-if="v$.country_id.$error">
                            {{ $t(v$.country_id.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- CITY -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <FloatLabel>
                            <label for="city_id" class="block font-bold mb-3">
                                {{ $t("city") }}
                            </label>
                            <Select
                                id="city_id"
                                v-model="company.city_id"
                                :options="props.cities"
                                optionLabel="name"
                                optionValue="id"
                                :placeholder="$t('city')"
                                fluid
                            />
                        </FloatLabel>

                        <Message
                            size="small"
                            severity="secondary"
                            variant="simple"
                        >
                            {{ $t('select_city') }}
                        </Message>

                        <small class="text-red-500" v-if="v$.city_id.$error">
                            {{ $t(v$.city_id.$errors[0].$message) }}
                        </small>
                    </div>
                </div>

                <!-- ADDRESS -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="address" class="block font-bold mb-3">
                            {{ $t("address") }}
                        </label>
                        <InputText
                            id="address"
                            v-model="company.address"
                            fluid
                        />
                    </FloatLabel>

                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_address') }}
                    </Message>

                    <small class="text-red-500" v-if="v$.address.$error">
                        {{ $t(v$.address.$errors[0].$message) }}
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
                    @click="saveCompany"
                />
            </template>
        </Dialog>

        <!-- COMPANY DELETE DIALOG -->
        <Dialog
            v-model:visible="deleteCompanyDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <span class="text-surface-500 dark:text-surface-400 block mb-8">
                {{ $t("companies_delete_title") }}
            </span>
            <!-- A párbeszédpanel tartalma -->
            <div class="flex items-center gap-4">
                <!-- A figyelmeztető ikon -->
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <!-- A szöveg, amely megjelenik a párbeszédpanelen -->
                <span v-if="company">
                    {{ $t("confirm_delete_2") }} <b>{{ company.name }}</b
                    >?
                </span>
            </div>
            <!-- A párbeszédpanel lábléc, amely tartalmazza a gombokat -->
            <template #footer>
                <!-- A "Nem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteCompanyDialog = false"
                    text
                />
                <!-- A "Igen" gomb, amely törli a céget -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteCompany"
                />
            </template>
        </Dialog>

        <!-- Erősítse meg a kiválasztott városok törlését párbeszédpanelen -->
        <!-- Ez a párbeszédpanel akkor jelenik meg, ha a felhasználó több várost szeretne törölni -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést -->
        <Dialog
            v-model:visible="deleteSelectedCompaniesDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <span class="text-surface-500 dark:text-surface-400 block mb-8">
                {{ $t("companies_delete_title_2") }}
            </span>

            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="company">{{ $t("confirm_delete") }}</span>
            </div>

            <template #footer>
                <!-- "Mégsem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSelectedCompaniesDialog = false"
                    text
                />
                <!-- Megerősítés gomb -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSelectedCompanies"
                />
            </template>
        </Dialog>
    </AppLayout>
</template>
