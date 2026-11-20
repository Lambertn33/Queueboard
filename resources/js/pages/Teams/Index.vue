<script setup lang="ts">
import AddTeamMemberModal from '@/components/teams/AddTeamMemberModal.vue';
import CreateTeamModal from '@/components/teams/CreateTeamModal.vue';
import EditTeamModal from '@/components/teams/EditTeamModal.vue';
import TeamsList from '@/components/teams/TeamsList.vue';
import TheButton from '@/components/ui/TheButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Can } from '@/types/can';
import type { Member } from '@/types/member';
import type { PaginatedTeams, Team } from '@/types/team';
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    teams: PaginatedTeams;
    assignableMembers: Member[];
}>();

const page = usePage<{
    can: Can;
}>();

const canCreateTeam = computed((): boolean => page.props.can.createTeam);
const canUpdateTeam = computed((): boolean => page.props.can.updateTeam);
const canAddTeamMember = computed((): boolean => page.props.can.addTeamMember);

const createOpen = ref(false);
const editingTeam = ref<Team | null>(null);
const addingMemberTeam = ref<Team | null>(null);

function openCreate(): void {
    createOpen.value = true;
}

function closeCreate(): void {
    createOpen.value = false;
}

function openEdit(team: Team): void {
    editingTeam.value = team;
}

function closeEdit(): void {
    editingTeam.value = null;
}

function openAddMember(team: Team): void {
    addingMemberTeam.value = team;
}

function closeAddMember(): void {
    addingMemberTeam.value = null;
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

        <TeamsList
            :teams="teams"
            :can-update-team="canUpdateTeam"
            :can-add-team-member="canAddTeamMember"
            @edit="openEdit"
            @add-member="openAddMember"
        />

        <CreateTeamModal v-if="canCreateTeam" :open="createOpen" @close="closeCreate" />

        <EditTeamModal
            v-if="canUpdateTeam && editingTeam"
            :open="editingTeam !== null"
            :team="editingTeam"
            @close="closeEdit"
        />

        <AddTeamMemberModal
            v-if="canAddTeamMember && addingMemberTeam"
            :open="addingMemberTeam !== null"
            :team="addingMemberTeam"
            :members="assignableMembers"
            @close="closeAddMember"
        />
    </main>
</template>
