<script setup>
const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        default: null,
    },
    label: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        default: '1',
    },
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <label :for="id" :class="['flex items-center gap-2 text-sm text-gray-600', props.class]">
        <input :id="id" :name="name ?? id" type="checkbox" :value="value">
        {{ label }}
    </label>
</template>
