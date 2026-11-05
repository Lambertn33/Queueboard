<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import TheButton from '@/components/ui/TheButton.vue';
import TheCheckbox from '@/components/ui/TheCheckbox.vue';
import TheInput from '@/components/ui/TheInput.vue';
</script>

<template>
    <main class="flex min-h-screen items-center justify-center p-6">
        <section class="w-full max-w-md rounded-2xl border border-blue-200 bg-white p-8 shadow-sm">
            <p class="text-sm font-medium text-blue-600">QueueBoard</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight">Sign in</h1>

            <Form action="/login" method="post" class="mt-6 space-y-4" #default="{ errors, processing }">
                <TheInput
                    id="email"
                    type="email"
                    label="Email"
                    autocomplete="username"
                    required
                    :error="errors.email"
                />

                <TheInput
                    id="password"
                    type="password"
                    label="Password"
                    autocomplete="current-password"
                    required
                />

                <TheCheckbox id="remember" label="Remember me" name="remember" value="1" class="w-full" />

                <TheButton type="submit" class="w-full" :disabled="processing">
                    Sign in
                </TheButton>
            </Form>
        </section>
    </main>
</template>
