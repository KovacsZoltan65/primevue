<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";
import { format } from "date-fns";
import { hu } from "date-fns/locale";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, minValue, maxValue, maxLength, minLength, required, email } from "@vuelidate/validators";
import validationRules from "@/Validation/validationRules.json";

import PersonService from "@/service/PersonService";

import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import IconField from "primevue/iconfield";
import InputText from "primevue/inputtext";
import InputIcon from "primevue/inputicon";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import MultiSelect from "primevue/multiselect";
import Tag from "primevue/tag";

const props = defineProps({});

const getBools = () => {
    return [
        {
            label: trans('inactive'),
            value: 0,
        },
        {
            label: trans('active'),
            value: 1,
        }
    ];
};

const toast = useToast();
const loading = ref(true);

const dt = ref();

const persons = ref();

const personDialog = ref(false);

const deleteSelectedPersonsDialog = ref(false);

const deletePersonDialog = ref(false);

const person = ref({
    id: null,
    name: "",
    email: "",
    password: "",
    language: "hu",
    birthdate: "",
    active: 1,
});

const languages = ref([
    { name: "hu", code: "hu" },
    { name: "en", code: "en" },
]);

const selectedPersons = ref();

const filters = ref({});

const submitted = ref(false);

/**
 * A születési dátum érvényesítésének minimális dátuma
 * @type {String}
 */
const minDate = format(new Date(validationRules['minDate']), 'yyyy-MM-dd');

/**
 * A születési dátum érvényesítésének maximális dátuma
 * A maximális dátum 20 évvel korábbi, mint a jelenlegi dátum
 */
const maxDate = computed(() => {
    const date = new Date();
    date.setFullYear(date.getFullYear() - 20);
    return date.toISOString().split('T')[0];
});

const rules = {
    name: {
        required: helpers.withMessage(trans('validation.required'), required),
    },
    email: {
        required: helpers.withMessage(trans('validation.required'), required),
        email: helpers.withMessage(trans('validation.email'), email),
    },
    password: {
        required: helpers.withMessage(trans('validate.'), required),
    },
    birthdate: {
        required: helpers.withMessage('birth date request', required),
        minValue: helpers.withMessage('birth date minValue', minValue(minDate) ),
        maxValue: helpers.withMessage('birth date maxValue', maxValue(maxDate) )
    }
};

const v$ = useVuelidate(rules, person);

const fetchItems = async () => {
    loading.value = true;

    try {
        const response = await PersonService.getPersons();
        persons.value = response.data.data;
    } catch(error) {
        console.error("getPersons API Error:", error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchItems();
})

const openNew = () => {
    person.value = { ...initialPerson };
};

const initialPerson = () => {
    return person.value;
}

const confirmDeleteSelected = () => {
    deleteSelectedPersonsDialog.value = true;
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        name: {
            operator: FilterOperator.AND,
            constraints: [ { value: null, matchMode: FilterMatchMode.STARTS_WITH }, ],
        },
        email: {
            operator: FilterOperator.AND,
            constraints: [ { value: null, matchMode: FilterMatchMode.STARTS_WITH }, ],
        },
        language: { value: null, matchMode: FilterMatchMode.IN }
    }
};

const clearFilter = () => {
    initFilters();
};

initFilters();

</script>

<template>
    <AppLayout>
        <Head :title="$t('persons')" />

        {{ $page.props.available_locales }}


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
                        :disabled="!selectedPersons || !selectedPersons.length"
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
                v-model:selection="selectedPersons"
                v-model:filters="filters"
                :value="persons"
                dataKey="id" :paginator="true" :rows="10" sortMode="multiple"
                :filters="filters" filterDisplay="menu"
                :globalFilterFields="['name', 'email', 'language.code']"
                :loading="loading" stripedRows removableSort
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :currentPageReportTemplate="$t('current_page_report_template', {table: 'aldomain'})"
            >
                <!-- FEJLÉC -->
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
                            {{ $t("persons_title") }}
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

                <!-- LAPOZÓ -->
                <template #paginatorstart>
                    <Button
                        type="button"
                        icon="pi pi-refresh"
                        text
                        @click="fetchItems"
                    />
                </template>
                <!-- NINCS ADAT -->
                <template #empty>{{ $t('data_not_found', {data: 'persons'} ) }}</template>
                <!-- BETÖLTŐ -->
                <template #loading>{{ $t('loader', {data: 'Persons'}) }}</template>

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
                    <template #body="{ data }">{{ data.name }}</template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'name'})"
                        />
                    </template>
                </Column>

                <!-- email -->
                <Column
                    field="email"
                    :header="$t('email')"
                    style="min-width: 16rem"
                    sortable
                >
                    <template #body="{ data }">{{ data.email }}</template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'email'})"
                        />
                    </template>
                </Column>

                <!-- languages -->
                <Column
                    header="language" sortable
                    sortField="language.code"
                    filterField="language"
                    :showFilterMatchModes="false"
                    :filterMenuStyle="{ with: '14rem' }"
                    style="min-width: 14rem"
                >
                    <template #body="{ data }">
                        {{ data.language }}
                    </template>

                    <template #filter="{ filterModel }">
                        <Select
                            id="language"
                            v-model="filterModel.value"
                            :options="languages"
                            optionLabel="code"
                            optionValue="code"
                            placeholder="Any"
                        />
                        <!--
                        <MultiSelect
                            v-model="filterModel.value"
                            :options="languages"
                            optionLabel="code"
                            placeholder="Any"
                        >
                            <template #option="slotProps">
                                <div class="flex items-center gap-2">
                                    <span>{{ slotProps.option.code }}</span>
                                </div>
                            </template>
                        </MultiSelect>
                        -->
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

                        <!-- Actions -->
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

    </AppLayout>
</template>
