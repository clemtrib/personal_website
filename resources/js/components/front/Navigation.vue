<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
const props = defineProps < {
    home: {
        id: number;
        hero_subtitle: string;
        hero_title: string;
        hero_description: string;
    },
    readyToLoad: boolean,
    config: {
        social_networks: Array < {
            facebook: string,
            linkedin: string,
            github: string,
        } > ,
        tjm: number,
    };
} > ();
</script>

<template>
    <nav
      v-if="props.readyToLoad && props.readyToLoad === true"
      class="hidden sm:block fixed top-0 left-0 w-full bg-[#0a192f] text-[#ccd6f6] shadow z-50"
    >
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-xl font-bold text-green-400">{{ props.home.hero_title }}</a>
            <ul class="flex gap-6 text-sm uppercase tracking-wider">
                <li><a href="#schools" class="hover:text-green-400">Formation</a></li>
                <li><a href="#skills" class="hover:text-green-400">Compétences</a></li>
                <li><a href="#experiences" class="hover:text-green-400">Expériences</a></li>
                <li><a href="#hobbies" class="hover:text-green-400">Loisirs</a></li>
                <li v-if="props.config?.tjm"><a href="#tjm" class="hover:text-green-400">TJM</a></li>
                <li><a href="#contact" class="hover:text-green-400">Contact</a></li>
            </ul>
        </div>
    </nav>
</template>
