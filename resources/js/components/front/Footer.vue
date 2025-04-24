<script setup lang="ts">
import { Facebook, Linkedin, Github } from 'lucide-vue-next';
</script>

<template>
    <footer class="p-6 text-center bg-black text-white">
        <p>Montréal, Québec, Canada</p>
        <div class="flex justify-center gap-4 mt-2">
            <a href="#" class="hover:text-green-400">
                <Facebook />
            </a>
            <a href="#" class="hover:text-green-400">
                <Linkedin />
            </a>
            <a href="#" class="hover:text-green-400">
                <Github />
            </a>
        </div>
    </footer>
</template>
