<script setup lang="ts">
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
    <section v-if="props.readyToLoad && props.readyToLoad == true && props.config?.tjm" id="tjm" class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6] text-center" data-aos="flip-up">
        <h2 class="text-3xl font-bold mb-4 border-b-2 border-green-400 inline-block">TJM</h2>
        <p class="text-2xl mt-4 text-white">Disponible pour missions freelance à partir de <span class="text-green-400 font-semibold">{{ props.config.tjm }} CAD/jour</span></p>
        <p class="mt-2 text-[#8892b0]">Tarif ajustable selon la complexité et la durée du projet.</p>
    </section>
</template>
