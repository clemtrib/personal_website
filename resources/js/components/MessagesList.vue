<script setup lang="ts">
import { computed, ref, defineExpose } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Eraser } from 'lucide-vue-next';

const navigate = (url: string) => {
  if (url) {
    router.visit(url, {
      preserveScroll: true,
      preserveState: true,
    });
  }
};

const props = defineProps < {
    messages: {
        data: Array < {
            id: number;
            fullname: string;
            email: string;
            message: string;
            created_at: string;
        } > ;
        current_page: number;
        last_page: number;
        links: Array < any > ;
    };
} > ();

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
    openDeleteModal
});
</script>

<template>
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="closeDeleteModal">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Confirmer la suppression</h3>
            <p class="mb-6">Êtes-vous sûr de vouloir supprimer ce message ?</p>

            <div class="flex justify-end gap-3">
                <button @click="closeDeleteModal" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                Annuler
                </button>
                <button @click="deleteMessage" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700" :disabled="form.processing">
                <span v-if="form.processing">Suppression...</span>
                <span v-else>Confirmer</span>
                </button>
            </div>
        </div>
    </div>

    <div class="relative w-full h-full">
        <svg class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" fill="none">
            <defs>
                <pattern :id="patternId" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
                </pattern>
            </defs>
            <rect stroke="none" :fill="`url(#${patternId})`" width="100%" height="100%"></rect>
            </svg>

        <div class="relative z-10 p-4">
            <h2 class="text-2xl font-bold mb-4">Messages</h2>
            <ul class="space-y-4">
                <li v-for="message in messages.data" :key="message.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-xs text-gray-500 dark:text-green-400 mt-2">Le {{ new Date(message.created_at).toLocaleString() }}</p>

                    <p class="text-sm text-gray-600 dark:text-gray-400">De {{ message.fullname }} &lt;{{ message.email }}&gt;</p>
                    <p class="mt-2">{{ message.message }}</p>

                    <button @click="openDeleteModal(message.id)" class="flex items-center gap-2 px-3 py-2 mt-4 text-sm bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition">
                    <Eraser class="h-4 w-4" />
                    Supprimer
                    </button>

                </li>
            </ul>

            <div class="mt-6 flex flex-wrap justify-center gap-2">
                <button v-for="(link, index) in messages.links" :key="index" v-html="link.label" :disabled="!link.url" @click="navigate(link.url)" :class="[
                    'px-3 py-1 rounded text-sm',
                    link.active
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white',
                    !link.url ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                ]" />
            </div>

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
