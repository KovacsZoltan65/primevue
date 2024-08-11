<script setup>
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Home',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/' }]
    },
    {
        label: 'UI Components',
        items: [
            { label: 'Form Layout', icon: 'pi pi-fw pi-id-card',      to: '#' },
            { label: 'Input',       icon: 'pi pi-fw pi-check-square', to: '#' },
            { label: 'Button',      icon: 'pi pi-fw pi-mobile',       to: '#', class: 'rotated-icon' },
            { label: 'Table',       icon: 'pi pi-fw pi-table',        to: '#' },
        ]
    },
]);

</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            
            <AppMenuItem v-if="!item.separator" :item="item" :index="i"></AppMenuItem>

            <li v-if="item.separator" class="menu-separator"></li>

        </template>
    </ul>
</template>