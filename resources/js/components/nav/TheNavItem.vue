<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    label: string;
    icon: string;
    href?: string;
}>();

const page = usePage();

const current = computed((): boolean => {
    if (props.href === undefined) {
        return false;
    }

    return page.url === props.href || page.url.startsWith(`${props.href}?`);
});
</script>

<template>
    <Link
        v-if="href"
        :href="href"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium"
        :class="current ? 'bg-blue-50 text-blue-700' : 'text-gray-600'"
        :aria-current="current ? 'page' : undefined"
    >
        <VIcon :name="icon" scale="0.85" />
        {{ label }}
    </Link>
    <span v-else class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-600">
        <VIcon :name="icon" scale="0.85" />
        {{ label }}
    </span>
</template>
