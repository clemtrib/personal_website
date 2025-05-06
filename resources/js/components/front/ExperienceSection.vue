<script setup lang="ts">
defineProps < {
    readyToLoad: boolean,
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
    <section v-if="readyToLoad && readyToLoad == true" id="experiences" class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6]" data-aos="fade-right">
        <h2 class="text-3xl font-bold mb-10 border-b-2 border-green-400 inline-block">Expériences & Projets</h2>
        <div class="space-y-10">
            <div v-for="experience in experiences" :key="experience.id">
                <h3 class="text-1xl">
                    <span class="text-green-400">
                        De {{ new Date(experience.begin_at).toLocaleDateString('fr-CA', { month: 'long', year: 'numeric' }) }}
                        <span v-if="experience.end_at">
                            à {{ new Date(experience.end_at).toLocaleDateString('fr-CA', { month: 'long', year: 'numeric' }) }}
                        </span>
                        <span v-else>
                            à aujourd'hui
                        </span>
                    </span> <small>- {{ experience.location }}</small>
                </h3>
                <h2 class="text-xl font-semibold text-white">{{ experience.job }}
                    <span :v-if="experience.company"> @ <span class="text-green-400">{{ experience.company }}</span></span></h2>
                <div class="block w-full md:w-1/2 text-1xl text-gray-600 dark:text-gray-400 text-justify prose prose-invert max-w-none" v-html="experience.description"></div>
            </div>
        </div>
    </section>
</template>
