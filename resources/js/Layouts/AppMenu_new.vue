<script setup>
import { onMounted, ref } from "vue";
import AppMenuItem from "./AppMenuItem.vue";
import MenuService from "@/service/MenuService";

const menuItems = ref([]);

const fetchItems = () => {
    MenuService.getMenuItems()
    .then((response) => {
        menuItems.value = response.data;
    })
    .catch((error) => {
        console.error("getMenuItems API Error:", error);
    });
}

onMounted(() => {
    fetchItems();
});

</script>
<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in menuItems" :key="i">
            <AppMenuItem :item="item" :index="i" />
        </template>
    </ul>
</template>
