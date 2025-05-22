<script setup lang="ts">
import { type PropType } from 'vue';
import { useVModel } from '@vueuse/core';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

// Props
const props = defineProps<{
  modelValue: [string, string] | null;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: [string, string] | null): void;
}>();

// Two-way binding
const modelValue = useVModel(props, 'modelValue', emit);
</script>

<template>
  <VueDatePicker
    v-model="modelValue"
    range
    :enable-time-picker="true"
    :format="'yyyy-MM-dd\'T\'HH:mm:ssXXX'"
    :model-type="'format'"
    :dark="true"
  />
</template>
