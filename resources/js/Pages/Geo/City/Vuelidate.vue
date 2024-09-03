<script setup>
import { reactive, computed } from 'vue';
import useVuelidate from '@vuelidate/core';
import { required, email, minLength, sameAs, helpers } from '@vuelidate/validators';

/**
 * Az űrlap adatai.
 * 
 * @property {string} email Az email cím.
 * @property {Object} password A jelszóval kapcsolatos adatok.
 * @property {string} password.password A megadott jelszó.
 * @property {string} password.confirm A megadott jelszó megerősít se.
 */
const state = reactive({
    email: '',
    password: {
        password: '',
        confirm: ''
    }
});

/**
 * Validációs szabály, amely ellenőrzi, hogy a megadott email cím tartalmazza-e a "learnvue" karakterláncot.
 * 
 * @param {string} value Az ellenőrzendő érték.
 * @returns {boolean} Igaz, ha a megadott email cím tartalmazza a "learnvue" karakterláncot, ellenkez  esetben hamis.
 */
const mustBeLearnVue = (value) => value.includes('learnvue');

/**
 * Validációs szabályok.
 * 
 * @returns {Object} Egy object, amely tartalmazza a validációs szabályokat.
 */
const rules = computed(() => ({
    /**
     * Email cím validációs szabályai.
     * 
     * @property {Function} required A mező nem lehet üres.
     * @property {Function} email A mezőnek email cím formátumúnak kell lennie.
     * @property {Function} mustBeLearnVue A mezőnek tartalmaznia kell a "learnvue" karakterláncot.
     */
    email: {
        required,
        email,
        mustBeLearnVue: helpers.withMessage('Must be learnvue', mustBeLearnVue),
    },

    /**
     * Jelszó validációs szabályai.
     * 
     * @property {Function} password.required A mező nem lehet üres.
     * @property {Function} password.minLength A mezőnek legalább 6 karakter hosszúnak kell lennie.
     * @property {Function} confirm.required A mező nem lehet üres.
     * @property {Function} confirm.sameAs A mezőnek egyeznie kell a jelszó mező értékével.
     */
    password: {
        password: { required, minLength: minLength(6) },
        confirm: { required, sameAs: sameAs(state.password.password) }
    }
}));

/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 * 
 * @type {Object}
 */
const v$ = useVuelidate(rules, state);

/**
 * Form beküldési metódus.
 * A metódus ellenőrzi, hogy a validációs szabályok alapján a form tartalmaz-e hibákat.
 * Ha nincs hiba, akkor a "Form submitted" szöveget jeleníti meg, egyébként pedig a "Form failed validation" szöveget.
 */
const submitForm = () => {
    // A validációs szabályok alapján ellenőrzi a form tartalmát.
    v$.$validate();

    // Ha nincs hiba, akkor a "Form submitted" szöveget jeleníti meg.
    if (!v$.$error) {
        alert('Form submitted');
    } else {
        // Egyébként pedig a "Form failed validation" szöveget jeleníti meg.
        alert('Form failed validation');
    }
};
</script>

<template>
    <div class="root">
        <h2>Create Account</h2>
        <p>
            <input type="text" placeholder="Email" v-model="state.email" />
            <span v-if="v$.email.$error">
                {{ v$.email.$errors[0].$message }}
            </span>
        </p>
        <p>
            <input type="password" placeholder="Password" v-model="state.password.password" />
            <span v-if="v$.password.password.$error">
                {{ v$.password.password.$errors[0].$message }}
            </span>
        </p>
        <p>
            <input type="password" placeholder="Confirm" v-model="state.password.confirm" />
            <span v-if="v$.password.confirm.$error">
                {{ v$.password.confirm.$errors[0].$message }}
            </span>
        </p>

        <button type="submit" @click="submitForm">Submit</button>
    </div>
</template>