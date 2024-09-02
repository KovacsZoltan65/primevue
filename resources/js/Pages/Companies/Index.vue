<script setup>
import { ref, onMounted } from 'vue';
import ApiService from '@/service/ApiService';

// Reaktív változó létrehozása
const items = ref([]);

// Adatok lekérése az API-ból
const fetchItems = () => {
  ApiService.getItems()
    .then(response => {
      console.log('response', response.data.data);
      items.value = response.data.data;
      //console.log('items', items.value);
    })
    .catch(error => {
      console.log('getItems API Error:', error);
    });
};

// fetchItems metódus meghívása, amikor a komponens létrejön
onMounted(() => {
  fetchItems();
});

const createItem = () => {
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

const updateItem = (id) => {
    
    console.log('id', id);

    let data = {
        name: 'Company xx',
        country: 'Country xx',
        city: 'City xx'
    };

    ApiService.updateItem(id, data)
    .then(response => {
        console.log('response', response);
        
        const index = items.value.findIndex((b) => {
            //console.log('id', id);
            //console.log('b.id', b.id);
            return b.id === id;
        });

        //const index = items.value.findIndex(
        //    (b) => b.id === id
        //);

        //console.log('index', index);
        
        if (index !== -1) {
            items.value[index] = response.data;
        }
    })
    .catch(error => {
        console.error('Update API Error:', error);
    });
}

const deleteItem = (id) => {
    ApiService.deleteItems(id)
    .then(response => {
        items.value = items.value.filter((b) => b.id !== id);
    })
    .catch(error => {
        console.error('Delete API Error:', error);
    });
};

</script>

<template>
    <div>
        <!--
        <ul>
            <li v-for="item in items" :key="item.id">{{ item.name }}</li>
        </ul>
    -->
        <div>
            <button @click="createItem()">CREATE</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items" :key="item.id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.country }}</td>
                    <td>{{ item.city }}</td>
                    <td>
                        <button @click="updateItem(item.id)" style="margin-left: 5px;">UPDATE</button>
                        <button @click="deleteItem(item.id)" style="margin-left: 5px;">DELETE</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>