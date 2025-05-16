<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import SkillsForm from '../components/SkillsForm.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { CirclePlus } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Compétences',
        href: '/dashboard/skills/list',
    },
    {
        title: 'Édition',
    },
];

const props = defineProps({
    skill: {
        type: Object,
        default: () => null // Modifier ici
    }
});

const showAlert = ref(false);

onMounted(() => {
    if (usePage().props.flash?.success) {
        showAlert.value = true;
        setTimeout(() => showAlert.value = false, 3000);
    }
});

</script>

<template>
    <!-- Alert -->
    <div v-if="showAlert" class="fixed top-4 right-4 bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded">
        {{ $page.props.flash.success }}
    </div>

    <Head title="Compétences" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <SkillsForm :skill=props.skill />
            </div>
        </div>
    </AppLayout>
</template>
