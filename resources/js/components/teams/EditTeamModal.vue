<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import TheButton from '@/components/ui/TheButton.vue';
import TheInput from '@/components/ui/TheInput.vue';
import TheModal from '@/components/ui/TheModal.vue';
import type { Team } from '@/types/team';

const props = defineProps<{
    open: boolean;
    team: Team;
}>();

const emit = defineEmits<{
    close: [];
}>();

function close(): void {
    emit('close');
}
</script>

<template>
    <TheModal :open="open" title="Edit team" @close="close">
        <Form
            :action="`/teams/${props.team.id}`"
            method="patch"
            class="flex flex-col gap-4"
            :preserve-state="false"
            reset-on-success
            :on-success="close"
            #default="{ errors, processing }"
        >
            <TheInput
                id="edit-team-name"
                name="name"
                label="Team name"
                required
                :value="team.name"
                :error="errors.name"
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
