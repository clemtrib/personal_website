<script setup lang="ts">
import { Facebook, Linkedin, Github } from 'lucide-vue-next';

const props = defineProps < {
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
    <footer class="p-6 text-center bg-black text-white">
        <p>codé à <span class="text-green-400">Montréal</span></p>
        <p>avec <span class="text-green-400">Laravel 12</span> et <span class="text-green-400">Vue.js 3</span></p>
        <p>- <span class="text-green-400">2025</span> -</p>
        <div class="flex justify-center gap-4 mt-2">
            <a v-if="props.config?.social_networks?.github" :href="props.config.social_networks.github" class="hover:text-green-400" target="_blank">
                <Github />
            </a>
            <a v-if="props.config?.social_networks?.linkedin" :href="props.config.social_networks.linkedin" class="hover:text-green-400" target="_blank">
                <Linkedin />
            </a>
            <a v-if="props.config?.social_networks?.facebook" :href="props.config.social_networks.facebook" class="hover:text-green-400" target="_blank">
                <Facebook />
            </a>
        </div>
    </footer>
</template>
