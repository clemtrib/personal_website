<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref, onMounted, nextTick, computed } from 'vue'
import gsap from 'gsap'

const form = useForm({
    firstname: '',
    lastname: '',
    email: '',
    phone: '',
    object: '',
    message: '',
});

const successMessage = ref < HTMLElement | null > (null)
const page = usePage()
const formSubmitted = computed(() => !!page.props.flash.success)

onMounted(() => {
    const fields = document.querySelectorAll('.gsap-hover')

    fields.forEach((el) => {
        el.addEventListener('mouseenter', () => {
            gsap.to(el, {
                scale: 1.03,
                boxShadow: '0 6px 18px rgba(100, 255, 218, 0.4)',
                duration: 0.3,
                ease: 'power2.out'
            })
        })

        el.addEventListener('mouseleave', () => {
            gsap.to(el, {
                scale: 1,
                boxShadow: '0 0 0 rgba(0,0,0,0)',
                duration: 0.3,
                ease: 'power2.out'
            })
        })
    })
})

const submit = () => {
    form.post(route('contact'), {
        preserveScroll: true,
        onSuccess: () => {
            formSubmitted.value = true

            // attend que le DOM soit mis à jour
            nextTick(() => {
                if (successMessage.value) {
                    gsap.fromTo(successMessage.value, { opacity: 0, y: 30 }, {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        ease: 'power2.out'
                    })
                }
            })
        },
        onFinish: () => form.reset('firstname', 'lastname', 'email', 'phone', 'object', 'message'),
    })
};
</script>

<template>
    <section id="contact" class="px-6 py-20 bg-[#112240] text-[#ccd6f6]">
        <h2 class="text-3xl font-bold mb-6 border-b-2 border-green-400 inline-block">Contact</h2>

        <div v-if="formSubmitted" ref="successMessage" class="text-center text-lg text-green-400">
            {{ page.props.flash.success }}
        </div>

        <div v-if="form.hasErrors && form.errors.general" class="text-red-400 text-center mb-4">
            {{ form.errors.general }}
        </div>

        <form v-if="!formSubmitted" @submit.prevent="submit" class="max-w-xl mx-auto grid gap-4">
            <div>
                <Input id="name" type="text" required autocomplete="firstname" v-model="form.firstname" placeholder="Prénom" class="gsap-hover p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]" />
                <InputError :message="form.errors.firstname" />
            </div>
            <div>
                <Input id="lastname" type="text" required autocomplete="lastname" v-model="form.lastname" placeholder="Nom de famille" class="gsap-hover p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]" />
                <InputError :message="form.errors.lastname" />
            </div>
            <div>
                <Input id="email" type="email" required autocomplete="email" v-model="form.email" placeholder="email@example.com" class="gsap-hover p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]" />
                <InputError :message="form.errors.email" />
            </div>
            <div>
                <Input id="phone" type="text" required autocomplete="phone" v-model.number="form.phone" placeholder="5140000000" class="gsap-hover p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]" />
                <InputError :message="form.errors.phone" />
            </div>
            <div>
                <Input id="object" type="text" required v-model.trim="form.object" placeholder="Objet" class="gsap-hover p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]" />
                <InputError :message="form.errors.object" />
            </div>
            <div>
                <Textarea id="message" required v-model.trim="form.message" placeholder="Message" :rows="4" class="gsap-hover rounded bg-[#0a192f] text-white border border-[#64ffda]"></Textarea>
            </div>
            <Button type="submit" class="mt-2 w-full gsap-hover bg-green-400 text-[#0a192f] px-4 py-2 rounded hover:bg-green-300 transition" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                Envoyer
            </Button>
        </form>
    </section>
</template>
