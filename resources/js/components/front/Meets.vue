<script setup lang="ts">
import { ref, onMounted } from 'vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps < {
    readyToLoad: boolean
    meetings: Array<string>
} > ();


const selectedDate = ref(null);
const attrs = ref([]);

onMounted(() => {
    if (props.meetings?.length) {
        const parsedDates = props.meetings.map(date => new Date(date + 'T00:00:00.000Z'));
        console.log(parsedDates);
        console.log('da');
        attrs.value = [{
            highlight: 'green',
            dates: parsedDates,
        }];
    }
});
</script>

<template>
    <section v-if="props.readyToLoad && props.meetings?.length" id="meets" class="px-6 py-20 bg-[#0a192f] text-[#ccd6f6]" data-aos="fade-bottom">
        <h2 class="text-3xl font-bold mb-6 border-b-2 border-green-400 inline-block">
            Prendre rendez-vous
        </h2>
        <VCalendar :min-date="new Date()" :attributes='attrs' :is-dark="true" />
    </section>
</template>
