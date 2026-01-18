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
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" /></svg>
                        <a :href="route('public.objek-wisata.index')" class="hover:text-primary">Destinasi</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" /></svg>
                        <span class="text-gray-900">{{ objekWisata.nama_objek }}</span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div class="space-y-2">
                             <div class="text-xs font-black text-primary uppercase tracking-[0.2em] italic">
                                {{ objekWisata.kecamatan.nama_kecamatan }}, Manggarai Barat
                            </div>
                            <h1 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter">{{ objekWisata.nama_objek }}</h1>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden md:block">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Rating Pengunjung</div>
                                <div class="flex items-center gap-2 justify-end">
                                    <div class="flex text-yellow-500">
                                        <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= Math.round(objekWisata.rating_avg || 0) ? 'fill-current' : 'text-gray-200'" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                    <span class="text-lg font-black text-gray-900">{{ Number(objekWisata.rating_avg || 0).toFixed(1) }}</span>
                                </div>
                            </div>
                            <button class="bg-gray-50 p-4 rounded-2xl hover:bg-red-50 hover:text-red-500 transition-all shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-width="2" /></svg>
                            </button>
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
                                <!-- Photo Indicators/thumbnails overlaid? Let's just do a simple list for now -->
                            </div>
                            <!-- Thumbnails -->
                            <div v-if="objekWisata.fotos.length > 1" class="flex gap-4 mt-6 overflow-x-auto pb-4 no-scrollbar">
                                <button 
                                    v-for="foto in objekWisata.fotos" 
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
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]">Tentang Destinasi</h3>
                            </div>
                            <div class="prose prose-lg max-w-none text-gray-600 font-medium leading-relaxed">
                                {{ objekWisata.keterangan }}
                            </div>
                        </div>

                        <!-- Facilities -->
                        <div v-if="objekWisata.fasilitas.length > 0" class="space-y-8">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-1 bg-primary rounded-full"></div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]">Fasilitas Tersedia</h3>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                                <div v-for="fas in objekWisata.fasilitas" :key="fas.id" class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 items-center text-center space-y-3 group hover:bg-primary transition-all duration-300">
                                    <div class="text-3xl group-hover:scale-110 transition-transform">{{ fas.icon || '📍' }}</div>
                                    <div class="text-xs font-black text-gray-900 group-hover:text-white uppercase tracking-wider">{{ fas.nama_fasilitas }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews (Static for now) -->
                        <div class="space-y-8">
                             <div class="flex items-center gap-2">
                                <div class="w-8 h-1 bg-primary rounded-full"></div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]">Review Pengunjung</h3>
                            </div>
                            <div v-if="objekWisata.reviews.length > 0" class="space-y-6">
                                <div v-for="review in objekWisata.reviews" :key="review.id" class="p-8 bg-white border border-gray-100 rounded-[2.5rem] shadow-sm space-y-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black">
                                                {{ review.user.name.charAt(0) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-gray-900">{{ review.user.name }}</div>
                                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ formatDate(review.created_at) }}</div>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-500">
                                            <svg v-for="i in 5" :key="i" class="w-3.5 h-3.5" :class="i <= review.rating ? 'fill-current' : 'text-gray-200'" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 font-medium leading-relaxed italic">"{{ review.komentar }}"</p>
                                </div>
                            </div>
                            <div v-else class="p-12 text-center bg-gray-50 rounded-[2.5rem] border border-dashed border-gray-200">
                                <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Belum ada review untuk tempat ini.</p>
                            </div>

                            <!-- Review Form -->
                            <div class="mt-12 p-8 md:p-12 bg-gray-50 rounded-[3rem] border border-gray-100 space-y-8">
                                <div class="space-y-2">
                                    <h4 class="text-2xl font-black text-gray-900 tracking-tight">Berikan <span class="text-primary italic">Ulasan Anda</span></h4>
                                    <p class="text-gray-500 text-sm font-medium">Bagikan pengalaman Anda mengunjungi tempat ini kepada wisatawan lain.</p>
                                </div>

                                <div v-if="!$page.props.auth.user" class="p-6 bg-white rounded-2xl border border-gray-100 text-center space-y-4">
                                    <p class="text-gray-600 font-bold">Silakan login terlebih dahulu untuk memberikan ulasan.</p>
                                    <a :href="route('login')" class="inline-flex bg-primary text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest text-[10px] hover:scale-105 transition-all shadow-lg shadow-primary/20">Login Sekarang</a>
                                </div>

                                <form v-else @submit.prevent="submitReview" class="space-y-6">
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Rating Anda</label>
                                        <div class="flex gap-2">
                                            <button 
                                                v-for="i in 5" 
                                                :key="i"
                                                type="button"
                                                @click="form.rating = i"
                                                class="w-12 h-12 rounded-xl flex items-center justify-center transition-all border-2"
                                                :class="form.rating >= i ? 'bg-yellow-50 border-yellow-400 text-yellow-500 shadow-lg shadow-yellow-500/10' : 'bg-white border-gray-100 text-gray-200 hover:border-yellow-200'"
                                            >
                                                <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <span v-if="form.errors.rating" class="text-xs text-red-500 font-bold ml-2">{{ form.errors.rating }}</span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Judul (Opsional)</label>
                                            <input 
                                                v-model="form.judul"
                                                type="text" 
                                                placeholder="Contoh: Sangat Indah!"
                                                class="w-full bg-white border-gray-100 rounded-2xl px-6 py-4 text-gray-900 focus:border-primary focus:ring-primary transition-all font-bold placeholder-gray-300"
                                            >
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Foto (Max 5)</label>
                                            <input 
                                                type="file" 
                                                multiple
                                                @change="handleFileUpload"
                                                accept="image/*"
                                                class="w-full bg-white border-gray-100 rounded-2xl px-6 py-[13px] text-gray-900 focus:border-primary focus:ring-primary transition-all font-bold text-xs file:hidden"
                                            >
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Komentar Ulasan</label>
                                        <textarea 
                                            v-model="form.komentar"
                                            rows="4"
                                            placeholder="Tuliskan detail pengalaman Anda di sini..."
                                            class="w-full bg-white border-gray-100 rounded-3xl px-6 py-4 text-gray-900 focus:border-primary focus:ring-primary transition-all font-bold placeholder-gray-300"
                                        ></textarea>
                                        <span v-if="form.errors.komentar" class="text-xs text-red-500 font-bold ml-2">{{ form.errors.komentar }}</span>
                                    </div>

                                    <button 
                                        type="submit"
                                        :disabled="form.processing"
                                        class="bg-gray-900 text-white px-10 py-5 rounded-[2rem] font-black uppercase tracking-widest text-[10px] hover:scale-105 transition-all shadow-xl shadow-gray-900/20 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ form.processing ? 'Mengirim...' : 'Kirim Ulasan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Sidebar Info -->
                    <div class="lg:col-span-4 space-y-8">
                        <!-- Quick Info Card -->
                        <div class="bg-gray-900 text-white rounded-[3rem] p-8 shadow-2xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-[60px] -mr-16 -mt-16"></div>
                            
                            <h4 class="text-sm font-black uppercase tracking-[0.3em] text-primary/80 mb-8">Informasi Wisata</h4>
                            
                            <ul class="space-y-8 relative z-10">
                                <li class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" /></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Lokasi</div>
                                        <div class="text-sm font-bold leading-relaxed">{{ objekWisata.alamat || 'Manggarai Barat, NTT' }}</div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" /></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Jam Operasional</div>
                                        <div class="text-sm font-bold leading-relaxed">{{ objekWisata.jam_operasional || '08:00 - 18:00 WITA' }}</div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-width="2" /></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Harga Tiket</div>
                                        <div class="text-sm font-bold leading-relaxed text-emerald-400">{{ objekWisata.harga_tiket ? 'Rp ' + objekWisata.harga_tiket.toLocaleString('id-ID') : 'Gratis' }}</div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7.5V5z" stroke-width="2" /></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Hubungi</div>
                                        <div class="text-sm font-bold leading-relaxed">{{ objekWisata.no_telepon || '-' }}</div>
                                    </div>
                                </li>
                            </ul>

                            <button class="w-full mt-10 bg-primary text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-primary/40 hover:scale-[1.02] transition-all">
                                Kunjungi Sekarang
                            </button>
                        </div>

                        <!-- Mini Map Card -->
                        <div class="bg-white rounded-[3rem] border border-gray-100 p-8 shadow-sm space-y-6">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Peta Lokasi</h4>
                            <div id="mini-map" class="w-full h-64 rounded-3xl overflow-hidden border border-gray-100 z-10"></div>
                            <div class="text-center">
                                <a :href="`https://www.google.com/maps/dir/?api=1&destination=${objekWisata.latitude},${objekWisata.longitude}`" target="_blank" class="text-xs font-black text-primary uppercase tracking-widest hover:underline">
                                    Lihat di Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    objekWisata: Object,
});

const activePhoto = ref(props.objekWisata.fotos.length > 0 ? props.objekWisata.fotos[0].path : null);

const mainPhoto = computed(() => {
    return activePhoto.value ? '/storage/' + activePhoto.value : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1?q=80&w=1000';
});

const form = useForm({
    id_objek: props.objekWisata.id,
    rating: 5,
    judul: '',
    komentar: '',
    fotos: [],
});

const handleFileUpload = (e) => {
    form.fotos = Array.from(e.target.files);
};

const submitReview = () => {
    form.post(route('public.reviews.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('rating', 'judul', 'komentar', 'fotos');
        },
    });
};

onMounted(() => {
    // Mini Map
    const map = L.map('mini-map', {
        zoomControl: false,
        attributionControl: false
    }).setView([props.objekWisata.latitude, props.objekWisata.longitude], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    const icon = L.icon({
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
    });

    L.marker([props.objekWisata.latitude, props.objekWisata.longitude], { icon }).addTo(map);
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
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
