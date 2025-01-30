<script setup>
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import axios from 'axios';

const isDeleting = ref(false);
const toast = useToast();
const confirm = useConfirm();

const emit = defineEmits(['deleted']);

const deleteCompany = (companyId, companyName) => {
    confirm.require({
        message: `Biztosan törölni szeretnéd a(z) ${companyName} céget?`,
        header: 'Megerősítés',
        icon: 'pi pi-exclamation-triangle',
        accept: async () => {
            isDeleting.value = true;
            try {
                await axios.delete(`/api/companies/${companyId}`);
                toast.add({ severity: 'success', summary: 'Sikeres törlés', detail: `A(z) ${companyName} cég törölve lett.`, life: 3000 });
                emit('deleted');
            } catch (error) {
                toast.add({ severity: 'error', summary: 'Hiba történt', detail: `Nem sikerült törölni a(z) ${companyName} céget.`, life: 3000 });
            } finally {
                isDeleting.value = false;
            }
        }
    });
};
</script>

<template>
    <div>
        <!-- Itt lehet egy gomb vagy esemény, amely meghívja a deleteCompany függvényt -->
    </div>
</template>
