<template>
    <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-100">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Tren Pengunjung</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">6 Bulan Terakhir</p>
            </div>
        </div>
        <div class="h-80 relative">
            <Line :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

const props = defineProps({
    data: Object // { labels: [], data: [] }
});

const chartData = computed(() => ({
    labels: props.data.labels,
    datasets: [{
        label: 'Visitor Views',
        data: props.data.data,
        borderColor: '#2563EB',
        backgroundColor: 'rgba(37, 99, 235, 0.05)',
        borderWidth: 4,
        fill: true,
        tension: 0.4,
        pointRadius: 0,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: '#2563EB',
        pointHoverBorderColor: '#fff',
        pointHoverBorderWidth: 4,
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            mode: 'index',
            intersect: false,
            padding: 12,
            backgroundColor: '#111827',
            titleFont: { size: 10, weight: 'bold' },
            bodyFont: { size: 12, weight: 'bold' },
            displayColors: false
        }
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: {
                font: { size: 10, weight: 'bold' },
                color: '#9CA3AF'
            }
        },
        y: {
            grid: { borderDash: [5, 5], color: '#F3F4F6' },
            ticks: {
                font: { size: 10, weight: 'bold' },
                color: '#9CA3AF'
            }
        }
    }
};
</script>
