<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

import RoleService from "@/service/RoleService";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from '@/Validation/ValidationRules.json';

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
import FloatLabel from "primevue/floatlabel";
import Message from "primevue/message";
import ErrorService from "@/service/ErrorService";
import { createId } from "@/helpers/functions";
import { FileUpload, Toast } from "primevue";

const props = defineProps({
    users: { type: Object, default: () => {}, },
    permissions: { type: Object, default: () => {}, },
    search: { type: Object, default: () => {}, },
    can: { type: Object, default: () => {}, },
});

const dt = ref();
const toast = useToast();
const roles = ref();
const role = ref({
    id: null,
    name: "",
    guard_name: "web"
});
const selectedRoles = ref([]);

/**
 * ===========================================
 * DIALOGOK
 * ===========================================
 */
const roleDialog = ref(false);
const deleteSelectedRolesDialog = ref(false);
const deleteRoleDialog = ref(false);
/**
 * ===========================================
 */

const loading = ref(false);
const filters = ref({});
const submitted = ref(false);

/**
 * ===========================================
 * VALIDÁLÁS
 * ===========================================
 */
const rules = {
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    }
};
const v$ = useVuelidate(rules, role);
/**
 * ===========================================
 */

const fetchItems = () => {
    loading.value = true;

    RoleService.getRoles()
        .then((response) => {
            roles.value = response.data;
        })
        .catch((error) => {
            console.error("getCompanies API Error:", error);

            ErrorService.logClientError(error, {
                componentName: "Fetch Roles",
                additionalInfo: "Failed to retrieve the role",
                category: "Error",
                priority: "high",
                data: null,
            });
        }).finally(() => {
            loading.value = false;
        });
}

const initialRole = () => {
    return {...role};
};

onMounted(() => {
    fetchItems();
});

const editRole = (data) => {
    role.value = { ...data };
    roleDialog.value = true;
};

const saveRole = async () => {
    const result = await v$.value.$validate();
    if(result) {
        submitted.value = true;

        if(role.value.id) {
            updateRole();
        } else {
            createRole();
        }
    } else {
        const validationErrors = v$.value.$errors.map((error) => ({
            field: error.$property,
            message: trans(error.$message),
        }));
        const data = {
            componentName: "saveRole",
            additionalInfo: trans('validation_client_side_error'),
            category: "Validation Error",
            priority: "low",
            validationErrors: validationErrors,
        }
        ErrorService.logValidationError(new Error('Client-side validation error'), data);

        toast.add({
            severity: "error",
            summary: trans("validation_error"),
            detail: trans('validation_client_side_error')
        })
    }
}

const createRole = () => {
    const newRole = { ...role.value, id:createId() };
    roles.value.push(newRole);
    toast.add({
        severity: "success",
        summary: "Creating...",
        detail: "Role creation in progress",
        life: 3000
    });

    RoleService.createRole(role.value)
        .then((response) => {
            const index = findIndexById(newRole.id);
            if (index !== -1) {
                roles.value.splice(index, 1, response.data);
            }
            hideDialog();
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Role Created",
                life: 3000
            });
        })
        .catch((error) => {
            if( error.response && error.response.status === 422 )
            {
                const index = findIndexById(newRole.id);
                if (index !== -1) {
                    roles.value.splice(index, 1);
                }

                const validationErrors = error.response.data.details;

                toast.add({
                    severity: "warn",
                    summary: "Validation Error",
                    detail: "Please check your inputs",
                    life: 4000,
                });

                ErrorService.logClientError(error, {
                    componentName: "CreateCompanyDialog",
                    additionalInfo: "Validation errors occurred during role creation",
                    category: "Validation Error",
                    priority: "medium",
                    validationErrors: validationErrors,
                });
            } else {
                const index = findIndexById(newRole.id);
                if (index !== -1) {
                    roles.value.splice(index, 1);
                }

                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: trans('error_role_create'),
                });

                ErrorService.logClientError(error, {
                    componentName: "CreateRoleDialog",
                    additionalInfo: "Failed to create a role in the backend",
                    category: "Error",
                    priority: "high",
                    data: company.value,
                });
            }
        });
}

