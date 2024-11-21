<script setup>
import { onMounted, ref} from "vue";
import { Head } from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';
import { Button, Column, DataTable, Dialog, FileUpload, IconField, InputIcon, InputText, Tag, Toolbar, useToast } from "primevue";
import DefaultSettingService from "@/service/DefaultSettingService";
import { FilterMatchMode } from "@primevue/core/api";
import { trans } from "laravel-vue-i18n";
import { createId } from "@/helpers/functions";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
//import validationRules from "../../../Validation/ValidationRules.json";
import validationRules from '@/Validation/ValidationRules.json';

const toast = useToast();
const dt = ref();
const settings = ref();
const setting = ref({
    id: null,
    name: '',
    default_value: '',
    active: true
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

const rules = {
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
    default_value: {
        required: helpers.withMessage(trans("validate_default_value"), required),
    }
};
const v$ = useVuelidate(rules, setting);

const fetchItems = async () => {
    loading.value = true;

    DefaultSettingService.getSettings()
        .then((result) => {
            console.log("result", result);
            settings.value = result.data.data;
        })
        .catch((error) => {
            console.error("getDefaultSettings API Error:", error);
        })
        .finally(() => {
            loading.value = false
        });
}

onMounted(() => {
    fetchItems();
});

const editSetting = (data) => {
    //console.log(data);
    setting.value = {...data};
    settingDialog.value = true;
};

const confirmDeleteSetting = (data) => {
    setting.value = {...data};

    deleteSettingDialog.value = true;
};

const confirmDeleteSelected = () => {
    deleteSelectedSettingDialog.value = true;
};

const saveSetting = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (setting.value.id) {
            updateSetting();
        } else {
            createSetting();
        }
    } else {
        alert("FAIL");
    }
};

const createSetting = async () => {
    loading.value = true;

    const newSetting = {...setting.value, id: createId()};
    settings.value.push(newSetting);

    toast.add({
        severity: "info",
        summary: "Creating...",
        detail: "Setting creation in progress",
        life: 2000,
    });

    DefaultSettingService.createSetting(newSetting)
        .then((response) => {
            const index = settings.value.findIndex(set => set.id === newSetting.id);
            subdomains.value.splice(index, 1, response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Setting Created",
                life: 3000,
            });
        })
        .catch((error) => {
            const index = settings.value.findIndex(set => set.id === newSetting.id);
            if (index !== -1) {
                entities.value.splice(index, 1);
            }

            console.error("createSetting API Error:", error);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to create setting",
                life: 3000,
            });
        })
        .finally(() => {
            loading.value = false;
        });
};

const updateSetting = async () => {
    const index = findIndexById(setting.value.id);
    if (index === -1) {
        console.error(`Setting with id ${setting.value.id} not found`);
        return;
    }

    loading.value = true;

    const originalSetting = { ...settings.value[index] };
    settings.value.splice(index, 1, { ...setting.value });

    hideDialog();

    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "Setting update in progress",
        life: 2000,
    });

    DefaultSettingService.updateSetting(setting.value)
    .then((response) => {
        settings.value.splice(index, 1, response.data);

        toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Setting Updated",
                life: 3000,
            });
    })
    .catch((error) => {
        settings.value.splice(index, 1, originalSetting);

        console.error("updateSetting API Error:", error);

        toast.add({
            severity: "error",
            summary: "Error",
            detail: error.response.data.message,
            life: 3000,
        });
    })
    .finally(() => {
        loading.value = false;
    });
};

const deleteSubdomain = () => {
    const index = findIndexById(setting.value.id);
    if (index === -1) {
        console.warn("No setting found with the given id:", setting.value.id);
        return;
    }

    loading.value = true;

    const originalSetting = { ...settings.value[index] };

    settings.value.splice(index, 1);

    hideDialog();

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Setting deletion in progress",
        life: 2000,
    });

    DefaultSettingService.deleteSetting(setting.value.id)
        .then((response) => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Setting Deleted",
                life: 3000,
            });
        })
        .catch((error) => {
            // Hiba esetén, állítsa vissza az eredeti beállítást
            // hogy a felhasználó ne veszítse el adatait.
            settings.value.splice(index, 0, originalSetting);
            
            console.error("deleteSetting API Error:", error);
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete setting",
                life: 3000,
            });
        })
        .finally(() => {
            loading.value = false;
        });
};

