<script setup lang="ts">
import MembersList from '@/components/members/MembersList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { PaginatedMembers } from '@/types/member';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    members: PaginatedMembers;
}>();
</script>

<template>
    <main class="flex flex-1 flex-col gap-5 p-6">
        <h1 class="text-3xl font-bold tracking-tight text-gray-950">Members</h1>

        <MembersList :members="members" />
    </main>
</template>
