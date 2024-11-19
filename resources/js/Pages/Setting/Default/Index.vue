<script setup>
import { onMounted, ref} from "vue";
import { Head } from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';
import { Button, Column, DataTable, FileUpload, IconField, InputIcon, InputText, Toolbar, useToast } from "primevue";
import useVuelidate from "@vuelidate/core";
import DefaultSettingService from "@/service/DefaultSettingService";
import { FilterMatchMode } from "@primevue/core/api";

const toast = useToast();
const dt = ref();
const settings = ref();
const setting = ref({
    id: null,
    name: '',
    default_value: '',
    is_active: true
});
const selectedSettings = ref([]);

const loading = ref(true);
const submitted = ref(false);

const settingDialog = ref(false);
const deleteSettingDialog = ref(false);
const deleteSelectedSettingDialog = ref(false);

const filters = ref({});
const initFilters = () => {
    filters.value = {
        global: {value: null, matchMode: FilterMatchMode.CONTAINS},
    }
};
const clearFilter = () => {
    initFilters();
};

initFilters();

const rules = {};
const v$ = useVuelidate(rules, setting);

const fetchItems = async () => {
    loading.value = true;
    DefaultSettingService.getSettings()
        .then((result) => {
            console.log(result.data.data);
            settings.value = result.data.data;
        })
        .catch((error) => {
            console.error("getDefaultSettings API Error:", error);
        })
        .finally(() => loading.value = false );
}

onMounted(() => {
    fetchItems();
});

const openNew = () => {
    setting.value = {...initialSetting};
    submitted.value = false;
    settingDialog.value = true;
};

const initialSetting = () => {
    return {...setting};
}

const exportCSV = () => {
    dt.value.exportCSV();
};

const onUpload = () => {};

const confirmDeleteSelected = () => {};

</script>

<template>
    <AppLayout>
        <Head :title="$t('default_settings')" />

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
                        :disabled="
                            !selectedSettings || !selectedSettings.length
                        "
                    />
                </template>

                <template #end>

                    <!-- IMPORT -->
                    <FileUpload 
                        mode="basic" 
                        accept="image/*" 
                        :maxFileSize="1000000" 
                        label="Import" 
                        customUpload auto 
                        chooseLabel="Import" 
                        class="mr-2" 
                        :chooseButtonProps="{
                            severity: 'secondary', 
                            icon: 'pi pi-upload'
                        }"
                        @upload="onUpload"
                    />

                    <!-- EXPORT -->
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
                v-model:selection="selectedSettings"
                :value="settings"
                dataKey="id"
                :paginator="true"
                :rows="10"
                :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} cities"
            >
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
                            {{ $t("companies_title") }}
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

                <template #empty>
                    {{ $t("data_not_found", { data: "setting" }) }}
                </template>

                <template #loading>
                    {{ $t("loader", { data: "Setting" }) }}
                </template>

                <template #paginatorstart>
                    <Button
                        type="button"
                        icon="pi pi-refresh"
                        class="p-button-text"
                        @click="fetchItems"
                    />
                </template>

                <!-- SELECTION -->
                <Column
                    selectionMode="multiple"
                    style="width: 3rem"
                    :exportable="false"
                />

                <Column
                    field="name"
                    :header="$t('name')"
                    sortable
                    style="min-width: 16rem"
                />

            </DataTable>

        </div>

    </AppLayout>
</template>