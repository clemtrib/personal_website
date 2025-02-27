<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import MessagesList from '../components/MessagesList.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { CirclePlus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expériences',
        href: '/dashboard/workExperiences',
    },
];

const workExperiences = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/experiences/list');
        workExperiences.value = response.data;
    } catch (error) {
        console.error('Erreur lors de la récupération des messages:', error);
    }
});

defineProps<{
    name?: string;
}>();
</script>

<template>
    <Head title="Expériences" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <WorkExperiencesList :workExperiences="workExperiences" />
            </div>
        </div>
        <div class="flex h-20 flex-col gap-4 rounded-xl p-4">
            <div class="relative h-full rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <Link href="" class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                    <component :is="CirclePlus" class="h-5 w-5" />
                    Ajouter
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
