<script setup lang="ts">
import { Facebook, Linkedin, Github } from 'lucide-vue-next';

const props = defineProps < {
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
        <p>Montréal, Québec, Canada</p>
        <div class="flex justify-center gap-4 mt-2">
            <a v-if="props.config?.social_networks?.linkedin" :href="props.config.social_networks.linkedin" class="hover:text-green-400" target="_blank">
                <Linkedin />
            </a>
            <a v-if="props.config?.social_networks?.github" :href="props.config.social_networks.github" class="hover:text-green-400" target="_blank">
                <Github />
            </a>
            <a v-if="props.config?.social_networks?.facebook" :href="props.config.social_networks.facebook" class="hover:text-green-400" target="_blank">
                <Facebook />
            </a>
        </div>
    </footer>
</template>
