<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    errors: {
        type: Object,
        required: true,
    },
});

onMounted(() => {
    //
})

</script>

<template>
    <AppLayout>
        <Head title="Error Logs" />

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Occurrences</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="error in props.errors.data" :key="error.id">
                    <td>{{ error.properties }}</td>
                    <td>{{ error.occurrence_count }}</td>
                    <td>{{ error.created_at }}</td>
                </tr>
            </tbody>
        </table>

    </AppLayout>
</template>
