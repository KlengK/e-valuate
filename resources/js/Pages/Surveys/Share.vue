<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    survey: Object,
    shareUrl: String,
    qrCode: String,
    logoUrl: String,
});

const copySuccess = ref(false);

const copyToClipboard = () => {
    const textToCopy = document.querySelector('#shareUrlInput');
    textToCopy.select();
    document.execCommand('copy');
    copySuccess.value = true;
    setTimeout(() => {
        copySuccess.value = false;
    }, 2000);
};

const downloadQrCode = () => {
    const svgContainer = document.getElementById('qr-code-container');
    const svgString = new XMLSerializer().serializeToString(svgContainer);
    const blob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `qrcode-survey-${props.survey.id}.svg`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
};
</script>

<template>
    <Head :title="`Share: ${survey.title}`" />

    <AuthenticatedLayout>
        <template #header>
             <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Share Survey: {{ survey.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Share URL Card -->
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                      <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Shareable Link</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Copy this link and share it directly.</p>
                    <div class="mt-4 flex items-center space-x-2">
                        <input
                            id="shareUrlInput"
                            :value="shareUrl"
                            type="text"
                            class="block w-full bg-gray-100 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"                            readonly
                        />
                        <PrimaryButton @click="copyToClipboard">
                            <span v-if="!copySuccess">Copy</span>
                            <span v-else>Copied!</span>
                        </PrimaryButton>
                    </div>
                </div>

                <!-- QR Code Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">QR Code</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Download this QR code to use on posters and flyers.</p>
                    
                    <div class="inline-block mt-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <!-- We wrap both images in an SVG to make downloading easier -->
                        <svg id="qr-code-container" width="256" height="256" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <!-- The QR Code Image -->
                            <image :href="qrCode" x="0" y="0" height="256" width="256" />
                            <!-- The Logo Image (without a background) -->
                            <image 
                                :href="logoUrl" 
                                x="55" y="55" height="150" width="150"
                            />
                        </svg>
                    </div>

                    <div class="mt-4">
                        <PrimaryButton @click="downloadQrCode">Download QR Code</PrimaryButton>
                    </div>
                </div>

                <div class="text-center">
                     <Link :href="route('surveys.index')" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        &larr; Back to All Surveys
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
