<script setup lang="ts">
import CreateMemberModal from '@/components/members/CreateMemberModal.vue';
import EditMemberModal from '@/components/members/EditMemberModal.vue';
import MembersList from '@/components/members/MembersList.vue';
import TheButton from '@/components/ui/TheButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Can } from '@/types/can';
import type { Member, PaginatedMembers } from '@/types/member';
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    members: PaginatedMembers;
}>();

const page = usePage<{
    can: Can;
}>();

const canCreateUser = computed((): boolean => page.props.can.createUser);
const canUpdateUser = computed((): boolean => page.props.can.updateUser);

const createOpen = ref(false);
const editingMember = ref<Member | null>(null);

function openCreate(): void {
    createOpen.value = true;
}

function closeCreate(): void {
    createOpen.value = false;
}

function openEdit(member: Member): void {
    editingMember.value = member;
}

function closeEdit(): void {
    editingMember.value = null;
}
</script>

<template>
    <main class="flex flex-1 flex-col gap-5 p-6">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-3xl font-bold tracking-tight text-gray-950">Members</h1>

            <TheButton
                v-if="canCreateUser"
                type="button"
                class="inline-flex items-center gap-2"
                :disabled="false"
                @click="openCreate"
            >
                <VIcon name="hi-plus" scale="0.75" />
                Create Member
            </TheButton>
        </div>

        <MembersList
            :members="members"
            :can-update-user="canUpdateUser"
            @edit="openEdit"
        />

        <CreateMemberModal v-if="canCreateUser" :open="createOpen" @close="closeCreate" />

        <EditMemberModal
            v-if="canUpdateUser && editingMember"
            :open="editingMember !== null"
            :member="editingMember"
            @close="closeEdit"
        />
    </main>
</template>
