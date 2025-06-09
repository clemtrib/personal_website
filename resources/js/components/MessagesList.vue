<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Eraser } from 'lucide-vue-next';
import { computed, defineExpose, ref } from 'vue';
import Pager from '../components/Pager.vue';

const props = defineProps<{
    messages: {
        data: Array<{
            id: number;
            fullname: string;
            email: string;
            message: string;
            created_at: string;
        }>;
        current_page: number;
        last_page: number;
        links: Array<any>;
    };
}>();

const patternId = computed(() => `pattern-${Math.random().toString(36).substring(2, 9)}`);

// Modale
const showDeleteModal = ref(false);
const messageIdToDelete = ref(null);

const form = useForm({});

const openDeleteModal = (id: number) => {
    messageIdToDelete.value = id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const deleteMessage = () => {
    form.delete(route('messages.destroy', messageIdToDelete.value), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            closeDeleteModal();
        },
    });
};

defineExpose({
    openDeleteModal,
});
</script>

<template>
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeDeleteModal">
        <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold">Confirmer la suppression</h3>
            <p class="mb-6">Êtes-vous sûr de vouloir supprimer ce message ?</p>

            <div class="flex justify-end gap-3">
                <button
                    @click="closeDeleteModal"
                    class="rounded px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Annuler
                </button>
                <button
                    @click="deleteMessage"
                    class="rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Suppression...</span>
                    <span v-else>Confirmer</span>
                </button>
            </div>
        </div>
    </div>

    <div class="relative h-full w-full">
        <svg class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" fill="none">
            <defs>
                <pattern :id="patternId" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                    <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
                </pattern>
            </defs>
            <rect stroke="none" :fill="`url(#${patternId})`" width="100%" height="100%"></rect>
        </svg>

        <div class="relative z-10 p-4">
            <h2 class="mb-4 text-2xl font-bold">Messages</h2>
            <ul class="space-y-4">
                <li v-for="message in messages.data" :key="message.id" class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                    <p class="mt-2 text-xs text-gray-500 dark:text-green-400">Le {{ new Date(message.created_at).toLocaleString() }}</p>

                    <p class="text-sm text-gray-600 dark:text-gray-400">De {{ message.fullname }} &lt;{{ message.email }}&gt;</p>
                    <p class="mt-2">{{ message.message }}</p>

                    <button
                        @click="openDeleteModal(message.id)"
                        class="mt-4 flex items-center gap-2 rounded-lg bg-red-100 px-3 py-2 text-sm transition hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50"
                    >
                        <Eraser class="h-4 w-4" />
                        Supprimer
                    </button>
                </li>
            </ul>

            <Pager :links="messages.links" />
        </div>
    </div>
</template>

<style scoped>
/* Animation modale */

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
