<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useForm, usePage } from '@inertiajs/vue3';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';
import gsap from 'gsap';
import { Calendar, CalendarCheck2, CalendarX2, Clock, Link, LoaderCircle } from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    readyToLoad: boolean;
    meetings: string[];
    googleauth: {
        google_email: string;
        google_name: string;
        google_picture: string;
    };
    googleauthurl: string;
    usermeet: {
        start_datetime: string;
        end_datetime: string;
    };
}>();

const form = useForm({
    summary: '',
    timeslot_id: null,
    recaptcha_token: '',
});

const page = usePage();
const successMeeting = computed(() => page.props.flash?.success_meeting);
const failureMeeting = computed(() => page.props.flash?.failure_meeting);
const failureMeetingLink = computed(() => page.props.flash?.failure_meeting_link);
const confirmedMeeting = computed(() => page.props.flash?.confirmed_meeting);

const selectedDate = ref<Date | null>(null);
const attrs = ref([]);
const timeslots = ref<any[]>([]);
const selectedTimeslotId = ref<number | null>(null);
const siteKey = import.meta.env.VITE_RECAPTCHA_SITE_KEY;
const formKey = ref(0);
const timeslotContainer = ref(null);
const formContainer = ref(null);
const confirmedSlot = ref<{ start: string; end: string } | null>(null);

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
            setupGsapAnimations();
            gsap.fromTo(formContainer.value, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6 });
        }
    });
};

