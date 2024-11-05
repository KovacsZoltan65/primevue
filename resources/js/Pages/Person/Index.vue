<script setup>
import PersonService from "@/service/PersonService";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
import { createId } from "@/helpers/functions";
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "primevue/datatable";

const toast = useToast();
const dt = ref();
const loading = ref(true);
const persons = ref([]);
const person = ref({});
//const originalPerson = ref({});

const personDialog = ref(false);
const deleteSelectedPersonsDialog = ref(false);
const deletePersonDialog = ref(false);
const selectedPersons = ref([]);
const filters = ref({});
const submitted = ref(false);

const props = defineProps({
    countries: {
        type: Object,
        default: () => {},
    },
    regions: {
        type: Object,
        default: () => {},
    }
});

const rules = {
    name: {},
    email: {},
    password: {},
    birthdate: {},
    language: {}
}

const v$ = useVuelidate(rules, person);

const initialPerson = () => {
    return person;
}

const confirmDeleteSelected = () => {
    deleteSelectedPersonsDialog.value = true;
}

const openNew = () => {
    person.value = { ...initialPerson };
    submitted.value = false;
    personDialog.value = true;
}

const editPerson = (person) => {
    person.value = { ...person };
    //originalPerson.value = { ...person };
    personDialog.value = true;
}

const savePerson = async () => {
    const result = await v$.value.$validate();
    if( result ) {
        submitted.value = true;

        if( person.value.id ) {
            updatePerson();
        } else {
            createPerson();
        }
    } else {
        console.log('ERROR');
    }
}

const createPerson = async () => {
    // Optimista frissítés - ideiglenesen hozzáadjuk a person-t, amíg a válaszra várunk
    // ideiglenes id-vel.
    //const tempPerson = { ...person.value, id: Date.now() };
    const tempPerson = { ...person.value, id: createId() };
    persons.value.push(tempPerson);

    try {
        // Mentés
        const response = await PersonService.createPerson(person.value);

        // A tempPerson helyére beszúrjuk az API által visszaadott adatokat
        const index = persons.value.findIndex(p => p.id === tempPerson.id);
        persons.value.splice(index, 1, response.data);

        hideDialog();

        toast.add({
            severity: "success",
            summary: "Sikeres létrehozás",
            detail: "Személy létrehozva",
            life: 3000,
        });

    } catch ( error ) {
        // Ha hiba történt, eltávolítjuk a tempPerson-t
        persons.value = persons.value.filter(p => p.id !== tempPerson.id);
        console.error("createPerson API Error:", error);

        toast.add({
            severity: "error",
            summary: "Hiba",
            detail: "Nem sikerült létrehozni a személyt",
            life: 3000,
        });
    }finally {
        //
    }
};

const updatePerson = async () => {
    // mentjük az eredeti adatokat optimista frissítéshez
    const index = findIndexById(person.value.id);
    const originalPerson = persons.value[index];

    const hasChanges = JSON.stringify(person.value) !== JSON.stringify(originalPerson);

    // Nincs változás, nincs módosítás.
    if( !hasChanges ) {
        toast.add({
            severity: "info",
            summary: "Nincs változás",
            detail: "Nincs módosítás",
            life: 3000,
        });
        return;
    }

    try {
        // optimista frissítés
        persons.value.splice(index, 1, person.value);
        hideDialog();
        const response = await PersonService.updatePerson(person.value.id, person.value);
        // frissítjük a pontos válasszal
        persons.value.splice(index, 1, response.data);

        toast.add({
            severity: "success",
            summary: "Sikeres frissítés",
            detail: "Személy frissítve",
            life: 3000,
        });

    } catch( error ) {
        // visszaállítjuk az eredeti adatokat
        persons.value.splice(index, 1, originalData);
        console.error("updatePerson API Error:", error);
        toast.add({
            severity: "error",
            summary: "Hiba",
            detail: "Nem sikerült frissíteni a személyt",
            life: 3000,
        });
    } finally {
        //
    }
};

const findIndexById = (id) => {
    return persons.value.findIndexById(() => person.value.id === id);
}

const confirmDeletePerson = (data) => {
    person.value = { ...data };

    deletePersonDialog.value = true;
}

const deletePerson = async () => {

    deletePersonDialog.value = false;
    // Keressük meg az eltávolítandó elem indexét
    const index = findIndexById(person.value.id);
    // Elmentjük az eredeti adatokat optimista frissítéshez
    const originalPerson = persons.value[index];
    // Ideiglenesen eltávolítjuk a személyt
    persons.value.splice(index, 1);

    try {
        await PersonService.deletePerson(person.value.id);

        hideDialog();

        toast.add({
            severity: "success",
            summary: "Sikeres törlés",
            detail: "Személy törölve",
            life: 3000,
        });

    } catch(error) {
        // Hiba esetén visszaállítjuk az eltávolított személyt
        persons.value.splice(index, 0, originalPerson);
        console.error("deletePerson API Error:", error);

        toast.add({
            severity: "error",
            summary: "Hiba",
            detail: "Nem sikerült törölni a személyt",
            life: 3000,
        });
    } finally {
        deletePersonDialog.value = false;
    }
}

const confirmDeletePersons = () => {
    selectedPersons.value = true;
}

const deletePersons = async () => {
    const aa = PersonService.deletePersons(selectedPersons.value);
}

const hideDialog = (dialogType) => {
    dialogType.value = false;
    submitted.value = false;
    v$.value.$reset();
};

const hideEditDialog = () => {
    hideDialog(personDialog);
}

const hideDeletePersonDialog = () => {
    hideDialog(deletePersonDialog);
}

const hideDeletePersonsDialog = () => {
    hideDialog(deleteSelectedPersonsDialog);
}

const getBools = () => {
    return [
        { label: trans("inactive"), value: 0, },
        { label: trans("active"), value: 1, },
    ];
};

const fetchItems = () => {
    loading.value = true;

    try {
        const response = PersonService.getPersons();
        persons.value = response.data.data;
    } catch( error ) {
        console.error("getPersons API Error:", error);
    } finally {
        loading.value = false;
    }
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        name: { value: null, matchMode: FilterMatchMode.CONTAINS },
        email: { value: null, matchMode: FilterMatchMode.CONTAINS },
        birthdate: { value: null, matchMode: FilterMatchMode.CONTAINS },
        language: { value: null, matchMode: FilterMatchMode.CONTAINS }
    }
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const onUpload = () => {
    toast.add({
        severity: 'info',
        summary: 'Success',
        detail: 'File Uploaded',
        life: 3000
    });
};

initFilters();

onMounted(() => {
    fetchItems();
});

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
                        @click="confirmDeleteSelected"
                        :disabled="
                            !selectedPersons || !selectedPersons.length
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
                v-model:selection="selectedPersons"
                v-model:filters="filters"
                :value="persons"
                dataKey="id"
                :paginator="true"
                :rows="10"
                sortMode="multiple"
                :filters="filters"
                filterDisplay="menu"
                :globalFilterFields="['name', 'email', 'birthdate', 'language']"
                :loading="loading"
                stripedRows removableSort
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} subdomains"
            >
                <template #header></template>
                <template #paginatorstart></template>
                <template #enpty></template>
                <template #loading></template>
            </DataTable>

        </div>

    </AppLayout>
</template>
