<script setup lang="ts">
import { Pencil, Eraser } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

defineProps < {
    hobbies: Array < {
        id: number;
        title: string;
        description: string;
        order: number;
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
            <h2 class="text-2xl font-bold mb-4">Loisirs</h2>
            <ul class="space-y-4">
                <li v-for="hobby in hobbies" :key="hobby.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3><span  class="font-semibold">{{ hobby.title }}</span> {{hobby.description}} </h3>
                    <div class="flex gap-2 mt-4">
                        <Link :href="route('hobbies.edit', hobby.id)" class="flex items-center gap-2 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded-lg transition">
                            <Pencil class="h-4 w-4" /> Modifier
                        </Link>
                        <button @click="openDeleteModal(hobby.id)" class="flex items-center gap-2 px-3 py-2 text-sm bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition">
                            <Eraser class="h-4 w-4" />
                            Supprimer
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
