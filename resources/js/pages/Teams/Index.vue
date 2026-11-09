<script setup lang="ts">
import CreateTeamModal from '@/components/teams/CreateTeamModal.vue';
import TeamsList from '@/components/teams/TeamsList.vue';
import TheButton from '@/components/ui/TheButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Can } from '@/types/can';
import type { PaginatedTeams } from '@/types/team';
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    teams: PaginatedTeams;
}>();

const page = usePage<{
    can: Can;
}>();

const canCreateTeam = computed((): boolean => page.props.can.createTeam);
const createOpen = ref(false);

function openCreate(): void {
    createOpen.value = true;
}

function closeCreate(): void {
    createOpen.value = false;
}
</script>

<template>
    <main class="flex flex-1 flex-col gap-5 p-6">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-3xl font-bold tracking-tight text-gray-950">Teams</h1>

            <TheButton
                v-if="canCreateTeam"
                type="button"
                class="inline-flex items-center gap-2"
                :disabled="false"
                @click="openCreate"
            >
                <VIcon name="hi-plus" scale="0.75" />
                Create Team
            </TheButton>
        </div>

        <TeamsList :teams="teams" />

        <CreateTeamModal v-if="canCreateTeam" :open="createOpen" @close="closeCreate" />
    </main>
</template>
