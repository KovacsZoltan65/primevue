<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { Button, Column, DataTable, Calendar } from 'primevue';

// Kiválasztott dátum tárolása
const selectedDate = ref(null);

// A dolgozók listája egy adott napon
const entities = ref([]);

// Naptár formátum beállítása
const formattedDate = computed(() => {
    if (!selectedDate.value) return null;
    return selectedDate.value.toISOString().split('T')[0]; // YYYY-MM-DD formátum
});

// Dolgozók betöltése adott dátum alapján
const loadEntities = async () => {
    if (!formattedDate.value) return;

    try {
        const response = await axios.get(`/api/entities-by-date/${formattedDate.value}`);
        entities.value = response.data;
    } catch (error) {
        console.error("Hiba a dolgozók betöltésekor:", error);
    }
};

// Ha változik a dátum, töltsük be az adatokat
watch(formattedDate, () => {
    loadEntities();
});

onMounted(() => {
    // Alapértelmezett dátum (mai nap)
    selectedDate.value = new Date();
    loadEntities();
});

</script>

<template>
    <div class="p-4">
        <h2 class="text-xl font-semibold mb-4">Naptár és dolgozók listája</h2>

        <div class="flex gap-4 items-center mb-4">
            <label for="calendar">Válassz dátumot:</label>
            <Calendar 
                id="calendar" 
                v-model="selectedDate" 
                dateFormat="yy-mm-dd" 
                :showIcon="true"
                :touchUI="true"
            />
        </div>

        <DataTable v-if="entities.length" :value="entities" responsiveLayout="scroll">
            <Column field="name" header="Név"></Column>
            <Column field="position" header="Pozíció"></Column>
            <Column header="Műveletek">
                <template #body="slotProps">
                    <Button 
                        icon="pi pi-trash" 
                        class="p-button-danger" 
                        @click="removeEntity(slotProps.data.id)"
                    />
                </template>
            </Column>
        </DataTable>

        <p v-else class="text-gray-500 mt-2">Nincs adat a kiválasztott napra.</p>

    </div>
</template>

<style scoped>
.p-4 {
    max-width: 600px;
    margin: auto;
}
</style>