<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';

import AppLayout from '@/Layouts/AppLayout.vue';
import PersonService from '@/service/PersonService';

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
const filters = ref();

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

const initFilters = () => {
    filters.value = {
        global:    { value: null, matchMode: FilterMatchMode.CONTAINS },
        name:      { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        email:     { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        language:  { value: null, matchMode: FilterMatchMode.EQUALS },
        birthdate: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] }
    };
};

initFilters();

const getPersons = (data) => {
    return [...(data || [])].map((d) => {
        d.birthdate = new Date(d.birthdate);

        return d;
    });
}

onMounted(() => {
    fetchItems();
})

const formatDate = (value) => {
    /*
    return value.toLocaleDateString('en-US', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
    */
    
    const date = new Date(value);

    return date.toLocaleDateString('en-US', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
    
};

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
                v-model:filters="filters" 
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
