<script setup>
import { onMounted, ref} from "vue";
import { Head } from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from "primevue";

const toast = useToast();
const dt = ref();
const settings = ref();
const setting = ref({
    id: null,
    name: '',
    default_value: '',
    is_active: true
});
const loading = ref(true);

const fetchItems = () => {
    loading.value = true;
    loading.value = false;
}

onMounted(() => {
    fetchItems();
});

</script>

<template>
    <AppLayout>
        <Head :title="$t('default_settings')" />

        

    </AppLayout>
</template>
