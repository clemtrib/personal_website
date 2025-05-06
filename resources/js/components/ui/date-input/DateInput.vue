<script setup lang="ts">
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';
import type { HTMLAttributes } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps<{
  defaultValue?: string | number;
  modelValue?: string | number;
  class?: HTMLAttributes['class'];
}>();

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
});

const handleUpdate = (date: unknown) => {
  if (date instanceof Date) {
    emits('update:modelValue', date.toLocaleDateString('fr-CA'));
  } else {
    console.warn('Date invalide reçue :', date);
  }
};
</script>

<template>
  <VueDatePicker
    v-model="modelValue"
    :max-date="new Date()"
    @update:model-value="handleUpdate"
    :class="cn('w-full', props.class)"
    :format="'dd-MM-yyyy'"
    :model-type="'yyyy-MM-dd'"
    auto-apply
    :enable-time-picker="false"
    :dark="true"
  />
</template>
