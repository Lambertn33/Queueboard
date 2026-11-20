<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import TheButton from '@/components/ui/TheButton.vue';
import TheModal from '@/components/ui/TheModal.vue';
import TheSelect from '@/components/ui/TheSelect.vue';
import type { Member } from '@/types/member';
import type { Team } from '@/types/team';
import { computed } from 'vue';

const props = defineProps<{
    open: boolean;
    team: Team;
    members: Member[];
}>();

const emit = defineEmits<{
    close: [];
}>();

const availableMembers = computed((): Member[] => {
    const assignedIds = new Set(props.team.member_ids);

    return props.members.filter((member) => !assignedIds.has(member.id));
});

const memberOptions = computed(() => {
    return availableMembers.value.map((member) => ({
        value: member.id,
        label: `${member.name} (${member.email})`,
    }));
});

const roleOptions = [
    { value: 'MEMBER', label: 'Member' },
    { value: 'PROJECT_LEAD', label: 'Project lead' },
];

function close(): void {
    emit('close');
}
</script>

<template>
    <TheModal :open="open" :title="`Add member to ${team.name}`" @close="close">
        <Form
            :action="`/teams/${props.team.id}/members`"
            method="post"
            class="flex flex-col gap-4"
            :preserve-state="false"
            reset-on-success
            :on-success="close"
            #default="{ errors, processing }"
        >
            <TheSelect
                id="team-member-user-id"
                name="user_id"
                label="Member"
                placeholder="Select a member"
                required
                :options="memberOptions"
                :error="errors.user_id"
            />

            <TheSelect
                id="team-member-role"
                name="role"
                label="Role"
                placeholder="Select a role"
                required
                :options="roleOptions"
                :error="errors.role"
            />

            <p v-if="availableMembers.length === 0" class="text-sm text-gray-500">
                No available members to add.
            </p>

            <div class="flex items-center justify-end gap-2">
                <TheButton type="button" variant="plain" class="px-4 py-2 text-gray-600" :disabled="processing" @click="close">
                    Cancel
                </TheButton>
                <TheButton type="submit" class="shrink-0" :disabled="processing || availableMembers.length === 0">
                    Add member
                </TheButton>
            </div>
        </Form>
    </TheModal>
</template>
