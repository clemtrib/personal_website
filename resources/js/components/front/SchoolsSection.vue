
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
    <section class="min-h-screen flex flex-col justify-center px-6 bg-[#0a192f] text-[#ccd6f6]">
        <p class="mt-4 max-w-xl text-[#8892b0]" v-for="school in schools" :key="school.id">
            <!-- Contenu expérience... -->
            {{ school.school }} <small>{{ school.location }}</small>
            <br /> {{ new Date(school.date).toLocaleDateString() }}
            <br /> {{ school.description }}
        </p>
    </section>
</template>