const updateRole = () => {
    const index = findIndexById(role.value.id);
    if (index === -1) {
        console.error(`Role with id ${role.value.id} not found`);
        return;
    }

    const originalRole = { ...roles.value[index] };

    roles.value.splice(index, 1, { ...role.value });
    hideDialog();

    toast.add({
        severity: "info",
        summary: "Updating...",
        detail: "Role update in progress",
        life: 2000,
    });

    RoleService.updateRole(role.value.id, role.value)
        .then((response) => {
            roles.value.splice(index, 1, response.data);

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Role Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            roles.value.splice(index, 1, originalRole);

            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to update role",
            });

            ErrorService.logClientError(error, {
                componentName: "UpdateRoleDialog",
                additionalInfo: "Failed to update a role in the backend",
                category: "Error",
                priority: "medium",
                data: role.value,
            });

        });
};

const deleteSelectedRoles = () => {
    const originalRoles = [...roles.value];

    selectedRoles.value.forEach(selectedRole => {
        const index = roles.value.findIndex(role => role.id === selectedRole.id);
        if (index !== -1) {
            roles.value.splice(index, 1);
        }
    });

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Deleting selected roles...",
        life: 2000,
    });

    RoleService.deleteRoles(selectedRoles.value.map(role => role.id))
        .then((response) => {
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Selected roles deleted",
                life: 3000,
            });

            selectedRoles.value = [];
        })
        .catch((error) => {
            roles.value = originalRoles;

            const errorMessage = error.response?.data?.error || "Failed to delete selected roles";

            toast.add({
                severity: "error",
                summary: "Error",
                detail: errorMessage,
                life: 3000,
            });

            ErrorService.logClientError(error, {
                componentName: "DeleteRolesDialog",
                additionalInfo: "Failed to delete a roles in the backend",
                category: "Error",
                priority: "low",
                data: roles.value
            });
        });
};

const deleteRole = () => {
    const index = findIndexById(role.value.id);
    if (index === -1) {
        console.warn("No role found with the given id:", role.value.id);
        return;
    }

    const originalRole = { ...roles.value[index] };
    roles.value.splice(index, 1);

    toast.add({
        severity: "info",
        summary: "Deleting...",
        detail: "Role deletion in progress",
        life: 2000,
    });

    CompanyService.deleteCompany(id)
        .then((response) => {
            // Sikeres törlés esetén értesítés
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Role Deleted",
                life: 3000,
            });
        })
        .error((error) => {
            // Hiba esetén visszaállítjuk a céget az eredeti helyére
            roles.value.splice(index, 0, originalRole);

            // Hibaüzenet megjelenítése a felhasználói felületen
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to delete role",
            });

            // A hiba naplózása a szerver oldalon
            // Ez hasznos lesz a hibakereséshez és az esetleges észleléshez
            // lehetséges problémák az API-val
            ErrorService.logClientError(error, {
                componentName: "DeleteRoleDialog",
                additionalInfo: "Failed to delete a role in the backend",
                category: "Error",
                priority: "medium",
                data: company.value,
            });
        });
};

function openNew() {
    role.value = initialRole();

    submitted.value = false;
    roleDialog.value = true;
};

const hideDialog = () => {
    roleDialog.value = false;
    deleteRoleDialog.value = false;
    deleteSelectedRolesDialog.value = false;
    submitted.value = false;

    // Visszaállítja a validációs objektumot az alapértelmezett állapotába.
    v$.value.$reset();
};

const getModalTitle = () => {
    return role.value.id
        ? trans("roles_edit_title")
        : trans("roles_new_title");
};

const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

const findIndexById = (id) => {
    return roles.value.findIndex((role) => role.id === id);
};

