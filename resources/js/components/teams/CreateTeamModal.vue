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
    <TheModal :open="open" title="Create team" @close="close">
        <Form
            action="/teams"
            method="post"
            class="flex flex-col gap-4"
            reset-on-success
            :on-success="close"
            #default="{ errors, processing }"
        >
            <TheInput
                id="name"
                name="name"
                label="Team name"
                required
                :error="errors.name"
            />

            <div class="flex items-center justify-end gap-2">
                <TheButton type="button" variant="plain" class="px-4 py-2 text-gray-600" :disabled="processing" @click="close">
                    Cancel
                </TheButton>
                <TheButton type="submit" class="shrink-0" :disabled="processing">
                    Create team
                </TheButton>
            </div>
        </Form>
    </TheModal>
</template>
