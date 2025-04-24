<script setup lang="ts">
import { computed, ref, defineExpose } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Pencil, Eraser } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps < {
    educations: Array < {
        id: BigInteger,
        school: string,
        graduation: string,
        date: string,
    } > ;
} > ();

const emit = defineEmits(['open-delete-modal']);

const openDeleteModal = (id: number) => {
    console.log('HERE ' + id)
    emit('open-delete-modal', id);
};
</script>

<template>
    <!-- Contenu principal -->
    <div class="relative w-full h-full">
        <!-- SVG pattern... -->
        <div class="relative z-10 p-4">
            <h2 class="text-2xl font-bold mb-4">Diplômes</h2>
            <ul class="space-y-4">
                <li v-for="education in educations" :key="education.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <!-- Contenu expérience... -->

                    <h3 class="font-semibold">{{ education.school }} <small>{{ education.location }}</small></h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ new Date(education.date).toLocaleDateString() }}
                    </p>
                    <p class="mt-2" v-html="education.description"></p>

                    <div class="flex gap-2 mt-4">
                        <Link :href="route('education.edit', education.id)" class="flex items-center gap-2 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded-lg transition">
                        <Pencil class="h-4 w-4" /> Modifier
                        </Link>

                        <button @click="openDeleteModal(education.id)" class="flex items-center gap-2 px-3 py-2 text-sm bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition">
                      <Eraser class="h-4 w-4" />
                      Supprimer
                    </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>


