<script setup lang="ts">
import TheTablePagination from '@/components/table/TheTablePagination.vue';
import TheButton from '@/components/ui/TheButton.vue';
import type { Member, PaginatedMembers } from '@/types/member';

defineProps<{
    members: PaginatedMembers;
    canUpdateUser: boolean;
}>();

const emit = defineEmits<{
    edit: [member: Member];
}>();

const roleLabels: Record<string, string> = {
    MEMBER: 'Member',
    PROJECT_LEAD: 'Project lead',
};

function roleLabel(role: string): string {
    return roleLabels[role] ?? role;
}

const actionClass = 'inline-flex size-8 items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700';
</script>

<template>
    <section>
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-brand-soft text-[13px] font-medium text-[#98A2B3]">
                    <th class="w-[22%] px-4 py-3 font-medium" scope="col">Name</th>
                    <th class="w-[26%] px-4 py-3 font-medium" scope="col">Email</th>
                    <th class="w-[22%] px-4 py-3 font-medium" scope="col">Team</th>
                    <th class="px-4 py-3 font-medium" scope="col">Role</th>
                    <th class="px-4 py-3 font-medium" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="members.data.length === 0">
                    <td class="px-4 py-4 text-sm text-[#98A2B3]" colspan="5">No members yet.</td>
                </tr>
                <tr
                    v-for="member in members.data"
                    :key="member.id"
                    class="border-b border-[#EEF2F6] last:border-b-0"
                >
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ member.name }}</td>
                    <td class="px-4 py-4 text-sm text-gray-500">{{ member.email }}</td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        <template v-if="member.memberships.length > 0">
                            <p
                                v-for="membership in member.memberships"
                                :key="`${member.id}-${membership.team}`"
                            >
                                {{ membership.team }}
                            </p>
                        </template>
                        <span v-else>—</span>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        <template v-if="member.memberships.length > 0">
                            <p
                                v-for="membership in member.memberships"
                                :key="`${member.id}-${membership.team}-role`"
                            >
                                {{ roleLabel(membership.role) }}
                            </p>
                        </template>
                        <span v-else>—</span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-1">
                            <TheButton
                                v-if="canUpdateUser"
                                type="button"
                                variant="plain"
                                :class="actionClass"
                                :disabled="false"
                                :aria-label="`Edit ${member.name}`"
                                @click="emit('edit', member)"
                            >
                                <VIcon name="hi-pencil-alt" scale="0.75" />
                            </TheButton>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <TheTablePagination v-if="members.data.length > 0" :paginator="members" />
    </section>
</template>
