<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="['rounded-lg bg-blue-600 px-4 py-2 font-medium text-white disabled:opacity-60 cursor-pointer', props.class]"
    >
        <slot />
    </button>
</template>
