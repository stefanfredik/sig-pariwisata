<template>
    <PublicLayout>
        <Head>
            <title>Agenda & Event - Manggarai Barat</title>
            <meta name="description" content="Kalender event dan festival di Manggarai Barat. Jangan lewatkan kemeriahan budaya dan acara spesial kami." />
        </Head>
        <!-- Page Header -->
        <section class="relative pt-32 pb-20 bg-gray-900 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-b from-black/60 to-gray-900 z-10"></div>
                <img 
                    src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1920" 
                    class="w-full h-full object-cover"
                >
            </div>

            <div class="relative z-20 max-w-7xl mx-auto px-4 text-center space-y-4">
                <div class="flex items-center justify-center gap-2">
                    <div class="w-8 h-1 bg-primary rounded-full"></div>
                    <span class="text-xs font-black text-primary uppercase tracking-[0.2em]">Agenda</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-white px-2 py-2">Kalender <span class="text-primary italic">Kegiatan</span></h1>
                <p class="text-gray-400 max-w-xl mx-auto font-medium">
                    Jangan lewatkan momen seru! Temukan berbagai festival, pertunjukan budaya, dan acara menarik lainnya di Manggarai Barat.
                </p>
            </div>
        </section>

        <!-- Filters -->
        <section class="relative z-30 -mt-12 mb-12">
            <div class="max-w-6xl mx-auto px-4">
                <div class="bg-white rounded-[2.5rem] shadow-2xl p-4 md:p-6 border border-gray-100">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-12 lg:col-span-4 relative group">
                            <input
                                v-model="filterForm.search"
                                type="text"
                                placeholder="Cari nama event..."
                                class="block w-full px-6 rounded-2xl border-gray-50 bg-gray-50/50 py-4 focus:ring-primary focus:border-primary font-bold text-gray-900"
                            />
                        </div>
                        <div class="md:col-span-6 lg:col-span-3">
                            <select
                                v-model="filterForm.id_objek"
                                class="block w-full rounded-2xl border-gray-50 bg-gray-50/50 py-4 focus:ring-primary focus:border-primary font-bold text-gray-900"
                            >
                                <option value="">Semua Lokasi</option>
                                <option v-for="objek in objekWisatas" :key="objek.id" :value="objek.id">
                                    {{ objek.nama_objek }}
                                </option>
                            </select>
                        </div>
                        <div class="md:col-span-6 lg:col-span-3">
                            <select
                                v-model="filterForm.status"
                                class="block w-full rounded-2xl border-gray-50 bg-gray-50/50 py-4 focus:ring-primary focus:border-primary font-bold text-gray-900"
                            >
                                <option value="">Semua Status</option>
                                <option value="upcoming">Mendatang</option>
                                <option value="ongoing">Sedang Berlangsung</option>
                                <option value="past">Sudah Selesai</option>
                            </select>
                        </div>
                        <div class="md:col-span-12 lg:col-span-2 flex gap-2">
                             <button type="submit" class="flex-1 bg-primary text-white p-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:scale-105 transition-transform shadow-lg shadow-primary/20">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Results Grid -->
        <section class="pb-24">
            <div class="max-w-7xl mx-auto px-4">
                <div v-if="events.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <div v-for="event in events.data" :key="event.id" class="group flex flex-col bg-white rounded-[2.5rem] p-4 border border-gray-100 hover:border-primary/20 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="relative aspect-video rounded-[2rem] overflow-hidden">
                             <img 
                                :src="event.fotos.length > 0 ? '/storage/' + event.fotos[0].path : 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1000'" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                :alt="event.nama_event"
                            >
                            <div class="absolute top-4 left-4 inline-flex flex-col items-center bg-white rounded-2xl p-2 px-3 shadow-xl">
                                <span class="text-primary font-black text-xl leading-none">{{ new Date(event.tanggal_mulai).getDate() }}</span>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">{{ new Date(event.tanggal_mulai).toLocaleString('id-ID', { month: 'short' }) }}</span>
                            </div>
                            
                            <div class="absolute bottom-4 right-4">
                                <span 
                                    class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg border backdrop-blur-md"
                                    :class="{
                                        'bg-blue-500/90 text-white border-blue-400': getStatus(event) === 'Upcoming',
                                        'bg-emerald-500/90 text-white border-emerald-400': getStatus(event) === 'Ongoing',
                                        'bg-gray-500/90 text-white border-gray-400': getStatus(event) === 'Past'
                                    }"
                                >
                                    {{ getStatus(event) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 p-6 space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ event.objek_wisata ? event.objek_wisata.nama_objek : 'Lokasi Umum' }}
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 leading-tight group-hover:text-primary transition-colors line-clamp-2 min-h-[3.5rem]">{{ event.nama_event }}</h3>
                            <p class="text-gray-500 text-sm font-medium line-clamp-2 leading-relaxed italic">
                                "{{ event.keterangan }}"
                            </p>
                            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">
                                    {{ formatDateRange(event.tanggal_mulai, event.tanggal_selesai) }}
                                </div>
                                <a :href="route('public.events.show', event.slug)" class="text-primary font-black text-xs uppercase tracking-widest hover:underline">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>

                 <!-- Empty State -->
                <div v-else class="py-32 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Tidak Ada Event</h3>
                    <p class="text-gray-400 font-medium">Coba gunakan filter pencarian lain.</p>
                </div>

                <!-- Pagination -->
                <div v-if="events.links.length > 3" class="mt-20 flex justify-center">
                    <Pagination :links="events.links" />
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    events: Object,
    objekWisatas: Array,
    filters: Object,
});

const filterForm = reactive({
    search: props.filters.search || '',
    id_objek: props.filters.id_objek || '',
    status: props.filters.status || '',
});

const applyFilters = () => {
    router.get(route('public.events.index'), filterForm, {
        preserveState: true,
        replace: true,
    });
};

const getStatus = (event) => {
    const now = new Date();
    const start = new Date(event.tanggal_mulai);
    const end = new Date(event.tanggal_selesai);
    end.setHours(23, 59, 59);

    if (now < start) return 'Upcoming';
    if (now >= start && now <= end) return 'Ongoing';
    return 'Past';
};

const formatDateRange = (start, end) => {
    const s = new Date(start);
    const e = new Date(end);
    
    if (s.getTime() === e.getTime()) {
        return s.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }
    
    return `${s.getDate()} - ${e.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}`;
};
</script>
