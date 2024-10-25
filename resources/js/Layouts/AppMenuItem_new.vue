<script setup>

const props = defineProps({
    item: Object,
    index: Number,
});

const onItemClick = () => {
    if (props.item.to) {
        // Navigálás vagy egyéb logika
        console.log(`Navigating to: ${props.item.to}`);
    }
};
</script>
<template>
    <li>
        <div class="menu-item" @click="onItemClick">
            <i :class="item.icon" v-if="item.icon"></i>
            <span>{{ item.label }}</span>
        </div>
        <ul v-if="item.items && item.items.length">
            <AppMenuItem
                v-for="(child, index) in item.items"
                :key="index"
                :item="child"
                :index="index"
            />
        </ul>
    </li>
</template>
