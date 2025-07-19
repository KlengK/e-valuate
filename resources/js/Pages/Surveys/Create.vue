<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    questions: [
        { question_text: '', question_type: 'rating', options: [''] }
    ],
});

const questionTypes = [
    { value: 'rating', label: 'Star Rating (1-5)' },
    { value: 'text', label: 'Open Text' },
    { value: 'multiple_choice', label: 'Multiple Choice' },
];

const addQuestion = () => {
    form.questions.push({ question_text: '', question_type: 'rating', options: [''] });
};

const removeQuestion = (index) => {
    form.questions.splice(index, 1);
};

const addOption = (questionIndex) => {
    form.questions[questionIndex].options.push('');
};

const removeOption = (questionIndex, optionIndex) => {
    form.questions[questionIndex].options.splice(optionIndex, 1);
};

const submit = () => {
    // Before submitting, filter out any empty options for multiple choice
    form.questions.forEach(q => {
        if (q.question_type === 'multiple_choice') {
            q.options = q.options.filter(opt => opt.trim() !== '');
        } else {
            q.options = [];
        }
    });
    form.post(route('surveys.store'));
};
</script>

<template>
    <Head title="Create Survey" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create a New Survey</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg space-y-6">
                    <!-- Survey Details Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Survey Details</h3>
                        <div class="mt-4">
                            <InputLabel for="title" value="Title" />
                            <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="description" value="Description (Optional)" />
                            <textarea id="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.description" rows="3"></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>
                    </div>

                    <hr />

                    <!-- Questions Section -->
                    <div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Questions</h3>
                             <InputError class="mt-2" :message="form.errors.questions" />
                        </div>
                        
                        <div v-for="(question, index) in form.questions" :key="index" class="p-4 mt-4 border rounded-md space-y-4 relative">
                             <button type="button" @click="removeQuestion(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold" v-if="form.questions.length > 1">X</button>
                            <div>
                                <InputLabel :for="'question_text_' + index" :value="`Question ${index + 1}`" />
                                <TextInput :id="'question_text_' + index" type="text" class="mt-1 block w-full" v-model="question.question_text" required placeholder="Enter the question text" />
                                <InputError class="mt-2" :message="form.errors[`questions.${index}.question_text`]" />
                            </div>
                            <div>
                                <InputLabel :for="'question_type_' + index" value="Question Type" />
                                <SelectInput :id="'question_type_' + index" class="mt-1 block w-full" v-model="question.question_type" :options="questionTypes" required />
                            </div>
                            <div>
                                <InputLabel value="Is this question optional or required?" />
                                <div class="mt-2 flex items-center space-x-3">
                                    <button
                                        type="button"
                                        @click="question.is_required = !question.is_required"
                                        :class="question.is_required ? 'bg-indigo-600' : 'bg-gray-200'"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        <span
                                            :class="question.is_required ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        ></span>
                                    </button>
                                    <span class="text-sm text-gray-600" v-text="question.is_required ? 'Required' : 'Optional'"></span>
                                </div>
                                <InputError class="mt-2" :message="form.errors[`questions.${index}.is_required`]" />
                            </div>                            
                            <div v-if="question.question_type === 'multiple_choice'" class="space-y-2 pl-4 border-l-2">
                                <InputLabel value="Options" />
                                <div v-for="(option, optionIndex) in question.options" :key="optionIndex" class="flex items-center space-x-2">
                                    <TextInput type="text" class="block w-full" v-model="question.options[optionIndex]" placeholder="Enter an option" />
                                    <button type="button" @click="removeOption(index, optionIndex)" class="text-sm text-red-500" v-if="question.options.length > 1">Remove</button>
                                </div>
                                <button type="button" @click="addOption(index)" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add another option</button>
                                <InputError class="mt-2" :message="form.errors[`questions.${index}.options`]" />
                            </div>
                        </div>

                        <button type="button" @click="addQuestion" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">
                            Add Another Question
                        </button>
                    </div>

                    <hr />

                    <!-- Form Submission -->
                    <div class="flex items-center justify-end">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save Survey and Questions
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
