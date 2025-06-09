<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Download, Eraser, Paperclip } from 'lucide-vue-next';
import { ref } from 'vue';
import Pager from '../components/Pager.vue';

const props = defineProps<{
    customers: Array<{
        id: number;
        name: string;
    }>;
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
    filters: {
        customer?: number;
        id?: number;
        start_date?: string;
        end_date?: string;
    };
}>();

const emit = defineEmits(['open-delete-modal']);

// Réactifs pour les filtres
const customerFilter = ref(props.filters?.customer || '');
const idFilter = ref(props.filters?.id || '');
const startDate = ref(props.filters?.start_date || '');
const endDate = ref(props.filters?.end_date || '');

const applyFilters = () => {
    router.get(
        route('bills'),
        {
            customer: customerFilter.value,
            id: idFilter.value,
            start_date: startDate.value,
            end_date: endDate.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

// Délai pour éviter les requêtes excessives
let timeoutId;
const debouncedApply = () => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(applyFilters, 300);
};

const openDeleteModal = (id: number) => {
    emit('open-delete-modal', id);
};
</script>

<template>
    <div class="relative h-full w-full">
        <div class="relative z-10 p-4">
            <h2 class="mb-4 text-2xl font-bold">Facturation</h2>

            <!-- Filtres -->
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <select
                    v-model="customerFilter"
                    @change="applyFilters"
                    class="rounded-lg border border-gray-300 p-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                >
                    <option value="">Tous les clients</option>
                    <option v-for="customer in props.customers" :value="customer.id" :key="customer.id">
                        {{ customer.name }}
                    </option>
                </select>

                <input
                    type="number"
                    v-model="idFilter"
                    @input="debouncedApply"
                    placeholder="ID Facture"
                    class="rounded-lg border border-gray-300 p-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />

                <input
                    type="date"
                    v-model="startDate"
                    @change="applyFilters"
                    class="rounded-lg border border-gray-300 p-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />

                <input
                    type="date"
                    v-model="endDate"
                    @change="applyFilters"
                    class="rounded-lg border border-gray-300 p-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
            </div>

            <!-- Liste des factures -->
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
