<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

import EntityService from '@/service/EntityService';
import { trans } from "laravel-vue-i18n";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import { formatCurrency, formatDate, createId } from "@/helpers/functions";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from "../../Validation/validationRules.json";

import { useToast } from "primevue/usetoast";
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import Toolbar from 'primevue/toolbar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import { Dialog, IconField, InputIcon } from 'primevue';

const toast = useToast();

const dt = ref();
const loading = ref(true);

const rules = {
    name: { required: helpers.withMessage(trans("validate_required"), required), },
    email: { required: helpers.withMessage(trans("validate_required"), required), },
    start_date: { required: helpers.withMessage(trans("validate_required"), required), },
    end_date: {}
};

const entities = ref([]);
const entity = {
    id: null,
    name: '',
    email: '',
    start_date: '',
    end_date: '',
    last_export: '',
    company_id: null,
    active: true
};

const v$ = useVuelidate(rules, entity);

const submitted = ref(false);

const selectedEntities = ref([]);

/**
 * ======================================
 * Dialog ablakok
 * ======================================
 */
const entityDialog = ref(false);
const deleteEntityDialog = ref(false);
const deleteSelectedEntitiesDialog = ref(false);

const confirmDeleteSelected = () => {};
const hideDialog = () => {
    entityDialog.value = false;
    deleteEntityDialog.value = false;
    deleteSelectedEntitiesDialog.value = false;

    submitted.value = false;

    v$.value.$reset();
};

/**
 * ======================================
 * Filterek
 * ======================================
 */
const filters = ref({});

const initFilters = () => {
    filters.value = {
        global: {
            value: null,
            matchMode: FilterMatchMode.CONTAINS
        },
        name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        email: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        start_date: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        end_date: { value: null, matchMode: FilterMatchMode.STARTS_WITH }
    };
}

const clearFilter = () => {
    initFilters();
};

initFilters();

const fetchItems = async () => {
    loading.value = true;

    try {
        const response = await EntityService.getEntities();
        entities.value = response.data.data;
    } catch(error) {
        console.error("getEntities API Error:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchItems();
});

const initialEntity = () => {
    return {...entity};
};

const openNew = () => {
    EntityService.value = {...initialEntity};
    submitted.value = false;
    entityDialog.value = true;
}

const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

const saveEntity = async () => {
    const result = await v$.value.$validate();
    if( result ) {
        submitted.value = true;

        if( entity.value.id ) {
            updateEntity();
        } else {
            createEntity();
        }
    } else {
        alert("FAIL");
    }
};

const createEntity = () => {
    // Optimista frissítés: az új cég ideiglenes hozzáadása a listához
    const tempEntity = { ...entity.value, id: createId() }; // Generálunk egy ideiglenes ID-t
    companies.value.push(tempEntity);

    // Azonnal megjelenítünk egy sikeres üzenetet
    toast.add({
        severity: "info",
        summary: "Creating...",
        detail: "Entity creation in progress",
        life: 2000,
    });

    EntityService.createCompany(entity.value)
        .then((response) => {
            // Frissítjük az ideiglenes elemet a tényleges adatokkal, ha a létrehozás sikeres
            const index = entities.value.findIndex(ent => ent.id === tempEntity.id);
            entities.value.splice(index, 1, response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Entity Created",
                life: 3000,
            });
        })
        .catch((error) => {
            // Hiba esetén eltávolítjuk az ideiglenes elemet a listából
            const index = entities.value.findIndex(ent => ent.id === tempEntity.id);
            if (index !== -1) {
                entities.value.splice(index, 1);
            }

            console.error("createEntity API Error:", error);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to create entity",
                life: 3000,
            });
        });
}

const updateEntity = () => {
    // Megkeresi a cég indexét a companies tömbben az ID alapján
    const index = findIndexById(entity.value.id);
    if (index === -1) return;

    // Eredeti állapot mentése, hogy hiba esetén visszaállíthassuk
    const originalEntity = { ...entities.value[index] };

    // Optimista frissítés: azonnal frissítjük az UI-t az új értékekkel
    entities.value.splice(index, 1, { ...entity.value });
    hideDialog();

    // Azonnal megjelenítünk egy sikeres üzenetet
    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "Entity update in progress",
        life: 2000,
    });

    EntityService.updateEntity(entity.value.id, entity.value)
    .then((response) => {
        // Ha az API sikeres, nincs további teendő, az érték már frissítve van
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Entity Updated",
            life: 3000,
        });
    })
    .catch((error) => {
        // Hiba esetén visszaállítjuk az eredeti állapotot
        entities.value.splice(index, 1, originalEntity);

        console.error("updateEntity API Error:", error);

        // Hibaüzenet megjelenítése a felhasználói felületen
        toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to update entity",
                life: 3000,
            });
    });
};

