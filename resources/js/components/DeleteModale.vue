<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits } from 'vue'; // Ajouter si nécessaire

const props = defineProps({
  show: Boolean,
  objectId: Number,
  actionForm: String // <-- Déclaration correcte de la prop
});

const emit = defineEmits(['close']);

const form = useForm({});

const deleteObject = () => {
  // Utiliser props.actionForm et props.objectId
  form.delete(route(props.actionForm, props.objectId), {
    preserveScroll: false,
    preserveState: false,
    onSuccess: () => {
      emit('close');
    }
  });
};



</script>

<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    @click.self="$emit('close')"
  >
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full mx-4">
      <h3 class="text-lg font-semibold mb-4">Confirmer la suppression</h3>
      <p class="mb-6">Êtes-vous sûr de vouloir supprimer cette entrée ?</p>

      <div class="flex justify-end gap-3">
        <button
          @click="$emit('close')"
          class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
        >
          Annuler
        </button>
        <button
          @click="deleteObject"
          class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700"
          :disabled="form.processing"
        >
          <span v-if="form.processing">Suppression...</span>
          <span v-else>Confirmer</span>
        </button>
      </div>
    </div>
  </div>
</template>
