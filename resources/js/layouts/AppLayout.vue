<script setup lang="ts">
import TheSidebar from '@/components/sidebar/TheSidebar.vue';
import TheTopbar from '@/components/topbar/TheTopbar.vue';
</script>

<template>
    <div class="p-4">
        <div class="flex min-h-[calc(100vh-2rem)] overflow-hidden rounded-2xl border border-brand-muted bg-panel shadow-sm">
            <TheSidebar />

            <div class="flex min-w-0 flex-1 flex-col">
                <TheTopbar />

                <slot />
            </div>
        </div>
    </div>
</template>
