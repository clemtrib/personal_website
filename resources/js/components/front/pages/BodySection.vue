<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    text: string;
    image: string;
}>();

const parsedDescription = computed(() => {
    return (
        props.text?.replace(/<h1>/g, '<h1 class="text-5xl">').replace(/<h2>/g, '<h2 class="text-4xl">').replace(/<h3>/g, '<h3 class="text-3xl">') ??
        ''
    );
});

const appUrl = import.meta.env.VITE_APP_URL || window.location.origin;
const appName = import.meta.env.VITE_APP_NAME || '';
</script>

<template>
    <section class="flex min-h-screen flex-col justify-between bg-[#0a192f] px-6 py-20 text-[#ccd6f6]" data-aos="fade-bottom">
        <!-- Zone texte + image -->
        <div class="flex flex-col items-center justify-between gap-12 md:flex-row">
            <!-- Colonne gauche : texte -->
            <div class="max-w-xl md:w-1/2">
                <span class="mt-4 text-[#8892b0] leading-relaxed prose prose-invert max-w-none transition-all duration-300" v-html="parsedDescription"></span>
            </div>

            <!-- Colonne droite : image -->
            <div class="mt-10 flex items-center justify-center md:mt-0 md:w-1/2" v-if="props.image">
                <img :src="`${appUrl}/uploads-ovh/${props.image.split('/').pop()}`" :alt="`${appName}`" class="h-64 w-64 rounded-xl object-cover" />
            </div>
        </div>
    </section>
</template>
