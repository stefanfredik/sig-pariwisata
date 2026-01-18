<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Page Header -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
                <p class="mt-1 text-sm text-gray-600">Selamat datang di panel admin SIG Wisata Manggarai Barat</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-50 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Kecamatan</dt>
                                    <dd class="text-3xl font-black text-gray-900">{{ stats.total_kecamatan }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-emerald-50 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5v.665M19 10.5a2 2 0 002 2v.5A2.5 2.5 0 0118.5 18H17m-5-3h1m1 0h1m2 0h1" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">Objek Wisata</dt>
                                    <dd class="text-3xl font-black text-gray-900">{{ stats.total_objek_wisata }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-50 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Event</dt>
                                    <dd class="text-3xl font-black text-gray-900">{{ stats.total_events }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-50 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.518 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Review</dt>
                                    <dd class="text-3xl font-black text-gray-900">{{ stats.total_reviews }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-50 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total User</dt>
                                    <dd class="text-3xl font-black text-gray-900">{{ stats.total_users }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-50 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">Review Pending</dt>
                                    <dd class="text-3xl font-black text-red-600">{{ stats.pending_reviews }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white shadow-sm rounded-xl p-8 border border-gray-100">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">Tren Pengunjung</h3>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">6 Bulan Terakhir</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="flex items-center gap-1.5 text-[10px] font-black uppercase text-primary bg-primary/5 px-3 py-1.5 rounded-lg border border-primary/10">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                Views
                            </span>
                        </div>
                    </div>
                    <div class="h-80 relative">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-100 flex flex-col">
                    <h3 class="text-lg font-black text-gray-900 tracking-tight mb-8">Aktivitas Terbaru</h3>
                    <div class="flex-1 space-y-6 overflow-y-auto pr-2 no-scrollbar">
                        <div v-for="review in recent_reviews" :key="review.id" class="flex gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex-shrink-0 flex items-center justify-center text-primary font-black uppercase text-xs">
                                {{ review.user.name.charAt(0) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ review.user.name }}</p>
                                <p class="text-[10px] text-gray-500 font-medium line-clamp-2 italic">"{{ review.komentar }}"</p>
                                <p class="text-[9px] text-primary font-black uppercase tracking-widest mt-1">{{ review.objek_wisata.nama_objek }}</p>
                            </div>
                        </div>
                        <div v-if="recent_reviews.length === 0" class="text-center py-12 text-gray-400 font-bold text-xs uppercase tracking-widest">
                            Belum ada aktivitas
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Akses Cepat</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <a :href="route('admin.kecamatan.create')" class="btn-primary py-3 group">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Kecamatan
                    </a>
                    <a :href="route('admin.objek-wisata.create')" class="btn-secondary py-3 group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5v.665M19 10.5a2 2 0 002 2v.5A2.5 2.5 0 0118.5 18H17m-5-3h1m1 0h1m2 0h1" />
                        </svg>
                        Tambah Objek Wisata
                    </a>
                    <a :href="route('admin.events.create')" class="btn-secondary py-3 group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tambah Event
                    </a>
                    <a :href="route('admin.reviews.index')" class="btn-secondary py-3 group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.518 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Moderasi Review
                    </a>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Chart from 'chart.js/auto';

defineProps({
    stats: Object,
    recent_reviews: Array,
    upcoming_events: Array,
});

onMounted(() => {
    const ctx = document.getElementById('visitorChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Visitor Views',
                    data: [1200, 1900, 1500, 2500, 2200, 3000],
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
            },
            options: {
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
            }
        });
    }
});
</script>
