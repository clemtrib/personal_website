<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { LoaderCircle } from 'lucide-vue-next';
import { DateInput } from '@/components/ui/date-input';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { computed } from 'vue';
import Toast from '../components/Toast.vue';

const props = defineProps({
    hobby: {
        type: Object,
        default: () => null // Modifier ici
    }
});

// Initialisation conditionnelle du formulaire
const form = useForm({
    title: props.hobby?.title || '',
    description: props.hobby?.description || '',
    order: props.hobby?.order || '',
});

// Mode édition seulement si l'expérience existe
const isEditMode = computed(() => !!props.hobby?.id);

const onSubmit = () => {
    // Utiliser form directement pour PUT/POST
    isEditMode.value
        ? form.put(route('hobbies.update', props.hobby.id))
        : form.post(route('hobbies.store'), {
            preserveScroll: true,
            onSuccess: () => form.reset()
        });
};

const page = usePage();

</script>

<template>
<Toast :flash="page.props.flash" />
<Head :title="isEditMode ? 'Modifier un loisirs' : 'Ajouter un loisirs'" />

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <form @submit.prevent="onSubmit">

        <!-- Title -->
        <div class="mb-4">
            <Label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</Label>
            <Input id="title" type="text" required tabindex="1" v-model="form.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <Label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</Label>
            <Input id="description" type="text" tabindex="1" autocomplete="description" v-model="form.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
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
