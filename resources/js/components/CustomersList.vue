<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import { Eraser, Pencil } from 'lucide-vue-next';
import { ref } from 'vue';
import Pager from '../components/Pager.vue';

const props = defineProps<{
    customers: {
        data: Array<{
            id: number;
            name: string;
            address_line_1: string;
            address_line_2: string;
            zip_code: string;
            city: string;
            province: string;
            tjm: number;
        }>;
        current_page: number;
        last_page: number;
        links: Array<any>;
    };
    filters: {
        search?: string;
    };
}>();

const emit = defineEmits(['open-delete-modal']);

const openDeleteModal = (id: number) => {
    emit('open-delete-modal', id);
};

const search = ref(props.filters?.search || '');

const onSearch = () => {
    router.get(route('customers'), { search: search.value }, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <div class="relative h-full w-full">
        <div class="relative z-10 p-4">
            <h2 class="mb-4 text-2xl font-bold">Clients</h2>
                        <div class="mb-4">
                <input
                    type="text"
                    v-model="search"
                    placeholder="Rechercher un client..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    @input="onSearch"
                />
            </div>
            <ul class="space-y-4">
                <li v-for="customer in props.customers.data" :key="customer.id" class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                    <h3 class="text-2xl">{{ customer.name }}</h3>
                    <p>{{ customer.address_line_1 }}</p>
                    <p>{{ customer.address_line_2 }}</p>
                    <p>{{ customer.city }} {{ customer.province }} {{ customer.zip_code }}</p>
                    <p>Taux horaire : {{ customer.tjm }} $</p>
                    <div class="mt-4 flex gap-2">
                        <Link
                            :href="route('customers.edit', customer.id)"
                            class="flex items-center gap-2 rounded-lg bg-blue-100 px-3 py-2 text-sm transition hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50"
                        >
                            <Pencil class="h-4 w-4" /> Modifier
                        </Link>
                        <button
                            @click="openDeleteModal(customer.id)"
                            class="flex items-center gap-2 rounded-lg bg-red-100 px-3 py-2 text-sm transition hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50"
                        >
                            <Eraser class="h-4 w-4" />
                            Supprimer
                        </button>
                    </div>
                </li>
            </ul>
            <Pager :links="customers.links" />
        </div>
    </div>
</template>
