<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PieChart from '@/Components/PieChart.vue'; // <-- Import the new component
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

// This function transforms the multiple-choice data for the pie chart
const formatPieChartData = (results) => {
    const labels = Object.keys(results);
    const data = Object.values(results);
    
    // Generate some nice colors for the chart
    const backgroundColors = labels.map((_, index) => {
        const colors = ['#4A55A2', '#7895CB', '#A0BFE0', '#C5DFF8', '#E1F0FA'];
        return colors[index % colors.length];
    });

    return {
        labels,
        datasets: [
            {
                backgroundColor: backgroundColors,
                data,
            },
        ],
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Report: {{ survey.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Tab Navigation -->
                    <div class="p-6 border-b">
                        <h3 class="text-2xl font-semibold">{{ totalCompletions }} Responses</h3>
                        <div class="mt-4 border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button @click="activeTab = 'summary'" :class="[activeTab === 'summary' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300']" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Summary
                                </button>
                                <button @click="activeTab = 'individual'" :class="[activeTab === 'individual' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300']" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Individual
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Summary View -->
                        <div v-if="activeTab === 'summary'" class="space-y-6">
                           <div v-for="question in reportData" :key="question.id" class="p-4 border rounded-md">
                                <h4 class="font-semibold text-gray-800">{{ question.question_text }}</h4>
                                <div class="mt-2">
                                    <div v-if="question.question_type === 'rating'">
                                        <p class="text-xl font-bold text-indigo-600">{{ question.results.average }} ★ <span class="text-sm font-normal text-gray-500">Average Rating</span></p>
                                    </div>
                                    
                                    <!-- vvv THIS IS THE UPDATED PART vvv -->
                                    <div v-else-if="question.question_type === 'multiple_choice'">
                                        <PieChart v-if="Object.keys(question.results).length > 0" :chart-data="formatPieChartData(question.results)" />
                                        <p v-else class="text-sm text-gray-500">No responses for this question yet.</p>
                                    </div>
                                    <!-- ^^^ END OF UPDATED PART ^^^ -->

                                    <div v-else-if="question.question_type === 'text'">
                                        <ul class="space-y-2 list-disc list-inside text-sm">
                                            <li v-for="(answer, index) in question.results.slice(0, 5)" :key="index" class="text-gray-600">{{ answer }}</li>
                                            <li v-if="question.results.length > 5" class="text-gray-500 italic">...and {{ question.results.length - 5 }} more.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Individual View -->
                        <div v-if="activeTab === 'individual'">
                            <!-- ... (Individual View content remains the same) ... -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
