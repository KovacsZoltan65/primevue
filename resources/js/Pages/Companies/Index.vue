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
import { createId } from "@/helpers/functions";

import {Toolbar,DataTable,Column,IconField,
    InputText,InputIcon,Button,Dialog,
    Select,Tag,FileUpload,FloatLabel,
    ErrorService,Message,Checkbox} from "primevue";

/**
 * Szerver felöl jövő adatok
 */
const props = defineProps({
    countries: { type: Object, default: () => {}, },
    cities: { type: Object, default: () => {}, },
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

const getBools = () => {
    return [
        {label: trans("inactive"),value: 0,},
        {label: trans("active"),value: 1,},
    ];
};

const toast = useToast();
const loading = ref(true);
const dt = ref();
const filters = ref({});

const companies = ref();
const defaultCompany = {
    id: null,
    name: "",
    directory: "",
    country_id: null,
    city_id: null,
    registration_number: null,
    tax_id: null,
    address: null,
    active: 1,
};

// Tároló kulcsok
const local_storage_companies = 'companies';
const local_storage_column_key = 'ln_companies_grid_columns';

const company = ref({ ...defaultCompany });

const initialCompany = () => {
    return { ...defaultCompany };
};

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

/**
 * Reaktív állapot a DataTable oszlopkonfigurációjának kezeléséhez.
 *
 * Minden oszlop rendelkezik olyan tulajdonságokkal, amelyek meghatározzák láthatóságát, rendezhetőségét és szűrhetőségét.
 * Az állapot dinamikusan szabályozza, hogy mely oszlopok jelenjenek meg vagy rejtsenek el,
 * és hogy rendezhetők-e vagy szűrhetők-e a felhasználói felületen.
 */
const state = reactive({
    columns: {
        'id': { field: 'id', is_visible: true, is_sortable: true, is_filterable: true },
        'name': { field: 'name', is_visible: true, is_sortable: true, is_filterable: true },
        'country_id': { field: 'country_id', is_visible: true, is_sortable: true, is_filterable: true },
        'city_id': { field: 'city_id', is_visible: true, is_sortable: true, is_filterable: true },
        'directory': { field: 'directory', is_visible: true, is_sortable: true, is_filterable: true },
        'registration_number': { field: 'registration_number', is_visible: true, is_sortable: true, is_filterable: true },
        'tax_id': { field: 'tax_id', is_visible: true, is_sortable: true, is_filterable: true },
        'address': { field: 'address', is_visible: true, is_sortable: true, is_filterable: true },
        'active': { field: 'active', is_visible: true, is_sortable: true, is_filterable: true }
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
}, { deep: true });

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
// táblázat beállításai
const settingsDialog = ref(false);
// új cég készítéséhez, vagy meglevő szerkesztéshez
const companyDialog = ref(false);
// kiválasztott cégekek törléséhez
const deleteSelectedCompaniesDialog = ref(false);
// cég törléséhez
const deleteCompanyDialog = ref(false);
// ======================================================

const fetchItems = async () => {
    loading.value = true;

    //let _companies = localStorage.getItem(local_storage_companies);

    //if ( _companies ) {
    //    companies.value = JSON.parse(_companies);

    //    loading.value = false;
    //} else {
        await CompanyService.getCompanies()
            .then((response) => {
                // A városok listája a companies változóban lesz elmentve
                companies.value = response.data;

                //localStorage.setItem(local_storage_companies, JSON.stringify(response.data));
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
    //}
};

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

const hideDialog = () => {
    company.value = initialCompany(); // Visszaáll az alapértelemezett állapotra
    settingsDialog.value = false;
    companyDialog.value = false;
    deleteCompanyDialog.value = false;
    deleteSelectedCompaniesDialog.value = false;
    submitted.value = false;

    v$.value.$reset();
};

function openNew() {
    company.value = initialCompany();
    submitted.value = false;
    companyDialog.value = true;
};

const editCompany = (data) => {
    company.value = { ...data };
    companyDialog.value = true;
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

        //localStorage.removeItem(local_storage_companies);
    } else {
        // Validációs hibák összegyűjtése
        const validationErrors = v$.value.$errors.map((error) => ({
                field: error.$property,
                message: trans(error.$message),
            }));
        // Adatok előkészítése logoláshoz
        const data = {
            componentName: "saveCompany",
            additionalInfo: "Client-side validation failed during company update",
            category: "Validation Error",
            priority: "low",
            validationErrors: validationErrors,
        };
        // Validációs hibák logolása
        ErrorService.logValidationError(new Error('Client-side validation error'), data);

        // Hibaüzenet megjelenítése a felhasználónak
        toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: "Please fix the highlighted errors before submitting.",
        });
    }
};

const createCompany = async () => {

    // Lokálisan hozzunk létre egy ideiglenes azonosítót az új céghez
    const newCompany = {...company.value, id: createId() };

    companies.value.push(newCompany);
    // Optimista visszajelzés a felhasználónak
    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "Company creation in progress",
        life: 3000,
    });

    // Szerver kérés
    await CompanyService.createCompany(company.value)
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

                // Toast hibaüzenet
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: trans('error_company_create'),
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

const updateCompany = async () => {
    const index = findIndexById(company.value.id);
    if (index === -1) {
        console.error(`Company with id ${country.value.id} not found`);
        return;
    }

    // Eredeti adat mentése az optimista frissítéshez
    const originalCompany = { ...companies.value[index] };

    // Lokális frissítés az optimista visszacsatoláshoz
    companies.value.splice(index, 1, { ...company.value });

    // "Frissítés folyamatban" visszajelzés
    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "Company update in progress",
        life: 2000,
    });

    // Hívás a szerver felé
    await CompanyService.updateCompany(company.value.id, company.value)
        .then((response) => {
            // Sikeres válasz kezelése
            // Frissített adat a válaszból
            companies.value.splice(index, 1, response.data);

            hideDialog();

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

const deleteSelectedCompanies = async () => {
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

    await CompanyService.deleteCompanies(selectedCompanies.value.map(company => company.id))
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
        .catch((error) => {
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

const deleteCompany = async () => {
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

    await CompanyService.deleteCompany(id)
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

const confirmDeleteCompany = (data) => {
    company.value = { ...data };
    deleteCompanyDialog.value = true;
};

function confirmDeleteSelected() {
    deleteSelectedCompaniesDialog.value = true;
};

const openSettingsDialog = () => {
    settingsDialog.value = true;
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

</script>

<template>
    <AppLayout>
        <Head :title="$t('companies')" />

        <Toast />

        {{ $page.props }}

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

                    <!-- ERROR -->
                <!--
                    <Button
                        label="ERROR"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="throwError"
                    />
                -->
                    <!-- New Button -->
                    <Button
                        :label="$t('add_new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                        :disabled="!props.can.companies_create"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete_selected')"
                        icon="pi pi-trash"
                        severity="secondary"
                        class="mr-2"
                        @click="confirmDeleteSelected"
                        :disabled="!props.can.companies_delete ||
                            !selectedCompanies ||
                            !selectedCompanies.length"
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
                filterDisplay="menu"
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
                    :disabled="!props.can.companies_delete"
                />

                <!-- ID -->
                <Column
                    :field="state.columns.id.field"
                    :header="$t(state.columns.id.field)"
                    :sortable="state.columns.id.is_sortable"
                    :hidden="!state.columns.id.is_visible"
                    style="min-width: 16rem"
                />

                <!-- NAME -->
                <Column
                    :field="state.columns.name.field"
                    :header="$t(state.columns.name.field)"
                    :sortable="state.columns.name.is_sortable"
                    :hidden="!state.columns.name.is_visible"
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
                    :field="state.columns.directory.field"
                    :header="$t('directory')"
                    :sortable="state.columns.directory.is_sortable"
                    :hidden="!state.columns.directory.is_visible"
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
                    :field="state.columns.country_id.filed"
                    :header="$t('country')"
                    :sortable="state.columns.country_id.is_sortable"
                    :hidden="!state.columns.country_id.is_visible"
                    style="min-width: 16rem"
                >
                    <template #body="slotProps">
                        {{ getCountryName(slotProps.data.country_id) }}
                    </template>
                </Column>

                <!-- CITY -->
                <Column
                    :field="state.columns.city_id.field"
                    :header="$t('city')"
                    :sortable="state.columns.city_id.is_sortable"
                    :hidden="!state.columns.city_id.is_visible"
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
                            :disabled="!props.can.companies_edit"
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
                            :disabled="!props.can.companies_delete"
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
                    <FloatLabel variant="on">
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
                    <FloatLabel variant="on">
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
                    <FloatLabel variant="on">
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
                    <FloatLabel variant="on">
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

                <!-- COUNTRY & CITY -->
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
                    <FloatLabel variant="on">
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
                <Button
                    :label="$t('save_and_new_db')"
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

        <!-- SETTINGS DIALOG -->
        <Dialog
            v-model:visible="settingsDialog"
            :style="{ width: '550px' }"
            :header="$t('app_settings_title')"
            :modal="true"
        >
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-wrap gap-4">
                        <div
                            v-for="(config, column) in state.columns"
                            :key="column"
                            class="flex items-center gap-2">

                            <Checkbox
                                v-model="config.is_visible"
                                :inputId="column"
                                :value="true" binary
                            />
                            <label :for="column">{{ column }}</label>

                        </div>
                    </div>
                </div>
            </div>
        </Dialog>

    </AppLayout>
</template>
