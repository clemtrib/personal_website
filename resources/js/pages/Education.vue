<script setup lang="ts">
import api from '@/api';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import EducationList from '../components/EducationList.vue';
import Toast from '../components/Toast.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { CirclePlus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Diplômes',
        href: '/dashboard/education',
    },
];

const educations = ref([]);

onMounted(async () => {
    try {
        const response = await api.get('/api/education/list');
        educations.value = response.data;
        console.log(educations.value)
    } catch (error) {
        console.error('Erreur lors de la récupération des messages:', error);
    }
});

defineProps<{
    name?: string;
}>();

const page = usePage();

</script>

<template>
    <Toast :flash="page.props.flash" />
    <Head title="Diplômes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-auto min-h-[50px] flex-col gap-4 rounded-xl p-4">
            <div class="relative h-full rounded-xl border border-sidebar-border/70 dark:border-sidebar-border overflow-hidden">
                <a :href="route('education.create')" class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                    <component :is="CirclePlus" />
                    <span>Ajouter</span>
                </a>
            </div>
        </div>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <EducationList :educations="educations" />
            </div>
        </div>
    </AppLayout>
</template>
