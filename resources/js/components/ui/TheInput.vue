<script setup lang="ts">
withDefaults(defineProps<{
    id: string;
    name?: string;
    label: string;
    type?: 'text' | 'email' | 'password' | 'number' | 'tel' | 'url' | 'search' | 'date' | 'time' | 'datetime-local' | 'month' | 'week';
    autocomplete?: string;
    required?: boolean;
    error?: string;
}>(), {
    type: 'text',
    required: false,
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
