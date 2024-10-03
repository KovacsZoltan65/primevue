<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode, FilterOperator  } from "@primevue/core/api";
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
import SubdomainService from "@/service/SubdomainService";
import { usePrimeVue } from "primevue/config";

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

const toast = useToast();

const dt = ref();

const subdomains = ref();

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

const subdomainDialog = ref(false);

const deleteSelectedSubdomainsDialog = ref(false);

const deleteSubdomainDialog = ref(false);

const selectedSubdomains = ref([]);

const filters = ref();
/*
const filters = ref({
    global: {
        value: null,
        matchMode: FilterMatchMode.CONTAINS,
    },
});
*/
/**
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 *
 * @type {ref<boolean>}
 */
const submitted = ref(false);

const rules = {
    subdomain: {},
    url: {},
    name: {
        required: helpers.withMessage("validate_name", required),
    },
    db_host: {required},
    db_port: {required},
    db_name: {required},
    db_user: {required},
    db_password: {required},
};

/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 *
 * @type {Object}
 */
const v$ = useVuelidate(rules, subdomain);

// ======================================================

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    };
};

const clearFilter = () => {
    initFilters();
};

initFilters();

const fetchItems = () => {

    loading.value = true;

    /*
    SubdomainService.getSubdomainsXLarge()
        .then((response) => {
            subdomains.value = getSubdomains(response.data.data);
            loading.value = false;
        });
    
    
    SubdomainService.getSubdomainsXLarge()
        .then((response) => {
            subdomains.value = getSubdomains(response.data.data);
            loading.value = false;
        });
    */
    SubdomainService.getSubdomains()
        .then(response => {
            subdomains.value = response.data.data;

            loading.value = false;
        })
        .catch(error => {
            console.error("getSubdomains API Error:", error);
        });
};

//const getSubdomains = (data) => {
//    return data;
//};
//import { primeVueLocaleConfig } from "@/primevue/config.js";
onMounted(() => {
    //const primevue = usePrimeVue();
    //primevue.config.locale = primeVueLocaleConfig;

    fetchItems();
});

function confirmDeleteSelected() {
    deleteSelectedSubdomainsDialog.value = true;
}

function openNew() {
    subdomain.value = { ...initialSubdomain };
    submitted.value = false;
    subdomainDialog.value = true;
}

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

const hideDialog = () => {
    subdomainDialog.value = false;
    submitted.value = false;
    v$.value.$reset();
};

const editSubdomain = (data) => {
    subdomain.value = { ...data };
    subdomainDialog.value = true;
};

const confirmDeleteCountry = (data) => {
    subdomain.value = { ...data };

    deleteSubdomainDialog.value = true;
};

const saveSubdomain = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (subdomain.value.id) {
            updateSubdomain();
        } else {
            createSubdomain();
        }
    } else {
        alert("FAIL");
    }
};

const createSubdomain = () => {
    CountryService.createCountry(subdomain.value)
        .then((response) => {
            subdomains.values.push(response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Country Created",
                life: 3000,
            });
        })
        .catch((error) => {
            console.error("createSubdomain API Error:", error);
        });
};

const updateSubdomain = () => {
    SubdomainService.updateSubdomain(subdomain.value.id, subdomain.value)
        .then(() => {
            const index = findIndexById(subdomain.value.id);
            subdomains.value.splice(index, 1, subdomain.value);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Subdomain Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("updateCountry API Error:", error);
        });
};

const findIndexById = (id) => {
    return subdomain.value.findIndex((subdomain) => subdomain.id === id);
};

const deleteSubdomain = () => {
    SubdomainService.deleteSubdomain(subdomain.value.id)
        .then((response) => {
            //
        })
        .catch((error) => {
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
                dataKey="id"
                :paginator="true"
                :rows="10"
                :filters="filters"
                filterDisplay="menu"
                :globalFilterFields="['name']"
                :loading="loading"
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} subdomains"
            >
            <!--<DataTable 
                v-model:filters="filters" 
                :value="customers" 
                paginator showGridlines 
                :rows="10" dataKey="id" 
                filterDisplay="menu" 
                :loading="loading" 
                :globalFilterFields="['name', 'country.name', 'representative.name', 'balance', 'status']"
            >-->
                <template #header>
                    <div class="flex flex-wrap gap-2 items-center justify-between">
                        <Button 
                            type="button" 
                            icon="pi pi-filter-slash" 
                            label="Clear" 
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
                    <Button type="button" icon="pi pi-refresh" text @click="fetchItems" />
                </template>

                <!--<template #paginatorend>
                    <Button type="button" icon="pi pi-download" text @click="" />
                </template>-->

                <template #empty> No customers found. </template>

                <template #loading> Loading customers data. Please wait. </template>

                <!-- Checkbox -->
                <Column
                    selectionMode="multiple"
                    style="min-width: 3rem"
                    :exportable="false"
                />

                <!-- Nev -->
                <Column field="name" header="Name" style="min-width: 12rem" sortable>
                    <template #body="{ data }">
                        {{ data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" type="text" placeholder="Search by name" />
                    </template>
                </Column>
                <!--<Column
                    field="name"
                    :header="$t('name')"
                    sortable
                    style="min-width: 16rem"
                />-->

                <!-- Subdomain -->
                <Column 
                    field="subdomain" 
                    :header="$t('subdomain')" 
                    style="min-width: 16rem" 
                    sortable
                />

                <!-- url -->
                <Column 
                    field="url" 
                    :header="$t('url')" 
                    style="min-width: 16rem" 
                    sortable
                />

                <!-- db_name -->
                <Column 
                    field="db_name" 
                    :header="$t('db_name')" 
                    style="min-width: 16rem" 
                    sortable
                />

                <!-- db_user -->
                <Column 
                    field="db_user" 
                    :header="$t('db_user')" 
                    style="min-width: 16rem" 
                    sortable
                />

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

        <!-- Város szerkesztése -->
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
                            for="inventoryStatus"
                            class="block font-bold mb-3"
                            >{{ $t("active") }}</label
                        >
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

        <!-- Város törlése -->
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
