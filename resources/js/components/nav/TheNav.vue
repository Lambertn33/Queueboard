<script setup lang="ts">
import TheNavItem from '@/components/nav/TheNavItem.vue';
import type { NavItem } from '@/types/nav';

const items: NavItem[] = [
    { label: 'Teams', icon: 'hi-user-group' },
    { label: 'Projects', icon: 'hi-folder' },
    { label: 'Tasks', icon: 'hi-clipboard-check' },
];
</script>

<template>
    <nav class="px-3" aria-label="Main">
        <ul class="flex flex-col gap-1">
            <li v-for="item in items" :key="item.label">
                <TheNavItem :label="item.label" :icon="item.icon" />
            </li>
        </ul>
    </nav>
</template>
