<script setup lang="ts">
import { computed, ref, defineExpose } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Pencil, Eraser } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';



const props = defineProps<{
  workExperiences: Array<{
    id: BigInteger,
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
  <!-- Modale -->
  <div
    v-if="showDeleteModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    @click.self="closeDeleteModal"
  >
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full mx-4">
      <h3 class="text-lg font-semibold mb-4">Confirmer la suppression</h3>
      <p class="mb-6">Êtes-vous sûr de vouloir supprimer cette expérience ?</p>

      <div class="flex justify-end gap-3">
        <button
          @click="closeDeleteModal"
          class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
        >
          Annuler
        </button>
        <button
          @click="deleteMessage"
          class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700"
          :disabled="form.processing"
        >
          <span v-if="form.processing">Suppression...</span>
          <span v-else>Confirmer</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Contenu principal -->
  <div class="relative w-full h-full">
    <!-- SVG pattern... -->
    <div class="relative z-10 p-4">
      <h2 class="text-2xl font-bold mb-4">Expériences</h2>
      <ul class="space-y-4">
        <li
          v-for="experience in workExperiences"
          :key="experience.id"
          class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow"
        >
          <!-- Contenu expérience... -->

                <h3 class="font-semibold">{{ experience.job }} - {{ experience.company }} <small>{{ experience.location }}</small></h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Du {{ new Date(experience.begin_at).toLocaleDateString() }} au {{ new Date(experience.end_at).toLocaleDateString() }}</p>
                <p class="mt-2">{{ experience.description }}</p>

          <div class="flex gap-2 mt-4">
            <Link
              :href="route('experiences.show', experience.id)"
              class="flex items-center gap-2 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded-lg transition"
            >
              <Pencil class="h-4 w-4" />
              Modifier
            </Link>

            <button
              @click="openDeleteModal(experience.id)"
              class="flex items-center gap-2 px-3 py-2 text-sm bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition"
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

<style scoped>
/* Animation modale */
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
}
</style>

