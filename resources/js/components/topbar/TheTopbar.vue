<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

import TheUserMenu from '@/components/topbar/TheUserMenu.vue';

import type { AuthUser } from '@/types/auth';

const page = usePage<{
    auth: {
        user: AuthUser | null;
    };
}>();

const user = computed(() => page.props.auth.user);

const roleLabels: Record<string, string> = {
    ADMIN: 'Admin',
    MEMBER: 'Member',
    PROJECT_LEAD: 'Project lead',
};

const rolesLabel = computed(() => {
    return (user.value?.roles ?? [])
        .map((role) => roleLabels[role] ?? role)
        .join(', ');
});

const initial = computed(() => user.value?.name.charAt(0).toUpperCase() ?? '');
</script>

<template>
    <header class="flex items-center justify-end border-b border-gray-200 px-6 py-4">
        <TheUserMenu
            v-if="user"
            :name="user.name"
            :roles-label="rolesLabel"
            :initial="initial"
        />
    </header>
</template>
