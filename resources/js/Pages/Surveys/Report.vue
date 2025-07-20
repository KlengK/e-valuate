<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PieChart from '@/Components/PieChart.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    survey: Object,
    totalCompletions: Number,
    reportData: Array,
    sessions: Array,
    individualResponse: {
        type: Object,
        default: null,
    },
});

const activeTab = ref('summary');
const currentResponseIndex = ref(0);

const formatPieChartData = (results) => {
    const labels = Object.keys(results);
    const data = Object.values(results);
    const backgroundColors = labels.map((_, index) => {
        const colors = ['#4A55A2', '#7895CB', '#A0BFE0', '#C5DFF8', '#E1F0FA'];
        return colors[index % colors.length];
    });
    return {
        labels,
        datasets: [{ backgroundColor: backgroundColors, data }],
    };
};

const currentSession = computed(() => {
    if (props.sessions.length > 0 && props.individualResponse) {
        return props.individualResponse;
    }
    return null;
});

const fetchIndividualResponse = (index) => {
    if (index < 0 || index >= props.sessions.length) return;
    currentResponseIndex.value = index;
    const sessionId = props.sessions[index].id;
    router.get(route('surveys.report.individual', { survey: props.survey.id, session: sessionId }), {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch(activeTab, (newTab) => {
    if (newTab === 'individual' && !props.individualResponse && props.sessions.length > 0) {
        fetchIndividualResponse(0);
    }
});

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>

<template>
    <Head :title="`Report: ${survey.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Report: {{ survey.title }}
                </h2>

                <Dropdown align="right" width="48">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                Export Options
                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    </template>
                    <template #content>
                        <a :href="route('surveys.report.export.summary_csv', survey.id)" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 transition duration-150 ease-in-out">
                            Export Summary (CSV)
                        </a>
                        <a :href="route('surveys.report.export.summary_pdf', survey.id)" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 transition duration-150 ease-in-out">
                            Export Summary (PDF)
                        </a>
                    </template>
                </Dropdown>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Tab Navigation -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ totalCompletions }} Responses</h3>
                        <div class="mt-4 border-b border-gray-200 dark:border-gray-700">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button @click="activeTab = 'summary'" :class="[activeTab === 'summary' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-300 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600']" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Summary
                                </button>
                                <button @click="activeTab = 'individual'" :class="[activeTab === 'individual' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-300 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600']" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Individual
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Summary View -->
                        <div v-if="activeTab === 'summary'" class="space-y-6">
                           <div v-for="question in reportData" :key="question.id" class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ question.question_text }}</h4>
                                <div class="mt-2">
                                    <div v-if="question.question_type === 'rating'">
                                        <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ question.results.average }} ★ <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Average Rating</span></p>
                                    </div>
                                    <div v-else-if="question.question_type === 'multiple_choice'">
                                        <PieChart v-if="Object.keys(question.results).length > 0" :chart-data="formatPieChartData(question.results)" />
                                        <p v-else class="text-sm text-gray-500 dark:text-gray-400">No responses for this question yet.</p>
                                    </div>
                                    <div v-else-if="question.question_type === 'text'">
                                        <ul class="space-y-2 list-disc list-inside text-sm">
                                            <li v-for="(answer, index) in question.results.slice(0, 5)" :key="index" class="text-gray-600 dark:text-gray-400">{{ answer }}</li>
                                            <li v-if="question.results.length > 5" class="text-gray-500 dark:text-gray-400 italic">...and {{ question.results.length - 5 }} more.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Individual View -->
                        <div v-if="activeTab === 'individual'">
                            <div v-if="totalCompletions === 0" class="text-center text-gray-500 dark:text-gray-400">
                                <p>No individual responses yet.</p>
                            </div>
                            <div v-else>
                                <!-- Pagination Controls -->
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center space-x-4 text-gray-800 dark:text-gray-200">
                                        <button @click="fetchIndividualResponse(currentResponseIndex - 1)" :disabled="currentResponseIndex === 0" class="p-1 rounded-full disabled:opacity-50 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                        </button>
                                        <p class="text-sm">Response {{ currentResponseIndex + 1 }} of {{ totalCompletions }}</p>
                                        <button @click="fetchIndividualResponse(currentResponseIndex + 1)" :disabled="currentResponseIndex >= totalCompletions - 1" class="p-1 rounded-full disabled:opacity-50 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Completed: {{ currentSession ? formatDate(currentSession.completed_at) : 'Loading...' }}</p>
                                </div>
                                <!-- Response Details -->
                                <div v-if="currentSession" class="mt-4">
                                    <ul class="space-y-6">
                                        <li v-for="response in currentSession.responses" :key="response.id">
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ response.question.order }}. {{ response.question.question_text }}</p>
                                            <div class="mt-2 pl-4 py-2 border-l-4 border-indigo-400">
                                                <p class="text-lg text-gray-700 dark:text-gray-300">{{ response.answer_value }} <span v-if="response.question.question_type === 'rating'">★</span></p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    Loading response...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
