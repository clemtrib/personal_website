<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    timeslots: {
        data: Array<any>;
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        next_page_url: string | null;
        prev_page_url: string | null;
    };
    filters?: {
        date?: string;
        per_page?: number;
    };
}>();

const filterDate = ref(props.filters?.date || '');
const perPage = ref(props.filters?.per_page || 25);

const goToPage = (page: number) => {
    router.get(
        route('meets'),
        {
            date: filterDate.value,
            per_page: perPage.value,
            page,
        },
        { preserveScroll: true, preserveState: true },
    );
};

const updateFilters = () => {
    router.get(
        route('meets'),
        {
            date: filterDate.value,
            per_page: perPage.value,
        },
        { preserveScroll: true, preserveState: true },
    );
};
</script>

<template>
    <div class="relative h-full w-full">
        <div class="relative z-10 p-4">
            <h2 class="mb-4 text-2xl font-bold">Rencontres</h2>

            <!-- Filtre par date et nombre par page -->
            <div class="mb-4 flex items-center gap-2">
                <label for="filter-date" class="font-medium">Filtrer par date :</label>
                <input id="filter-date" type="date" v-model="filterDate" class="rounded border px-2 py-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                <label class="ml-4 font-medium">Par page :</label>
                <select v-model="perPage" class="rounded border px-2 py-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option :value="10" v-if="perPage != 10">10</option>
                    <option :value="10" v-if="perPage == 10" selected>10</option>
                    <option :value="25" v-if="perPage != 25">25</option>
                    <option :value="25" v-if="perPage == 25" selected>25</option>
                </select>
                <button @click="updateFilters" class="ml-2 rounded bg-green-600 px-3 py-1 text-sm text-white hover:bg-green-700">Appliquer</button>
                <button
                    v-if="filterDate"
                    @click="
                        filterDate = '';
                        updateFilters();
                    "
                    class="ml-2 text-sm text-gray-600 dark:text-green-400 hover:underline"
                >
                    Réinitialiser
                </button>
            </div>

            <ul v-if="timeslots && timeslots.data" class="space-y-4">
                <li v-for="meet in timeslots.data" :key="meet.id" class="flex items-center gap-4 rounded-lg bg-white p-4 shadow dark:bg-gray-800">

                    <!-- Bloc date -->
                    <div class="w-12 shrink-0 text-center text-xl font-bold text-gray-700 dark:text-gray-300">
                        <div class="text-gray-600 dark:text-green-400">
                            <pre>{{ new Date(meet.start_datetime).getDate().toString().padStart(2, '0') }}</pre>
                        </div>
                        <div>
                            <pre>{{ (new Date(meet.start_datetime).getMonth() + 1).toString().padStart(2, '0') }}</pre>
                        </div>
                        <div>
                            <pre>{{ new Date(meet.start_datetime).getFullYear().toString().slice(-2) }}</pre>
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="flex-1">
                        <div class="mb-1 flex items-center justify-between text-sm text-gray-600 dark:text-green-400">
                            <div>
                                <span v-if="meet.recipient_fullname == null && meet.recipient_email == null" class="text-gray-600 dark:text-gray-400">
                                    Plage horaire disponible de
                                </span>
                                <span>
                                    {{ new Date(meet.start_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                    <span class="text-gray-600 dark:text-gray-400"> à </span>
                                    {{ new Date(meet.end_datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                </span>
                            </div>
                        </div>

                        <h3 class="font-semibold" v-if="meet.recipient_fullname && meet.recipient_email">
                            {{ meet.recipient_fullname }} - {{ meet.recipient_email }}
                        </h3>

                        <p v-if="meet.summary" class="prose prose-invert mt-2 max-w-none text-gray-800 dark:text-gray-400">
                            {{ meet.summary }}
                        </p>

                        <div class="mt-4 flex gap-2">
                            <button
                                v-if="!meet.recipient_fullname && !meet.recipient_email"
                                @click="openDeleteModal(meet.id)"
                                class="flex items-center gap-2 rounded-lg bg-red-100 px-3 py-2 text-sm transition hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50"
                            >
                                <Eraser class="h-4 w-4" />
                                Supprimer
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

            <!-- Pagination -->
            <div v-if="timeslots.last_page > 1" class="mt-6 flex justify-center gap-2">
                <button
                    @click="goToPage(timeslots.current_page - 1)"
                    :disabled="!timeslots.prev_page_url"
                    class="rounded px-3 py-1 text-sm"
                    :class="!timeslots.prev_page_url ? 'bg-gray-200 text-gray-400' : 'bg-blue-600 text-white hover:bg-blue-700'"
                >
                    Précédent
                </button>
                <span class="px-2 py-1 text-sm font-medium"> Page {{ timeslots.current_page }} / {{ timeslots.last_page }} </span>
                <button
                    @click="goToPage(timeslots.current_page + 1)"
                    :disabled="!timeslots.next_page_url"
                    class="rounded px-3 py-1 text-sm"
                    :class="!timeslots.next_page_url ? 'bg-gray-200 text-gray-400' : 'bg-blue-600 text-white hover:bg-blue-700'"
                >
                    Suivant
                </button>
            </div>
        </div>
    </div>
</template>
