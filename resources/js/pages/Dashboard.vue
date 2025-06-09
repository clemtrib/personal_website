<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import SalesChart from '../components/SalesChart.vue';

import { Calendar, Mail, Receipt, Recycle } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps<{
    name?: string;
    annualSummary: Array<{
        year: number;
        total_subtotal: number;
        total_tps: number;
        total_tvq: number;
        total_total: number;
    }>;
    last12MonthsSales: Array<{
        month: string;
        total_subtotal_month: number;
    }>;
    recentBills: Array<{
        id: number;
        customer_name: string;
        subtotal: number;
        total: number;
    }>;
    messages: Array<{
        id: number;
        fullname: string;
        email: string;
        message: string;
    }>;
    meets: Array<{
        id: number;
        recipient_email: string;
        recipient_fullname: string;
        start_datetime: string;
        end_datetime: string;
        link: string;
    }>;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Contenu principal -->
        <div class="flex h-auto min-h-[50px] flex-col gap-4 rounded-xl p-4">
            <div class="relative h-full overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <a :href="route('dashboard.cache')" class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                    <component :is="Recycle" />
                    <span>Clear cache</span>
                </a>
            </div>
        </div>

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <h3 class="mb-4 flex items-center text-xl font-medium"><Receipt class="mr-2" /> Facturation</h3>
                    <div class="overflow-auto">
                        <table class="w-full border border-gray-200 dark:border-gray-600">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Année</th>
                                    <th class="px-4 py-2 text-right">Sous-total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="summary in annualSummary"
                                    :key="summary.year"
                                    class="border-t border-gray-200 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800"
                                >
                                    <td class="px-4 py-2 text-left text-green-400">{{ summary.year }}</td>
                                    <td class="px-4 py-2 text-right text-green-400">{{ summary.total_subtotal.toFixed(2) }} $</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="mb-2 mt-4 text-lg font-medium">Dernières factures</h4>
                    <div v-for="bill in recentBills" :key="bill.id" class="mb-2 rounded border border-gray-200 p-2 dark:border-gray-600">
                        <p class="text-base">{{ bill.customer_name }}</p>
                        <div class="grid grid-cols-2 gap-2">
                            <span class="text-sm">
                                <a :href="route('bills.download', bill.id)" target="_blank" class="text-green-400" :title="bill.id">{{
                                    new Date(bill.created_at).toLocaleDateString('fr-FR')
                                }}</a>
                            </span>
                            <span class="text-right text-sm">{{ bill.subtotal.toFixed(2) }} $</span>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <h3 class="mb-4 flex items-center text-xl font-medium"><Calendar class="mr-2" /> Rencontres à venir</h3>
                    <div v-for="meet in meets" :key="meet.id" class="mb-2 rounded border border-gray-200 p-2 dark:border-gray-600">
                        <p class="text-base text-green-400">
                            <a :href="`mailto:${meet.recipient_email}`">{{ meet.recipient_fullname }}</a>
                        </p>
                        <p class="text-base">
                            <a :href="meet.link">
                                le
                                {{ new Date(meet.start_datetime).toLocaleDateString() }}
                                de
                                {{ new Date(meet.start_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                à
                                {{ new Date(meet.end_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                            </a>
                        </p>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <h3 class="mb-4 flex items-center text-xl font-medium"><Mail class="mr-2" /> Derniers messages</h3>
                    <div v-for="message in messages" :key="message.id" class="mb-2 rounded border border-gray-200 p-2 dark:border-gray-600">
                        <p class="text-base text-green-400">
                            <a :href="`mailto:${message.email}`">{{ message.fullname }}</a>
                        </p>
                        <p class="text-xs">{{ message.message }}</p>
                    </div>
                </div>
            </div>

            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <SalesChart :data="last12MonthsSales" />
            </div>
        </div>
    </AppLayout>
</template>
