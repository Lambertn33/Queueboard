<script setup lang="ts">
export interface SelectOption {
    value: string | number;
    label: string;
    disabled?: boolean;
}

withDefaults(defineProps<{
    id: string;
    name?: string;
    label: string;
    options: SelectOption[];
    placeholder?: string;
    required?: boolean;
    error?: string;
    value?: string | number;
}>(), {
    required: false,
});
</script>

<template>
    <div>
        <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <select
            :id="id"
            :name="name ?? id"
            :required="required"
            :value="value"
            class="mt-1 w-full rounded-lg border border-gray-300 bg-panel px-3 py-2"
        >
            <option v-if="placeholder" value="" disabled selected>
                {{ placeholder }}
            </option>
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
                :disabled="option.disabled"
            >
                {{ option.label }}
            </option>
        </select>
        <p v-if="error" class="mt-1 text-sm font-medium text-red-600">{{ error }}</p>
    </div>
</template>
