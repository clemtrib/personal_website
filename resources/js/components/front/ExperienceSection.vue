<script setup lang="ts">
defineProps < {
    experiences: Array < {
        id: number;
        location: string;
        company: string;
        job: string;
        description: string;
        begin_at: string;
        end_at: string;
    } > ;
} > ();
</script>

<template>
    <section class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6]">
        <h2 class="text-3xl font-bold mb-10 border-b-2 border-green-400 inline-block">Expériences & Projets</h2>
        <div class="space-y-10">
            <div v-for="experience in experiences" :key="experience.id">
                <h3 class="text-xl font-semibold text-white">{{ experience.job }} - {{ experience.company }} <small>{{ experience.location }}</small></h3>
                <p class="text-[#8892b0] mt-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Du {{ new Date(experience.begin_at).toLocaleDateString() }}
                        <template v-if="experience.end_at">
                            au {{ new Date(experience.end_at).toLocaleDateString() }}
                        </template>
                        <template v-else>
                            à aujourd'hui
                        </template>
                    </p>
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400" v-html="experience.description"></p>
            </div>
        </div>
    </section>
</template>
