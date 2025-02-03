<script setup>
import { ref, watch } from 'vue';
import { useToast } from 'primevue/usetoast';
import { Button, Dialog } from 'primevue';

const props = defineProps({
    entityName: String, // Az entitás megnevezése (pl. "cég")
    deleteEndpoint: String, // Az API végpont az entitás törléséhez
    selectedCompany: { type: Object, default: () => null } // Alapértelmezett érték null
});

const emit = defineEmits(['deleted', 'close']);

const toast = useToast();
const isDeleting = ref(false);
const isDialogVisible = ref(false);

watch(
    () => props.selectedCompany,
    (newValue) => {
        isDialogVisible.value = !!newValue;
    },
    { immediate: true }
);

const closeDialog = () => {
    isDialogVisible.value = false;
    emit('close', null); // Null értéket küldünk, hogy az Index.vue kezelni tudja
};

const deleteCompany = async () => {
    if (!props.selectedCompany) return;
    isDeleting.value = true;
    try {
        // await axios.delete(`/api/companies/${props.selectedCompany.id}`);
        toast.add({
            severity: 'success',
            summary: 'Sikeres törlés',
            detail: `A(z) ${props.selectedCompany.name} cég törölve lett.`,
            life: 3000
        });
        emit('deleted');
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Hiba történt',
            detail: `Nem sikerült törölni a(z) ${props.selectedCompany.name} céget.`,
            life: 3000
        });
    } finally {
        isDeleting.value = false;
        closeDialog();
    }
};
</script>

<template>
    <Dialog
        v-model:visible="isDialogVisible"
        header="Cég törlése" modal
    >
        <p v-if="selectedCompany">
            Biztosan törölni szeretnéd a(z) <strong>{{ selectedCompany.name }}</strong> céget?
        </p>
        <template #footer>
            <Button
                label="Mégse"
                icon="pi pi-times"
                @click="closeDialog"
            />
            <Button
                label="Törlés"
                icon="pi pi-check"
                severity="danger"
                :loading="isDeleting"
                @click="deleteCompany"
            />
        </template>
    </Dialog>
</template>
