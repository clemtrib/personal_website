<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import MessagesList from '../components/MessagesList.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Messages',
        href: '/dashboard/messages',
    },
];

const messages = ref([]);

const page = ref([]);
const schools = ref([]);
const skills = ref([]);
const experiences = ref([]);
const hobbies = ref([]);

onMounted(async () => {
    try {
        var response = await axios.get('/messages/list');
        page.value = response.data;

        response = await axios.get('/messages/list');
        schools.value = response.data;

        response = await axios.get('/messages/list');
        skills.value = response.data;

        response = await axios.get('/experiences/list');
        experiences.value = response.data;

        response = await axios.get('/messages/list');
        hobbies.value = response.data;
    } catch (error) {
        console.error('Erreur lors de la récupération des messages:', error);
    }
});

defineProps<{
    name?: string;
}>();
</script>

<template>
    <Head title="Messages" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <MessagesList :messages="messages" />
            </div>
        </div>
    </AppLayout>
</template>
