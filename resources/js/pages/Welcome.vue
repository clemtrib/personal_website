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

onMounted(async () => {
    try {
        var response = await axios.get('/api/spa/list');
        experiences.value = response.data.experiences;
        schools.value = response.data.schools;
        hobbies.value = response.data.hobbies;

    } catch (error) {
        console.error('Erreur lors de la récupération des messages:', error);
    }
});
</script>

<template>
    <div class="font-sans scroll-smooth">
        <Navigation />
        <HeroSection />
        <SchoolsSection :schools=schools />
        <SkillsSection />
        <ExperienceSection :experiences=experiences />
        <HobbiesSection :hobbies=hobbies />
        <TJMSection />
        <ContactForm />
        <Footer />
    </div>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"> Dashboard
                </Link>
                <template v-else>
                        <Link
                            :href="route('contact')"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                        >
                           Contact
                        </Link>
                        <Link
                            :href="route('login')"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="route('register')"
                            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                        >
                            Register
                        </Link>
                </template>
            </nav>
        </header>
    </div>
</template>
