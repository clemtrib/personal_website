<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { onMounted, ref } from 'vue';
import axios from 'axios';

import Navigation from '@/components/front/Navigation.vue';
import HeroSection from '@/components/front/HeroSection.vue';
import SchoolsSection from '@/components/front/SchoolsSection.vue';
import SkillsSection from '@/components/front/SkillsSection.vue';
import ExperienceSection from '@/components/front/ExperienceSection.vue';
import HobbiesSection from '@/components/front/HobbiesSection.vue';
import TJMSection from '@/components/front/TJMSection.vue';
import ContactForm from '@/components/front/ContactForm.vue';
import Footer from '@/components/front/Footer.vue';

const page = ref([]);
const schools = ref([]);
const skills = ref([]);
const experiences = ref([]);
const hobbies = ref([]);
const config = ref([]);

onMounted(async () => {
    try {
        var response = await axios.get('/api/spa/list');
        experiences.value = response.data.experiences;
        schools.value = response.data.schools;
        hobbies.value = response.data.hobbies;
        skills.value = response.data.skills;
        config.value = response.data.config;
    } catch (error) {
        console.error('Erreur lors de la récupération des messages:', error);
    }
});
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="font-sans scroll-smooth">
        <Navigation :config=config />
        <HeroSection />
        <SchoolsSection :schools=schools />
        <SkillsSection :skills=skills />
        <ExperienceSection :experiences=experiences />
        <HobbiesSection :hobbies=hobbies />
        <TJMSection :config=config />
        <ContactForm />
        <Footer :config=config />
    </div>
</template>
