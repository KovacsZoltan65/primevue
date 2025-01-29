<script setup>
import { ref, watch } from "vue";
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

const localVisible = ref(props.visible);
// Helyi másolat a cég adataiból, hogy a validáció megfelelően működjön
const defaultCompany = { name: "" };
const localCompany = ref({ ...defaultCompany });

const emit = defineEmits(['save-company', 'update:visible']);

// Figyeljük a props.company változását és frissítjük a localCompany-t
watch(
    () => props.company,
    (newCompany) => {
        Object.assign(localCompany, newCompany);
    }
);

// Figyeljük a visible változást, és alaphelyzetbe állítjuk a formot új elem esetén
watch(
    () => props.visible,
    (newVisible) => {
        if (newVisible) {
            Object.assign(
                localCompany, 
                props.company?.id ? props.company : defaultCompany
            );
        }
    }
);

// Validációs szabályok helyes beállítása
const rules = {
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
    },
};

const v$ = useVuelidate(rules, localCompany);

const saveCompany = async () => {

    v$.value.$touch();
    if( v$.value.$invalid ) {
        console.log("Validation failed");
        return;
    } else {
        console.log("CompanyDialog.vue saveCompany");

        emit("save-company", props.company); // Emit the save event with the company data
        emit("update:visible", false); // Close the dialog after saving
    }
    
};

const onClose = () => {
    console.log("onClose");
    v$.value.$reset();
    emit("update:visible", false); // Ensure the dialog closes
};

const onHide = () => {
    console.log("onHide");
};

</script>

<template>
    <Dialog
        v-model:visible="props.visible"
        :style="{ width: '550px' }"
        :header="dialogTitle"
        :modal="true"
        @hide="onHide"
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
                <Message 
                    v-if="v$.name.$error" 
                    size="small" 
                    severity="error" 
                    variant="simple"
                >
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