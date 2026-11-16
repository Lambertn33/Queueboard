<script setup lang="ts">
import TheTablePagination from '@/components/table/TheTablePagination.vue';
import type { PaginatedMembers } from '@/types/member';

defineProps<{
    members: PaginatedMembers;
}>();

const roleLabels: Record<string, string> = {
    MEMBER: 'Member',
    PROJECT_LEAD: 'Project lead',
};

function rolesLabel(roles: string[]): string {
    return roles.map((role) => roleLabels[role] ?? role).join(', ');
}
</script>

<template>
    <section>
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-[#F8F9FB] text-[13px] font-medium text-[#98A2B3]">
                    <th class="w-[30%] px-4 py-3 font-medium" scope="col">Name</th>
                    <th class="w-[35%] px-4 py-3 font-medium" scope="col">Email</th>
                    <th class="px-4 py-3 font-medium" scope="col">Roles</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="members.data.length === 0">
                    <td class="px-4 py-4 text-sm text-[#98A2B3]" colspan="3">No members yet.</td>
                </tr>
                <tr
                    v-for="member in members.data"
                    :key="member.id"
                    class="border-b border-[#EEF2F6] last:border-b-0"
                >
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ member.name }}</td>
                    <td class="px-4 py-4 text-sm text-gray-500">{{ member.email }}</td>
                    <td class="px-4 py-4 text-sm text-gray-500">{{ rolesLabel(member.roles) }}</td>
                </tr>
            </tbody>
        </table>

        <TheTablePagination v-if="members.data.length > 0" :paginator="members" />
    </section>
</template>
