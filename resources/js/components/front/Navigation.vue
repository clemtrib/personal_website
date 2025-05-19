<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Mail, CalendarPlus } from 'lucide-vue-next';

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

const isOpen = ref(false);
const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};
</script>

<template>
    <nav v-if="props.readyToLoad" class="fixed top-0 left-0 w-full box-border bg-[#0a192f] text-[#ccd6f6] shadow z-50 overflow-x-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-sm font-bold text-green-400">{{ props.home.hero_title }}</a>

            <!-- Menu desktop -->
            <ul class="hidden sm:flex gap-6 text-xs uppercase tracking-wider">
                <li><a href="#schools" class="hover:text-green-400">Formation</a></li>
                <li><a href="#skills" class="hover:text-green-400">Compétences</a></li>
                <li><a href="#experiences" class="hover:text-green-400">Expériences</a></li>
                <li><a href="#hobbies" class="hover:text-green-400">Loisirs</a></li>
                <li v-if="props.config?.tjm"><a href="#tjm" class="hover:text-green-400">TJM</a></li>
                <li><a href="#contact" class="hover:text-green-400"><Mail :size="16" /></a></li>
                <li><a href="#meets" class="hover:text-green-400"><CalendarPlus :size="16" /></a></li>
            </ul>

            <!-- Icône burger -->
            <button class="sm:hidden text-green-400 focus:outline-none" @click="toggleMenu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
        </div>

        <!-- Menu mobile -->
        <transition name="fade">
            <ul v-show="isOpen" class="sm:hidden bg-[#0a192f] px-6 pb-4 space-y-3 text-sm uppercase tracking-wider">
                <li><a href="#schools" class="block py-1 hover:text-green-400" @click="toggleMenu">Formation</a></li>
                <li><a href="#skills" class="block py-1 hover:text-green-400" @click="toggleMenu">Compétences</a></li>
                <li><a href="#experiences" class="block py-1 hover:text-green-400" @click="toggleMenu">Expériences</a></li>
                <li><a href="#hobbies" class="block py-1 hover:text-green-400" @click="toggleMenu">Loisirs</a></li>
                <li v-if="props.config?.tjm"><a href="#tjm" class="block py-1 hover:text-green-400" @click="toggleMenu">TJM</a></li>
                <li><a href="#contact" class="block py-1 hover:text-green-400" @click="toggleMenu">Contact</a></li>
                <li><a href="#meets" class="block py-1 hover:text-green-400" @click="toggleMenu">Prendre rendez-vous</a></li>
            </ul>
        </transition>
    </nav>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
}

nav {
    border-bottom: 0.1em solid rgb(74, 222, 128);
}

</style>
