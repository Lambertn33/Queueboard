<script setup lang="ts">
import TheTablePagination from '@/components/table/TheTablePagination.vue';
import TheButton from '@/components/ui/TheButton.vue';
import type { PaginatedTeams, Team } from '@/types/team';

defineProps<{
    teams: PaginatedTeams;
    canUpdateTeam: boolean;
    canAddTeamMember: boolean;
}>();

const emit = defineEmits<{
    edit: [team: Team];
    addMember: [team: Team];
}>();

const actionClass = 'inline-flex size-8 items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700';
</script>

<template>
    <section>
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-brand-soft text-[13px] font-medium text-[#98A2B3]">
                    <th class="w-[40%] px-4 py-3 font-medium" scope="col">Team</th>
                    <th class="px-4 py-3 font-medium" scope="col">Members</th>
                    <th class="px-4 py-3 font-medium" scope="col">Projects</th>
                    <th class="px-4 py-3 font-medium" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="teams.data.length === 0">
                    <td class="px-4 py-4 text-sm text-[#98A2B3]" colspan="4">No teams yet.</td>
                </tr>
                <tr
                    v-for="team in teams.data"
                    :key="team.id"
                    class="border-b border-[#EEF2F6] last:border-b-0"
                >
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ team.name }}</td>
                    <td class="px-4 py-4 text-sm text-gray-500">{{ team.members_count }}</td>
                    <td class="px-4 py-4 text-sm text-gray-500">{{ team.projects_count }}</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-1">
                            <TheButton
                                v-if="canAddTeamMember"
                                type="button"
                                variant="plain"
                                :class="actionClass"
                                :disabled="false"
                                :aria-label="`Add a member to ${team.name}`"
                                @click="emit('addMember', team)"
                            >
                                <VIcon name="hi-user-add" scale="0.75" />
                            </TheButton>
                            <TheButton
                                v-if="canUpdateTeam"
                                type="button"
                                variant="plain"
                                :class="actionClass"
                                :disabled="false"
                                :aria-label="`Edit ${team.name}`"
                                @click="emit('edit', team)"
                            >
                                <VIcon name="hi-pencil-alt" scale="0.75" />
                            </TheButton>
                            <TheButton
                                type="button"
                                variant="plain"
                                :class="actionClass"
                                :disabled="false"
                                :aria-label="`Archive ${team.name}`"
                            >
                                <VIcon name="hi-archive" scale="0.75" />
                            </TheButton>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <TheTablePagination v-if="teams.data.length > 0" :paginator="teams" />
    </section>
</template>
