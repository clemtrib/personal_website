<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { LoaderCircle } from 'lucide-vue-next';
import { DatetimeInput } from '@/components/ui/datetime-input';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { computed } from 'vue';
import Toast from '../components/Toast.vue';

const props = defineProps({
    meet: {
        type: Object,
        default: () => null // Modifier ici
    }
});

// Initialisation conditionnelle du formulaire
const form = useForm({
    summary: props.meet?.summary || null,
    recipient_email: props.meet?.recipient_email || null,
    recipient_fullname: props.meet?.recipient_fullname || null,
    start_datetime: props.meet?.start_datetime || null,
    end_datetime: props.meet?.end_datetime || null,
});

// Mode édition seulement si l'expérience existe
const isEditMode = computed(() => !!props.meet?.id);

const onSubmit = () => {
    // Utiliser form directement pour PUT/POST
    isEditMode.value ?
        form.put(route('meets.update', props.experience.id)) :
        form.post(route('meets.store'), {
            preserveScroll: true,
            onSuccess: () => form.reset()
        });
};

const page = usePage();
</script>

<template>
    <Toast :flash="page.props.flash" />
    <Head :title="isEditMode ? 'Modifier une plage horaire' : 'Ajouter une plage horaire'" />

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form @submit.prevent="onSubmit">
            <!-- Début -->
            <div class="mb-4">
                <Label for="start_datetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Début</Label>
                <DatetimeInput id="start_datetime" type="text" required autofocus tabindex="1" v-model="form.start_datetime" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <!-- Fin -->
            <div class="mb-4">
                <Label for="end_datetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fin</Label>
                <DatetimeInput id="end_datetime" type="text" tabindex="1" v-model="form.end_datetime" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <!-- Bouton -->
            <div class="mt-6">
                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Envoyer
                  </Button>
            </div>
        </form>
    </div>
</template>
