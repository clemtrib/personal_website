<script setup lang="ts">
defineProps < {
    hobbies: Array < {
        id: number;
        title: string;
        description: string;
        order: number;
    } > ;
} > ();
</script>

<template>
    <section id="hobbies" class="px-6 py-20 bg-[#112240] text-[#ccd6f6] flex flex-col md:flex-row items-center gap-10">
        <div class="md:w-1/2">
            <h2 class="text-3xl font-bold mb-6 border-b-2 border-green-400 inline-block">Loisirs</h2>
            <ul class="space-y-4 text-[#8892b0]">
                <li v-for="hobby in hobbies" :key="hobby.id"><strong>{{ hobby.title }}</strong> {{ hobby.description }}</li>
            </ul>
        </div>
        <div class="md:w-1/2">
            <img src="" alt="" class="rounded-xl w-64 h-64 object-cover">
        </div>
    </section>
</template>
