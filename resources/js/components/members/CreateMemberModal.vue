<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import TheButton from '@/components/ui/TheButton.vue';
import TheInput from '@/components/ui/TheInput.vue';
import TheModal from '@/components/ui/TheModal.vue';

defineProps<{
    open: boolean;
}>();

const emit = defineEmits<{
    close: [];
}>();

function close(): void {
    emit('close');
}
</script>

<template>
    <TheModal :open="open" title="Create member" @close="close">
        <Form
            action="/members"
            method="post"
            class="flex flex-col gap-4"
            :preserve-state="false"
            reset-on-success
            :on-success="close"
            #default="{ errors, processing }"
        >
            <TheInput
                id="name"
                name="name"
                label="Name"
                required
                :error="errors.name"
            />

            <TheInput
                id="email"
                name="email"
                type="email"
                label="Email"
                autocomplete="off"
                required
                :error="errors.email"
            />

            <div class="flex items-center justify-end gap-2">
                <TheButton type="button" variant="plain" class="px-4 py-2 text-gray-600" :disabled="processing" @click="close">
                    Cancel
                </TheButton>
                <TheButton type="submit" class="shrink-0" :disabled="processing">
                    Create member
                </TheButton>
            </div>
        </Form>
    </TheModal>
</template>
