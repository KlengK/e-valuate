<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center p-4 bg-gray-100 gradient-bg">
        <div class="w-full max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg flex flex-wrap overflow-hidden">
                
                <!-- Left Column (Branding with Background Image) -->
                <div class="w-full lg:w-1/2 hidden lg:block bg-cover bg-center" style="background-image: url('/images/hero-background.png');">
                    <div class="flex flex-col items-center justify-center h-full bg-blue-900 bg-opacity-50 p-12 text-white text-center">
                        <Link href="/" class="flex items-center space-x-3 mb-6">
                            <img src="/images/logoo.png" alt="ValACE Logo" class="h-12 w-auto">
                            <span class="text-3xl font-bold">e-VALuate</span>
                        </Link>
                        <h1 class="text-3xl font-bold leading-tight">Insights for a Better Library.</h1>
                        <p class="mt-4 text-blue-100">Welcome to the official survey management portal.</p>
                    </div>
                </div>

                <!-- Right Column (Form) -->
                <div class="w-full lg:w-1/2 p-8 sm:p-12">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.gradient-bg {
    background: linear-gradient(-90deg, rgba(84, 122, 226, 0.8), rgba(59, 130, 246, 0.8), rgba(226, 46, 46, 0.8), rgba(42, 141, 253, 0.8));
    background-size: 400% 400%;
    animation: gradient 15s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>
