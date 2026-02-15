<template>
    <PublicLayout :forceSolid="true">
        <Head>
            <title>{{ event.nama_event }} - Event Manggarai Barat</title>
            <meta name="description" :content="event.keterangan ? event.keterangan.substring(0, 160) : 'Ikuti keseruan ' + event.nama_event" />
            <meta property="og:title" :content="event.nama_event + ' - Agenda Wisata Manggarai Barat'" />
            <meta property="og:description" :content="event.keterangan ? event.keterangan.substring(0, 160) : 'Informasi detail event ' + event.nama_event" />
            <meta property="og:image" :content="mainPhoto" />
            <component :is="'script'" type="application/ld+json">
                {{ jsonLd }}
            </component>
        </Head>
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

                            <div class="mt-8 border-t border-white/10 pt-8">
                                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500 mb-4 text-center">Bagikan</h4>
                                <div class="flex gap-3 justify-center">
                                    <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent($page.props.ziggy.location)}`" target="_blank" class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                    <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent($page.props.ziggy.location)}&text=${encodeURIComponent(event.nama_event)}`" target="_blank" class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                    </a>
                                    <a :href="`https://wa.me/?text=${encodeURIComponent(event.nama_event + ' ' + $page.props.ziggy.location)}`" target="_blank" class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    event: Object,
});

const activePhoto = ref(props.event.fotos.length > 0 ? props.event.fotos[0].path : null);

const mainPhoto = computed(() => {
    return activePhoto.value ? '/storage/' + activePhoto.value : 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1000';
});

const jsonLd = computed(() => {
    return JSON.stringify({
        "@context": "https://schema.org",
        "@type": "Event",
        "name": props.event.nama_event,
        "description": props.event.keterangan,
        "image": [mainPhoto.value],
        "startDate": props.event.tanggal_mulai,
        "endDate": props.event.tanggal_selesai,
        "eventStatus": "https://schema.org/EventScheduled",
        "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
        "location": {
            "@type": "Place",
            "name": props.event.objek_wisata ? props.event.objek_wisata.nama_objek : "Lokasi Umum",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Manggarai Barat",
                "addressRegion": "Nusa Tenggara Timur",
                "addressCountry": "ID"
            }
        }
    });
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