const exportCSV = () => {
    dt.value.exportCSV();
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

function confirmDeleteSelected() {
    deleteSelectedRolesDialog.value = true;
}
const confirmDeleteRole = (data) => {
    role.value = { ...data };

    deleteRoleDialog.value = true;
};



</script>

<template>
    <AppLayout :title="$t('roles')">
        <Head :title="$t('roles')" />

        <Toast />

        <div class="card">
            <Toolbar class="md-6">

                <template #start>
                    <!-- New Button -->
                    <Button
                        :label="$t('add_new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                        :disabled="!props.can.create"
                    />

                    <!-- Delete Selected Button -->
                    <Button
                        :label="$t('delete_selected')"
                        icon="pi pi-trash"
                        severity="secondary"
                        class="mr-2"
                        @click="confirmDeleteSelected"
                        :disabled="
                            !selectedRoles || !selectedRoles.length
                        "
                    />
                </template>

                <template #end>
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
                :value="roles"
                dataKey="id"
                v-model:selection="selectedRoles"
                v-model:filters="filters"
                filterDisplay="menu"
                :paginator="true" :rows="10" sortMode="multiple"
                :loading="loading" stripedRows removableSort
                :globalFilterFields="['name']"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
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
                            {{ $t("roles_title") }}
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
                        icon="pi pi-refresh"
                        class="p-button-text"
                        @click="fetchItems()"
                    />
                </template>
                <template #empty>
                    {{ $t("data_not_found", { data: "role" }) }}
                </template>
                <template #loading>
                    {{ $t("loader", { data: "Role" }) }}
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
                >
                    <template #body="slotProps">
                        {{ slotProps.data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            :placeholder="$t('search_by', {data: 'name'})"
                        />
                    </template>
                </Column>

                <!-- ACTIONS -->
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            :disabled="!props.can.edit"
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            class="mr-2"
                            @click="editRole(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteRole(slotProps.data)"
                            :disabled="!props.can.delete"
                        />
                    </template>
                </Column>

            </DataTable>

        </div>

        <!-- ROLE EDIT DIALOG -->
        <Dialog
            v-model:visible="roleDialog"
            :style="{ width: '550px' }"
            :header="getModalTitle()"
            :modal="true"
        >
            <!-- NAME -->
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
                <FloatLabel>
                    <label for="name" class="block font-bold mb-3">
                        {{ $t("name") }}
                    </label>
                    <InputText
                        id="name"
                        v-model="role.name"
                        fluid
                    />
                </FloatLabel>
                <Message
                    size="small"
                    severity="secondary"
                    variant="simple"
                >
                    {{ $t('enter_role_name') }}
                </Message>
                <small class="text-red-500" v-if="v$.name.$error">
                    {{ $t(v$.name.$errors[0].$message) }}
                </small>
            </div>

            <!-- GUARD NAME -->
            <div class="flex flex-col gap-6" style="margin-top: 17px;">
                <FloatLabel>
                    <label for="guard_name" class="block font-bold mb-3">
                        {{ $t("guard_name") }}
                    </label>
                    <InputText
                        id="guard_name"
                        v-model="role.guard_name"
                        fluid disabled
                    />
                </FloatLabel>
                <Message
                    size="small"
                    severity="secondary"
                    variant="simple"
                >
                    {{ $t('enter_role_guard_name') }}
                </Message>
                <small class="text-red-500" v-if="v$.name.$error">
                    {{ $t(v$.guard_name.$errors[0].$message) }}
                </small>
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
                    @click="saveRole"
                />
            </template>
        </Dialog>

        <!-- ROLE DELETE DIALOG -->
        <Dialog
            v-model:visible="deleteRoleDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <span class="text-surface-500 dark:text-surface-400 block mb-8">
                {{ $t("roles_delete_title") }}
            </span>

            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="role">
                    {{ $t("confirm_delete_2") }} <b>{{ role.name }}</b
                    >?
                </span>
            </div>

            <template #footer>
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteRoleDialog = false"
                    text
                />
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteRole"
                />
            </template>

        </Dialog>

        <!-- ROLES DELETE DIALOG -->
        <Dialog
            v-model:visible="deleteSelectedRolesDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >

            <span class="text-surface-500 dark:text-surface-400 block mb-8">
                {{ $t("roles_delete_title_2") }}
            </span>

            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="role">{{ $t("confirm_delete") }}</span>
            </div>

            <template #footer>
                <!-- "Mégsem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSelectedRolesDialog = false"
                    text
                />
                <!-- Megerősítés gomb -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSelectedRoles"
                />
            </template>

        </Dialog>

    </AppLayout>
</template>
