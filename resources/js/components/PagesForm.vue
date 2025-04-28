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
    page: {
        type: Object,
        default: () => null // Modifier ici
    }
});

// Initialisation conditionnelle du formulaire
const form = useForm({
    id: props.page?.id || '',
    page_slug: props.page?.page_slug || '',
    page_name: props.page?.page_name || '',
    hero_subtitle: props.page?.hero_subtitle || '',
    hero_title: props.page?.hero_title || '',
    hero_description: props.page?.hero_description || '',
    hero_image: props.page?.hero_image || '',
    content_text: props.page?.content_text || '',
    content_image: props.page?.content_image || '',
    page_seo: props.page?.page_seo || '',
});

// Mode édition seulement si l'expérience existe
const isEditMode = computed(() => !!props.page?.id);

const onSubmit = () => {
    if (form.hero_description.trim() === '<p></p>' || form.hero_description.trim() === '<p><br></p>') {
        form.setError('hero_description', 'Le champ Texte est obligatoire');
        return;
    }

    // Utiliser form directement pour PUT/POST
    isEditMode.value ?
        form.put(route('pages.update', props.page.id)) :
        form.post(route('pages.store'), {
            preserveScroll: true,
            onSuccess: () => form.reset()
        });
};

const page = usePage();
</script>

<template>
    <Toast :flash="page.props.flash" />
    <Head :title="isEditMode ? 'Modifier une page' : 'Ajouter une page'" />

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form @submit.prevent="onSubmit">

            <!-- page_slug -->
            <div class="mb-4">
                <Label for="page_slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</Label>
                <Input id="page_slug" type="text" required tabindex="1" autocomplete="page_slug" v-model="form.page_slug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <!-- page_name -->
            <div class="mb-4">
                <Label for="page_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</Label>
                <Input id="page_name" type="text" required tabindex="1" autocomplete="page_name" v-model="form.page_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <!-- hero_subtitle -->
            <div class="mb-4">
                <Label for="hero_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Surtitre</Label>
                <Input id="hero_subtitle" type="text" required tabindex="1" autocomplete="hero_subtitle" v-model="form.hero_subtitle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <!-- hero_title -->
            <div class="mb-4">
                <Label for="hero_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titre</Label>
                <Input id="hero_title" type="text" required tabindex="1" autocomplete="hero_title" v-model="form.hero_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <!-- Missions -->
            <div class="mb-4">
                <Label for="hero_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Texte</Label>
                <QuillEditor theme="snow" id="hero_description" required tabindex="4" v-model:content="form.hero_description" contentType="html" class="custom-quill-style" />
                <!-- Affichage des erreurs -->
                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.hero_description }}</p>
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

<style>
/* Styles personnalisés pour Quill */

.custom-quill-style {
    background: white;
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    min-height: 200px;
    color: black;
    /* Couleur de texte par défaut */
}

/* Style pour le mode sombre */

.dark .custom-quill-style {
    background: #374151;
    border-color: #4b5563;
    color: white;
}

.dark .ql-editor {
    color: white;
}

/* Style du placeholder */

.ql-editor.ql-blank::before {
    color: #9ca3af;
}

.dark .ql-editor.ql-blank::before {
    color: #6b7280;
}
</style>
