<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { LoaderCircle } from 'lucide-vue-next';
import { RangeInput } from '@/components/ui/range-input';
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
    days_multiple: props.meet?.days_multiple || null,
    duration: props.meet?.duration || null,
    datetime_range: props.meet?.start_datetime || null,
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
            <!-- Range de date -->
            <div class="mb-4">
                <Label for="datetime_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Plage de dates
                </Label>
                <RangeInput id="datetime_range" type="text" required autofocus tabindex="1" v-model="form.datetime_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <!-- Durée -->
            <div class="mb-4">
                <Label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Durée des rencontres
                </Label>
                <select id="duration" v-model="form.duration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="15">15 minutes</option>
                    <option value="30" selected>30 minutes</option>
                    <option value="45">45 minutes</option>
                    <option value="60">60 minutes</option>
                </select>
            </div>

            <!-- Jours disponibles - select multiple -->
            <div class="mb-4">
                <Label for="days-multiple" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Jours disponibles
                </Label>
                <select id="days-multiple" v-model="form.days_multiple" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white h-40">
                    <option value=1>Lundi</option>
                    <option value=2>Mardi</option>
                    <option value=3>Mercredi</option>
                    <option value=4>Jeudi</option>
                    <option value=5>Vendredi</option>
                </select>
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

