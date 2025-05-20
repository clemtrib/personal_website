<script setup lang="ts">
import { Pencil, Eraser } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

defineProps < {
    timeslots: Array < {
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
                <li v-for="meet in timeslots" :key="meet.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex items-center gap-4">
                    <!-- Bloc date -->
                    <div class="text-center text-xl font-bold text-gray-700 dark:text-gray-300 w-12 shrink-0">
                        <div class="text-gray-600 dark:text-green-400"><pre>{{ new Date(meet.start_datetime).getDate().toString().padStart(2, '0') }}</pre></div>
                        <div><pre>{{ (new Date(meet.start_datetime).getMonth() + 1).toString().padStart(2, '0') }}</pre></div>
                        <div><pre>{{ new Date(meet.start_datetime).getFullYear().toString().slice(-2) }}</pre></div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="flex-1">
                        <div class="flex justify-between items-center text-sm text-gray-600 dark:text-green-400 mb-1">

                            <div>
                                <span v-if="meet.recipient_fullname == null && meet.recipient_email == null" class="text-gray-600 dark:text-gray-400">
                                    Plage horaire disponible de
                                </span>
                                <span>
                                {{
                                new Date(meet.start_datetime).toLocaleTimeString('fr-CA', { hour: '2-digit', minute: '2-digit' })
                                }}  <span class="text-gray-600 dark:text-gray-400"> à </span>
                                {{
                                new Date(meet.end_datetime).toLocaleTimeString('fr-CA', { hour: '2-digit', minute: '2-digit' })
                                }}
                                </span>
                            </div>
                        </div>

                        <h3 class="font-semibold" v-if="meet.recipient_fullname && meet.recipient_email">
                            {{ meet.recipient_fullname }} - {{ meet.recipient_email }}
                        </h3>

                        <p v-if="meet.summary" class="mt-2 prose prose-invert max-w-none text-gray-800 dark:text-gray-400">
                            {{ meet.summary }}
                        </p>

                        <div class="flex gap-2 mt-4">
                            <button v-if="!meet.recipient_fullname && !meet.recipient_email" @click="openDeleteModal(meet.id)" class="flex items-center gap-2 px-3 py-2 text-sm bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition">
            <Eraser class="h-4 w-4" />
            Supprimer
          </button>
                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</template>
