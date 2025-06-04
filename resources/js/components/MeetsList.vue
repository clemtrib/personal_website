<script setup lang="ts">
import { createCalendar, createViewDay, createViewMonthAgenda, createViewMonthGrid, createViewWeek } from '@schedule-x/calendar';
import '@schedule-x/theme-default/dist/index.css';
import { ScheduleXCalendar } from '@schedule-x/vue';

import { Eraser } from 'lucide-vue-next';

const emit = defineEmits(['open-delete-modal']);

const props = defineProps<{
  timeslots: Array<any>;
}>();

// Création du calendrier
const calendarApp = createCalendar({
    selectedDate: new Date().toISOString().split('T')[0],
    views: [
        createViewDay(),
        createViewWeek({
            weekOptions: {
                visibleDays: [1, 2, 3, 4, 5], // Lundi à Vendredi (0 = Dimanche)
            },
        }),
        createViewMonthGrid(),
        createViewMonthAgenda(),
    ],
    events: props.timeslots.map((meet) => ({
        id: meet.id,
        title: meet.recipient_fullname ? `${meet.recipient_fullname}` : 'Disponible',
        start: meet.start_datetime.substr(0, 16),
        end: meet.end_datetime.substr(0, 16),
        classNames: meet.recipient_fullname ? ['event-booked'] : ['event-free'],
        meta: {
            summary: meet.summary || null,
            joinUrl: meet.link || null,
            deletable: !meet.recipient_fullname,
            start: meet.start_datetime.substr(10, 6),
            end: meet.end_datetime.substr(10, 6),
            title: meet.recipient_email + ' | ' + meet.summary,
        },
    })),
});

const openDeleteModal = (id: number) => {
    emit('open-delete-modal', id);
};
</script>

<template>
    <div class="relative h-full w-full">
        <div class="relative z-10 p-4">
            <h2 class="mb-4 text-2xl font-bold">Rencontres</h2>

            <ScheduleXCalendar :calendar-app="calendarApp">
                <template #timeGridEvent="{ calendarEvent }">
                    <div v-if="calendarEvent.meta.deletable" :class="calendarEvent.classNames">
                        <div @click="openDeleteModal(calendarEvent.id)">
                            <p>{{ calendarEvent.meta.start }} - {{ calendarEvent.meta.end }}</p>
                            <p class="font-bold">{{ calendarEvent.title }}</p>
                            <p class="italic">{{ calendarEvent.meta.summary }}</p>
                        </div>
                    </div>
                    <div v-else :class="calendarEvent.classNames">
                        <a :href="calendarEvent.meta.joinUrl" :title="calendarEvent.meta.title" target="_blank">
                            <p>{{ calendarEvent.meta.start }} - {{ calendarEvent.meta.end }}</p>
                            <p class="font-bold">{{ calendarEvent.title }}</p>
                        </a>
                    </div>
                </template>
            </ScheduleXCalendar>

        </div>
    </div>
</template>

<style>
.event-free,
.event-booked {
    width: 100% !important;
    min-height: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 4px 6px;
    font-size: 12px;
    overflow: hidden;
    border-radius: 4px;
    box-sizing: border-box;
    line-height: 1.2;
    height: 100%; /* Laisse ScheduleX gérer la hauteur */
}

.event-free {
    background-color: #10b981 !important;
    border-left: 4px solid #096949;
    color: white !important;
}

.event-booked {
    background-color: #ef4444 !important;
    border-left: 4px solid #942b2b;
    color: white !important;
}
</style>
