<template>
    <PublicLayout>
        <Head title="Beranda">
            <meta name="description" content="Jelajahi keindahan Manggarai Barat, Nusa Tenggara Timur. Temukan destinasi wisata terbaik, event budaya, dan petualangan seru di Labuan Bajo dan sekitarnya." />
            <meta property="og:title" content="SIG Wisata Manggarai Barat - Jelajahi Pesona Labuan Bajo" />
            <meta property="og:description" content="Portal informasi pariwisata resmi Kabupaten Manggarai Barat. Panduan lengkap destinasi, akomodasi, dan event." />
            <meta property="og:image" content="https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" />
        </Head>
        <!-- Hero Section -->
        <section class="relative h-screen min-h-[700px] flex items-center justify-center overflow-hidden bg-gray-900">
            <!-- Hero Background -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/30 to-gray-900 z-10"></div>
                <img 
                    src="https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                    class="w-full h-full object-cover scale-110 animate-pulse-slow"
                    alt="Labuan Bajo"
                >
            </div>

            <div class="relative z-20 max-w-5xl mx-auto px-4 text-center space-y-8 mt-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.2em] animate-fade-in-down">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-ping"></span>
                    Wonderful Indonesia
                </div>
                
                <h1 class="text-5xl md:text-8xl font-black text-white leading-tight tracking-tighter animate-fade-in">
                    Jelajahi <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-400 italic">Pesona</span><br/>Manggarai Barat
                </h1>
                
                <p class="text-lg md:text-xl text-white/70 max-w-2xl mx-auto font-medium leading-relaxed animate-fade-in-up">
                    Selamat datang di portal wisata Kabupaten Manggarai Barat. Jelajahi berbagai objek wisata, budaya lokal, serta informasi perjalanan untuk menikmati pesona alam yang menakjubkan di wilayah ini.
                </p>

                <div class="max-w-3xl mx-auto pt-8 animate-fade-in-up delay-300">
                    <form @submit.prevent="handleSearch" class="bg-white/10 backdrop-blur-xl p-1 rounded-2xl md:rounded-full border border-white/20 shadow-2xl flex flex-col md:flex-row gap-2">
                        <div class="flex-1 flex items-center px-4 gap-3">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Cari Destinasi Wisata"
                                class="w-full bg-transparent border-none focus:ring-0 text-white placeholder-white/50 font-bold"
                            >
                        </div>
                        <button type="submit" class="bg-primary text-white px-8 py-4 rounded-xl md:rounded-full font-black uppercase tracking-widest text-xs hover:scale-105 transition-transform active:scale-95 shadow-xl shadow-primary/40">
                            Cari
                        </button>
                    </form>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-2">
                <span class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em]">Explore</span>
                <div class="w-px h-12 bg-gradient-to-b from-primary to-transparent"></div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="text-4xl md:text-6xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors">{{ stats.objek_count }}+</div>
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">Destinasi Wisata</div>
                </div>
                <div class="text-center group">
                    <div class="text-4xl md:text-6xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors">{{ stats.kecamatan_count }}</div>
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">Kecamatan</div>
                </div>
                <div class="text-center group">
                    <div class="text-4xl md:text-6xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors">{{ stats.event_count }}+</div>
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">Event Tahunan</div>
                </div>
                <div class="text-center group">
                    <div class="text-4xl md:text-6xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors">4.8</div>
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">Rating Rata-rata</div>
                </div>
            </div>
        </section>

        <!-- Popular Destinations -->
        <section class="py-24 bg-gray-50 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-1 bg-primary rounded-full"></div>
                            <span class="text-xs font-black text-primary uppercase tracking-[0.2em]">Destinasi Pilihan</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">Menjelajahi yang <span class="text-primary italic">Populer</span></h2>
                    </div>
                    <a :href="route('public.objek-wisata.index')" class="inline-flex items-center gap-2 text-sm font-black text-gray-900 hover:text-primary transition-colors group uppercase tracking-widest">
                        Semua Destinasi 
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="objek in popularObjects" :key="objek.id" class="group bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-primary/10 transition-all duration-500 hover:-translate-y-2 flex flex-col border border-gray-100">
                        <div class="relative aspect-[4/5] overflow-hidden">
                            <img 
                                :src="objek.fotos.length > 0 ? '/storage/' + objek.fotos[0].path : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1?q=80&w=1000'" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                :alt="objek.nama_objek"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                            
                            <!-- Rating Badge -->
                            <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-white/90 backdrop-blur-md shadow-lg flex items-center gap-1.5 border border-white/20">
                                <svg class="w-3.5 h-3.5 text-yellow-500 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-xs font-black text-gray-900">{{ objek.rating_avg ? Number(objek.rating_avg).toFixed(1) : 'NEW' }}</span>
                            </div>

                            <div class="absolute bottom-6 left-6 right-6 text-white space-y-1">
                                <div class="text-xs font-bold text-primary italic">{{ objek.kecamatan.nama_kecamatan }}</div>
                                <h3 class="text-xl font-black leading-tight">{{ objek.nama_objek }}</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed font-medium">
                                <span v-if="objek.daya_tarik_utama" class="text-primary font-black uppercase text-[9px] block mb-1">Daya Tarik Utama</span>
                                {{ objek.daya_tarik_utama || objek.keterangan }}
                            </p>
                            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    {{ getStartingPrice(objek) 
                                        ? 'Mulai dari Rp ' + getStartingPrice(objek).toLocaleString('id-ID') 
                                        : 'Gratis / Cek Detail' }}
                                </div>
                                <a :href="route('public.objek-wisata.show', objek.slug)" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary transition-colors">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Events Section -->
        <section v-if="upcomingEvents.length > 0" class="py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4">
                 <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-1 bg-primary rounded-full"></div>
                            <span class="text-xs font-black text-primary uppercase tracking-[0.2em]">Agenda Seru</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">Event <span class="text-primary italic">Mendatang</span></h2>
                    </div>
                    <a :href="route('public.events.index')" class="inline-flex items-center gap-2 text-sm font-black text-gray-900 hover:text-primary transition-colors group uppercase tracking-widest">
                        Semua Event 
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <a v-for="event in upcomingEvents" :key="event.id" :href="route('public.events.show', event.slug)" class="group flex flex-col bg-white rounded-[2.5rem] p-4 border border-gray-100 hover:border-primary/20 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="relative w-full aspect-video rounded-[2rem] overflow-hidden">
                             <img 
                                :src="event.fotos.length > 0 ? '/storage/' + event.fotos[0].path : 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=1000'" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                :alt="event.nama_event"
                            >
                            <div class="absolute top-4 left-4 inline-flex flex-col items-center bg-white rounded-2xl p-2 px-3 shadow-xl">
                                <span class="text-primary font-black text-lg leading-none">{{ new Date(event.tanggal_mulai).getDate() }}</span>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">{{ new Date(event.tanggal_mulai).toLocaleString('id-ID', { month: 'short' }) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 p-6 space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ event.objek_wisata ? event.objek_wisata.nama_objek : 'Lokasi Umum' }}
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 leading-tight group-hover:text-primary transition-colors">{{ event.nama_event }}</h3>
                            <p class="text-gray-500 text-sm font-medium line-clamp-2 leading-relaxed">
                                {{ event.keterangan }}
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    popularObjects: Array,
    upcomingEvents: Array,
    stats: Object,
});

const searchQuery = ref('');

const handleSearch = () => {
    router.get(route('public.objek-wisata.index'), { search: searchQuery.value });
};

const getStartingPrice = (objek) => {
    const prices = [
        objek.harga_tiket_lokal,
        objek.harga_tiket_domestik,
        objek.harga_tiket_asing
    ].filter(p => p !== null && p !== undefined);

    if (prices.length === 0) return null;
    return Math.min(...prices);
};
</script>

<style scoped>
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes pulse-slow {
    0%, 100% { transform: scale(1.1); }
    50% { transform: scale(1.15); }
}

.animate-fade-in-down { animation: fade-in-down 1s ease-out forwards; }
.animate-fade-in-up { animation: fade-in-up 1s ease-out forwards; }
.animate-fade-in { animation: fade-in 1.5s ease-out forwards; }
.animate-pulse-slow { animation: pulse-slow 20s infinite ease-in-out; }

.delay-300 { animation-delay: 0.3s; }
</style>
