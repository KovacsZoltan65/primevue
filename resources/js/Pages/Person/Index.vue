<script setup>
import PersonService from "@/service/PersonService";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
import { createId } from "@/helpers/functions";

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

    /*
    deletePersonDialog.value = true;

    try {
        await PersonService.deletePerson(person.value.id);

        const index = findIndexById(subdomain.value.id);
        persons.value.splice(index, 1);

        hideDialog();

        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Person Deleted",
            life: 3000,
        });
    } catch(error) {
        console.error("deletePerson API Error:", error);

        toast.add({
            severity: "error",
            summary: "Hiba",
            detail: "Nem sikerült frissíteni a személyt",
            life: 3000,
        });
    } finally {
        deletePersonDialog.value = false;
    }
    */
}

const confirmDeletePersons = () => {
    selectedPersons.value = true;
}

const deletePersons = async () => {
    const aa = PersonService.deletePersons(selectedPersons.value);
}

const hideDialog = () => {
    personDialog.value = false;
    submitted.value = false;
    v$.value.$reset();
}

const hideDeletePersonDialog = () => {
    deletePersonDialog.value = false;
    submitted.value = false;
    v$.value.$reset();
}

const hideDeletePersonsDialog = () => {
    deleteSelectedPersonsDialog.value = false;
    submitted.value = false;
    v$.value.$reset();
}

const getBools = () => {
    return [
        {
            label: trans("inactive"),
            value: 0,
        },
        {
            label: trans("active"),
            value: 1,
        },
    ];
};

const fetchItems = () => {
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

initFilters();

onMounted(() => {
    fetchItems();
});

</script>

<template></template>