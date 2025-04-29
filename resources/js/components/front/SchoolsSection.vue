
<script setup lang="ts">
const props = defineProps < {
    readyToLoad: boolean,
    schools: Array < {
        id: BigInteger,
        school: string,
        graduation: string,
        date: string,
    } > ;
} > ();
</script>

<template>
    <section v-if="props.readyToLoad && props.readyToLoad == true" id="schools" class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6]" data-aos="fade-left">
        <h2 class="text-3xl font-bold mb-10 border-b-2 border-green-400 inline-block">Formation</h2>
        <div class="space-y-10"  v-for="school in schools" :key="school.id">
            <p class="mt-4 text-[#8892b0]">
                <span class="text-green-400">{{ new Date(school.date).toLocaleDateString('fr-FR', { year: 'numeric' }) }}</span> - {{ school.school }} <small>{{ school.location }}</small>
                <span v-html="school.graduation"></span>
            </p>
        </div>
    </section>
</template>
