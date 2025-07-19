<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    surveys: Array,
});

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>

<template>
    <Head title="Surveys" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Surveys</h2>
                <Link :href="route('surveys.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Create Survey
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p v-if="surveys.length === 0">You haven't created any surveys yet.</p>
                        
                        <table v-else class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="survey in surveys" :key="survey.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <Link :href="route('surveys.show', survey.id)" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                            {{ survey.title }}
                                        </Link>
                                        <div class="text-sm text-gray-500">{{ survey.description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="{
                                                  'bg-green-100 text-green-800': survey.status === 'active',
                                                  'bg-yellow-100 text-yellow-800': survey.status === 'draft',
                                                  'bg-red-100 text-red-800': survey.status === 'closed'
                                              }">
                                            {{ survey.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate(survey.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('surveys.report', survey.id)" class="text-indigo-600 hover:text-indigo-900">
                                            View Report
                                        </Link>
                                            <Link :href="route('surveys.share', survey.id)" class="text-indigo-600 hover:text-indigo-900">
        Share
    </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
