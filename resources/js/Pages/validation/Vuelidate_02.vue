<script setup>

/**
 * https://www.youtube.com/watch?v=7alh1KowAEI
 */

import { computed, reactive } from 'vue';
//import BaseInput from './BaseInput.vue';

// Validation with Vuelidate
import useVuelidate from '@vuelidate/core';
import { required, email, minLength, sameAs, helpers } from '@vuelidate/validators';
import InputText from 'primevue/inputtext';

const formData = reactive({
    username: '',
    email: '',
    password: '',
    confirmPassword: ''
});

// -= Egyedi szabályok =-
const containsUser = (value) => {
    console.log('value', value);
    return value.includes('user');
}
// ==================

// -= Szabályok =-
/*
const rules = {
    username: {
        required, minLength: minLength(10),
        containsUser: helpers.withMessage('a mezőnek tartalmaznia kell az "user" kifejezést', containsUser),
    },
    email: { required, email },
    password: { required },
    confirmPassword: { required, sameAs: sameAs(formData.password) },
};
const v$ = useVuelidate(rules, formData);
*/
const rules2 = computed(() => {
    return {
        username: {
            required, 
            minLength: minLength(10),
            containsUser: helpers.withMessage('a mezőnek tartalmaznia kell az "user" kifejezést', containsUser),
        },
        email: { required, email },
        password: { required },
        confirmPassword: { required, sameAs: sameAs(formData.password) },
    };
});
const v$ = useVuelidate(rules2, formData);
// =================

const submitForm = async () => {
    const result = await v$.value.$validate();
    if( result ){
        alert('success, form submitted!');
    }else{
        alert('error, form not submitted!');
    }
};

</script>

<template>
    <div class="pt-20 h-screen bg-[#34495E] text-white">
        <div class="max-w-screen-sm mx-auto bg-[#2D3741] p-10 rounded-md shadow-md">
            <h1 class="mb-4 text-3xl">Create User</h1>
            <form @submit.prevent="submitForm" class="flex flex-col gap-6">

                <div>
                    <label for="name" class="block font-bold mb-3">User Name</label>
                    <InputText id="username" v-model="formData.username" />
                    <div>
                        <span v-for="error in v$.username.$errors" :key="error.$uid" class="text-red-500">
                            {{ error.$message }}
                        </span>
                    </div>
                </div>
<!--                
                <BaseInput v-model="formData.username" label="Username" type="text" />
-->
                <div>
                    <label for="email" class="block font-bold mb-3">Email</label>
                    <InputText id="email" v-model="formData.email" />
                    <div>
                        <span v-for="error in v$.email.$errors" :key="error.$uid" class="text-red-500">
                            {{ error.$message }}
                        </span>
                    </div>
                </div>
<!--
                <BaseInput v-model="formData.email" label="Email" type="email" />
-->
                <div>
                    <label for="password" class="block font-bold mb-3">Password</label>
                    <InputText type="password" id="password" v-model="formData.password" />
                    <div>
                        <span v-for="error in v$.password.$errors" :key="error.$uid" class="text-red-500">
                            {{ error.$message }}
                        </span>
                    </div>
                </div>
<!--
                <BaseInput v-model="formData.password" label="Password" type="password" />
-->
                <div>
                    <label for="password" class="block font-bold mb-3">Confirm Password</label>
                    <InputText type="password" id="confirm_password" v-model="formData.confirmPassword" />
                    <div>
                        <span v-for="error in v$.confirmPassword.$errors" :key="error.$uid" class="text-red-500">
                            {{ error.$message }}
                        </span>
                    </div>
                </div>
<!--
                <BaseInput v-model="formData.confirmPassword" label="Confirm Password" type="password" />
-->

                <button type="submit" class="self-start bg-[#476583] px-3 py-2 rounded-md">Create User</button>
            </form>

            <div class="mt-4">
                <p class="text-red-500">Errors:</p>
                <span v-for="error of v$.$errors" :key="error.$uid">
                    {{ error.$property }} - {{ error.$message }},
                </span>
            </div>
        </div>
    </div>
</template>