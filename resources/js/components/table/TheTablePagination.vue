<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import type { PageLink, Paginator } from '@/types/pagination';
import { computed } from 'vue';

const props = defineProps<{
    paginator: Paginator;
}>();

const page = usePage();

const pageLinks = computed((): PageLink[] => {
    return props.paginator.links.filter((link) => /^\d+$/.test(link.label));
});

const pageSizes = computed((): number[] => {
    const sizes = [10, 25, 50];

    if (!sizes.includes(props.paginator.per_page)) {
        return [props.paginator.per_page, ...sizes].sort((left, right) => left - right);
    }

    return sizes;
});

const controlClass = 'flex size-8 shrink-0 items-center justify-center rounded-md border text-[13px]';

function changePageSize(event: Event): void {
    const perPage = Number((event.target as HTMLSelectElement).value);
    const path = page.url.split('?')[0] ?? '/teams';

    router.get(path, { page: 1, per_page: perPage }, {
        preserveScroll: true,
        preserveState: true,
    });
}
</script>

<template>
    <footer class="flex items-center justify-between gap-4 border-t border-[#EEF2F6] px-4 py-4">
        <nav class="flex items-center gap-1.5" aria-label="Pagination">
            <Link
                v-if="paginator.prev_page_url"
                :href="paginator.prev_page_url"
                :class="[controlClass, 'border-[#E4E7EC] bg-panel text-gray-500 hover:bg-gray-50']"
                aria-label="Previous page"
            >
                <VIcon name="hi-chevron-left" scale="0.65" />
            </Link>
            <span
                v-else
                :class="[controlClass, 'border-[#E4E7EC] bg-panel text-gray-300']"
                aria-hidden="true"
            >
                <VIcon name="hi-chevron-left" scale="0.65" />
            </span>

            <template v-for="link in pageLinks" :key="link.label">
                <span
                    v-if="link.active"
                    :class="[controlClass, 'border-brand bg-brand text-white']"
                    aria-current="page"
                >
                    {{ link.label }}
                </span>
                <Link
                    v-else-if="link.url"
                    :href="link.url"
                    :class="[controlClass, 'border-[#E4E7EC] bg-panel text-gray-600 hover:bg-gray-50']"
                >
                    {{ link.label }}
                </Link>
            </template>

            <Link
                v-if="paginator.next_page_url"
                :href="paginator.next_page_url"
                :class="[controlClass, 'border-[#E4E7EC] bg-panel text-gray-500 hover:bg-gray-50']"
                aria-label="Next page"
            >
                <VIcon name="hi-chevron-right" scale="0.65" />
            </Link>
            <span
                v-else
                :class="[controlClass, 'border-[#E4E7EC] bg-panel text-gray-300']"
                aria-hidden="true"
            >
                <VIcon name="hi-chevron-right" scale="0.65" />
            </span>
        </nav>

        <div class="flex shrink-0 items-center gap-3">
            <p class="text-[13px] whitespace-nowrap text-gray-500">Total {{ paginator.total }} Data</p>

            <label class="relative">
                <span class="sr-only">Rows per page</span>
                <select
                    :value="paginator.per_page"
                    class="h-9 appearance-none rounded-lg border border-[#E4E7EC] bg-panel pr-8 pl-3 text-[13px] text-gray-600"
                    @change="changePageSize"
                >
                    <option v-for="size in pageSizes" :key="size" :value="size">Show {{ size }} Data</option>
                </select>
                <VIcon
                    name="hi-chevron-down"
                    class="pointer-events-none absolute top-1/2 right-2 -translate-y-1/2 text-gray-400"
                    scale="0.6"
                />
            </label>
        </div>
    </footer>
</template>
