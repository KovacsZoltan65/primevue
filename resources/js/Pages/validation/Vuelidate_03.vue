<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, maxLength, minLength, helpers } from '@vuelidate/validators';

import { validationRules } from '../../validationRules';

//onMounted(() => {
//    console.log('onMounted');
//    console.log('validationRules.minStringLength', validationRules.minStringLength);
//    console.log('validationRules.maxStringLength', validationRules.maxStringLength);
//});

const form = reactive({
    fieldName: '',
});

const serverErrors = ref({});

const rules = {
    fieldName: {
        required: helpers.withMessage('A mező megadása kötelező', required),
        minLength: helpers.withMessage(({ $params }) => `A szövegnek legalább ${$params.min} karakter hosszúnak kell lennie.`, minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage(({ $params }) => `A szövegnek legfeljebb ${$params.max} karakter hosszú lehet.`, maxLength(validationRules.maxStringLength)),
    }
};

const v$ = useVuelidate(rules, form);

import ValidationService from '@/service/ValidationService';
const submitForm = async () => {
    //
    const result = await v$.value.$validate();
    if (result) {
        try {
            await ValidationService.submitForm();
            serverErrors.value = [];
        } catch(error) {
            if (error.response && error.response.status === 422) {
                serverErrors.value = error.response.data.errors;
            }
            console.error("submitForm API Error:", error);
        }
    }

}

</script>

<template>

    <form @submit.prevent="submitForm">
        <div>
            <label for="fieldName">Field Name</label>
            <input v-model="form.fieldName" type="text" id="fieldName" />

            <span v-if="serverErrors.field_name">{{ serverErrors.field_name[0] }}</span>

            <span v-if="v$.fieldName.$error && v$.fieldName.$invalid">
                {{ v$.fieldName.$errors[0].$message }}
            </span>
        </div>

        <button type="submit">Submit</button>
    </form>

  </template>
