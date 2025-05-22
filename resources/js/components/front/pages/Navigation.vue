<script setup lang="ts">
const props = defineProps<{
    title: string;
}>();
</script>

<template>
    <nav class="fixed top-0 left-0 w-full box-border bg-[#0a192f] text-[#ccd6f6] shadow z-50 overflow-x-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-sm font-bold text-green-400">{{ props.title }}</a>
        </div>
    </nav>
</template>
