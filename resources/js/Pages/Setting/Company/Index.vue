<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    companies: {
        type: Object,
        default: () => {},
    },
    settings: {
        type: Object,
        default: () => {},
    }
});

</script>

<template>
    <AppLayout>
        <Head :title="$t('companies_settings')" />

        {{ $page.props }}
        {{ props.companies }}
        {{ props.settings }}

    </AppLayout>
</template>