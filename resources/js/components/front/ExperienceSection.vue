<script setup lang="ts">
import { ref } from 'vue';

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
    } >
} > ();

const activeIndex = ref(0);

function togglePanel(index: number) {
    activeIndex.value = activeIndex.value === index ? -1 : index;
}
</script>

<template>
    <section v-if="readyToLoad" id="experiences" class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6]" data-aos="fade-right">
        <h2 class="text-3xl font-bold mb-10 border-b-2 border-green-400 inline-block">
            Expériences & Projets
        </h2>
        <div class="space-y-10">
            <div v-for="(experience, index) in experiences" :key="experience.id">
                <div class="title-section cursor-pointer hover:bg-[#112240] transition-colors duration-200 rounded-md p-3" :class="{ 'bg-[#112240]': activeIndex === index }" @click="togglePanel(index)" role="button" tabindex="0">
                    <h3 class="text-1xl">
                        <span class="text-green-400">
                                De {{ new Date(experience.begin_at).toLocaleDateString('fr-CA', { month: 'long', year: 'numeric' }) }}
                                <span v-if="experience.end_at">
                                    à {{ new Date(experience.end_at).toLocaleDateString('fr-CA', { month: 'long', year: 'numeric' }) }}
                                </span>
                        <span v-else>à aujourd'hui</span>
                        </span>
                        <small>- {{ experience.location }}</small>
                    </h3>
                    <h2 class="text-xl font-semibold text-white">
                        {{ experience.job }}
                        <span v-if="experience.company"> @ <span class="text-green-400">{{ experience.company }}</span></span>
                    </h2>
                </div>

                <!-- Transition -->
                <transition name="expand">
                    <div v-show="activeIndex === index" class="overflow-hidden pt-8 text-1xl text-gray-600 dark:text-gray-400 leading-relaxed prose prose-invert max-w-none transition-all duration-300" v-html="experience.description"></div>
                </transition>
            </div>
        </div>
    </section>
</template>

<style scoped>
.prose-custom {
    line-height: 1.4 !important;
    margin: 0 !important;
    padding: 0 !important;
}

.prose-custom p,
.prose-custom ul,
.prose-custom ol {
    margin-top: 0.3rem !important;
    margin-bottom: 0.3rem !important;
    line-height: 1.4 !important;
}

/* Animation pour le dépliement */

.expand-enter-active,
.expand-leave-active {
    transition: max-height 0.4s ease, opacity 0.4s ease;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
}

.expand-enter-to,
.expand-leave-from {
    max-height: 1000px;
    opacity: 1;
}
</style>

<style>
#experiences a {
    color: rgb(74, 222, 128);
    text-decoration: none;
}

#experiences a:hover {
    color: rgb(74, 222, 128);
    text-decoration: underline;
}

.title-section {
    border-left: 0.4em solid rgb(74, 222, 128);
    padding-left: 10px;
}
</style>
