<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    settings: {
        type: Object,
        default: () => {},
    }
});

</script>

<template>
    <AppLayout>
        <Head :title="$t('default_settings')" />

        {{ props.settings }}

    </AppLayout>
</template>
