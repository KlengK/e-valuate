<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdateStatusForm from './Partials/UpdateStatusForm.vue'; // <-- Import the form
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    survey: Object,
});
</script>

<template>
    <Head :title="`Survey: ${survey.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ survey.title }}
                </h2>
                <Link :href="route('surveys.edit', survey.id)" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                    Edit Survey
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- Survey Details Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium">Survey Details</h3>
                        <div class="mt-2 text-gray-600">
                            <p>{{ survey.description }}</p>
                            <p class="mt-2">
                                <span class="font-semibold">Status:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      :class="{
                                          'bg-green-100 text-green-800': survey.status === 'active',
                                          'bg-yellow-100 text-yellow-800': survey.status === 'draft',
                                          'bg-red-100 text-red-800': survey.status === 'closed'
                                      }">
                                    {{ survey.status }}
                                </span>
                            </p>
                             <div class="mt-4">
                                <Link :href="route('surveys.report', survey.id)" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    View Report &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Status Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                       <UpdateStatusForm :survey="survey" />
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

            </div>
        </div>
    </AuthenticatedLayout>
</template>
