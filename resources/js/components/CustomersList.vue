<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Eraser, Pencil } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    customers: Array<{
        id: number;
        name: string;
        address_line_1: string;
        address_line_2: string;
    }>;
}>();

const customersList = ref([...props.customers]);

const emit = defineEmits(['open-delete-modal']);

const openDeleteModal = (id: number) => {
    emit('open-delete-modal', id);
};
</script>

<template>
    <div class="relative h-full w-full">
        <div class="relative z-10 p-4">
            <h2 class="mb-4 text-2xl font-bold">Clients</h2>
            <ul class="space-y-4">
                <li v-for="customer in customers" :key="customer.id" class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                    <h3>{{ customer.name }}</h3>
                    <p>{{ customer.address_line_1 }}</p>
                    <p>{{ customer.address_line_2 }}</p>
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
        </div>
    </div>
</template>
