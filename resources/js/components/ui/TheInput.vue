<script setup>
defineProps({
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
    type: {
        type: String,
        default: 'text',
    },
    autocomplete: {
        type: String,
        default: null,
    },
    required: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div>
        <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <input
            :id="id"
            :name="name ?? id"
            :type="type"
            :autocomplete="autocomplete"
            :required="required"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2"
        >
        <p v-if="error" class="mt-1 text-sm text-red-600 font-medium">{{ error }}</p>
    </div>
</template>
