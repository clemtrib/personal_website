<script setup lang="ts">
import { Pencil, Eraser } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

defineProps<{
  workExperiences: Array<{
    id: number;
    location: string;
    company: string;
    job: string;
    description: string;
    begin_at: string;
    end_at: string;
  }>;
}>();

const emit = defineEmits(['open-delete-modal']);

const openDeleteModal = (id: number) => {
  emit('open-delete-modal', id);
};
</script>

<template>
  <div class="relative w-full h-full">
    <div class="relative z-10 p-4">
      <h2 class="text-2xl font-bold mb-4">Expériences</h2>
      <ul class="space-y-4">
        <li
          v-for="experience in workExperiences"
          :key="experience.id"
          class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow"
        >
          <h3 class="font-semibold">{{ experience.job }} <span :v-if="experience.company">@ <span class="text-gray-500 dark:text-green-400">{{ experience.company }}</span></span> <small>{{ experience.location }}</small></h3>
          <p class="text-sm text-gray-600 dark:text-green-400">
            Du {{ new Date(experience.begin_at).toLocaleDateString() }}
            <template v-if="experience.end_at">
              au {{ new Date(experience.end_at).toLocaleDateString() }}
            </template>
            <template v-else>
              à aujourd'hui
            </template>
          </p>
          <p class="mt-2 prose prose-invert max-w-none text-gray-800 dark:text-gray-400" v-html="experience.description"></p>

          <div class="flex gap-2 mt-4">
            <Link
              :href="route('experiences.edit', experience.id)"
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
