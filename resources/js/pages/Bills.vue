<script setup lang="ts">
import api from '@/api';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import BillsList from '../components/BillsList.vue';
import DeleteModale from '../components/DeleteModale.vue';
import Toast from '../components/Toast.vue';
import { onMounted, ref, computed } from 'vue';
import { CirclePlus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Facturation',
        href: '/dashboard/bills/list',
    },
];

// État de la modale
const showDeleteModal = ref(false);
const objectIdToDelete = ref < number | null > (null);

const openDeleteModal = (id: number) => {
    objectIdToDelete.value = id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    objectIdToDelete.value = null;
};

const page = usePage();
const bills = computed(() => page.props.bills);

onMounted(async () => {
    const message = localStorage.getItem('flashMessage');
    const type = localStorage.getItem('flashType');
    if (message) {
        page.props.flash = {
            [type]: message
        };
        localStorage.removeItem('flashMessage');
        localStorage.removeItem('flashType');
    }
});

</script>

<template>
    <Toast :flash="page.props.flash" />
    <Head title="Facturation" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Contenu principal -->
        <div class="flex h-auto min-h-[50px] flex-col gap-4 rounded-xl p-4">
            <div class="relative h-full rounded-xl border border-sidebar-border/70 dark:border-sidebar-border overflow-hidden">
                <a :href="route('bills.create')" class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                    <component :is="CirclePlus" />
                    <span>Ajouter</span>
                </a>
            </div>
        </div>

        <!-- Liste des clients -->
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <BillsList :bills="bills" @open-delete-modal="openDeleteModal" />
            </div>
        </div>

        <!-- Modale de suppression -->
        <DeleteModale :show="showDeleteModal" :object-id="objectIdToDelete" @close="closeDeleteModal" action-form="bills.destroy" />
    </AppLayout>
</template>
