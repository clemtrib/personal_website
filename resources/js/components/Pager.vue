<script setup lang="ts">
import { router } from '@inertiajs/vue3';

defineProps<{
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}>();

const navigate = (url: string | null) => {
    if (url) {
        router.visit(url, {
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<template>
    <div class="flex flex-wrap justify-center gap-2 mt-5">
        <button
            v-for="(link, index) in links"
            :key="index"
            v-html="link.label"
            :disabled="!link.url"
            @click="navigate(link.url)"
            :class="[
                'rounded px-3 py-1 text-sm',
                link.active ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white',
                !link.url ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-300 dark:hover:bg-gray-600',
            ]"
        />
    </div>
</template>
