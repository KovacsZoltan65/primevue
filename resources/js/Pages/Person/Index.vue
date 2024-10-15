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
import validationRules from "../../Validation/validationRules.json";

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

const city = ref({
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
        required: helpers.withMessage(trans('validate'), required),
        minValue: helpers.withMessage(trans('validate'), minDate),
        maxValue: helpers.withMessage(trans('validate"'), maxDate)
    }
};

</script>
<template></template>