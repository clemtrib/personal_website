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
    school: props.experience?.company || '',
    graduation: props.experience?.graduation || '<p></p>',
    date: props.experience?.begin_at || '',
});

// Mode édition seulement si l'expérience existe
const isEditMode = computed(() => !!props.education?.id);

const onSubmit = () => {
    if (form.graduation.trim() === '<p></p>' || form.graduation.trim() === '<p><br></p>') {
        form.setError('graduation', 'Le champ Diplôme est obligatoire');
        return;
    }

    // Utiliser form directement pour PUT/POST
    isEditMode.value
        ? form.put(route('education.update', props.experience.id))
        : form.post(route('education.store'), {
            preserveScroll: true,
            onSuccess: () => form.reset()
        });
};

const page = usePage();

</script>

<template>
<Toast :flash="page.props.flash" />
<Head :title="isEditMode ? 'Modifier un diplôme' : 'Ajouter un diplôme'" />

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <form @submit.prevent="onSubmit">

        <!-- Fin -->
        <div class="mb-4">
            <Label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</Label>
            <DateInput id="date" type="text" tabindex="1" v-model="form.date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">{{ form.errors.date }}</p>
        </div>

        <!-- Entreprise -->
        <div class="mb-4">
            <Label for="school" class="block text-sm font-medium text-gray-700 dark:text-gray-300">École</Label>
            <Input id="school" type="text" required tabindex="1" autocomplete="school" v-model="form.school" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            <p v-if="form.errors.school" class="mt-1 text-sm text-red-600">{{ form.errors.school }}</p>
        </div>

        <!--
        <div class="mb-4">
            <Label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ville</Label>
            <Input id="location" type="text" required tabindex="1" autocomplete="location" v-model="form.location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            <p v-if="form.errors.location" class="mt-1 text-sm text-red-600">{{ form.errors.location }}</p>
        </div>
        -->

        <!-- Missions -->
        <div class="mb-4">
            <Label for="graduation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diplôme</Label>
            <QuillEditor
                theme="snow"
                id="graduation"
                required
                tabindex="4"
                v-model:content="form.graduation"
                contentType="html"
                class="custom-quill-style"
            />
            <!-- Affichage des erreurs -->
            <p v-if="form.errors.graduation" class="mt-1 text-sm text-red-600">{{ form.errors.graduation }}</p>
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
