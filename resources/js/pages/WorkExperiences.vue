<script setup lang="ts">
import api from '@/api';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import WorkExperiencesList from '../components/WorkExperiencesList.vue';
import { onMounted, ref, watch } from 'vue';
import axios from 'axios';
import { CirclePlus } from 'lucide-vue-next';
import { useToast } from 'vue-toastification';

const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expériences',
        href: '/dashboard/workExperiences',
    },
];

const workExperiences = ref([]);

onMounted(async () => {
    try {
        const response = await api.get('/api/experiences/list');
        workExperiences.value = response.data;
    } catch (error) {
        console.error('Erreur lors de la récupération des messages:', error);
    }
});

defineProps<{
    name?: string;
}>();

const page = usePage();

watch(
  () => page.props.flash, // Surveillance de l'objet flash complet
  (newFlashValues) => { // Paramètre renommé pour clarté
    if (newFlashValues.success) {
        toast.success(newFlashValues.success, {
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
        });
    }
    if (newFlashValues.error) {
        toast.error(newFlashValues.error, {
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
        });
    }
  },
  { deep: true, immediate: true } // Déclenche au montage du composant
);

</script>

<template>
    <Head title="Expériences" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-auto min-h-[50px] flex-col gap-4 rounded-xl p-4">
            <div class="relative h-full rounded-xl border border-sidebar-border/70 dark:border-sidebar-border overflow-hidden">
                <a :href="route('experiences.create')" class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                    <component :is="CirclePlus" />
                    <span>Ajouter</span>
                </a>
            </div>
        </div>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <WorkExperiencesList :workExperiences="workExperiences" />
            </div>
        </div>
    </AppLayout>
</template>
