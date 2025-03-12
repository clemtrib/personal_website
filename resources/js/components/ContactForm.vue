<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    firstname: '',
    lastname: '',
    email: '',
    phone: '',
    object: '',
    message: '',
});

const submit = () => {
    form.post(route('contact'), {
        onFinish: () => form.reset('firstname', 'lastname', 'email', 'phone', 'object', 'message'),
    });
};
</script>

<template>
        <Head title="Me contacter" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-3">
                    <Label for="name">Prénom</Label>
                    <Input id="name" type="text" required tabindex="1" autocomplete="firstname" v-model="form.firstname" placeholder="Prénom" />
                    <InputError :message="form.errors.firstname" />
                </div>

                <div class="grid gap-3">
                    <Label for="lastname">Nom de famille</Label>
                    <Input id="lastname" type="text" required tabindex="1" autocomplete="lastname" v-model="form.lastname" placeholder="Nom de famille" />
                    <InputError :message="form.errors.lastname" />
                </div>

                <div class="grid gap-3">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" required tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-3">
                    <Label for="phone">Numéro de téléphone</Label>
                    <Input id="phone" type="text" required tabindex="2" autocomplete="phone" v-model.number="form.phone" placeholder="5140000000" />
                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-6">
                    <Label for="object">Objet</Label>
                    <Input id="object" type="text" required tabindex="4" v-model.trim="form.object" placeholder="Objet" />
                    <InputError :message="form.errors.object" />
                </div>

                <div class="grid gap-6">
                    <Label for="message">Message</Label>
                    <Textarea id="message" required tabindex="4" v-model.trim="form.message" placeholder="Message"></textarea>
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Envoyer
                </Button>
            </div>

        </form>
</template>

