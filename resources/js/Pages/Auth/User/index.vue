<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, minLength, required, sameAs } from "@vuelidate/validators";
import validationRules from '@/Validation/ValidationRules.json';

import UserService from "@/service/UserService";
import functions from '../../../helpers/functions.js';

// PRIMEVUE components
import {
    Toolbar, DataTable, Column, IconField, InputText,
    InputIcon, Button, Dialog, Select, Tag, Password

} from 'primevue';

// TOAST
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';

/**
 * Szerver felöl jövő adatok
 */
const props = defineProps({
    permissions: { type: Object, default: () => {}, },
    roles: { type: Object, default: () => {}, },
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
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
const loading = ref(true);
const dt = ref();
const filters = ref({});
const submitted = ref(false);

const users = ref();
const defaultUser = {
    id: null,
    name: "",
    email: "",
    email_verified_at: "",
    password: "",
    language: "",
    birthdate: null,
    active: 1,
};

const defaultPassword = {
    password: "",
    password_confirmation: "",
};

// Tároló kulcsok
const local_storage_companies = 'companies';
const local_storage_column_key = 'ln_users_grid_columns';

const user = ref({ ...defaultUser });

const initialUser = () => {
    return { ...defaultUser };
};

const rules = {
    name: { required },
    email: [ required ],
    language: { required },
    birthdate: { required },
}
const v$ = useVuelidate(rules, user);

const state = reactive({
    columns: {
        id:        { field: '', is_visible: true, is_sortable: true, is_filterable: true },
        name:      { field: '', is_visible: true, is_sortable: true, is_filterable: true },
        email:     { field: '', is_visible: true, is_sortable: true, is_filterable: true },
        birthdate: { field: '', is_visible: true, is_sortable: true, is_filterable: true },
        language:  { field: '', is_visible: true, is_sortable: true, is_filterable: true },
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
}, { deep: true });

const selectedUsers = ref([]);

/**
 * ===========================================
 * DIALOGOK
 * ===========================================
 */
const settingsDialog = ref(false);
const userDialog = ref(false);
const passwordDialog = ref(false);
const deleteSelectedUsersDialog = ref(false);
const deleteUserDialog = ref(false);
// ======================================================

const fetchItems = async () => {};

onMounted(() => {});

const hideDialog = () => {};

const openNew = () => {};

const editUser = (data) => {};

const saveUser = async () => {};

const createUser = async () => {};

const updateUser = async () => {};

const deleteSelectedUsers = async () => {};

const deleteUser = async () => {};

const confirmDeleteUser = (data) => {
    user.value = { ...data };
    deleteUserDialog.value = true;
};

const confirmDeleteSelected = () => {
    deleteSelectedUsersDialog.value = true;
};

const openSettingsDialog = () => {
    settingsDialog.value = true;
};

const findIndexById = (id) => {
    return users.value.findIndex((user) => user.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const getModalTitle = () => {
    return user.value.id
        ? trans("users_edit_title")
        : trans("users_new_title");
};

const getModalDetails = () => {
    return users.value.id
        ? trans("users_edit_details")
        : trans("users_new_details");
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

</script>

<template>
    <AppLayout>
        <Head :title="$t('users')" />

    </AppLayout>
</template>
