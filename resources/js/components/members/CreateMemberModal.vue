<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import TheButton from '@/components/ui/TheButton.vue';
import TheCheckbox from '@/components/ui/TheCheckbox.vue';
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

            <fieldset>
                <legend class="text-sm font-medium text-gray-700">Roles</legend>
                <div class="mt-2 flex flex-col gap-2">
                    <TheCheckbox id="role-member" name="roles[]" value="MEMBER" label="Member" />
                    <TheCheckbox id="role-project-lead" name="roles[]" value="PROJECT_LEAD" label="Project lead" />
                </div>
                <p v-if="errors.roles" class="mt-1 text-sm font-medium text-red-600">{{ errors.roles }}</p>
            </fieldset>

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
