<script setup lang="ts">
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';
import type { HTMLAttributes } from 'vue';

const props = defineProps < {
    defaultValue ? : string | number;
    modelValue ? : string | number;
    class ? : HTMLAttributes['class'];
    rows ? : number;
} > ();

const emits = defineEmits < {
    (e: 'update:modelValue', payload: string | number): void;
} > ();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
});
</script>

<template>
    <textarea v-model.trim="modelValue" :class="
                cn(
                    [
                      'flex w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
                      !props.rows && 'h-10', // Ajoute h-10 uniquement si rows n'est PAS défini
                      props.class,
                    ]
                )
            " :rows="props.rows" />
</template>
