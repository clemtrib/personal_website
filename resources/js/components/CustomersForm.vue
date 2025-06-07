<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { LoaderCircle } from 'lucide-vue-next';
import { computed } from 'vue';
import Toast from '../components/Toast.vue';

const props = defineProps({
    customer: {
        type: Object,
        default: () => null,
    },
});

// Initialisation conditionnelle du formulaire
const form = useForm({
    name: props.customer?.name || '',
    company: props.customer?.company || '',
    address_line_1: props.customer?.address_line_1 || '',
    address_line_2: props.customer?.address_line_2 || '',
    zip_code: props.customer?.zip_code || '',
    city: props.customer?.city || '',
    province: props.customer?.province || '',
    country: props.customer?.country || '',
    tjm: props.customer?.tjm || '',
    email: props.customer?.email || '',
});

// Mode édition seulement si l'expérience existe
const isEditMode = computed(() => !!props.customer?.id);

const onSubmit = () => {
    // Utiliser form directement pour PUT/POST
    isEditMode.value
        ? form.put(route('customers.update', props.customer.id))
        : form.post(route('customers.store'), {
              preserveScroll: true,
              onSuccess: () => form.reset(),
          });
};

const page = usePage();
</script>

<template>
    <Toast :flash="page.props.flash" />
    <Head :title="isEditMode ? 'Modifier un client' : 'Ajouter un client'" />

    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <form @submit.prevent="onSubmit">
            <!-- Email -->
            <div class="mb-4">
                <Label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    tabindex="1"
                    v-model="form.email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
            </div>

            <!-- Fullname or company -->
            <div class="mb-4">
                <Label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom ou société</Label>
                <Input
                    id="name"
                    type="text"
                    required
                    tabindex="1"
                    v-model="form.name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <!-- Company -->
            <div class="mb-4">
                <Label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Société</Label>
                <Input
                    id="company"
                    type="text"
                    required
                    tabindex="1"
                    v-model="form.company"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
                <p v-if="form.errors.company" class="mt-1 text-sm text-red-600">{{ form.errors.company }}</p>
            </div>

            <!-- Adresse (ligne 1) -->
            <div class="mb-4">
                <Label for="address_line_1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse (ligne 1)</Label>
                <Input
                    id="address_line_1"
                    type="text"
                    tabindex="1"
                    v-model="form.address_line_1"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
                <p v-if="form.errors.address_line_2" class="mt-1 text-sm text-red-600">{{ form.errors.address_line_1 }}</p>
            </div>

            <!-- Adresse (ligne 2) -->
            <div class="mb-4">
                <Label for="address_line_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse (ligne 2)</Label>
                <Input
                    id="address_line_2"
                    type="text"
                    tabindex="1"
                    v-model="form.address_line_2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
                <p v-if="form.errors.address_line_2" class="mt-1 text-sm text-red-600">{{ form.errors.address_line_2 }}</p>
            </div>

            <!-- Ville / Province / Code postal -->
            <div class="mb-4 flex flex-col gap-4 md:flex-row">
                <!-- Ville -->
                <div class="w-full">
                    <Label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ville</Label>
                    <Input
                        id="city"
                        type="text"
                        tabindex="1"
                        v-model="form.city"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                    <p v-if="form.errors.city" class="mt-1 text-sm text-red-600">{{ form.errors.city }}</p>
                </div>

                <!-- Province -->
                <div class="w-full">
                    <Label for="province" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Province</Label>
                    <select
                        id="province"
                        v-model="form.province"
                        class="mt-1 block h-[42px] w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="QC" selected>Québec</option>
                    </select>
                </div>

                <!-- Code postal -->
                <div class="w-full">
                    <Label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code postal</Label>
                    <Input
                        id="zip_code"
                        type="text"
                        tabindex="1"
                        v-model="form.zip_code"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                    <p v-if="form.errors.zip_code" class="mt-1 text-sm text-red-600">{{ form.errors.zip_code }}</p>
                </div>
            </div>

            <!-- Pays -->
            <div class="mb-4">
                <Label for="Country" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Pays </Label>
                <select
                    id="country"
                    v-model="form.country"
                    class="mt-1 block h-[42px] w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
                    <option value="CA" selected>Canada</option>
                </select>
            </div>

            <!-- Taux horaire -->
            <div class="mb-4">
                <Label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Taux horaire</Label>
                <Input
                    id="tjm"
                    type="number"
                    min="0"
                    step=".25"
                    required
                    tabindex="1"
                    v-model="form.tjm"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                />
                <p v-if="form.errors.tjm" class="mt-1 text-sm text-red-600">{{ form.errors.tjm }}</p>
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
