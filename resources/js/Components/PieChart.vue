<script setup>
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

defineProps({
    chartData: {
        type: Object,
        required: true,
    },
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};
</script>

<template>
    <div style="height: 300px">
        <Pie :data="chartData" :options="chartOptions" />
    </div>
</template>
