<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navigation from '@/components/front/pages/Navigation.vue';
import HeroSection from '@/components/front/pages/HeroSection.vue';
import BodySection from '@/components/front/pages/BodySection.vue';
import Footer from '@/components/front/pages/Footer.vue';

// Reçois les données d'Inertia
const props = defineProps<{
    page_slug: string,
    page_name: string,
    hero_subtitle: string,
    hero_title: string,
    hero_description: string,
    hero_image: string,
    content_text: string,
    content_image: string,
    meta: {
        description: string,
        canonical: string,
        "og:title": string,
        "og:description": string,
        "og:image": string,
        "og:url": string,
        "og:type": string,
        "og:site_name": string,
        "twitter:card": string,
        "twitter:title": string,
        "twitter:description": string,
        "twitter:image": string,
    },
    config: {
        social_networks: {
            facebook: string | null,
            github: string | null,
            linkedin: string | null,
        },
        tjm: string | null,
    },
    meetings: any, // À typer selon ta structure de $timeslots si tu veux plus de précision
}>();
</script>

<template>
    <Head :title="page_name">
        <!-- Balises SEO dynamiques -->
        <meta name="description" :content="meta.description" />
        <meta property="og:title" :content="meta['og:title']" />
        <meta property="og:image" :content="meta['og:image']" />
        <!-- ... autres balises -->
    </Head>

    <div class="font-sans scroll-smooth">
        <Navigation
            :title="hero_subtitle"
        />
        <HeroSection
            :title="hero_title"
            :description="hero_description"
            :image="hero_image"
        />
        <BodySection
            :text="content_text"
            :image="content_image"
        />
        <Footer :config="config" />
    </div>
</template>
