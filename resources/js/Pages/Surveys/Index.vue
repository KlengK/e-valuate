<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    surveys: Array,
});

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

// --- NEW DELETE LOGIC ---
const confirmingSurveyDeletion = ref(false);
const surveyToDelete = ref(null);

const confirmSurveyDeletion = (survey) => {
    surveyToDelete.value = survey;
    confirmingSurveyDeletion.value = true;
};

const deleteSurvey = () => {
    router.delete(route('surveys.destroy', surveyToDelete.value.id), {
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => {
    confirmingSurveyDeletion.value = false;
    surveyToDelete.value = null;
};
// --- END OF NEW DELETE LOGIC ---
</script>

<template>
    <Head title="Surveys" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Surveys</h2>
                <Link :href="route('surveys.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                    Create Survey
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p v-if="surveys.length === 0">You haven't created any surveys yet.</p>
                        
                        <div v-else>
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="survey in surveys" :key="survey.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link :href="route('surveys.show', survey.id)" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                {{ survey.title }}
                                            </Link>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ survey.description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                  :class="{
                                                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': survey.status === 'active',
                                                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': survey.status === 'draft',
                                                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': survey.status === 'closed'
                                                  }">
                                                {{ survey.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ formatDate(survey.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="relative inline-block text-left">
                                                <Dropdown align="right" width="48">
                                                    <template #trigger>
                                                        <button class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                                            Actions
                                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                        </button>
                                                    </template>
                                                    <template #content>
                                                        <DropdownLink :href="route('surveys.report', survey.id)">Report</DropdownLink>
                                                        <DropdownLink :href="route('surveys.share', survey.id)">Share</DropdownLink>
                                                        <DropdownLink :href="route('surveys.edit', survey.id)">Edit</DropdownLink>
                                                        <DropdownLink :href="route('surveys.duplicate', survey.id)" method="post" as="button" class="text-yellow-600 dark:text-yellow-400">Duplicate</DropdownLink>
                                                        <!-- vvv ADD THIS DELETE BUTTON vvv -->
                                                        <button @click="confirmSurveyDeletion(survey)" class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 transition duration-150 ease-in-out">
                                                            Delete
                                                        </button>
                                                        <!-- ^^^ END OF DELETE BUTTON ^^^ -->
                                                    </template>
                                                </Dropdown>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- vvv ADD THIS CONFIRMATION MODAL vvv -->
        <Modal :show="confirmingSurveyDeletion" @close="closeModal">
            <div class="p-6 bg-white dark:bg-gray-800">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete this survey?
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Once a survey is deleted, all of its questions and responses will be permanently deleted. This action cannot be undone.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                    <DangerButton class="ms-3" @click="deleteSurvey">
                        Delete Survey
                    </DangerButton>
                </div>
            </div>
        </Modal>
        <!-- ^^^ END OF CONFIRMATION MODAL ^^^ -->

    </AuthenticatedLayout>
</template>
