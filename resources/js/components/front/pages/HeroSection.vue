<script setup lang="ts">
import { computed, onMounted, nextTick } from 'vue';
import gsap from 'gsap';

const props = defineProps<{
  subtitle: string,
  title: string,
  description: string,
  image: string,
}>();

const parsedDescription = computed(() => {
  return props.description
    ?.replace(/<h1>/g, '<h1 class="text-5xl">')
    .replace(/<h2>/g, '<h2 class="text-4xl">')
    .replace(/<h3>/g, '<h3 class="text-3xl">') ?? '';
});

const appUrl = import.meta.env.VITE_APP_URL || window.location.origin;
const appName = import.meta.env.VITE_APP_NAME || '';
const showImage = import.meta.env.VITE_SHOW_HERO_IMAGE || 0;

</script>

<template>
  <section
    class="min-h-screen flex flex-col justify-between px-6 py-20 bg-[#112240] text-[#ccd6f6]"
    data-aos="fade-up"
  >
    <!-- Zone texte + image -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-12">
      <!-- Colonne gauche : texte -->
      <div class="md:w-1/2 max-w-xl">
        <h1 class="text-5xl font-bold text-white mt-2">{{ props.title }}</h1>
        <span class="mt-4 text-[#8892b0] text-3xl" v-html="parsedDescription"></span>
      </div>

      <!-- Colonne droite : image -->
      <div class="md:w-1/2 flex justify-center items-center mt-10 md:mt-0 " v-if="props.image && showImage">
        <img
          :src="`${appUrl}/uploads-ovh/${props.image.split('/').pop()}`"
          :alt="`${appName}`"
          class="rounded-xl w-64 h-64 object-cover"
        />
      </div>
    </div>
  </section>
</template>
