<script setup lang="ts">
import { Pencil, Eraser } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

defineProps < {
    meets: Array < {
        id: number;
        summary: string;
        recipient_email: string;
        recipient_fullname: string;
        start_datetime: string;
        end_datetime: string;
    } > ;
} > ();

const emit = defineEmits(['open-delete-modal']);

const openDeleteModal = (id: number) => {
    emit('open-delete-modal', id);
};
</script>

<template>
    <div class="relative w-full h-full">
        <div class="relative z-10 p-4">
            <h2 class="text-2xl font-bold mb-4">Rencontres</h2>
            <ul class="space-y-4">
                <li v-for="meet in meets" :key="meet.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 class="font-semibold" v-if="meet.recipient_fullname && meet.recipient_email">{{ meet.recipient_fullname }} - {{ meet.recipient_email }}</h3>
                    <p class="text-sm text-gray-600 dark:text-green-400">
                        Du {{ new Date(meet.start_datetime).toLocaleDateString() }} au {{ new Date(experience.end_datetime).toLocaleDateString() }}
                    </p>
                    <p v-if="meet.summary" class="mt-2 prose prose-invert max-w-none text-gray-800 dark:text-gray-400">{{ meet.summary }}</p>
                    <div class="flex gap-2 mt-4">
                        <Link :href="route('meets.edit', experience.id)" class="flex items-center gap-2 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded-lg transition">
                            <Pencil class="h-4 w-4" /> Modifier
                        </Link>
                        <button v-if="!meet.recipient_fullname && !meet.recipient_email" @click="openDeleteModal(experience.id)" class="flex items-center gap-2 px-3 py-2 text-sm bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition">
                            <Eraser class="h-4 w-4" />
                            Supprimer
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
