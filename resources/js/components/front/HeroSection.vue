<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  readyToLoad: boolean,
  home: {
    id: number;
    hero_subtitle: string;
    hero_title: string;
    hero_description: string;
    hero_image: string;
  };
}>();

const parsedDescription = computed(() => {
  return props.home.hero_description
    ?.replace(/<h1>/g, '<h1 class="text-5xl">')
    .replace(/<h2>/g, '<h2 class="text-4xl">')
    .replace(/<h3>/g, '<h3 class="text-3xl">') ?? '';
});

const appUrl = import.meta.env.VITE_APP_URL || window.location.origin;
const showImage = import.meta.env.VITE_SHOW_HERO_IMAGE || 0;

</script>

<template>
  <section
    v-if="props.readyToLoad"
    class="min-h-screen flex flex-col justify-between px-6 py-20 bg-[#112240] text-[#ccd6f6]"
    data-aos="fade-up"
  >
    <!-- Zone texte + image -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-12">
      <!-- Colonne gauche : texte -->
      <div class="md:w-1/2 max-w-xl">
        <p class="text-green-400">{{ props.home?.hero_subtitle }}</p>
        <h1 class="text-5xl font-bold text-white mt-2">{{ props.home?.hero_title }}</h1>
        <span class="mt-4 text-[#8892b0]" v-html="parsedDescription"></span>
      </div>

      <!-- Colonne droite : image -->
      <div class="md:w-1/2 flex justify-center items-center mt-10 md:mt-0 " v-if="props.home?.hero_image && showImage">
        <img
          :src="`${appUrl}/uploads-ovh/${props.home.hero_image.split('/').pop()}`"
          alt="Image d'illustration"
          class="rounded-xl w-64 h-64 object-cover"
        />
      </div>
    </div>

    <!-- Bouton en bas -->
    <div class="mt-12 w-full">
      <a
        href="#contact"
        class="block w-full md:w-auto text-center bg-green-400 text-[#0a192f] px-6 py-3 rounded hover:bg-green-300 transition"
      >
        Me contacter
      </a>
    </div>
  </section>
</template>
