<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle, MailCheck, MailX } from 'lucide-vue-next';
import { ref, onMounted, nextTick, computed, watch } from 'vue';
import gsap from 'gsap';

// Props
const props = defineProps<{ readyToLoad: boolean }>();

// Formulaire Inertia
const form = useForm({
    fullname: '',
    email: '',
    message: '',
    recaptcha_token: '',
});

// Variables
const page = usePage();
const successMessage = computed(() => page.props.flash?.success_message);
const failureMessage = computed(() => page.props.flash?.failure_message);

const siteKey = import.meta.env.VITE_RECAPTCHA_SITE_KEY;
const showForm = ref(true); // 🔁 Pour forcer le re-render du formulaire

// Animation GSAP
function setupGsapAnimations() {
    const fields = document.querySelectorAll('.gsap-hover');
    fields.forEach((el) => {
        el.addEventListener('mouseenter', () => {
            gsap.to(el, {
                scale: 1.03,
                boxShadow: '0 6px 18px rgba(100, 255, 218, 0.4)',
                duration: 0.3,
                ease: 'power2.out',
            });
        });
        el.addEventListener('mouseleave', () => {
            gsap.to(el, {
                scale: 1,
                boxShadow: '0 0 0 rgba(0,0,0,0)',
                duration: 0.3,
                ease: 'power2.out',
            });
        });
    });
}

onMounted(() => {
    if (props.readyToLoad) {
        nextTick(() => {
            setupGsapAnimations();
        });
    }
});

watch(() => props.readyToLoad, (newValue) => {
    if (newValue) {
        nextTick(() => {
            setupGsapAnimations();
        });
    }
});

// Fonction pour vider les champs sans toucher à recaptcha_token
const resetFormFields = () => {
    form.fullname = '';
    form.email = '';
    form.message = '';

    showForm.value = false;
    nextTick(() => {
        showForm.value = true;
    });
};

// Envoi du formulaire avec reCAPTCHA
const submit = async () => {
    if (!window.grecaptcha) {
        alert('reCAPTCHA non chargé');
        return;
    }

    const token = await window.grecaptcha.execute(siteKey, { action: 'submit' });
    form.recaptcha_token = token;

    form.post(route('messages.store'), {
        preserveScroll: true,
        onSuccess: () => {
            nextTick(() => {
                // éventuelle animation sur succès
            });
        },
        onFinish: () => {
            resetFormFields(); // 👈 vide les champs et déclenche le remount
        },
    });
};
</script>

<template>
    <section v-if="readyToLoad" id="contact" class="px-6 py-20 bg-[#112240] text-[#ccd6f6]" data-aos="fade-up">
        <h2 class="text-3xl font-bold mb-6 border-b-2 border-green-400 inline-block">
            Contact
        </h2>

        <div v-if="successMessage" ref="successMessage" class="text-center justify-center text-lg text-green-400 gap-4 mt-2">
            <p class="w-full">{{ successMessage }}</p>
            <p class="w-full flex justify-center pt-10"><MailCheck :size="40" /></p>
        </div>

        <div v-if="failureMessage" ref="failureMessage" class="text-center justify-center text-lg text-red-400 gap-4 mt-2">
            <p class="w-full">{{ failureMessage }}</p>
            <p class="w-full flex justify-center pt-10"><MailX :size="40" /></p>
        </div>

        <div v-if="form.hasErrors && form.errors.general" class="text-red-400 text-center mb-4">
            {{ form.errors.general }}
        </div>

        <form v-if="!successMessage && !failureMessage && showForm" @submit.prevent="submit" class="max-w-xl mx-auto grid gap-4">
            <div>
                <Input
                    id="fullname"
                    type="text"
                    required
                    autocomplete="name"
                    v-model="form.fullname"
                    placeholder="Nom"
                    class="gsap-hover p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]"
                />
                <InputError :message="form.errors.fullname" />
            </div>

            <div>
                <Input
                    id="email"
                    type="email"
                    required
                    autocomplete="email"
                    v-model="form.email"
                    placeholder="Email"
                    class="gsap-hover p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div>
                <Textarea
                    id="message"
                    required
                    v-model.trim="form.message"
                    placeholder="Message"
                    :rows="4"
                    class="gsap-hover rounded bg-[#0a192f] text-white border border-[#64ffda]"
                />
                <InputError :message="form.errors.message" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full gsap-hover bg-green-400 text-[#0a192f] px-4 py-2 rounded hover:bg-green-300 transition"
                :disabled="form.processing"
            >
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                <span v-else>Envoyer</span>
            </Button>
        </form>
    </section>
</template>
