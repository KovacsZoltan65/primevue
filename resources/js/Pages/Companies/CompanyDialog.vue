<script setup>
import { ref, watch, reactive } from "vue";
import { Button, Dialog, FloatLabel, InputText, Message, Select } from "primevue";

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
    name: {
        required: helpers.withMessage(trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
    directory: { required: helpers.withMessage(trans("validate_directory"), required), },
    tax_id: { required: helpers.withMessage(trans("validate_tax_id"), required), },
    registration_number: { required: helpers.withMessage(trans("validate_registration_number"), required), },
    address: { required: helpers.withMessage(trans("validate_address"), required), },
    country_id: { required: helpers.withMessage(trans("validate_city_id"), required), },
    city_id: { required: helpers.withMessage(trans("validate_country_id"), required), },
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

    v$.value.$reset();

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
            <div class="flex flex-col grow basis-0 gap-2">
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
                    size="small" 
                    severity="secondary" 
                    variant="simple"
                >
                    {{ $t('enter_company_name') }}
                </Message>
                <small class="text-red-500" v-if="v$.name.$error">
                    {{ v$.name.$errors[0].$message }}
                </small>
            </div>

            <!-- DIRECTORY -->
            <div class="flex flex-col grow basis-0 gap-2">
                <FloatLabel variant="on">
                    <label for="directory" class="block font-bold mb-3">
                        {{ $t("directory") }}
                    </label>
                    <InputText
                        id="directory"
                        v-model="localCompany.directory"
                        fluid
                        :class="{'p-invalid': v$.directory.$error}"
                    />
                </FloatLabel>
                <Message 
                    size="small" 
                    severity="secondary" 
                    variant="simple"
                >
                    {{ $t('enter_directory') }}
                </Message>
                <small class="text-red-500" v-if="v$.directory.$error">
                    {{ v$.directory.$errors[0].$message }}
                </small>
            </div>

            <!-- TAX ID & REG NO -->
            <div class="flex flex-wrap gap-4">
                <!-- TAX ID -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="tax_id" class="block font-bold mb-3">
                            {{ $t("tax_id") }}
                        </label>
                        <InputText
                            id="tax_id"
                            v-model="localCompany.tax_id"
                            fluid
                            :class="{'p-invalid': v$.tax_id.$error}"
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_tax_id') }}
                    </Message>
                    <small class="text-red-500" v-if="v$.tax_id.$error">
                        {{ $t(v$.tax_id.$errors[0].$message) }}
                    </small>
                </div>

                <!-- REGISTRATION NUMBER -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel variant="on">
                        <label for="registration_number" class="block font-bold mb-3">
                            {{ $t("registration_number") }}
                        </label>
                        <InputText
                            id="registration_number"
                            v-model="localCompany.registration_number"
                            fluid
                            :class="{'p-invalid': v$.registration_number.$error}"
                        />
                    </FloatLabel>

                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('enter_registration_number') }}
                    </Message>

                    <small class="text-red-500" v-if="v$.registration_number.$error">
                        {{ $t(v$.registration_number.$errors[0].$message) }}
                    </small>
                </div>
            </div>

            <!-- COUNTRY & CITY -->
            <div class="flex flex-wrap gap-4">
                <!-- COUNTRY -->
                <div class="flex flex-col grow basis-0 gap-2 field">
                    <FloatLabel>
                        <label for="country_id" class="block font-bold mb-3">
                            {{ $t("country") }}
                        </label>
                        <Select
                            id="country_id"
                            v-model="localCompany.country_id"
                            :options="props.countries"
                            optionLabel="name"
                            optionValue="id"
                            :placeholder="$t('country')"
                            fluid
                            :class="{'p-invalid': v$.country_id.$error}"
                        />
                    </FloatLabel>

                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('select_country') }}
                    </Message>

                    <small class="text-red-500" v-if="v$.country_id.$error">
                        {{ $t(v$.country_id.$errors[0].$message) }}
                    </small>
                </div>

                <!-- CITY -->
                <div class="flex flex-col grow basis-0 gap-2 field">
                    <FloatLabel>
                        <label for="city_id" class="block font-bold mb-3">
                            {{ $t("city") }}
                        </label>
                        <Select
                            id="city_id"
                            v-model="localCompany.city_id"
                            :options="props.cities"
                            optionLabel="name"
                            optionValue="id"
                            :placeholder="$t('city')"
                            fluid
                            :class="{'p-invalid': v$.city_id.$error}"
                        />
                    </FloatLabel>

                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('select_city') }}
                    </Message>

                    <small class="text-red-500" v-if="v$.city_id.$error">
                        {{ $t(v$.city_id.$errors[0].$message) }}
                    </small>
                </div>
            </div>

            <!-- ADDRESS -->
            <div class="flex flex-col grow basis-0 gap-2">
                <FloatLabel variant="on">
                    <label for="address" class="block font-bold mb-3">
                        {{ $t("address") }}
                    </label>
                    <InputText
                        id="address"
                        v-model="localCompany.address"
                        fluid
                        :class="{'p-invalid': v$.address.$error}"
                    />
                </FloatLabel>

                <Message
                    size="small"
                    severity="secondary"
                    variant="simple"
                >
                    {{ $t('enter_address') }}
                </Message>

                <small class="text-red-500" v-if="v$.address.$error">
                    {{ $t(v$.address.$errors[0].$message) }}
                </small>
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
