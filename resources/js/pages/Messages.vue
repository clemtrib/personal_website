<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import MessagesList from '../components/MessagesList.vue';
import Toast from '../components/Toast.vue';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{
    title: 'Messages',
    href: '/dashboard/messages/list',
}, ];

// Récupération des props envoyées par Inertia
const page = usePage();
const messages = computed(() => page.props.messages);
</script>

<template>
    <Toast :flash="page.props.flash" />
    <Head title="Messages" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <MessagesList :messages="messages" />
            </div>
        </div>
    </AppLayout>
</template>
