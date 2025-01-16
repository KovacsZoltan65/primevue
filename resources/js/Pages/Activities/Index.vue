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

import ActivityService from "@/service/ActivityService";

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
import FileUpload from "primevue/fileupload";
import { createId } from "@/helpers/functions";
import FloatLabel from "primevue/floatlabel";
import ErrorService from "@/service/ErrorService";
import Message from "primevue/message";
import Checkbox from "primevue/checkbox";

const props = defineProps({
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

const toast = useToast();
const loading = ref(true);
const dt = ref();
const filters = ref({});

const activities = ref();
const defaultActivity = {
    id: null,
    log_name: "",
    description: "",
    subject_type: null,
    event: null,
    subject_id: null,
    causer_id: null,
    properties: null,
    batch_uuid: 1,
    occurrence_count: 0
};

// Tároló kulcsok
const local_storage_companies = 'activities';
const local_storage_column_key = 'ln_activities_grid_columns';

const initialActivity= () => {
    return { ...defaultActivity };
};

//const activity = ref({ ...defaultActivity });
const activity = ref(initialActivity());

const submitted = ref(false);

const state = reactive({
    columns: {
        'id': { field: 'id', is_visible: true, is_sortable: true, is_filterable: true },
        'log_name': { field: 'log_name', is_visible: true, is_sortable: true, is_filterable: true },
        'description': { field: 'description', is_visible: true, is_sortable: true, is_filterable: true },
        'subject_type': { field: 'subject_type', is_visible: true, is_sortable: true, is_filterable: true },
        'event': { field: 'event', is_visible: true, is_sortable: true, is_filterable: true },
        'subject_id': { field: 'subject_id', is_visible: true, is_sortable: true, is_filterable: true },
        'causer_id': { field: 'tax_id', is_visible: true, is_sortable: true, is_filterable: true },
        'properties': { field: 'address', is_visible: true, is_sortable: true, is_filterable: true },
        'batch_uuid': { field: 'active', is_visible: true, is_sortable: true, is_filterable: true },
        'occurrence_count': { field: 'active', is_visible: true, is_sortable: true, is_filterable: true }
    }
});

watch(state.columns, (new_value, old_value) => {
    localStorage.setItem(local_storage_column_key, JSON.stringify(new_value));
}, { deep: true });

const selectedActivities = ref([]);

/**
 * ===========================================
 * DIALOGOK
 * ===========================================
 */
// táblázat beállításai
const settingsDialog = ref(false);
// új cég készítéséhez, vagy meglevő szerkesztéshez
const activityDialog = ref(false);
// ======================================================

const fetchItems = async () => {
    loading.value = true;

    await ActivityService.getActivities()
        .then((response) => {
            activities.value = response.data;
        })
        .catch((error) => {
            console.error("getActivities API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch Activities",
                additionalInfo: "Failed to retrieve the activity",
                category: "Error",
                priority: "high",
                data: null,
            });
        })
        .finally(() => {
            loading.value = false;
    });
};

const getBools = () => {
    return [
        {label: trans("inactive"),value: 0,},
        {label: trans("active"),value: 1,},
    ];
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
})

const exportCSV = () => {
    dt.value.exportCSV();
};

const openSettingsDialog = () => {};

</script>

<template>
    <AppLayout>
        <Head title="Error Logs" />

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
                </template>

                <template #end>
                <!--
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
                -->
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
