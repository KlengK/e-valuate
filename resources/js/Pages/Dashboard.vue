<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BarChart from '@/Components/BarChart.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    activeSurveysList: Array,
    recentActivityFeed: Array,
    weeklyTrendData: Object,
    mostActiveSurvey: Object,
    latestFeedback: Array,
    housekeeping: Object,
});

const formatDate = (dateString) => {
    const options = { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const weeklyChartData = computed(() => {
    if (!props.weeklyTrendData) return {};
    return {
        labels: Object.keys(props.weeklyTrendData),
        datasets: [
            {
                label: 'Responses',
                backgroundColor: '#4f46e5', // indigo-600
                borderColor: '#4f46e5',
                data: Object.values(props.weeklyTrendData),
                barThickness: 15,
            },
        ],
    };
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
                <Link :href="route('surveys.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    + Create New Survey
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- 1. "At-a-Glance" Statistics -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Surveys</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.totalSurveys }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Surveys</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.activeSurveys }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Responses Today</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.responsesToday }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Responses</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.totalResponses }}</p>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- 2. "Weekly Response Trend" Chart -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Weekly Response Trend</h3>
                            </div>
                            <div class="p-6">
                                <BarChart :chart-data="weeklyChartData" />
                            </div>
                        </div>

                        <!-- 3. "Active Surveys" List -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Active Surveys</h3>
                            </div>
                            <div class="p-6">
                                <p v-if="activeSurveysList.length === 0" class="text-gray-500 dark:text-gray-400">You have no active surveys.</p>
                                <ul v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <li v-for="survey in activeSurveysList" :key="survey.id" class="py-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                        <div>
                                            <p class="text-md font-semibold text-indigo-600 dark:text-indigo-400">{{ survey.title }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ survey.survey_sessions_count }} responses today</p>
                                        </div>
                                        <div class="flex-shrink-0 flex items-center space-x-4">
                                            <Link :href="route('surveys.report', survey.id)" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-500">Report</Link>
                                            <Link :href="route('surveys.share', survey.id)" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-500">Share</Link>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- 4. "Most Active Survey" Card -->
                        <div v-if="mostActiveSurvey" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Most Active This Week</h3>
                                <div class="mt-4 text-center bg-indigo-50 dark:bg-indigo-900/50 p-4 rounded-lg">
                                    <p class="font-semibold text-indigo-800 dark:text-indigo-300">{{ mostActiveSurvey.title }}</p>
                                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ mostActiveSurvey.survey_sessions_count }}</p>
                                    <p class="text-sm text-indigo-500 dark:text-indigo-400">responses</p>
                                </div>
                            </div>
                        </div>

                        <!-- 5. "Latest Feedback" Snippet -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Latest Feedback</h3>
                            </div>
                            <div class="p-6">
                                <p v-if="latestFeedback.length === 0" class="text-gray-500 dark:text-gray-400">No text responses yet.</p>
                                <ul v-else class="space-y-4">
                                    <li v-for="feedback in latestFeedback" :key="feedback.id">
                                        <p class="text-sm text-gray-800 dark:text-gray-200 truncate">"{{ feedback.answer_value }}"</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            From: {{ feedback.question.question_text }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- 6. "Housekeeping" Alerts -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Needs Attention</h3>
                                <ul class="mt-4 space-y-2">
                                    <li v-if="housekeeping.draftSurveys > 0">
                                        <Link :href="route('surveys.index')" class="text-sm text-yellow-600 dark:text-yellow-400 hover:underline">
                                            {{ housekeeping.draftSurveys }} survey(s) are in draft mode.
                                        </Link>
                                    </li>
                                    <li v-if="housekeeping.oldActiveSurveys > 0">
                                        <Link :href="route('surveys.index')" class="text-sm text-yellow-600 dark:text-yellow-400 hover:underline">
                                            {{ housekeeping.oldActiveSurveys }} survey(s) have been active for over 30 days.
                                        </Link>
                                    </li>
                                     <li v-if="housekeeping.draftSurveys === 0 && housekeeping.oldActiveSurveys === 0" class="text-sm text-gray-500 dark:text-gray-400">
                                        Everything looks good!
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
