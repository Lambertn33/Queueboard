<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import TheButton from '@/components/ui/TheButton.vue';

defineProps<{
    name: string;
    rolesLabel: string;
    initial: string;
}>();

const open = ref(false);

function toggle(): void {
    open.value = !open.value;
}

function logout(): void {
    router.post('/logout');
}
</script>

<template>
    <div class="relative">
        <TheButton
            type="button"
            variant="plain"
            class="flex items-center gap-3 px-2 py-1"
            :disabled="false"
            :aria-expanded="open"
            aria-haspopup="menu"
            @click="toggle"
        >
            <span class="flex size-10 items-center justify-center rounded-full bg-brand text-sm font-semibold text-white">
                {{ initial }}
            </span>
            <span class="text-left">
                <span class="block text-sm font-semibold leading-5">{{ name }}</span>
                <span v-if="rolesLabel" class="block text-xs font-normal text-gray-500">{{ rolesLabel }}</span>
            </span>
            <VIcon name="hi-chevron-down" scale="0.7" />
        </TheButton>

        <div
            v-if="open"
            class="absolute top-full right-0 z-10 mt-2 w-40 rounded-lg border border-gray-200 bg-panel p-2 shadow-sm"
            role="menu"
        >
            <TheButton type="button" class="w-full" :disabled="false" role="menuitem" @click="logout">
                Log out
            </TheButton>
        </div>
    </div>
</template>
