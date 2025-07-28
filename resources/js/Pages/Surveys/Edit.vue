<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    survey: Object,
});

const form = useForm({
    title: props.survey.title,
    description: props.survey.description,
    questions: props.survey.questions.map(q => ({
        id: q.id,
        question_text: q.question_text,
        question_type: q.question_type,
        is_required: q.is_required,
        options: q.options || [''],
    })),
});

const questionTypes = [
    { value: 'rating', label: 'Star Rating (1-5)' },
    { value: 'text', label: 'Open Text' },
    { value: 'multiple_choice', label: 'Multiple Choice (Single Answer)' },
    { value: 'checkbox', label: 'Checkboxes (Multiple Answers)' }, // <-- NEW OPTION
];

const addQuestion = () => {
    form.questions.push({ id: null, question_text: '', question_type: 'rating', options: [''], is_required: false });
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
    form.put(route('surveys.update', props.survey.id));
};

const hasOptions = (type) => {
    return ['multiple_choice', 'checkbox'].includes(type);
};
</script>

<template>
    <Head :title="`Edit Survey: ${survey.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Survey</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg space-y-6">
                    <!-- Survey Details Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Survey Details</h3>
                        <div class="mt-4">
                            <InputLabel for="title" value="Title" />
                            <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="description" value="Description (Optional)" />
                            <textarea id="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" v-model="form.description" rows="3"></textarea>
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700" />

                    <!-- Questions Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Questions</h3>
                        <div v-for="(question, index) in form.questions" :key="index" class="p-4 mt-4 border dark:border-gray-700 rounded-md space-y-4 relative">
                             <button type="button" @click="removeQuestion(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold" v-if="form.questions.length > 1">X</button>
                            <div>
                                <InputLabel :for="'question_text_' + index" :value="`Question ${index + 1}`" />
                                <TextInput :id="'question_text_' + index" type="text" class="mt-1 block w-full" v-model="question.question_text" required placeholder="Enter the question text" />
                            </div>
                            <div>
                                <InputLabel :for="'question_description_' + index" value="Description (Optional)" />
                                <TextInput :id="'question_description_' + index" type="text" class="mt-1 block w-full" v-model="question.description" placeholder="Add extra instructions or context" />
                            </div>
                            <div>
                                <InputLabel :for="'question_type_' + index" value="Question Type" />
                                <SelectInput :id="'question_type_' + index" class="mt-1 block w-full" v-model="question.question_type" :options="questionTypes" required />
                            </div>
                            <div>
                                <InputLabel value="Is this question required?" />
                                <div class="mt-2 flex items-center space-x-3">
                                    <button type="button" @click="question.is_required = !question.is_required" :class="question.is_required ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700'" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out">
                                        <span :class="question.is_required ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition"></span>
                                    </button>
                                    <span class="text-sm text-gray-600 dark:text-gray-400" v-text="question.is_required ? 'Required' : 'Optional'"></span>
                                </div>
                            </div>
                            <div v-if="hasOptions(question.question_type)" class="space-y-2 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                                <InputLabel value="Options" />
                                <div v-for="(option, optionIndex) in question.options" :key="optionIndex" class="flex items-center space-x-2">
                                    <TextInput type="text" class="block w-full" v-model="question.options[optionIndex]" placeholder="Enter an option" />
                                    <button type="button" @click="removeOption(index, optionIndex)" class="text-sm text-red-500" v-if="question.options.length > 1">Remove</button>
                                </div>
                                <button type="button" @click="addOption(index)" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">+ Add another option</button>
                            </div>
                        </div>
                        <button type="button" @click="addQuestion" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600">
                            Add Another Question
                        </button>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700" />

                    <div class="flex items-center justify-end">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Update Survey
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
