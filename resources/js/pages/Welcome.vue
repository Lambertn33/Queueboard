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
        <section class="w-full max-w-xl rounded-2xl border border-blue-200 bg-white p-8 shadow-sm">
            <p class="text-sm font-medium text-blue-600">QueueBoard</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight">Vue and Inertia are connected</h1>
            <p class="mt-4 text-gray-600">{{ message }}</p>
        </section>
    </main>
</template>
