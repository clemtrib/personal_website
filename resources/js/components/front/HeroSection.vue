<script setup lang="ts">
import gsap from 'gsap';
import { Download } from 'lucide-vue-next';
import { computed, nextTick, onMounted } from 'vue';

const props = defineProps<{
    readyToLoad: boolean;
    home: {
        id: number;
        hero_subtitle: string;
        hero_title: string;
        hero_description: string;
        hero_image: string;
    };
}>();

const parsedDescription = computed(() => {
    return (
        props.home.hero_description
            ?.replace(/<h1>/g, '<h1 class="text-5xl">')
            .replace(/<h2>/g, '<h2 class="text-4xl">')
            .replace(/<h3>/g, '<h3 class="text-3xl">') ?? ''
    );
});

const appUrl = import.meta.env.VITE_APP_URL || window.location.origin;
const appName = import.meta.env.VITE_APP_NAME || '';
const showImage = import.meta.env.VITE_SHOW_HERO_IMAGE || 0;

// GSAP animation setup for button hover
function setupGsapAnimations() {
    const button = document.querySelector('.gsap-hover');
    if (button) {
        button.addEventListener('mouseenter', () => {
            gsap.to(button, {
                scale: 1.05,
                boxShadow: '0 6px 18px rgba(100, 255, 218, 0.4)',
                duration: 0.3,
                ease: 'power2.out',
            });
        });
        button.addEventListener('mouseleave', () => {
            gsap.to(button, {
                scale: 1,
                boxShadow: '0 0 0 rgba(0,0,0,0)',
                duration: 0.3,
                ease: 'power2.out',
            });
        });
    }
}

onMounted(() => {
    if (props.readyToLoad) {
        nextTick(() => {
            setupGsapAnimations();
        });
    }
});
</script>

<template>
    <section v-if="props.readyToLoad" class="flex min-h-screen flex-col justify-between bg-[#112240] px-6 py-20 text-[#ccd6f6]" data-aos="fade-up">
        <!-- Zone texte + image -->
        <div class="flex flex-col items-center justify-between gap-12 md:flex-row">
            <!-- Colonne gauche : texte -->
            <div class="max-w-xl md:w-1/2">
                <p class="text-green-400">{{ props.home?.hero_subtitle }}</p>
                <h1 class="mt-2 text-5xl font-bold text-white">{{ props.home?.hero_title }}</h1>
                <span class="text-1xl mt-4 text-[#8892b0]" v-html="parsedDescription"></span>
                <a
                    :href="route('cv.download')"
                    target="_blank"
                    class="inline-flex items-center rounded border border-green-400 bg-[#0a192f] px-4 py-2 text-white transition-all duration-200 hover:bg-green-800"
                >
                    <Download class="mr-2 h-5 w-5" />
                    Télécharger mon CV
                </a>
            </div>
            <!-- Colonne droite : image -->
            <div class="mt-10 flex items-center justify-center md:mt-0 md:w-1/2" v-if="props.home?.hero_image && showImage">
                <img
                    :src="`${appUrl}/uploads-ovh/${props.home.hero_image.split('/').pop()}`"
                    :alt="`${appName}`"
                    class="h-64 w-64 rounded-xl object-cover"
                />
            </div>
        </div>

        <!-- Bouton en bas -->
        <div class="mt-12 w-full">
            <a
                href="#contact"
                class="gsap-hover block w-full rounded bg-green-400 px-6 py-3 text-center text-[#0a192f] transition hover:bg-green-300 md:w-auto"
            >
                Me contacter
            </a>
        </div>
    </section>
</template>
