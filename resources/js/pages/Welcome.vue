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
import Meets from '@/components/front/Meets.vue';
import Footer from '@/components/front/Footer.vue';

const page = ref([]),
    schools = ref([]),
    skills = ref([]),
    experiences = ref([]),
    hobbies = ref([]),
    config = ref([]),
    home = ref([]),
    meetings = ref([]),
    readyToLoad = ref([]),
    googleauth = ref([]),
    googleauthurl = ref([]);

readyToLoad.value = false;
const siteKey =
    import.meta.env.VITE_RECAPTCHA_SITE_KEY;

const appUrl =
    import.meta.env.VITE_APP_URL || window.location.origin;

onMounted(async () => {
    if (siteKey && typeof window.grecaptcha === 'undefined') {
        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
    try {
        var response = await axios.get('/api/spa/list');
        experiences.value = response.data.experiences;
        schools.value = response.data.schools;
        hobbies.value = response.data.hobbies;
        skills.value = response.data.skills;
        config.value = response.data.config;
        home.value = response.data.content;
        meetings.value = response.data.meetings;
        googleauth.value = response.data.google_auth;
        googleauthurl.value = response.data.google_auth_url;
        readyToLoad.value = true;
    } catch (error) {
        console.error('Erreur lors de la récupération des messages:', error);
    }
});
</script>

<template>
    <Head v-if="readyToLoad" title="Portfolio">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="font-sans scroll-smooth">
        <Navigation :home=home :config=config :readyToLoad=readyToLoad />
        <HeroSection :home=home :readyToLoad=readyToLoad />
        <SchoolsSection :schools=schools :readyToLoad=readyToLoad />
        <SkillsSection :skills=skills :readyToLoad=readyToLoad />
        <ExperienceSection :experiences=experiences :readyToLoad=readyToLoad />
        <HobbiesSection :home=home :hobbies=hobbies :readyToLoad=readyToLoad />
        <TJMSection :config=config :readyToLoad=readyToLoad />
        <ContactForm :readyToLoad=readyToLoad />
        <div id="meets">
            <Meets v-if="readyToLoad" :readyToLoad=readyToLoad :meetings=meetings :googleauth=googleauth :googleauthurl=googleauthurl />
        </div>
        <Footer :config=config :readyToLoad=readyToLoad />
    </div>
</template>
