<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import { useToast } from "primevue/usetoast";
import { trans } from "laravel-vue-i18n";

import AppLayout from '@/Layouts/AppLayout.vue';
import PersonService from '@/service/PersonService';

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from "@/Validation/ValidationRules.json"

import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Toolbar from 'primevue/toolbar';
import Column from 'primevue/column';
import InputIcon from 'primevue/inputicon';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Checkbox from 'primevue/checkbox';
import DatePicker from 'primevue/datepicker';

const persons = ref([]);
const languages = ref(['hu', 'en']);
const loading = ref(true);
//const filters = ref();
const dt = ref();
const selectedPersons = ref();
const submitted = ref(false);

const toast = useToast();

const personDialog = ref(false);
const deleteSelectedPersonsDialog = ref(false);
const deletePersonDialog = ref(false);

const person = ref({
    id: null,
    name: '',
    email: '',
    password: '',
    language: '',
    birthdate: '',
    active: 1
})

const fetchItems = () => {
    PersonService.getPersons()
        .then((response) => {
            persons.value = getPersons(response.data.data);
        })
        .catch((error) => {
            console.log(error);
        }).finally(() => {
            loading.value = false;
        });
};

/*
const columns = [
    { field: 'name', header: 'Name' },
    { field: 'email', header: 'Email' },
    { field: 'language', header: 'Language' },
    { field: 'birthdate', header: 'Birthdate' },
];
*/

/**
 * Inicializálja az adattábla szűrőit.
 * 
 * Ez a funkció minden oszlophoz beállítja az alapértelmezett szűrőértékeket és az illesztési módokat
 * az adattáblázatban, amely lehetővé teszi az adatok keresését és szűrését.
 */
const filters = ref({
        // Globális szűrő minden oszlopra alkalmazva
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        // Szűrje a 'név' oszlopot, a megadott értékkel kezdődő bejegyzéseket
        name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        // Szűrje az 'e-mail' oszlopot, az adott értékkel kezdődő bejegyzéseket
        email: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        // Szűrje a „nyelv” oszlopot, és a megadott értékkel megegyező bejegyzéseket adja meg
        language: { value: null, matchMode: FilterMatchMode.EQUALS },
        // Szűrje a „születési dátum” oszlopot, amely megköveteli az összes megkötés teljesítését
        birthdate: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] }
    }
);

const rules = {
    name: {
        required: helpers.withMessage(trans('validate_name'), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
    email: {
        required: '',
    },
    password: {
        required: helpers.withMessage(trans('validate_password'), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.password', { min: $params.min }), minLength(validationRules.password_min_length)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.password', { max: $params.max }), maxLength(validationRules.password_max_length)),
    },
    language: {
        required: helpers.withMessage(trans('validate_required'), required),
    },
    birthdate: {
        required: helpers.withMessage(trans('validate_required'), required),
    },
    active: {}
}

/**
 * Leképezi a személyek adatait egy használhatóbb formátumra.
 *
 * @param {Array|Object} data A személyek adatai
 * @returns {Array} A feltérképezett személyek adatai
 */
const getPersons = (data) => {
    return [...(data || [])].map((d) => {
        // Leképezi a személyek születési dátumát egy Date objektumra
        // A személyek születési dátuma a szerveren egy ISO 8601 formátumú dátumként lesz elmentve
        // A Date konstruktorral leképezzük ezt a szerveren elmentett dátumot egy Date objektumra
        d.birthdate = new Date(d.birthdate);

        return d;
    });
}

onMounted(() => {
    loading.value = true;
    fetchItems();
})

/**
 * A dátumot 'MM/nn/yyyy' formában formálja.
 *
 * @param {string|Date} value A formázandó dátum
 * @returns {string} A formázott dátum
 */
const formatDate = (value) => {
    const date = new Date(value);

    return date.toLocaleDateString('en-US', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const exportCSV = () => {
    dt.value.exportCSV();
}

const deleteSelectedPersons = () => {
    PersonService.deletePersons(selectedPersons.value)
        .then((response) => {
            fetchItems();
        })
        .catch((error) => {
            console.log(error);
        });
}

</script>

<template>
    <AppLayout>
        <Head :title="$t('persons')" />

        <div class="card">
            <Toolbar>
                <template #start>
                    <Button 
                        :label="$t('new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                    />
                    <Button 
                        :label="$t('delete')"
                        icon="pi pi-trash"
                        severity="secondary"
                        @click="deleteSelectedPersons"
                        :disabled="!selectedPersons || !selectedPersons.length"
                    />
                </template>
                <template #end>
                    <Button
                        :label="$t('export')"
                        icon="pi pi-upload"
                        severity="secondary"
                    />
                </template>
            </Toolbar>

            <DataTable 
                :filters="filters" 
                v-model:selection="selectedPersons"
                :value="persons" paginator 
                :rows="10" 
                dataKey="id" 
                filterDisplay="row" 
                :loading="loading"
                :globalFilterFields="['name', 'email', 'status', 'birthdate']"
            >
                <template #header>
                    <div class="flex justify-end">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText 
                                v-model="filters['global'].value" 
                                placeholder="Keyword Search"
                            />
                        </IconField>
                    </div>
                </template>

                <!-- checkbox -->
                <Column 
                    selectionMode="multiple" 
                    style="min-width: 3rem" 
                    :exportable="false"
                />

                <!-- name -->
                <Column 
                    field="name" 
                    header="Name" 
                    style="min-width: 12rem"
                >
                    <template #body="{ data }">
                        {{ data.name }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText 
                            v-model="filterModel.value" 
                            type="text" 
                            @input="filterCallback()" 
                            :placeholder="$t('search_by_name')"
                        />
                    </template>
                </Column>

                <!-- email -->
                <Column 
                    field="email" 
                    header="Email" 
                    style="min-width: 12rem"
                >
                    <template #body="{ data }">
                        {{ data.email }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText 
                            v-model="filterModel.value" 
                            type="text" 
                            @input="filterCallback()" 
                            :placeholder="$t('search_by_email')"
                        />
                    </template>
                </Column>

                <!-- language -->
                <Column 
                    field="language" 
                    header="Language"
                    :showFilterMenu="false" 
                    style="min-width: 12rem"
                >
                    <template #body="{ data }">
                        {{ data.language }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <Select 
                            v-model="filterModel.value"
                            :options="languages"
                            :placeholder="$t('select_one')" 
                            style="min-width: 12rem" 
                            :showClear="true"
                            @change="filterCallback()"
                        />
                    </template>
                </Column>

                <!-- birthdate -->
                <Column 
                    field="birthdate" 
                    header="Birth Date" sortable 
                    filterField="birthdate" 
                    dataType="date" 
                    style="min-width: 10rem"
                >
                    <template #body="{ data }">
                        {{ formatDate(data.birthdate) }}
                    </template>
                    
                    <template #filter="{ filterModel }">
                        <DatePicker 
                            v-model="filterModel.value" 
                            dateFormat="mm/dd/yy" 
                            placeholder="mm/dd/yyyy"
                        />
                    </template>
                    
                </Column>
            
            </DataTable>
        </div>

    </AppLayout>
</template>
