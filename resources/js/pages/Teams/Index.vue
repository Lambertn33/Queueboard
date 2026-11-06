<script setup lang="ts">
import TeamsList from '@/components/teams/TeamsList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { PaginatedTeams } from '@/types/team';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    teams: PaginatedTeams;
}>();
</script>

<template>
    <main class="flex flex-1 flex-col gap-5 p-6">
        <h1 class="text-3xl font-bold tracking-tight text-gray-950">Teams</h1>

        <TeamsList :teams="teams" />
    </main>
</template>
