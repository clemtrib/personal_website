<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { DateRangeInput } from '@/components/ui/date-range-input';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Toast from '../components/Toast.vue';

const props = defineProps<{
    customers: Array<{
        id: number;
        name: string;
        address_line_1: string;
        address_line_2: string;
    }>;
}>();

const { customers } = props;

// Initialisation conditionnelle du formulaire
const form = useForm({
        customer_id: null,
        label: null,
        hours: null,
        date_range: null,
});

// Mode édition seulement si l'expérience existe

const onSubmit = () => {
    // Utiliser form directement pour PUT/POST
        form.post(route('bills.store'), {
              preserveScroll: true,
              onSuccess: () => form.reset(),
          });
};

const page = usePage();
</script>

<template>
    <Toast :flash="page.props.flash" />
    <Head :title="'Ajouter un client'" />

    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <form @submit.prevent="onSubmit">
            <!-- Période de facturation -->
            <div class="mb-4">
                <Label for="date_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Période de facturation</Label>
                <DateRangeInput
                    id="date_range"
                    type="text"
                    required
                    autofocus
                    tabindex="1"
                    v-model="form.date_range"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
            </div>

            <!-- Client -->
            <div class="mb-4">
                <Label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client </Label>
                <select
                    id="customer_id"
                    required
                    v-model="form.customer_id"
                    class="mt-1 block h-[42px] w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                </select>
            </div>

            <!-- Service -->
            <div class="mb-4">
                <Label for="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Libellé produit</Label>
                <Input
                    id="label"
                    type="text"
                    required
                    tabindex="1"
                    v-model="form.label"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
                <p v-if="form.errors.label" class="mt-1 text-sm text-red-600">{{ form.errors.label }}</p>
            </div>

            <!-- Nombre d'heure -->
            <div class="mb-4">
                <Label for="hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre d'heures travaillées</Label>
                <Input
                    id="hours"
                    type="number"
                    min="0"
                    step="1"
                    required
                    tabindex="1"
                    v-model="form.hours"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
                <p v-if="form.errors.hours" class="mt-1 text-sm text-red-600">{{ form.errors.hours }}</p>
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