const deleteSelectedSettings = () => {
    const originalSettings = [...settings.value];

    selectedSettings.value.forEach((setting) => {
        const index = findIndexById(setting.id);
        if (index !== -1) {
            settings.value.splice(index, 1);
        }
    });

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Deleting selected settings...",
        life: 2000,
    });

    DefaultSettingService.deleteSubdomains(selectedSettings.value.map(set => set.id))
        .then((response) => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Selected settings deleted",
                life: 3000,
            });
            // Törölt elemek eltávolítása a selectedSubdomains-ből
            selectedSettings.value = [];
        })
        .catch((error) => {
            settings.value = originalSettings;

            console.error("deleteSelectedSettings API Error:", error);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete selected settings",
                life: 3000,
            });
        });
};

const openNew = () => {
    setting.value = {...initialSetting};
    submitted.value = false;
    settingDialog.value = true;
};

const initialSetting = () => {
    return {...setting};
}

const findIndexById = (id) => {
    return settings.value.findIndex((setting) => setting.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const onUpload = () => {};

const hideDialog = () => {
    settingDialog.value = false;
    deleteSettingDialog.value = false;
    deleteSelectedSettingDialog.value = false;

    submitted.value = false;
    v$.value.$reset();
};

const getBools = () => {
    return [
        {label: trans("inactive"), value: 0},
        {label: trans("active"), value: 1}
    ];
};

const getModalTitle = () => {
    return setting.value.id
        ? trans("settings_edit_title")
        : trans("settings_new_title");
};

const getActiveLabel = (subdomain) =>
    ["danger", "success", "warning"][subdomain.active || 2];
    
const getActiveValue = (setting) => 
    ["inactive", "active", "pending"][setting.active] || "pending";

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
                :loading="loading" stripedRows removableSort
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

                <!-- NAME -->
                <Column
                    field="name"
                    :header="$t('name')"
                    sortable
                    style="min-width: 16rem"
                />

                <!-- DEFAULT VALUE -->
                <Column
                    field="default_value"
                    :header="$t('default_value')"
                    style="min-width: 16rem"
                />

                <!-- ACTIVE -->
                <Column
                    field="active"
                    :header="$t('active')"
                    sortable
                    style="min-width: 6rem"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="$t(getActiveValue(slotProps.data))"
                            :severity="getActiveLabel(slotProps.data)"
                        />
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
                            @click="editSetting(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteSetting(slotProps.data)"
                        />
                    </template>
                </Column>

            </DataTable>

        </div>

        <!-- SETTING DETAILS DIALOG -->
         <Dialog
            v-model:visible="settingDialog"
            :style="{ width: '450px' }"
            :header="getModalTitle()"
            :modal="true"
         >
            <div class="flex flex-col gap-6">
                <!-- NAME -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="name" class="block font-bold mb-3">
                        {{ $t("name") }}
                    </label>
                    <InputText
                        id="name"
                        v-model="setting.name"
                        autofocus
                        fluid
                    />
                    <small class="text-red-500" v-if="v$.name.$error">
                        {{ $t(v$.name.$errors[0].$message) }}
                    </small>
                </div>

                <!-- DEFAULT VALUE -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="default_value" class="block font-bold mb-3">
                        {{ $t("default_value") }}
                    </label>
                    <InputText
                        id="default_value"
                        v-model="setting.default_value"
                        autofocus
                        fluid
                    />
                    <small class="text-red-500" v-if="v$.default_value.$error">
                        {{ $t(v$.default_value.$errors[0].$message) }}
                    </small>
                </div>

                <!-- ACTIVE -->
                
            </div>

            <template #footer>
                <Button
                    :label="$t('cancel')"
                    icon="pi pi-times"
                    text
                    @click="hideDialog"
                />
                <Button
                    :label="$t('save')"
                    icon="pi pi-check"
                    @click="saveSetting"
                />
            </template>

         </Dialog>

        <!-- DELETE SETTING DIALOG -->

        <!-- DELETE SELECTED DIALOG -->

    </AppLayout>
</template>