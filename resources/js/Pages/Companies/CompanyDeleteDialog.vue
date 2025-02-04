<script setup>
import { Button, Dialog } from 'primevue';

// i18n
import { trans } from "laravel-vue-i18n";
import { reactive, watch } from 'vue';
import CompanyService from '@/service/CompanyService';

const emit = defineEmits(['delete-company', 'update:visible']);

const props = defineProps({
    visible: { type: Boolean, required: true },
    dialogTitle: { type: String, default: "" },
    company: { type: Object, required: true }
});

const defaultCompany = {
    id: null,
    name: "",
    directory: "",
    country_id: null,
    city_id: null,
    registration_number: null,
    tax_id: null,
    address: null,
    active: 1,
};

const localCompany = reactive({ ...defaultCompany });

watch(
    () => props.company,
    (newCompany) => {
        Object.assign(localCompany, newCompany?.id ? newCompany : { ...defaultCompany });
    },
    { deep: true, immediate: true }
);

watch(
    () => props.visible,
    (newVisible) => {
        if (newVisible && !props.company?.id) {
            Object.assign(localCompany, { ...defaultCompany });
        }
    },
    { deep: true, immediate: true }
);

const deleteCompany = () => {
    console.log("CompanyDialog.vue deleteCompany");

    const originalCompany = { ...props.company };

    CompanyService.deleteCompany(props.company.id)
    .then((response) => {
        emit("delete-company", { ...props.company });
        emit("update:visible", false);
    })
    .catch((error) => {
        //
    });
};

const onClose = () => {
    console.log('CompanyDeleteDialog onClose');

    emit('update:visible', false);
};

</script>

<template>
    <Dialog
        v-model:visible="props.visible"
        header="Cég törlése" modal
        :closable="false"
    >
        <p v-if="company">
            Biztosan törölni szeretnéd a(z) <strong>{{ company.name }}</strong> céget?
        </p>
        <template #footer>

            <!-- CANCEL -->
            <Button
                label="Mégsem"
                icon="pi pi-times"
                @click="onClose" text
            />

            <!-- DELETE -->
            <Button
                label="Törlés"
                icon="pi pi-check"
                @click="deleteCompany"
            />

        </template>
    </Dialog>
</template>
