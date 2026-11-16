<script setup lang="ts">
const props = defineProps<{
    id: string;
    name?: string;
    label: string;
    value?: string;
    checked?: boolean;
    class?: string;
}>();
</script>

<template>
    <label :for="id" :class="['flex items-center gap-2 text-sm text-gray-600', props.class]">
        <input :id="id" :name="name ?? id" type="checkbox" :value="value" :checked="checked">
        {{ label }}
    </label>
</template>
