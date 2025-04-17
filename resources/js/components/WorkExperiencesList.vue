<script setup lang="ts">
import { computed, ref, defineExpose } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  workExperiences: Array<{
    location: string,
    company: string,
    job: string,
    description: string,
    begin_at: string,
    end_at: string
  }>;
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
  openDeleteModal
});
</script>

<template>

    <div v-if="showDeleteModal" class="modal">
        <div class="modal-content">
            <h3>Confirmer la suppression</h3>
            <p>Êtes-vous sûr de vouloir supprimer ce message ?</p>
            <button @click="deleteMessage">Confirmer</button>
            <button @click="closeDeleteModal">Annuler</button>
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
            <h2 class="text-2xl font-bold mb-4">Expériences</h2>
            <ul class="space-y-4">
                <li v-for="experience in workExperiences" :key="experience.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h3 class="font-semibold">{{ experience.job }} - {{ experience.company }} <small>{{ experience.location }}</small></h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Du {{ new Date(experience.begin_at).toLocaleDateString() }} au {{ new Date(experience.end_at).toLocaleDateString() }}</p>
                <p class="mt-2">{{ experience.description }}</p>
                <button @click="openDeleteModal(experience.id)" class="btn btn-danger">Supprimer</button>
                </li>
            </ul>
        </div>
  </div>
</template>
