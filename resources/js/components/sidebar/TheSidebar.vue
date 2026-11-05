<script setup lang="ts">
import TheNav from '@/components/nav/TheNav.vue';
</script>

<template>
    <aside class="flex w-60 shrink-0 flex-col border-r border-gray-200 bg-white">
        <div class="flex items-center gap-2 px-5 py-5">
            <span class="flex size-8 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                Q
            </span>
            <span class="text-base font-semibold tracking-tight">QueueBoard</span>
        </div>

        <TheNav />
    </aside>
</template>
