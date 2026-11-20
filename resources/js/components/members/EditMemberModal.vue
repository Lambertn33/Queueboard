<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import TheButton from '@/components/ui/TheButton.vue';
import TheInput from '@/components/ui/TheInput.vue';
import TheModal from '@/components/ui/TheModal.vue';
import type { Member } from '@/types/member';

const props = defineProps<{
    open: boolean;
    member: Member;
}>();

const emit = defineEmits<{
    close: [];
}>();

function close(): void {
    emit('close');
}
</script>

<template>
    <TheModal :open="open" title="Edit member" @close="close">
        <Form
            :action="`/members/${props.member.id}`"
            method="patch"
            class="flex flex-col gap-4"
            :preserve-state="false"
            reset-on-success
            :on-success="close"
            #default="{ errors, processing }"
        >
            <TheInput
                id="edit-member-name"
                name="name"
                label="Name"
                required
                :value="member.name"
                :error="errors.name"
            />

            <TheInput
                id="edit-member-email"
                name="email"
                type="email"
                label="Email"
                autocomplete="off"
                required
                :value="member.email"
                :error="errors.email"
            />

            <div class="flex items-center justify-end gap-2">
                <TheButton type="button" variant="plain" class="px-4 py-2 text-gray-600" :disabled="processing" @click="close">
                    Cancel
                </TheButton>
                <TheButton type="submit" class="shrink-0" :disabled="processing">
                    Save changes
                </TheButton>
            </div>
        </Form>
    </TheModal>
</template>
