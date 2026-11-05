<script setup lang="ts">
defineProps<{
    label: string;
    icon: string;
}>();
</script>

<template>
    <span class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-600">
        <VIcon :name="icon" scale="0.85" />
        {{ label }}
    </span>
</template>
