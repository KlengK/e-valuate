<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// The import for AddQuestionForm has been removed.
import { Head } from '@inertiajs/vue3';

defineProps({
    survey: Object,
});
</script>

<template>
    <Head :title="`Survey: ${survey.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ survey.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- Survey Details Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b">
                        <h3 class="text-lg font-medium">Survey Details</h3>
                        <p class="mt-2 text-gray-600">{{ survey.description }}</p>
                    </div>
                </div>

                <!-- Questions List Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900">Questions</h3>
                        <div class="mt-4">
                            <p v-if="survey.questions.length === 0">This survey has no questions yet.</p>
                            <ul v-else class="space-y-4">
                                <li v-for="question in survey.questions" :key="question.id" class="p-4 border rounded-md">
                                    <p class="font-semibold">{{ question.order }}. {{ question.question_text }}</p>
                                    <p class="text-sm text-gray-500">Type: {{ question.question_type.replace('_', ' ') }}</p>
                                    <ul v-if="question.question_type === 'multiple_choice' && question.options" class="mt-2 list-disc list-inside text-sm text-gray-600">
                                        <li v-for="(option, index) in question.options" :key="index">{{ option }}</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- The "Add Question Form" card has been completely removed from this page. -->

            </div>
        </div>
    </AuthenticatedLayout>
</template>
