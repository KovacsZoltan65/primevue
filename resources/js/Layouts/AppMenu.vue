<script setup>
import { onMounted, ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';
import { MenuService } from '@/service/MenuService';

/**
 * Tároló a MenuService-ből letöltött menüelemek tárolásához.
 *
 * @type {ref<unknown>}
 */
const menuItems = ref();


/**
 * Ez a funkció akkor hívódik meg, amikor az összetevő fel van szerelve.
 * Lekéri a menüelemeket a MenuService-ből, és a menuItems reaktív változóban tárolja.
 */
onMounted(() => {
    // A menüelemek lekérése a MenuService-ből.
    // A MenuService.getMenuItems() metódus egy ígéretet ad vissza, amely feloldja a menüadatokat.
    MenuService.getMenuItems()
        .then((data) => {
            // Tárolja a menüadatokat a menuItems reactive változóban.
            // A menuItems változó a menüelemek megjelenítésére szolgál a sablonban.
            menuItems.value = data;
        });
});
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in menuItems" :key="item">
            
            <AppMenuItem v-if="!item.separator" :item="item" :index="i"></AppMenuItem>

            <li v-if="item.separator" class="menu-separator"></li>

        </template>
    </ul>
</template>