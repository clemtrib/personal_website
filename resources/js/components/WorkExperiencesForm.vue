<script setup lang="ts">

import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { LoaderCircle } from 'lucide-vue-next';
import { DateInput } from '@/components/ui/date-input';

const form = useForm({
    location: '',
    company: '',
    job: '',
    description: '',
    begin_at: '',
    end_at: ''
});

const onSubmit = () => {
    // Logique de soumission du formulaire
    console.log(form.value)
    form.post(route('experiences.store'), {
        onFinish: () => form.reset('location', 'company', 'job', 'description', 'begin_at', 'end_at'),
    });
}
</script>


<template>

<Head title="Ajouter une expérience" />

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <form @submit.prevent="onSubmit">

        <div class="mb-4">
            <Label for="begin_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Début</Label>
            <DateInput id="begin_at" type="text" required autofocus tabindex="1" v-model="form.begin_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <div class="mb-4">
            <Label for="end_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fin</Label>
            <DateInput id="end_at" type="text" required tabindex="1" v-model="form.end_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <div class="mb-4">
            <Label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Entreprise</Label>
            <Input id="company" type="text" required tabindex="1" autocomplete="company" v-model="form.company" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            <!-- <InputError :message="form.errors.company" />-->
        </div>

        <div class="mb-4">
            <Label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ville</Label>
            <Input id="location" type="text" required tabindex="1" autocomplete="location" v-model="form.location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <div class="mb-4">
            <Label for="job" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emploi</Label>
            <Input id="job" type="text" required tabindex="1" autocomplete="job" v-model="form.job" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <div class="mb-4">
            <Label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Missions</Label>
            <Textarea id="description" required tabindex="4" v-model.trim="form.description" placeholder="description"></textarea>
        </div>

        <div class="mt-6">
            <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Envoyer
            </Button>
        </div>

    </form>
  </div>
</template>
