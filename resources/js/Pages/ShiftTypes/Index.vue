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

import ShiftTypeService from "@/service/ShiftTypeService";
import ErrorService from "@/service/ErrorService";
import { createId } from "@/helpers/functions";

import {Toolbar,DataTable,Column,IconField,
    InputText,InputIcon,Button,Dialog,
    Select,Tag,FileUpload,FloatLabel,
    Message,Checkbox} from "primevue";

const props = defineProps({
    search: { type: Object, default: () => {} },
    can: { type: Object, default: () => {} },
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

const shiftTypes = ref();
const defaultShiftType = {
    id: null,
    company_id: null,
    code: "",
    name: "",
    trunk_time_start: "",
    trunk_time_end: "",
    edge_time_start: "",
    edge_time_end: "",
    active: 1,
};

// Tároló kulcsok
const local_storage_column_key = 'ln_shift_types_grid_columns';

const shiftType = ref({ ...defaultShiftType });
const initialShiftType = () => {
    return { ...defaultShiftType };
};

const submitted = ref(false);

const rules = {
    company_id: {},
    code: {},
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
    trunk_time_start: { required: helpers.withMessage(trans("validate_name"), required) },
    trunk_time_end: { required: helpers.withMessage(trans("validate_name"), required) },
    edge_time_start: { required: helpers.withMessage(trans("validate_name"), required) },
    edge_time_end: { required: helpers.withMessage(trans("validate_name"), required) },
};
const v$ = useVuelidate(rules, shiftType);

const state = reactive({
    columns: {
        'company_id': { field: 'company_id', is_visible: true, is_sortable: true, is_filterable: true },
        'code': { field: 'code', is_visible: true, is_sortable: true, is_filterable: true },
        'name': { field: 'name', is_visible: true, is_sortable: true, is_filterable: true },
        'trunk_time_start': { field: 'trunk_time_start', is_visible: true, is_sortable: true, is_filterable: true },
        'trunk_time_end': { field: 'trunk_time_end', is_visible: true, is_sortable: true, is_filterable: true },
        'edge_time_start': { field: 'edge_time_start', is_visible: true, is_sortable: true, is_filterable: true },
        'edge_time_end': { field: 'edge_time_end', is_visible: true, is_sortable: true, is_filterable: true },
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
}, { deep: true });

const selectedCompanies = ref([]);

/**
 * ===========================================
 * DIALOGOK
 * ===========================================
 */
// táblázat beállításai
const settingsDialog = ref(false);
// új cég készítéséhez, vagy meglevő szerkesztéshez
const shiftTypeDialog = ref(false);
// kiválasztott cégekek törléséhez
const deleteSelectedShiftTypesDialog = ref(false);
// cég törléséhez
const deleteShiftTypeDialog = ref(false);
// ======================================================

const fetchItems = async () => {
    loading.value = true;

    await ShiftTypeService.getShiftTypes().then((response) => {
            // A műszak típusok listája a shiftTypes változóban lesz elmentve
            shiftTypes.value = response.data;
        }).catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("getShiftTypes API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch ShiftTypes",
                additionalInfo: "Failed to retrieve the shiftTypes",
                category: "Error",
                priority: "high",
                data: null,
            }).finally(() => {
                loading.value = false;
            });
        });
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
    shiftType.value = initialShiftType(); // Visszaáll az alapértelemezett állapotra
    settingsDialog.value = false;
    shiftTypeDialog.value = false;
    deleteShiftTypeDialog.value = false;
    deleteSelectedShiftTypesDialog.value = false;
    submitted.value = false;

    v$.value.$reset();
};

function openNew() {
    shiftType.value = initialShiftType();
    submitted.value = false;
    shiftTypeDialog.value = true;
};

const editShiftType = (data) => {
    shiftType.value = { ...data };
    shiftTypeDialog.value = true;
};

const saveShiftType = async () => {
    const result = await v$.value.$validate();

    if (result) {
        submitted.value = true;

        if( shiftType.value.id ) {
            updateShiftType();
        } else {
            createShiftType();
        }

    } else {
        //
    }
};

const createShiftType = async () => {
    // Lokálisan hozzunk létre egy ideiglenes azonosítót az új műszak típushoz
    const newShiftType = {...shiftType.value, id: createId() };

    shiftTypes.value.push(newShiftType);
    // Optimista visszajelzés a felhasználónak
    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "ShifType creation in progress",
        life: 3000,
    });

    // Szerver kérés
    await ShiftTypeService.createShiftType(shiftType.value)
        .then((response) => {
            // Lokális adat frissítése a szerver válasza alapján
            const index = findIndexById(newShiftType.id);
            if (index !== -1) {
                shiftTypes.value.splice(index, 1, response.data);
            }

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "ShiftType Created",
                life: 3000,
            });
        })
        .catch((error) => {
            if( error.response && error.response.status === 422 ) {
                const validationErrors = error.response.data.details;

                toast.add({
                    severity: "warn",
                    summary: "Validation Error",
                    detail: "Please check your inputs",
                    life: 4000,
                });

                // Validációs hibák logolása
                ErrorService.logClientError(error, {
                    componentName: "createShiftTypeDialog",
                    additionalInfo: "Validation errors occurred during shift_type creation",
                    category: "Validation Error",
                    priority: "medium",
                    validationErrors: validationErrors,
                });

            } else {
                // Hibás esetben a lokális adat törlése
                const index = findIndexById(newShiftType.id);
                if (index !== -1) {
                    shiftTypes.value.splice(index, 1);
                }
            }
        });
};

const updateShiftType = async () => {
    const index = findIndexById(shiftType.value.id);
    if (index === -1) {
        console.error(`Shift Type with id ${shiftType.value.id} not found`);
        return;
    }

    // Eredeti adat mentése az optimista frissítéshez
    const originalShifType = { ...shiftTypes.value[index] };

    // Lokális frissítés az optimista visszacsatoláshoz
    shiftTypes.value.splice(index, 1, { ...shiftType.value });

    // "Frissítés folyamatban" visszajelzés
    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "ShiftType update in progress",
        life: 2000,
    });

    // Hívás a szerver felé
    await ShiftTypeService.updateShiftType(shiftType.value.id, shiftType.value).then((response) => {
        shiftTypes.value.splice(index, 1, response.data);

        hideDialog();

        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "ShiftType Updated",
            life: 3000,
        });
    }).catch((error) => {
        shiftTypes.value.splice(index, 1, originalShifType);

        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Failed to update ShiftType",
        });

        ErrorService.logClientError(error, {
            componentName: "UpdateShiftTypeDialog",
            additionalInfo: "Failed to update a shift type in the backend",
            category: "Error",
            priority: "medium",
            data: shiftType.value,
        });
    });
};

const deleteSelectedShiftTypes = async () => {};

const deleteShiftType = async () => {};

const confirmDeleteShiftType = (data) => {};

const confirmDeleteSelected = () => {};

const openSettingsDialog = () => {};

const findIndexById = (id) => {
    return shiftTypes.value.findIndex((shiftType) => shiftType.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const getModalTitle = () => {
    return company.value.id
        ? trans("shift_types_edit_title")
        : trans("shift_types_new_title");
};

const getModalDetails = () => {
    return company.value.id
        ? trans("shift_types_edit_details")
        : trans("shift_types_new_details");
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
        <Head :title="$t('shift_tipes')" />
    </AppLayout>
</template>

<style scoped>

</style>
