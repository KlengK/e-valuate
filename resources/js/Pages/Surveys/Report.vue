<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    survey: Object,
    totalCompletions: Number,
    reportData: Array,
});

// Helper function to format text
const formatText = (text) => {
    return text.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
};
</script>

<template>
    <Head :title="`Report: ${survey.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Report: {{ survey.title }}
                    </h2>
                    <Link :href="route('surveys.show', survey.id)" class="text-sm text-indigo-600 hover:text-indigo-800">
                        &larr; Back to Survey Details
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <!-- Overall Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900">Overall Statistics</h3>
                    <div class="mt-4">
                        <p class="text-gray-700">
                            <span class="font-semibold">{{ totalCompletions }}</span> completed surveys.
                        </p>
                    </div>
                </div>

                <!-- Per-Question Breakdown -->
                <div v-for="question in reportData" :key="question.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b">
                        <h4 class="font-semibold text-gray-800">{{ question.question_text }}</h4>
                        <p class="text-sm text-gray-500">Type: {{ formatText(question.question_type) }} | {{ question.total_responses }} responses</p>
                    </div>
                    <div class="p-6 bg-gray-50">
                        <!-- Rating Results -->
                        <div v-if="question.question_type === 'rating'">
                            <p class="text-2xl font-bold text-indigo-600">{{ question.results.average }} ★</p>
                            <p class="text-sm text-gray-600">Average Rating</p>
                        </div>

                        <!-- Multiple Choice Results -->
                        <div v-else-if="question.question_type === 'multiple_choice'">
                            <ul class="space-y-2">
                                <li v-for="(count, option) in question.results" :key="option" class="flex justify-between items-center">
                                    <span class="text-gray-700">{{ option }}</span>
                                    <span class="font-semibold text-gray-900">{{ count }} votes</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Text Results -->
                        <div v-else-if="question.question_type === 'text'">
                             <ul class="space-y-3 list-disc list-inside">
                                <li v-for="(answer, index) in question.results" :key="index" class="text-gray-700 bg-gray-100 p-2 rounded">
                                    {{ answer }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
