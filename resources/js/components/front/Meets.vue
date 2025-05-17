<script setup lang="ts">
const props = defineProps < {
    readyToLoad: boolean
} > ();
</script>

<template>
    <section v-if="props.readyToLoad" id="meets" class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6]" data-aos="fade-bottom">
        <h2 class="text-3xl font-bold mb-6 border-b-2 border-green-400 inline-block">
            Prendre rendez-vous
        </h2>
    </section>
</template>
