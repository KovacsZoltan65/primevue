<script setup>
import { ref, watch, reactive } from "vue";
import { Button, Dialog, FloatLabel, InputText, Message } from "primevue";

// i18n
import { trans } from "laravel-vue-i18n";

// validation
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import validationRules from '@/Validation/ValidationRules.json';

const props = defineProps({
    visible: { type: Boolean, required: true },
    dialogTitle: { type: String, default: "" },
    company: { type: Object, required: true },
});

//const localVisible = ref(props.visible);
// Helyi másolat a cég adataiból, hogy a validáció megfelelően működjön
//const defaultCompany = { name: "" };
//const localCompany = ref({ ...defaultCompany });

const emit = defineEmits(['save-company', 'update:visible']);

// Alapértelmezett üres objektum
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

// Helyi másolat a cég adataiból, hogy a validáció megfelelően működjön
const localCompany = reactive({ ...defaultCompany });

// Figyeljük a props.company változását és frissítjük a localCompany-t
watch(
    () => props.company,
    (newCompany) => {
        Object.assign(localCompany, newCompany?.id ? newCompany : { ...defaultCompany });
    },
    { deep: true, immediate: true }
);

// Figyeljük a visible változást, ha új elem, akkor reseteljük az adatokat
watch(
    () => props.visible,
    (newVisible) => {
        if (newVisible && !props.company?.id) {
            Object.assign(localCompany, { ...defaultCompany });
        }
    }
);

// Validációs szabályok beállítása
const rules = {
    name: { required },
};

const v$ = useVuelidate(rules, localCompany);

const saveCompany = async () => {
    v$.value.$touch();
    if (v$.value.$invalid) {
        console.log("Validation failed");
        return;
    }

    console.log("CompanyDialog.vue saveCompany");
    emit("save-company", { ...localCompany });
    emit("update:visible", false);
};

const onClose = () => {
    console.log("onClose");
    emit("update:visible", false);
};

</script>

<template>
    <Dialog
        v-model:visible="props.visible"
        :style="{ width: '550px' }"
        :header="dialogTitle"
        :modal="true"
        @hide="onClose"
    >
        <div class="flex flex-col gap-6" style="margin-top: 17px;">
            <!-- NAME -->
            <div class="flex flex-col grow basis-0 gap-2 field">
                <FloatLabel variant="on">
                    <label for="name" class="block font-bold mb-3">
                        {{ $t("name") }}
                    </label>
                    <InputText
                        id="name"
                        v-model="localCompany.name"
                        fluid
                        :class="{'p-invalid': v$.name.$error}"
                    />
                </FloatLabel>
                <Message v-if="v$.name.$error" size="small" severity="error" variant="simple">
                    {{ $t('validation.required') }}
                </Message>
            </div>
        </div>

        <template #footer>
            <Button
                :label="$t('cancel')"
                icon="pi pi-times"
                text
                @click="onClose"
            />
            <Button
                :label="$t('save')"
                icon="pi pi-check"
                @click="saveCompany"
            />
        </template>
    </Dialog>
</template>

<style scoped>
.field {
    margin-bottom: 1rem;
}
</style>
