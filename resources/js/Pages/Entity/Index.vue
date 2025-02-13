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
import ErrorService from "@/service/ErrorService";
import { createId } from "@/helpers/functions";

import {Toolbar,DataTable,Column,IconField,
    InputText,InputIcon,Button,Dialog,
    Select,Tag,FileUpload,FloatLabel,
    Message,Checkbox} from "primevue";
import EntityService from "@/service/EntityService.js";

const props = defineProps({
  search: { type: Object, default: () => {}, },
  //can: { type: Object, default: () => {}, },
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

const entities = ref({});

const defaultEntity = {
    id: null,
    name: "",
    email: "",
    start_date: "",
    end_date: "",
    last_export: "",
    company_id: null,
    active: 1
};

const local_storage_column_key = 'ln_entities_grid_columns';

const entity = ref({ ...defaultEntity });

const initialEntity = () => {
    return { ...defaultEntity };
};

const submitted = ref(false);

const rules = {
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
    email: { required: helpers.withMessage(trans("validate_email"), required), },
    start_date: { required: helpers.withMessage(trans("validate_start_date"), required), },
    end_date: { required: helpers.withMessage(trans("validate_end_date"), required), },
    last_export: { required: helpers.withMessage(trans("validate_last_export"), required), },
    company_id: { required: helpers.withMessage(trans("validate_company_id"), required), }
};

const v$ = useVuelidate(rules, entity);

const state = reactive({
    columns: {
        'id': { fields: 'id', is_visible: true, is_sortable: true, is_filterable: true },
        'name': { fields: 'name', is_visible: true, is_sortable: true, is_filterable: true },
        'email': { fields: 'email', is_visible: true, is_sortable: true, is_filterable: true },
        'start_date': { fields: 'start_date', is_visible: true, is_sortable: true, is_filterable: true },
        'end_date': { fields: 'end_date', is_visible: true, is_sortable: true, is_filterable: true },
        'last_export': { fields: 'last_export', is_visible: true, is_sortable: true, is_filterable: true },
        'company_id': { fields: 'company_id', is_visible: true, is_sortable: true, is_filterable: true },
        'active': { fields: 'active', is_visible: true, is_sortable: true, is_filterable: true },
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value) );
}, { deep: true });

const selectedEntities = ref([]);

const settingsDialog = ref(false);
const entityDialog = ref(false);
const deleteSelectedEntitiesDialog = ref(false);
const deleteEntityDialog = ref(false);

const fetchItems = async () => {
    loading.value = true;

    await EntityService.getEntities()
        .then((response) => {
            entities.value = response.data;
        }).catch((error) => {
            console.log('getEntities API Error:', error);

            ErrorService.logClientError(error, {
                componentName: "Fetch Entities",
                additionalInfo: "Failed to receive the entities",
                category: "Error",
                priority: "high",
                data: null,
            })
        }).finally(() => {
            loading.value = false;
        });
};

onMounted(() => {
    fetchItems();

    let columns = localStorage.getItem(local_storage_column_key);
    if( columns ) {
        columns = JSON.parse(columns);
        for( const column_name in columns ) {
            state.columns[column_name] = columns[column_name];
        }
    }
});

const hideDialog = () => {
    entitiy.value = initialEntity();
    settingsDialog.value = false;
    entityDialog.value = false;
    deleteSelectedEntityDialog.value = false;
    deleteEntityDialog.value = false;
    submitted.value = false;

    v$.value.$reset();
};

const openNew = () => {
    entity.value = initialEntity();
    submitted.value = false;
    entityDialog.value = true;
}



</script>

<template>
  <AppLayout>
    <Head :title="$t('entities')"/>

    {{ JSON.stringify(entities) }}
  </AppLayout>
</template>