const deleteEntity = () => {
    // Megkeresi a cég indexét a companies tömbben az ID alapján
    const index = findIndexById(entity.value.id);
    if (index === -1) return;

    // Eredeti állapot mentése, hogy hiba esetén visszaállíthassuk
    const originalEntity = { ...entities.value[index] };

    // Optimista törlés: azonnal eltávolítjuk a céget a listából
    companies.value.splice(index, 1);

    // Törlési értesítés optimista frissítés után
    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Entity deletion in progress",
        life: 2000,
    });

    EntityService.deleteEntity(entity.value.id)
    .then((response) => {
        // Sikeres törlés esetén értesítés
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Entity Deleted",
            life: 3000,
        });
    })
    .catch((error) => {
        // Hiba esetén visszaállítjuk a céget az eredeti helyére
        entities.value.splice(index, 0, originalEntity);

        console.error("deleteEntity API Error:", error);

        // Hibaüzenet megjelenítése a felhasználói felületen
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Failed to delete entity",
            life: 3000,
        });
    });
};

const deleteSelectedEntities = () => {
    // Eredeti állapot mentése az összes kiválasztott céghez, hogy visszaállíthassuk hiba esetén
    const originalEntities = [...selectedEntities.value];

    // Optimista törlés: azonnal eltávolítjuk az összes kijelölt céget
    selectedEntities.value.forEach(selectedEntity => {
        const index = entities.value.findIndex(ent => ent.id === selectedEntity.id);
        if (index !== -1) {
            entities.value.splice(index, 1);
        }

        // Törlési értesítés optimista frissítés után
        toast.add({
            severity: "info",
            summary: "Deleting...",
            detail: "Deleting selected entities...",
            life: 2000,
        });

        EntityService.deleteEntities()
        .then((response) => {
            // Sikeres törlés esetén értesítés
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Selected entities deleted",
                life: 3000,
            });
            // Törölt elemek eltávolítása a selectedCompanies-ből
            selectedEntities.value = [];
        })
        .catch((error) => {
            // Hiba esetén visszaállítjuk az eredeti állapotot
            companies.value = originalEntities;

            console.error("deleteSelectedEntities API Error:", error);

            // Hibaüzenet megjelenítése a felhasználói felületen
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete selected entities",
                life: 3000,
            });
        });
    });
};

const editEntity = (data) => {
    entity.value = {...data};
    entityDialog.value = true;
};

const confirmDeleteEntity = (data) => {
    entity.value = {...data};
    deleteEntityDialog.value = true;
};

