<script setup lang="ts">
import Pager from '../components/Pager.vue';
import { Download, Eraser, Paperclip } from 'lucide-vue-next';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const navigate = (url: string) => {
  if (url) {
    router.visit(url, {
      preserveScroll: true,
      preserveState: true,
    });
  }
};

const props = defineProps<{
    bills: {
        data: Array<{
            id: number;
            customer_name: string;
            start_date: string;
            end_date: string;
            total: string;
            is_send: boolean;
        }>;
        current_page: number;
        last_page: number;
        links: Array<any>;
    };
}>();

const emit = defineEmits(['open-delete-modal']);

const openDeleteModal = (id: number) => {
    emit('open-delete-modal', id);
};
</script>

<template>
    <div class="relative h-full w-full">
        <div class="relative z-10 p-4">
            <h2 class="mb-4 text-2xl font-bold">Facturation</h2>
            <ul class="space-y-4">
                <li v-for="bill in props.bills.data" :key="bill.id" class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                    <p>Du {{ new Date(bill.start_date).toLocaleDateString('fr-FR') }} au {{ new Date(bill.end_date).toLocaleDateString('fr-FR') }}</p>
                    <h3 class="text-2xl">Facture #{{ bill.id }} - {{ bill.customer_name }}</h3>
                    <p>Total : {{ bill.total }} $</p>
                    <div class="mt-4 flex gap-2">
                        <a
                            :href="route('bills.download', bill.id)"
                            class="flex items-center gap-2 rounded-lg bg-blue-100 px-3 py-2 text-sm transition hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50"
                            target="_blank"
                        >
                            <Download class="h-4 w-4" /> Télécharger
                        </a>
                        <a
                            v-if="!bill.is_send"
                            :href="route('bills.sendByMail', bill.id)"
                            class="flex items-center gap-2 rounded-lg bg-blue-100 px-3 py-2 text-sm transition hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50"
                        >
                            <Paperclip class="h-4 w-4" />Envoyer par email
                        </a>
                        <button
                            @click="openDeleteModal(bill.id)"
                            class="flex items-center gap-2 rounded-lg bg-red-100 px-3 py-2 text-sm transition hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50"
                        >
                            <Eraser class="h-4 w-4" />
                            Supprimer
                        </button>
                    </div>
                </li>
            </ul>

            <Pager :links="bills.links" />
        </div>
    </div>
</template>
