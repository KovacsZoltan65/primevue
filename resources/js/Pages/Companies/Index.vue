<script setup>
import { ref, onMounted } from 'vue';
import ApiService from '@/service/ApiService';

// Reaktív változó létrehozása
const items = ref([]);

// Adatok lekérése az API-ból
const fetchItems = () => {
  ApiService.getItems()
    .then(response => {
      //console.log('response.data.data', response.data.data);
      items.value = response.data.data;
      //console.log('items', items.value);
    })
    .catch(error => {
      console.error('getItems API Error:', error);
    });
};

// fetchItems metódus meghívása, amikor a komponens létrejön
onMounted(() => {
  fetchItems();
});

const create = () => {
    //
    let data = {
        name: 'new company',
        country: 'Country 01',
        city: 'City 01'
    };

    ApiService.createItems(data)
        .then(response => {
            items.value.push(response.data);
        })
        .catch(error => {
            console.error('Create API Error:', error);
        });
};

const update = (id) => {
    let data = {
        name: 'Company xx',
        country: 'Country xx',
        city: 'City xx'
    };
    ApiService.updateItem(id, data)
    .then(response => {
        const index = state.Books.findIndex(
            (b) => b.id === response.data.id
        );

        if (index !== -1) {
            items.value[index] = response.data;
        }
    })
    .catch(error => {
        console.error('Company Update Error:', error);
    });
}

</script>

<template>
    <div>
        <ul>
            <li v-for="item in items" :key="item.id">{{ item.name }}</li>
        </ul>

        <div>
            <button @click="create()">CREATE</button>
        </div>
        <div>
            <button @click="update(28)">UPDATE</button>
        </div>
    </div>
</template>