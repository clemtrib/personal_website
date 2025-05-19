<script setup lang="ts">
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref, onMounted, nextTick, computed } from 'vue';
import gsap from 'gsap';

const props = defineProps < {
    readyToLoad: boolean;
    meetings: Array < string > ;
    googleauth: Array < {
        email: string,
        name: string,
        picture: string,
    } >;
    googleauthurl: string;
} > ();

const form = useForm({
    recipient_fullname: '',
    recipient_email: '',
    summary: '',
    timeslot_id: null,
    recaptcha_token: '',
});

const page = usePage();
const formSubmitted = computed(() => !!page.props.flash.success);

const selectedDate = ref < Date | null > (null);
const attrs = ref([]);
const timeslots = ref < Array < any >> ([]);
const selectedTimeslotId = ref < number | null > (null);
const siteKey =
    import.meta.env.VITE_RECAPTCHA_SITE_KEY;

const timeslotContainer = ref(null);
const formContainer = ref(null);
const successMessage = ref < HTMLElement | null > (null);

const handleDateClick = async ({ date }) => {
    selectedDate.value = date;
    selectedTimeslotId.value = null;
    timeslots.value = [];

    try {
        const response = await axios.get(`/api/spa/timeslots/${date.toISOString().split('T')[0]}`);
        timeslots.value = response.data;

        nextTick(() => {
            if (timeslotContainer.value) {
                gsap.fromTo(timeslotContainer.value, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6 });
            }
        });
    } catch (error) {
        console.error('Erreur lors du chargement des créneaux', error);
    }
};

const selectTimeslot = (id: number) => {
    selectedTimeslotId.value = id;
    form.timeslot_id = id;

    nextTick(() => {
        if (formContainer.value) {
            gsap.fromTo(formContainer.value, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6 });
        }
    });
};

onMounted(() => {
    if (props.meetings?.length) {
        const parsedDates = props.meetings.map(date => new Date(date + 'T00:00:00.000Z'));
        attrs.value = [{
            highlight: 'green',
            dates: parsedDates
        }];
    }
});

const hasGoogleToken = computed(() => {
    return page.props.googleToken !== null;
});

const submit = async () => {
    if (!hasGoogleToken.value) {
        window.location.href = route('google.auth');
        return;
    }

    // Sinon, on continue normalement
    const token = await window.grecaptcha.execute(siteKey, { action: 'submit' });
    form.recaptcha_token = token;

    form.put(route('meets.book', { 'timeslot': form.timeslot_id }), {
        preserveScroll: true,
        onSuccess: () => {
            nextTick(() => {
                if (successMessage.value) {
                    gsap.fromTo(successMessage.value, { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.8 });
                }
            });
        },
        onFinish: () => form.reset('recipient_fullname', 'recipient_email', 'summary'),
    });
};
</script>

<template>
    <section v-if="props.readyToLoad && props.meetings?.length" id="meets" class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6]" data-aos="fade-bottom">
        <h2 class="text-3xl font-bold mb-10 inline-block border-b-2 border-green-400">
            Prendre rendez-vous
        </h2>

        <!-- Grille -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

            <!-- Colonne calendrier (1/3) -->
            <div class="w-full flex justify-center">
                <VCalendar :min-date="new Date()" :attributes="attrs" :is-dark="true" @dayclick="handleDateClick" />
            </div>

            <!-- Colonne créneaux + formulaire (2/3) -->
            <div class="md:col-span-2 space-y-6">

                <template v-if="props.googleauth">

                    <!-- Créneaux -->
                    <div ref="timeslotContainer" class="w-full">
                        <h3 class="text-lg font-semibold mb-2" v-if="selectedDate">Créneaux disponibles :</h3>
                        <div v-if="timeslots.length" class="flex flex-wrap gap-2">
                            <button v-for="slot in timeslots" :key="slot.id" @click="selectTimeslot(slot.id)" class="px-4 py-2 rounded border transition-all duration-200" :class="[
                                        selectedTimeslotId === slot.id
                                            ? 'bg-green-400 text-[#0a192f] border-green-400'
                                            : 'bg-[#0a192f] text-white border-green-400 hover:bg-green-800'
                                    ]">
                                    {{ new Date(slot.start_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                    -
                                    {{ new Date(slot.end_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                </button>
                        </div>
                        <p v-else-if="selectedDate" class="text-gray-400">
                            Aucun créneau disponible pour cette date.
                        </p>
                        <template v-else>
                            <h3 class="text-lg font-semibold mb-2">
                                {{ props.googleauth?.email }} est identifié.
                            </h3>
                            <p class="text-gray-400">
                                Choisir une date.
                            </p>
                        </template>
                    </div>

                    <!-- Formulaire -->
                    <form v-if="selectedTimeslotId && !formSubmitted" ref="formContainer" @submit.prevent="submit" class="max-w-full w-full grid gap-4">
                        <div>
                            <p class="p-2 rounded bg-[#0a192f] text-white border border-[#64ffda] w-full">{{ props.googleauth?.name }}</p>
                        </div>

                        <div>
                            <p class="p-2 rounded bg-[#0a192f] text-white border border-[#64ffda] w-full">{{ props.googleauth?.email }}</p>
                        </div>

                        <div>
                            <Input id="summary" type="text" required v-model="form.summary" placeholder="Objet de la rencontre" class="p-2 rounded bg-[#0a192f] text-white border border-[#64ffda] w-full" />
                            <InputError :message="form.errors.summary" />
                        </div>

                        <Button type="submit" class="mt-2 w-full bg-green-400 text-[#0a192f] px-4 py-2 rounded hover:bg-green-300 transition" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                <span v-else>Envoyer</span>
                            </Button>
                    </form>

                </template>

                <template v-else>
                    <a :href="googleauthurl" class="px-4 py-2 rounded border transition-all duration-200 bg-[#0a192f] text-white border-green-400 hover:bg-green-800">
                        S'identifier avec Google
                    </a>
                </template>

            </div>
        </div>

        <!-- Messages -->
        <div v-if="formSubmitted" ref="successMessage" class="text-center text-lg text-green-400 mt-6">
            {{ page.props.flash.success }}
        </div>

        <div v-if="form.hasErrors && form.errors.general" class="text-red-400 text-center mt-4">
            {{ form.errors.general }}
        </div>
    </section>
</template>

