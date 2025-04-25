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
    <section id="contact" class="px-6 py-20 bg-[#112240] text-[#ccd6f6]" data-aos="fade-up">
        <h2 class="text-3xl font-bold mb-6 border-b-2 border-green-400 inline-block">Contact</h2>
        <form @submit.prevent="submit" class="max-w-xl mx-auto grid gap-4">

            <div>
                <Input id="name" type="text" required autocomplete="firstname" v-model="form.firstname" placeholder="Prénom" :class="'p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]'" />
                <InputError :message="form.errors.firstname" />
            </div>

            <div>
                <Input id="lastname" type="text" required autocomplete="lastname" v-model="form.lastname" placeholder="Nom de famille" :class="'p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]'" />
                <InputError :message="form.errors.lastname" />
            </div>

            <div>
                <Input id="email" type="email" required autocomplete="email" v-model="form.email" placeholder="email@example.com" :class="'p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]'" />
                <InputError :message="form.errors.email" />
            </div>

            <div>
                <Input id="phone" type="text" required autocomplete="phone" v-model.number="form.phone" placeholder="5140000000" :class="'p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]'" />
                <InputError :message="form.errors.phone" />
            </div>

            <div>
                <Input id="object" type="text" required v-model.trim="form.object" placeholder="Objet" :class="'p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]'" />
                <InputError :message="form.errors.object" />
            </div>

            <div>
                <Textarea id="message" required v-model.trim="form.message" placeholder="Message" :rows="4" :class="'rounded bg-[#0a192f] text-white border border-[#64ffda]'"></textarea>
            </div>

            <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing" :class="'bg-green-400 text-[#0a192f] px-4 py-2 rounded hover:bg-green-300 transition'">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Envoyer
            </Button>

        </form>
    </section>
</template>
