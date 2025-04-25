
<script setup lang="ts">
const props = defineProps < {
    schools: Array < {
        id: BigInteger,
        school: string,
        graduation: string,
        date: string,
    } > ;
} > ();
</script>

<template>
    <section id="schools" class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6]">
        <h2 class="text-3xl font-bold mb-10 border-b-2 border-green-400 inline-block">Formation</h2>
        <div class="space-y-10">
            <p class="mt-4 max-w-xl text-[#8892b0]" v-for="school in schools" :key="school.id">
                <!-- Contenu expérience... -->
                {{ school.school }} <small>{{ school.location }}</small>
                <br /> {{ new Date(school.date).toLocaleDateString() }}
                <br /> {{ school.description }}
            </p>
        </div>
    </section>
</template>
