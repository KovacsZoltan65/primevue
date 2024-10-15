<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

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

const selectedPersons = ref();

const filters = ref({
    global: {
        value: null,
        matchMode: FilterMatchMode.CONTAINS,
    }
});

const submitted = ref(false);


/**
 * const minDate = '1950-01-01';
 * const maxDate = () => new Date(Date.now() - 20 * 365.25 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
*/

const minDate = new Date('1950-01-01').toISOString().split('T')[0];

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
    console.log('fetchItems');
}

onMounted(() => {
    fetchItems();
})

const openNew = () => {};

const exportCSV = () => {
    dt.value.exportCSV();
};

</script>

<template>
    <AppLayout>
        <Head :title="$t('persons')" />

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
        </div>

    </AppLayout>
</template>