onMounted(() => {
    if (props.meetings?.length) {
        const parsedDates = props.meetings.map((date) => {
            const [year, month, day] = date.split('-').map(Number);
            return new Date(year, month - 1, day);
        });
        attrs.value = [{ highlight: 'green', dates: parsedDates }];
    }
    if (window.location.hash) {
        nextTick(() => {
            const el = document.querySelector(window.location.hash);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});

watch(
    () => props.readyToLoad,
    (newVal) => {
        if (newVal) {
            nextTick(() => {
                setupGsapAnimations();
            });
        }
    },
);

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

const hasGoogleToken = computed(() => page.props.googleToken !== null);

const submit = async () => {
    if (!form.timeslot_id) {
        console.log('Veuillez sélectionner un créneau horaire.');
        return;
    }

    const token = await window.grecaptcha.execute(siteKey, { action: 'submit' });
    form.recaptcha_token = token;

    form.put(route('meets.book', { timeslot: form.timeslot_id }), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('success');
            const selectedSlot = timeslots.value.find((slot) => slot.id === selectedTimeslotId.value);
            if (selectedSlot) {
                confirmedSlot.value = {
                    start: selectedSlot.start_datetime,
                    end: selectedSlot.end_datetime,
                };
            }
        },
        onFinish: () => {
            console.log('finish');
            selectedDate.value = null;
            selectedTimeslotId.value = null;
            timeslots.value = [];
            form.summary = '';
            form.timeslot_id = null;
            form.reset('summary', 'timeslot_id');
            formKey.value++;
        },
    });
};
</script>

<template>
    <section id="meets" v-if="props.readyToLoad && props.meetings?.length" class="bg-[#0a192f] px-6 py-20 text-[#ccd6f6]" data-aos="fade-bottom">
        <h2 class="mb-10 inline-block border-b-2 border-green-400 text-3xl font-bold">Prendre rendez-vous</h2>

        <!-- L'utilisateur a déjà rendez-vous -->
        <template v-if="props.usermeet">
            <h3 class="mb-2 text-lg font-semibold">Bonjour {{ props.googleauth?.google_name }},</h3>
            <p>Nous avons déjà une rencontre programmée.</p>
            <p class="flex w-full justify-center pb-10 pt-10"><CalendarCheck2 :size="40" /></p>
                <p class="flex w-full flex-col items-center gap-2 text-white">
                    <span v-if="props.usermeet.start_datetime" class="flex items-center justify-center gap-2">
                        <Calendar :size="16" />
                        <span>{{ new Date(props.usermeet.start_datetime).toLocaleDateString() }}</span>
                    </span>
                    <span v-if="props.usermeet.start_datetime" class="flex items-center justify-center gap-2">
                        <Clock :size="16" />
                        <span>{{ new Date(props.usermeet.start_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }} – {{ new Date(props.usermeet.end_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}</span>
                    </span>
                    <span v-if="props.usermeet.link" class="flex items-center justify-center gap-2">
                        <Link :size="16" />
                        <a :href="props.usermeet.link" class="text-green-400" target="_blank"> Google Meet </a>
                    </span>
                </p>
        </template>

        <!-- Message de confirmation -->
        <template v-else-if="successMeeting">
            <div class="mt-2 flex flex-col items-center gap-4 text-center text-lg text-green-400">
                <p class="w-full" v-html="successMeeting"></p>
                <p class="flex w-full justify-center pb-10 pt-10"><CalendarCheck2 :size="40" /></p>
                <p v-if="confirmedMeeting" class="flex w-full flex-col items-center gap-2 text-white">
                    <span class="flex items-center justify-center gap-2">
                        <Calendar :size="16" />
                        <span>{{ confirmedMeeting.date }}</span>
                    </span>
                    <span class="flex items-center justify-center gap-2">
                        <Clock :size="16" />
                        <span>{{ confirmedMeeting.start }} – {{ confirmedMeeting.end }}</span>
                    </span>
                    <span class="flex items-center justify-center gap-2">
                        <Link :size="16" />
                        <a :href="confirmedMeeting.link" class="text-green-400" target="_blank"> Google Meet </a>
                    </span>
                </p>
                <p></p>
            </div>
        </template>

        <template v-else-if="failureMeeting">
            <div class="mt-2 justify-center gap-4 text-center text-lg text-red-400">
                <p class="w-full">{{ failureMeeting }}</p>
                <p class="flex w-full justify-center pb-10 pt-10">
                    <CalendarX2 :size="40" />
                </p>
                <div v-if="failureMeetingLink" class="mt-4">
                    <a
                        :href="failureMeetingLink"
                        class="rounded border border-green-400 bg-[#0a192f] px-4 py-2 text-white transition-all duration-200 hover:bg-green-800"
                    >
                        S'identifier avec Google
                    </a>
                </div>
            </div>
        </template>

        <!-- Contenu normal (calendrier + formulaire) -->

        <!-- Grille -->
        <div v-else class="grid grid-cols-1 items-start gap-6 md:grid-cols-3">
            <!-- Colonne calendrier (1/3) -->
            <div class="flex w-full justify-center">
                <VCalendar :min-date="new Date()" :attributes="attrs" :is-dark="true" @dayclick="handleDateClick" />
            </div>

            <!-- Colonne créneaux + formulaire (2/3) -->
            <div class="space-y-6 md:col-span-2">
                <template v-if="props.googleauth">
                    <!-- Créneaux -->
                    <div ref="timeslotContainer" class="w-full">
                        <h3 class="mb-2 text-lg font-semibold" v-if="selectedDate">Plages horaire disponibles :</h3>
                        <div v-if="timeslots.length" class="flex flex-wrap gap-2">
                            <button
                                v-for="slot in timeslots"
                                :key="slot.id"
                                @click="selectTimeslot(slot.id)"
                                class="rounded border px-4 py-2 font-mono transition-all duration-200"
                                :class="[
                                    selectedTimeslotId === slot.id
                                        ? 'border-green-400 bg-green-400 text-[#0a192f]'
                                        : 'border-green-400 bg-[#0a192f] text-white hover:bg-green-800',
                                ]"
                            >
                                {{ new Date(slot.start_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                -
                                {{ new Date(slot.end_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                            </button>
                        </div>
                        <p v-else-if="selectedDate" class="text-gray-400">Aucune plage horaire disponible pour cette date.</p>

                        <template v-else>
                                                    <h3 class="text-lg font-semibold mb-2">
                                                        {{ props.googleauth?.google_email }} est identifié.
                                                    </h3>
                                                    <p class="text-gray-400">
                                                        Choisir une date dans le calendrier.
                                                    </p>
                    </template>
                    </div>

                    <!-- Formulaire -->
                    <form
                        :key="formKey"
                        v-if="selectedTimeslotId && !successMeeting"
                        ref="formContainer"
                        @submit.prevent="submit"
                        class="grid w-full max-w-full gap-4"
                    >
                        <div>
                            <p class="w-full rounded border border-[#64ffda] bg-[#0a192f] p-2 text-white">{{ props.googleauth?.google_name }}</p>
                        </div>

                        <div>
                            <p class="w-full rounded border border-[#64ffda] bg-[#0a192f] p-2 text-white">{{ props.googleauth?.google_email }}</p>
                        </div>

                        <div>
                            <Input
                                id="summary"
                                type="text"
                                required
                                v-model="form.summary"
                                placeholder="Objet de la rencontre"
                                class="gsap-hover w-full rounded border border-[#64ffda] bg-[#0a192f] p-2 text-white"
                            />
                            <InputError :message="form.errors.summary" />
                        </div>

                        <Button
                            type="submit"
                            class="gsap-hover mt-2 w-full rounded bg-green-400 px-4 py-2 text-[#0a192f] transition hover:bg-green-300"
                            :disabled="form.processing"
                        >
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            <span v-else>Envoyer</span>
                        </Button>
                    </form>
                </template>

                <template v-else>
                    <a
                        :href="googleauthurl"
                        class="rounded border border-green-400 bg-[#0a192f] px-4 py-2 text-white transition-all duration-200 hover:bg-green-800"
                    >
                        S'identifier avec Google
                    </a>
                </template>
            </div>
        </div>
    </section>
</template>
