<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';

const props = defineProps({
    survey: Object,
});

const form = useForm({
    status: props.survey.status,
});

const statusOptions = [
    { value: 'draft', label: 'Draft' },
    { value: 'active', label: 'Active' },
    { value: 'closed', label: 'Closed' },
];

const submit = () => {
    form.patch(route('surveys.status.update', props.survey.id), {
        preserveScroll: true, // Prevents page from scrolling to top on success
    });
};
</script>

<template>
    <section>
        <header>
            <h3 class="text-lg font-medium text-gray-900">Survey Status</h3>
            <p class="mt-1 text-sm text-gray-600">
                Update the survey's status. Only 'Active' surveys are visible to the public.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 flex items-end space-x-4">
            <div class="flex-grow">
                <InputLabel for="status" value="Status" />
                <SelectInput
                    id="status"
                    class="mt-1 block w-full"
                    v-model="form.status"
                    :options="statusOptions"
                    required
                />
            </div>

            <div class="flex items-center">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
            </div>
        </form>
    </section>
</template>
