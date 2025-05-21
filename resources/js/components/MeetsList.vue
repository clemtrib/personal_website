<script setup lang="ts">
import { Eraser } from 'lucide-vue-next';

defineProps<{
    timeslots: Array<{
        id: number;
        summary: string;
        recipient_email: string;
        recipient_fullname: string;
        start_datetime: string;
        end_datetime: string;
    }>;
}>();

const emit = defineEmits(['open-delete-modal']);

const openDeleteModal = (id: number) => {
    emit('open-delete-modal', id);
};
</script>

<template>
    <div class="relative h-full w-full">
        <div class="relative z-10 p-4">
            <h2 class="mb-4 text-2xl font-bold">Rencontres</h2>
            <ul class="space-y-4">
                <li v-for="meet in timeslots" :key="meet.id" class="flex items-center gap-4 rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                    <!-- Bloc date -->
                    <div class="w-12 shrink-0 text-center text-xl font-bold text-gray-700 dark:text-gray-300">
                        <div class="text-gray-600 dark:text-green-400">
                            <pre>{{ new Date(meet.start_datetime).getDate().toString().padStart(2, '0') }}</pre>
                        </div>
                        <div>
                            <pre>{{ (new Date(meet.start_datetime).getMonth() + 1).toString().padStart(2, '0') }}</pre>
                        </div>
                        <div>
                            <pre>{{ new Date(meet.start_datetime).getFullYear().toString().slice(-2) }}</pre>
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="flex-1">
                        <div class="mb-1 flex items-center justify-between text-sm text-gray-600 dark:text-green-400">
                            <div>
                                <span v-if="meet.recipient_fullname == null && meet.recipient_email == null" class="text-gray-600 dark:text-gray-400">
                                    Plage horaire disponible de
                                </span>
                                <span>
                                    {{ new Date(meet.start_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                    <span class="text-gray-600 dark:text-gray-400"> à </span>
                                    {{ new Date(meet.end_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                </span>
                            </div>
                        </div>

                        <h3 class="font-semibold" v-if="meet.recipient_fullname && meet.recipient_email">
                            {{ meet.recipient_fullname }} - {{ meet.recipient_email }}
                        </h3>

                        <p v-if="meet.summary" class="prose prose-invert mt-2 max-w-none text-gray-800 dark:text-gray-400">
                            {{ meet.summary }}
                        </p>

                        <div class="mt-4 flex gap-2">
                            <button
                                v-if="!meet.recipient_fullname && !meet.recipient_email"
                                @click="openDeleteModal(meet.id)"
                                class="flex items-center gap-2 rounded-lg bg-red-100 px-3 py-2 text-sm transition hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50"
                            >
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
