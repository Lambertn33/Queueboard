<script setup lang="ts">
import TheTablePagination from '@/components/table/TheTablePagination.vue';
import type { PaginatedTeams } from '@/types/team';

defineProps<{
    teams: PaginatedTeams;
}>();
</script>

<template>
    <section>
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-[#F8F9FB] text-[13px] font-medium text-[#98A2B3]">
                    <th class="w-[46%] px-4 py-3 font-medium" scope="col">Team</th>
                    <th class="px-4 py-3 font-medium" scope="col">Members</th>
                    <th class="px-4 py-3 font-medium" scope="col">Projects</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="teams.data.length === 0">
                    <td class="px-4 py-4 text-sm text-[#98A2B3]" colspan="3">No teams yet.</td>
                </tr>
                <tr
                    v-for="team in teams.data"
                    :key="team.id"
                    class="border-b border-[#EEF2F6] last:border-b-0"
                >
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ team.name }}</td>
                    <td class="px-4 py-4 text-sm text-gray-500">{{ team.members_count }}</td>
                    <td class="px-4 py-4 text-sm text-gray-500">{{ team.projects_count }}</td>
                </tr>
            </tbody>
        </table>

        <TheTablePagination :paginator="teams" v-if="teams.data.length > 0" />
    </section>
</template>
