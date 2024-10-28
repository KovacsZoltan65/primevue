<script setup>
import { onMounted, ref } from "vue";
import AppMenuItem from "./AppMenuItem.vue";
import MenuService from "@/service/MenuService";

/**
 * Tároló a MenuService-ből letöltött menüelemek tárolásához.
 *
 * @type {ref<unknown>}
 */
const menuItems = ref();

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
        <template v-for="(item, i) in menuItems" :key="item">
            <AppMenuItem
                v-if="!item.separator"
                :item="item"
                :index="i"
            />

            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>