const findIndexById = (id) => {
    return entities.value.findIndex((entity) => entity.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
};

</script>

<template>
    <AppLayout>
        <Head :title="$t('entities')" />

        <div class="card">

            <Toolbar class="md-6">
                <template #start>

                    <!-- Add New Button -->
                    <Button
                        :label="$t('add_new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete_selected')"
                        icon="pi pi-trash"
                        severity="secondary"
                        @click="confirmDeleteSelected"
                        :disabled="!selectedEntities || !selectedEntities.length"
                    />
                </template>

                <template #end>

                    <!-- Upload Button -->
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

                    <!-- Export Button -->
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
                v-model:selection="selectedEntities"
                v-model:filters="filters"
                :value="entities"
                dataKey="id" :paginator="true" :rows="10" sortMode="multiple"
                :filters="filters" filterDisplay="row"
                :globalFilterFields="['name','email','start_date','end_date']"
                :loading="loading" stripedRows removableSort
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} subdomains"
            >

                <template #header>
                    <div class="flex flex-wrap gap-2 items-center justify-between">
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
                            {{ $t("entities_title") }}
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

                <template #paginatorstart>
                    <Button
                        type="button"
                        icon="pi pi-refresh" text
                        @click="fetchItems"
                    />
                </template>

                <template #empty>
                    {{ $t('data_not_found', {data: $t('entity') || $t('entities') } ) }}
                </template>

                <template #loading>
                    {{ $t('loader', {data: $t('entity') || $t('entities') }) }}
                </template>

                <!-- Checkbox -->
                <Column
                    selectionMode="multiple"
                    style="min-width: 3rem"
                    :exportable="false"
                />

                <!-- Name -->
                <Column
                    field="name"
                    :header="$t('name')"
                    style="min-width: 12rem"
                    sortable
                >
                    <template #body="slotProps">
                        {{ slotProps.data.name }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: $t('name') })"
                            @input="filterCallback()"
                        />
                    </template>
                </Column>

                <!-- Email -->
                <Column 
                    field="email" 
                    :header="$t('email')"
                    style="min-width: 12rem"
                    sortable
                >

                    <template #body="slotProps">
                        {{ slotProps.data.email }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'email'})"
                            @input="filterCallback()"
                        />
                    </template>
                </Column>

                <!-- Start Date -->
                <Column 
                    field="start_date"
                    :header="$t('start_date')"
                    style="min-width: 12rem"
                    sortable
                >
                    <template #body="slotProps">
                        {{ formatDate(slotProps.data.start_date) }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: $t('start_date')})"
                            @input="filterCallback()"
                        />
                    </template>
                </Column>

                <!-- End Date -->
                <Column 
                    field="end_date"
                    :header="$t('end_date')"
                    style="min-width: 12rem"
                    sortable
                >
                    <template #body="slotProps">
                        {{ formatDate(slotProps.data.end_date) }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: $t('end_date')})"
                            @input="filterCallback()"
                        />
                    </template>
                </Column>

                <!-- Actions -->
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        
                        <!-- szerkesztés -->
                        <Button
                            icon="pi pi-pencil"
                            outlined rounded
                            class="mr-2"
                            @click="editEntity(slotProps.data)"
                        />

                        <!-- törlés -->
                        <Button
                            icon="pi pi-trash"
                            outlined rounded
                            severity="danger"
                            @click="confirmDeleteEntity(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- szerkesztés -->
        <Dialog
            v-model:visible="entityDialog"
            :style="{ width: '450px' }"
            :header="$t('entity_details')"
            :modal="true"
        >
            <div class="flex flex-col gap-6">
                <div class="flex flex-wrap gap-4">
                    <!-- Name -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="name" class="block font-bold mb-3">
                            {{ $t("name") }}
                        </label>
                        <InputText 
                            id="name"
                            v-model="entity.name"
                            autofocus 
                            fluid
                        />
                        <small 
                            class="text-red-500" 
                            v-if="v$.name.$error">
                            {{ $t(v$.name.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="email" class="block font-bold mb-3">
                            {{ $t("email") }}
                        </label>
                        <InputText 
                            id="email"
                            v-model="entity.email"
                            fluid
                        />
                        <small 
                            class="text-red-500" 
                            v-if="v$.email.$error">
                            {{ $t(v$.email.$errors[0].$message) }}
                        </small>
                    </div>

                </div>
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
                    @click="saveEntity"
                />
            </template>
        </Dialog>

        <!-- Egy elem törlése -->
        <Dialog
            v-model:visible="deleteEntityDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <span class="text-surface-500 dark:text-surface-400 block mb-8">
                {{ $t("entities_delete_title") }}
            </span>

            <div class="flex items-center gap-4">
                <!-- A figyelmeztető ikon -->
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <!-- A szöveg, amely megjelenik a párbeszédpanelen -->
                <span v-if="entity">
                    {{ $t("confirm_delete_2") }} <b>{{ entity.name }}</b>?
                </span>
            </div>
            <template #footer>
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteEntityDialog = false"
                    text
                />
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteEntity"
                />
            </template>
        </Dialog>

        <!-- Kijelölt elemek törlése -->
        <Dialog
            v-model:visible="deleteSelectedEntitiesDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="product">{{ $t("confirm_delete") }}</span>
            </div>

            <template #footer>
                <Button 
                    :label="$t('no')"
                    icon="" text
                    @click="deleteSelectedEntitiesDialog = false"
                />
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSelectedEntities"
                />
            </template>
        </Dialog>

    </AppLayout>
</template>
