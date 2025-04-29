<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  readyToLoad: boolean,
  home: {
    id: number;
    hero_subtitle: string;
    hero_title: string;
    hero_description: string;
  };
}>();

const parsedDescription = computed(() => {
  return props.home.hero_description
    ?.replace(/<h1>/g, '<h1 class="text-5xl">').replace(/<h2>/g, '<h2 class="text-4xl">').replace(/<h3>/g, '<h2 class="text-3xl">') ?? '';
});
</script>

<template>
  <section v-if="props.readyToLoad && props.readyToLoad == true" class="min-h-screen flex flex-col justify-center px-6 bg-[#112240] text-[#ccd6f6]" data-aos="fade-up">
    <p class="text-green-400">{{ props.home?.hero_subtitle }}</p>
    <h1 class="text-5xl font-bold text-white mt-2">{{ props.home?.hero_title }}</h1>
    <span class="mt-4 max-w-xl text-[#8892b0] text-justify" v-html="parsedDescription"></span>
    <a href="#contact" class="mt-6 inline-block bg-green-400 text-[#0a192f] px-6 py-3 rounded hover:bg-green-300 transition">Me contacter</a>
  </section>
</template>
