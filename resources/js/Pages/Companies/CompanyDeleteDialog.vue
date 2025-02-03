<script setup>
import { Button, Dialog } from 'primevue';

// i18n
import { trans } from "laravel-vue-i18n";
import { reactive, watch } from 'vue';

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

const deleteCompany = () => {};

const onClose = () => {
    console.log('CompanyDeleteDialog onClose');

    emit('update:visible', false);
};

</script>

<template>
    <Dialog
        v-model:visible="props.visible"
        header="Cég törlése" modal
    >
        <p>
            Biztosan törölni akarod?
        </p>
        <template #footer>

            <!-- CANCEL -->
            <Button
                label="Mégse"
                icon="pi pi-times"
                @click="onClose"
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
