<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  messages: Array<{
    id: number;
    firstname: string;
    lastname: string;
    email: string;
    object: string;
    message: string;
    created_at: string;
  }>;
}>();

const patternId = computed(() => `pattern-${Math.random().toString(36).substring(2, 9)}`);
</script>

<template>
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
        <li v-for="message in messages" :key="message.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
          <h3 class="font-semibold">{{ message.object }}</h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">De: {{ message.firstname }} {{ message.lastname }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Email: {{ message.email }}</p>
          <p class="mt-2">{{ message.message }}</p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Reçu le: {{ new Date(message.created_at).toLocaleString() }}</p>
        </li>
      </ul>
    </div>
  </div>
</template>
