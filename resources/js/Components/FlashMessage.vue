<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

// THE FIX IS HERE: We use optional chaining (?.) to safely access the property.
// This will return `undefined` instead of crashing if `page.props.flash` doesn't exist.
const message = computed(() => page.props.flash?.success);
</script>

<template>
    <div v-if="message" class="fixed top-5 right-5 z-50">
        <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-lg">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Checkmark Icon -->
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">Success!</p>
                        <p class="mt-1 text-sm text-gray-500">{{ message }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
