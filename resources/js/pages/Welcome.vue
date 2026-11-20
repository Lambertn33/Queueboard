<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    message: string;
}>();
</script>

<template>
    <main class="flex flex-1 items-center justify-center p-6">
        <section class="w-full max-w-xl rounded-2xl border border-brand-muted bg-panel p-8 shadow-sm">
            <p class="text-sm font-medium text-brand">QueueBoard</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight">Vue and Inertia are connected</h1>
            <p class="mt-4 text-gray-600">{{ message }}</p>
        </section>
    </main>
</template>
