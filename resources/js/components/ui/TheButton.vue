<script setup lang="ts">
const props = defineProps<{
    type: 'button' | 'submit' | 'reset';
    disabled: boolean;
    class: string;
    variant?: 'primary' | 'plain';
}>();

const emit = defineEmits<{
    click: [event: MouseEvent];
}>();
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="[
            'cursor-pointer rounded-lg font-medium disabled:opacity-60',
            props.variant === 'plain' ? '' : 'bg-blue-600 px-4 py-2 text-white',
            props.class,
        ]"
        @click="emit('click', $event)"
    >
        <slot />
    </button>
</template>
