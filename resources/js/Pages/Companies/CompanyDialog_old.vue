<script setup>
import { Button, Dialog, FloatLabel, InputText, Message, Select } from 'primevue';
import { ref, watch } from 'vue';

const props = defineProps({
    visible: Boolean,
    header: String,
    company: Object,
    countries: Array,
    cities: Array,
    v$: Object
});

const emit = defineEmits(['hide-dialog', 'save-company', 'update:visible']);

// Local copy of the `visible` prop
const localVisible = ref(props.visible);
// Local copy of the `company` prop
const localCompany = ref({ ...props.company });

watch(
    () => props.visible,
    (newValue) => {
        localVisible.value = newValue;
    }
);

// Emit changes to the parent
watch(
    () => localVisible.value,
    (newValue) => {
        emit('update:visible', newValue);
        if ( !newValue ) {
            emit('hide-dialog'); // Trigger hide-dialog when dialog is closed
        }
    }
);

watch(
    () => props.company,
    (newCompany) => {
        localCompany.value = { ...newCompany };
    }
);

const saveCompany = () => {
    emit('save-company', localCompany.value);
    emit('hide-dialog');
};

//const saveCompanyAndNew = () => {
//    emit('company-saved', localCompany.value);
//    localCompany.value = {};
//};

</script>

<template>
    <Dialog
        v-model:visible="localVisible"
        :style="{ width: '550px' }"
        :header="header"
        :modal="true"
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
                    />
                </FloatLabel>
                <Message
                    size="small"
                    severity="secondary"
                    variant="simple"
                >
                    {{ $t('enter_company_name') }}
                </Message>
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
                        fluid disabled
                    />
                </FloatLabel>
                
            </div>

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
                    />
                </FloatLabel>
                <Message
                    size="small"
                    severity="secondary"
                    variant="simple"
                >
                    {{ $t('enter_tax_id') }}
                </Message>
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
                    />
                </FloatLabel>
                <Message
                    size="small"
                    severity="secondary"
                    variant="simple"
                >
                    {{ $t('enter_registration_number') }}
                </Message>
            </div>

            <!-- COUNTRY & CITY -->
            <div class="flex flex-wrap gap-4">
                <!-- COUNTRY -->
                <div class="flex flex-col grow basis-0 gap-2">
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
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('select_country') }}
                    </Message>
                </div>

                <!-- CITY -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <FloatLabel>
                        <label for="city_id" class="block font-bold mb-3">
                            {{ $t("city") }}
                        </label>
                        <Select
                            id="city_id"
                            v-model="localCompany.city_id"
                            :options="cities"
                            optionLabel="name"
                            optionValue="id"
                            :placeholder="$t('city')"
                            fluid
                        />
                    </FloatLabel>
                    <Message
                        size="small"
                        severity="secondary"
                        variant="simple"
                    >
                        {{ $t('select_city') }}
                    </Message>
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
                    />
                </FloatLabel>
                <Message
                    size="small"
                    severity="secondary"
                    variant="simple"
                >
                    {{ $t('enter_address') }}
                </Message>
            </div>

        </div>

        <template #footer>
            <Button
                :label="$t('cancel')"
                icon="pi pi-times"
                text
                @click="$emit('hide-dialog')"
            />
            <Button
                :label="$t('save')"
                icon="pi pi-check"
                @click="saveCompany"
            />
        </template>
        
    </Dialog>
</template>