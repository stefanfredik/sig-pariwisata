<template>
    <PublicLayout :transparent="false">
        <!-- Sticky Header Background -->
        <div class="h-20 bg-gray-900"></div>

        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Breadcrumbs & Title -->
                <div class="mb-10 space-y-4">
                    <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <a :href="route('home')" class="hover:text-primary">Home</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke="currentColor" /></svg>
                        <a :href="route('public.events.index')" class="hover:text-primary">Events</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke="currentColor" /></svg>
                        <span class="text-gray-900">{{ event.nama_event }}</span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div class="space-y-2">
                             <div class="text-xs font-black text-primary uppercase tracking-[0.2em] italic">
                                {{ event.objek_wisata ? event.objek_wisata.nama_objek : 'Lokasi Umum' }}, Manggarai Barat
                            </div>
                            <h1 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter">{{ event.nama_event }}</h1>
                        </div>
                        <div class="flex items-center gap-4">
                             <span 
                                class="px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest shadow-lg border"
                                :class="{
                                    'bg-blue-500 text-white border-blue-400': status === 'Upcoming',
                                    'bg-emerald-500 text-white border-emerald-400': status === 'Ongoing',
                                    'bg-gray-500 text-white border-gray-400': status === 'Past'
                                }"
                            >
                                {{ status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <!-- Left Side: Content -->
                    <div class="lg:col-span-8 space-y-12">
                        <!-- Image Gallery -->
                        <div class="relative group">
                            <div class="aspect-[16/9] rounded-[3rem] overflow-hidden bg-gray-100 shadow-2xl relative">
                                <img 
                                    :src="mainPhoto" 
                                    class="w-full h-full object-cover"
                                >
                            </div>
                            <!-- Thumbnails -->
                            <div v-if="event.fotos.length > 1" class="flex gap-4 mt-6 overflow-x-auto pb-4 no-scrollbar">
                                <button 
                                    v-for="foto in event.fotos" 
                                    :key="foto.id"
                                    @click="activePhoto = foto.path"
                                    class="relative flex-shrink-0 w-32 aspect-video rounded-2xl overflow-hidden border-2 transition-all"
                                    :class="activePhoto === foto.path ? 'border-primary shadow-lg scale-105' : 'border-transparent opacity-60 hover:opacity-100'"
                                >
                                    <img :src="'/storage/' + foto.path" class="w-full h-full object-cover">
                                </button>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-1 bg-primary rounded-full"></div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]">Tentang Event</h3>
                            </div>
                            <div class="prose prose-lg max-w-none text-gray-600 font-medium leading-relaxed">
                                {{ event.keterangan }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Sidebar Info -->
                    <div class="lg:col-span-4 space-y-8">
                        <!-- Quick Info Card -->
                        <div class="bg-gray-900 text-white rounded-[3rem] p-8 shadow-2xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-[60px] -mr-16 -mt-16"></div>
                            
                            <h4 class="text-sm font-black uppercase tracking-[0.3em] text-primary/80 mb-8">Detail Acara</h4>
                            
                            <ul class="space-y-8 relative z-10">
                                <li class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" stroke-width="2" stroke="currentColor" /></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Tanggal</div>
                                        <div class="text-sm font-bold leading-relaxed">{{ formatDateRange(event.tanggal_mulai, event.tanggal_selesai) }}</div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" stroke="currentColor" /></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Lokasi</div>
                                        <div class="text-sm font-bold leading-relaxed">{{ event.objek_wisata ? event.objek_wisata.nama_objek : 'Lokasi Umum, Manggarai Barat' }}</div>
                                    </div>
                                </li>
                            </ul>

                            <a 
                                v-if="event.objek_wisata"
                                :href="route('public.objek-wisata.show', event.objek_wisata.slug)"
                                class="inline-block w-full mt-10 text-center bg-primary text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-primary/40 hover:scale-[1.02] transition-all"
                            >
                                Lihat Lokasi Wisata
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    event: Object,
});

const activePhoto = ref(props.event.fotos.length > 0 ? props.event.fotos[0].path : null);

const mainPhoto = computed(() => {
    return activePhoto.value ? '/storage/' + activePhoto.value : 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1000';
});

const status = computed(() => {
    const now = new Date();
    const start = new Date(props.event.tanggal_mulai);
    const end = new Date(props.event.tanggal_selesai);
    end.setHours(23, 59, 59);

    if (now < start) return 'Upcoming';
    if (now >= start && now <= end) return 'Ongoing';
    return 'Past';
});

const formatDateRange = (start, end) => {
    const s = new Date(start);
    const e = new Date(end);
    
    if (s.getTime() === e.getTime()) {
        return s.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }
    
    return `${s.getDate()} ${s.toLocaleDateString('id-ID', { month: 'short' })} - ${e.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`;
};
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
