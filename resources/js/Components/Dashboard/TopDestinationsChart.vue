<template>
    <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-100 flex flex-col">
        <h3 class="text-lg font-black text-gray-900 tracking-tight mb-8">Top Destinasi</h3>
        <div class="flex-1 relative h-64">
             <Bar :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const props = defineProps({
    destinations: Array // [{ nama_objek: '', rating_avg: 0, review_count: 0 }]
});

const chartData = computed(() => ({
    labels: props.destinations.map(d => d.nama_objek.substring(0, 15) + (d.nama_objek.length > 15 ? '...' : '')),
    datasets: [{
        label: 'Rating',
        data: props.destinations.map(d => d.rating_avg),
        backgroundColor: [
            'rgba(37, 99, 235, 0.8)',
            'rgba(124, 58, 237, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(239, 68, 68, 0.8)',
        ],
        borderRadius: 4,
        barThickness: 20,
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: 'y',
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#111827',
            padding: 12,
            titleFont: { size: 10, weight: 'bold' },
            bodyFont: { size: 12, weight: 'bold' },
        }
    },
    scales: {
        x: {
            max: 5,
            grid: { borderDash: [5, 5], color: '#F3F4F6' },
            ticks: {
                font: { size: 10, weight: 'bold' },
                color: '#9CA3AF'
            }
        },
        y: {
            grid: { display: false },
            ticks: {
                font: { size: 10, weight: 'bold' },
                color: '#4B5563'
            }
        }
    }
};
</script>
