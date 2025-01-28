<script setup>
import { ref, watch } from "vue";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { Button, Dialog, FloatLabel, InputText, Message } from "primevue";

const props = defineProps({
    visible: { type: Boolean, required: true },
    dialogTitle: { type: String, default: "" },
    company: { type: Object, required: true },
});

const localVisible = ref(props.visible);

const localCompany = ref({ ...props.company });

const emit = defineEmits(['save-company', 'update:visible']);

const v$ = useVuelidate({
  company: {
    name: { required },
  },
}, props);

const saveCompany = () => {
    console.log("CompanyDialog.vue saveCompany");
    emit("save-company", props.company); // Emit the save event with the company data
    emit("update:visible", false); // Close the dialog after saving
};

const onClose = () => {
    console.log("onClose");
    emit("update:visible", false); // Ensure the dialog closes
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
                        v-model="props.company.name"
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