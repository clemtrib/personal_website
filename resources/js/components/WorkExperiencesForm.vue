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
    experience: {
        type: Object,
        default: () => null // Modifier ici
    }
});

// Initialisation conditionnelle du formulaire
const form = useForm({
    location: props.experience?.location || '',
    company: props.experience?.company || '',
    job: props.experience?.job || '',
    description: props.experience?.description || '<p></p>',
    begin_at: props.experience?.begin_at || '',
    end_at: props.experience?.end_at || ''
});

// Mode édition seulement si l'expérience existe
const isEditMode = computed(() => !!props.experience?.id);

const onSubmit = () => {
    if (form.description.trim() === '<p></p>' || form.description.trim() === '<p><br></p>') {
        form.setError('description', 'Le champ missions est obligatoire');
        return;
    }

    // Utiliser form directement pour PUT/POST
    isEditMode.value
        ? form.put(route('experiences.update', props.experience.id))
        : form.post(route('experiences.store'), {
            preserveScroll: true,
            onSuccess: () => form.reset()
        });
};

const page = usePage();

</script>

<template>
<Toast :flash="page.props.flash" />
<Head :title="isEditMode ? 'Modifier une expérience' : 'Ajouter une expérience'" />

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <form @submit.prevent="onSubmit">
        <!-- Début -->
        <div class="mb-4">
            <Label for="begin_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Début</Label>
            <DateInput id="begin_at" type="text" required autofocus tabindex="1" v-model="form.begin_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <!-- Fin -->
        <div class="mb-4">
            <Label for="end_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fin</Label>
            <DateInput id="end_at" type="text" required tabindex="1" v-model="form.end_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <!-- Entreprise -->
        <div class="mb-4">
            <Label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Entreprise</Label>
            <Input id="company" type="text" required tabindex="1" autocomplete="company" v-model="form.company" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <!-- Ville -->
        <div class="mb-4">
            <Label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ville</Label>
            <Input id="location" type="text" required tabindex="1" autocomplete="location" v-model="form.location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <!-- Emploi -->
        <div class="mb-4">
            <Label for="job" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emploi</Label>
            <Input id="job" type="text" required tabindex="1" autocomplete="job" v-model="form.job" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <!-- Missions -->
        <div class="mb-4">
            <Label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Missions</Label>
            <QuillEditor
                theme="snow"
                id="description"
                required
                tabindex="4"
                v-model:content="form.description"
                contentType="html"
                placeholder="description"
                class="custom-quill-style"
            />
            <!-- Affichage des erreurs -->
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

<style>
/* Styles personnalisés pour Quill */
.custom-quill-style {
    background: white;
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    min-height: 200px;
    color: black; /* Couleur de texte par défaut */
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
