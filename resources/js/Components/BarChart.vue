<script setup>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
import { useDark } from '@vueuse/core';
import { computed } from 'vue';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
    chartData: {
        type: Object,
        required: true,
    },
});

const isDark = useDark();

const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
    },
    scales: {
        x: {
            ticks: {
                color: isDark.value ? '#9ca3af' : '#6b7280', // gray-400 or gray-500
            },
            grid: {
                display: false,
            },
        },
        y: {
            ticks: {
                color: isDark.value ? '#9ca3af' : '#6b7280',
            },
            grid: {
                color: isDark.value ? '#374151' : '#e5e7eb', // gray-700 or gray-200
            },
        },
    },
}));
</script>

<template>
    <div style="height: 250px">
        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>
