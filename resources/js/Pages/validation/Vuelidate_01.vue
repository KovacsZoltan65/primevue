<template>
    <div class="root">
        <h2>Create Account</h2>

        <p v-for="error of v$.$errors" :key="error.$uid">
            <strong>{{ error.$validator }}</strong>
            <small> on property</small>
            <strong>{{ error.$property }}</strong>
            <small> says:</small>
            <strong>{{ error.$message }}</strong>
        </p>

        <p>
            <input type="text" placeholder="Email" v-model="state.email" />
        <!--
            <span v-if="v$.email.$error">
                {{ v$.email.$errors[0].$message }}
            </span>
        -->
        </p>
        <p>
            <input type="password" placeholder="Password" v-model="state.password.password" />
        <!--
            <span v-if="v$.password.password.$error">
                {{ v$.password.password.$errors[0].$message }}
            </span>
        -->
        </p>
        <p>
            <input type="password" placeholder="Confirm" v-model="state.password.confirm" />
        <!--
            <span v-if="v$.password.confirm.$error">
                {{ v$.password.confirm.$errors[0].$message }}
            </span>
        -->
        </p>

        <button type="submit" @click="submitForm">Submit</button>
    </div>

</template>

<script>
/**
 * https://www.youtube.com/watch?v=2BR6Vvjw3wQ&t=8s
 */

import { reactive, computed } from 'vue';
import useVuelidate from '@vuelidate/core';
import { required, email, minLength, sameAs, helpers } from '@vuelidate/validators';

export default {
    setup (){
        const state = reactive({
            email: '',
            password: {
                password: '',
                confirm: ''
            }
        });

        const mustBeLearnVue = (value) => value.includes('learnvue');

        const rules = computed(() => {
            return {
                email: {
                    required, email, 
                    mustBeLearnVue: helpers.withMessage('Must be learnvue', mustBeLearnVue),
                },
                password: {
                    password: { required, minLenght: minLength(6) },
                    confirm: { required, sameAs: sameAs(state.password.password) }
                }
            };
        });

        const v$ = useVuelidate(rules, state);

        return {
            state,
            v$,
        };
    },
    data(){
        return {
            v$: useVuelidate(),
            email: '',
            password: {
                password: '',
                confirm: ''
            }
        }
    },
    methods: {
        submitForm(){
            this.v$.$validate();
            if( !this.v$.$error ){
            //    alert('Form submitted');
            }else{
                console.log(this.v$.$errors);
                console.log(this.v$.$errors[0]);
            //    alert('Form failed validation');
            }
        }
    }
}
</script>