<template>
    <PublicLayout :forceSolid="true">
        <Head>
            <title>Peta Interaktif - SIG Wisata Manggarai Barat</title>
            <meta name="description" content="Peta interaktif sebaran objek wisata dan fasilitas di Kabupaten Manggarai Barat." />
        </Head>
        <div class="fixed inset-0 top-20 w-full overflow-hidden bg-slate-50 dark:bg-slate-950">
            <!-- Map Container -->
            <div id="main-map" class="w-full h-full z-10 transition-all duration-500 ease-in-out" :class="{ 'lg:pl-[400px]': sidebarOpen }"></div>

            <!-- Floating Search Bar (Mobile Only) -->
            <div class="lg:hidden absolute top-6 left-1/2 -translate-x-1/2 z-[1000] w-[90%] transition-all duration-300">
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 p-2 flex items-center gap-2">
                    <button class="p-2 text-slate-400">
                        <Menu class="w-5 h-5" />
                    </button>
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Cari destinasi..." 
                        class="flex-1 bg-transparent border-none focus:ring-0 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400"
                    />
                    <div class="w-8 h-8 rounded-xl bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/20">
                        <Search class="w-4 h-4" />
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside 
                class="absolute z-[2000] transition-all duration-500 ease-in-out lg:top-0 lg:left-0 lg:h-full w-full lg:w-[400px] bottom-0 h-[60vh] lg:h-full"
                :class="[
                    sidebarOpen 
                        ? 'translate-y-0 lg:translate-x-0 lg:w-[400px]' 
                        : 'translate-y-full lg:-translate-x-full lg:w-0'
                ]"
            >
                <div class="h-full bg-white dark:bg-slate-900 border-t-2 lg:border-t-0 lg:border-r-2 border-slate-100 dark:border-slate-800 shadow-2xl p-5 lg:p-7 flex flex-col gap-5 rounded-t-[2.5rem] lg:rounded-none">
                    <!-- Mobile Drag Handle -->
                    <div class="lg:hidden w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto -mt-1 mb-1"></div>
                    
                    <!-- Sidebar Header -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                                <MapIcon class="w-6 h-6" />
                            </div>
                            <div>
                                <h1 class="text-lg lg:text-xl font-black text-slate-900 dark:text-white leading-none">Interactive <span class="text-primary italic">Peta</span></h1>
                                <p class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Manggarai Barat, NTT</p>
                            </div>
                        </div>
                        <button @click="sidebarOpen = false" class="p-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-500 hover:text-primary transition-all shadow-sm">
                            <X class="lg:hidden w-5 h-5" />
                            <ChevronLeft class="hidden lg:block w-5 h-5" />
                        </button>
                    </div>

                    <!-- Tabs: Wisata / UMKM -->
                    <div class="flex bg-slate-100 dark:bg-slate-800 rounded-2xl p-1">
                        <button 
                            @click="activeTab = 'wisata'"
                            class="flex-1 py-2.5 px-4 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all"
                            :class="activeTab === 'wisata' ? 'bg-white dark:bg-slate-700 text-blue-600 shadow-md' : 'text-slate-400 hover:text-slate-600'"
                        >
                            🏝️ Wisata
                        </button>
                        <button 
                            @click="activeTab = 'umkm'"
                            class="flex-1 py-2.5 px-4 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all"
                            :class="activeTab === 'umkm' ? 'bg-white dark:bg-slate-700 text-amber-600 shadow-md' : 'text-slate-400 hover:text-slate-600'"
                        >
                            🏪 UMKM
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="relative">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="activeTab === 'wisata' ? 'Cari objek wisata...' : 'Cari UMKM...'"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-400 placeholder:font-medium transition-all outline-none"
                        />
                    </div>

                    <!-- Results List -->
                    <div class="flex-1 overflow-y-auto no-scrollbar space-y-2">

                        <!-- Objek Wisata List -->
                        <template v-if="activeTab === 'wisata'">
                            <!-- Category Chips -->
                            <div class="flex gap-2 overflow-x-auto pb-3 no-scrollbar">
                                <button 
                                    v-for="cat in categories" 
                                    :key="cat.name"
                                    @click="activeCategory = cat.name"
                                    class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider whitespace-nowrap transition-all border-2"
                                    :class="[activeCategory === cat.name ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-blue-400 hover:text-blue-600']"
                                >
                                    <component :is="cat.icon" class="w-3.5 h-3.5" />
                                    {{ cat.name }}
                                </button>
                            </div>

                            <!-- Jumlah hasil -->
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                                {{ filteredObjekWisatas.length }} Destinasi Ditemukan
                            </p>

                            <div 
                                v-for="objek in filteredObjekWisatas" 
                                :key="objek.id"
                                @click="focusToMarker(objek)"
                                class="group flex gap-4 p-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-50 dark:hover:shadow-none transition-all cursor-pointer"
                            >
                                <!-- Foto -->
                                <div class="w-[72px] h-[72px] rounded-xl overflow-hidden flex-shrink-0 bg-slate-100">
                                    <img :src="objek.fotos.length > 0 ? '/storage/' + objek.fotos[0].path : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1'" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <!-- Info -->
                                <div class="flex-1 min-w-0 py-0.5">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-tight leading-none">{{ objek.kecamatan.nama_kecamatan }}</span>
                                        <div v-if="userLocation" class="flex items-center gap-1 text-[9px] font-bold text-slate-400 flex-shrink-0">
                                            <Navigation class="w-2.5 h-2.5" />
                                            {{ calculateDistance(userLocation.lat, userLocation.lng, objek.latitude, objek.longitude) }} km
                                        </div>
                                    </div>
                                    <h3 class="font-black text-slate-900 dark:text-white text-sm leading-tight mb-2 truncate group-hover:text-blue-600 transition-colors">{{ objek.nama_objek }}</h3>
                                    <div class="flex items-center gap-1.5 mb-3">
                                        <div class="flex text-yellow-400">
                                            <svg v-for="i in 5" :key="i" class="w-3 h-3" :class="[i <= Math.round(objek.rating_avg || 0) ? 'fill-current' : 'fill-slate-200']" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-500">{{ Number(objek.rating_avg || 0).toFixed(1) }}</span>
                                    </div>
                                    
                                    <div v-if="objek.akses_transportasi && objek.akses_transportasi.length > 0" class="space-y-1 bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700/50">
                                        <div class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Akses Transportasi</div>
                                        <ul class="text-[10px] text-slate-600 dark:text-slate-400 font-bold space-y-1">
                                            <li v-for="akses in objek.akses_transportasi" :key="akses" class="flex items-start gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-500 mt-[-1px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                <span>{{ akses }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div v-if="filteredObjekWisatas.length === 0" class="py-16 text-center space-y-4">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto text-slate-300">
                                    <Search class="w-8 h-8" />
                                </div>
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Tidak ditemukan</p>
                            </div>
                        </template>

                        <!-- UMKM List -->
                        <template v-if="activeTab === 'umkm'">
                            <!-- Jumlah hasil -->
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                                {{ filteredUmkms.length }} UMKM Ditemukan
                            </p>

                            <div 
                                v-for="umkm in filteredUmkms" 
                                :key="umkm.id"
                                @click="focusToUmkmMarker(umkm)"
                                class="group flex gap-4 p-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-amber-400 hover:shadow-lg hover:shadow-amber-50 dark:hover:shadow-none transition-all cursor-pointer"
                            >
                                <!-- Foto/Emoji -->
                                <div class="w-[60px] h-[60px] rounded-xl overflow-hidden flex-shrink-0 bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center">
                                    <img v-if="umkm.fotos && umkm.fotos.length > 0" :src="'/storage/' + umkm.fotos[0].path" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <span v-else class="text-2xl">🏪</span>
                                </div>
                                <!-- Info -->
                                <div class="flex-1 min-w-0 py-0.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 text-[9px] font-black uppercase tracking-wider mb-1.5">{{ umkm.kategori }}</span>
                                    <h3 class="font-black text-slate-900 dark:text-white text-sm leading-tight truncate group-hover:text-amber-600 transition-colors">{{ umkm.nama_umkm }}</h3>
                                    <p class="text-[10px] text-slate-400 mt-1 truncate font-medium">{{ umkm.alamat }}</p>
                                </div>
                            </div>

                            <div v-if="filteredUmkms.length === 0" class="py-16 text-center space-y-4">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto text-2xl">🏪</div>
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Tidak ditemukan</p>
                            </div>
                        </template>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Toggle Button (when closed) / Mobile Search Trigger -->
            <button 
                v-if="!sidebarOpen" 
                @click="sidebarOpen = true"
                class="absolute bottom-10 left-10 z-[2000] w-14 h-14 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl flex items-center justify-center text-slate-900 dark:text-white hover:text-primary border border-white/20 transition-all hover:scale-110 active:scale-95"
            >
                <Search class="w-6 h-6" />
            </button>

            <!-- Map Legend -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 lg:left-auto lg:translate-x-0 lg:bottom-28 right-auto lg:right-10 z-[1400] bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 px-4 py-3 flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-blue-600 border-2 border-white shadow"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300">Wisata</span>
                </div>
                <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-amber-500 border-2 border-white shadow"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300">UMKM</span>
                </div>
            </div>

            <!-- Lokasi Saya (kanan bawah) -->
            <button 
                @click="locateUser"
                :disabled="isLocating"
                class="absolute bottom-10 right-10 z-[1500] w-14 h-14 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl flex items-center justify-center transition-all hover:scale-110 border border-slate-100 dark:border-slate-800 disabled:opacity-60 disabled:cursor-wait"
                :class="isLocating ? 'text-primary' : 'text-slate-700 dark:text-white hover:text-primary'"
                title="Lokasi Saya"
            >
                <LocateFixed class="w-6 h-6" :class="isLocating ? 'animate-pulse' : ''" />
            </button>

            <!-- Toast Notif -->
            <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="locationToast" class="absolute bottom-28 right-10 z-[2000] px-4 py-3 rounded-2xl shadow-xl text-xs font-black uppercase tracking-wider text-white" :class="locationToast.type === 'error' ? 'bg-red-500' : 'bg-emerald-500'">
                    {{ locationToast.message }}
                </div>
            </transition>

            <!-- Routing Overlay (if active) -->
            <transition 
                enter-active-class="transition duration-500 ease-out"
                enter-from-class="translate-x-full opacity-0"
                enter-to-class="translate-x-0 opacity-100"
                leave-active-class="transition duration-300 ease-in"
                leave-from-class="translate-x-0 opacity-100"
                leave-to-class="translate-x-full opacity-0"
            >
                <div v-if="isRouting" class="absolute top-10 right-10 z-[2500] w-80 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/20 p-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                             <Route class="w-4 h-4 text-primary" />
                             <h4 class="text-[10px] font-black uppercase tracking-widest text-primary">Petunjuk Arah</h4>
                        </div>
                        <button @click="stopRouting" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-slate-400 transition-colors">
                            <X class="w-5 h-5" />
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="relative pl-6 border-l-2 border-dashed border-slate-200 dark:border-slate-700 space-y-6">
                            <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-slate-400"></div>
                            <div class="absolute -left-[5px] bottom-0 w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                            
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">Titik Awal</p>
                                <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ userLocationName }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-primary uppercase tracking-tighter mb-1">Tujuan</p>
                                <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ selectedObjek?.nama_objek }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div id="routing-details" class="max-h-40 overflow-y-auto no-scrollbar text-[10px] font-bold text-slate-500 space-y-2">
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </PublicLayout>
</template>

<script setup>
import { onMounted, ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet-routing-machine';
import 'leaflet-routing-machine/dist/leaflet-routing-machine.css';
import { 
    Search, 
    Layers, 
    Navigation2, 
    Navigation, 
    LocateFixed, 
    X, 
    Menu, 
    ChevronLeft, 
    ChevronRight,
    Map as MapIcon,
    Tent,
    Waves,
    Mountain,
    Camera,
    Info,
    Share2,
    Route
} from 'lucide-vue-next';

const props = defineProps({
    objekWisatas: Array,
    umkms: Array,
});

const searchQuery = ref('');
const activeCategory = ref('All');
const activeTab = ref('wisata'); // 'wisata' | 'umkm'
const sidebarOpen = ref(true);
const activeLayer = ref('street');
const map = ref(null);
const markers = ref([]);
const umkmMarkers = ref([]);
const clusterGroup = ref(null);
const umkmClusterGroup = ref(null);
const routingControl = ref(null);
const isRouting = ref(false);
const selectedObjek = ref(null);
const userLocation = ref(null);
const userLocationName = ref('Lokasi Saya');
const showLayerSwitcher = ref(false);
const isLocating = ref(false);
const locationToast = ref(null);

const showToast = (message, type = 'success') => {
    locationToast.value = { message, type };
    setTimeout(() => { locationToast.value = null; }, 3000);
};

const locateUser = () => {
    if (!navigator.geolocation) {
        showToast('Browser tidak mendukung geolokasi', 'error');
        return;
    }

    isLocating.value = true;

    navigator.geolocation.getCurrentPosition(
        (position) => {
            isLocating.value = false;
            const latlng = L.latLng(position.coords.latitude, position.coords.longitude);
            userLocation.value = latlng;

            // Hapus marker lama
            const ref = window.__mapRef__;
            if (ref) {
                const old = ref.getMarker();
                if (old) ref.map.removeLayer(old);

                const pulsingIcon = L.divIcon({
                    className: '',
                    html: `
                        <div style="position:relative;width:40px;height:40px;">
                            <div style="position:absolute;inset:0;border-radius:50%;background:rgba(37,99,235,0.2);animation:locatePulse 1.8s ease-out infinite;"></div>
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:18px;height:18px;border-radius:50%;background:#2563EB;border:3px solid #fff;box-shadow:0 2px 14px rgba(37,99,235,0.7);"></div>
                        </div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                });

                const newMarker = L.marker(latlng, { icon: pulsingIcon, zIndexOffset: 1000 })
                    .addTo(ref.map)
                    .bindPopup('<div style="font-weight:900;font-size:12px;color:#2563EB;padding:4px 0">📍 Lokasi Saya</div>', { className: 'custom-popup-box' });

                ref.map.flyTo(latlng, 16, { duration: 1.2, easeLinearity: 0.25 });
                setTimeout(() => newMarker.openPopup(), 1300);
                ref.setMarker(newMarker);
            }

            showToast('Lokasi ditemukan!', 'success');
        },
        (err) => {
            isLocating.value = false;
            const msgs = {
                1: 'Akses lokasi ditolak. Izinkan lokasi di pengaturan browser.',
                2: 'Lokasi tidak tersedia saat ini.',
                3: 'Permintaan lokasi timeout.',
            };
            showToast(msgs[err.code] || 'Gagal mendapatkan lokasi', 'error');
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
};

const categories = [
    { name: 'All', icon: MapIcon },
    { name: 'Pantai', icon: Waves },
    { name: 'Air Terjun', icon: Waves },
    { name: 'Pegunungan', icon: Mountain },
    { name: 'Budaya', icon: Camera },
    { name: 'Camping', icon: Tent },
];

const layers = {
    street: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO'
    }),
    satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{x}/{y}', {
        attribution: 'Tiles &copy; Esri'
    }),
    dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO'
    })
};

const filteredObjekWisatas = computed(() => {
    let filtered = props.objekWisatas;
    
    if (searchQuery.value) {
        filtered = filtered.filter(objek => 
            objek.nama_objek?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            objek.kecamatan?.nama_kecamatan?.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    if (activeCategory.value !== 'All') {
        // Mock category filtering based on keywords in name for demonstration
        const keywords = {
            'Pantai': ['pantai', 'beach', 'pulau', 'island', 'pink'],
            'Air Terjun': ['terjun', 'waterfall', 'curug'],
            'Pegunungan': ['gunung', 'bukit', 'hill', 'puncak'],
            'Budaya': ['kampung', 'desa', 'adat', 'budaya'],
            'Camping': ['camp', 'camping', 'tenda']
        };
        
        filtered = filtered.filter(objek => {
            const targetWords = keywords[activeCategory.value] || [];
            return targetWords.some(word => 
                objek.nama_objek.toLowerCase().includes(word) || 
                objek.keterangan?.toLowerCase().includes(word)
            );
        });
    }
    
    return filtered;
});

const filteredUmkms = computed(() => {
    if (!props.umkms) return [];
    if (!searchQuery.value) return props.umkms;
    const q = searchQuery.value.toLowerCase();
    return props.umkms.filter(u =>
        u.nama_umkm?.toLowerCase().includes(q) ||
        u.kategori?.toLowerCase().includes(q) ||
        u.alamat?.toLowerCase().includes(q)
    );
});

// Watch for layer changes
watch(activeLayer, (newLayer) => {
    Object.values(layers).forEach(layer => map.value.removeLayer(layer));
    layers[newLayer].addTo(map.value);
});

// Calculate distance between two points in km
const calculateDistance = (lat1, lon1, lat2, lon2) => {
    const R = 6371; // Rayon of the earth in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return (R * c).toFixed(1);
};

onMounted(() => {
    // Initial map focus on Manggarai Barat / Labuan Bajo
    map.value = L.map('main-map', {
        zoomControl: false,
    }).setView([-8.4877, 119.8883], 11);

    layers[activeLayer.value].addTo(map.value);

    L.control.zoom({ position: 'bottomright' }).addTo(map.value);

    // Initialize Cluster Group
    clusterGroup.value = L.markerClusterGroup({
        showCoverageOnHover: false,
        spiderfyOnMaxZoom: true,
        zoomToBoundsOnClick: true,
        maxClusterRadius: 50, // Improved radius for density
        polygonOptions: {
            fillColor: '#2563EB',
            color: '#2563EB',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.1
        }
    });

    // ── Objek Wisata markers (blue) ──────────────────────────────
    if (props.objekWisatas) {
        props.objekWisatas.forEach(objek => {
            const icon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div style="position:relative;width:52px;height:66px;cursor:pointer;">
                        <!-- Pin circle -->
                        <div style="
                            position:absolute;top:0;left:0;
                            width:52px;height:52px;
                            border-radius:50%;
                            background:#2563EB;
                            box-shadow:0 6px 20px rgba(37,99,235,0.55);
                            display:flex;align-items:center;justify-content:center;
                            border:3px solid #fff;
                        ">
                            <!-- Photo clipped in circle -->
                            <div style="width:44px;height:44px;border-radius:50%;overflow:hidden;flex-shrink:0;">
                                <img src="${objek.fotos.length > 0 ? '/storage/' + objek.fotos[0].path : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1'}" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </div>
                        <!-- Tail -->
                        <div style="
                            position:absolute;bottom:0;left:50%;transform:translateX(-50%);
                            width:0;height:0;
                            border-left:8px solid transparent;
                            border-right:8px solid transparent;
                            border-top:16px solid #fff;
                        "></div>
                    </div>
                `,
                iconSize: [52, 66],
                iconAnchor: [26, 66],
                popupAnchor: [0, -68]
            });

            const marker = L.marker([objek.latitude, objek.longitude], { icon });

            const getPopupContent = () => {
                const distanceStr = userLocation.value
                    ? `<div class="flex items-center gap-1 text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-width="3"/></svg>
                        ${calculateDistance(userLocation.value.lat, userLocation.value.lng, objek.latitude, objek.longitude)} KM
                       </div>`
                    : '';

                let aksesTransportasiHtml = '';
                if (objek.akses_transportasi && objek.akses_transportasi.length > 0) {
                    aksesTransportasiHtml = `
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <div class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Akses Transportasi</div>
                            <ul class="text-[10px] text-slate-600 font-bold space-y-1">
                                ${objek.akses_transportasi.map(akses => `
                                    <li class="flex items-start gap-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 mt-[-1px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        <span>${akses}</span>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    `;
                }

                return `
                    <div class="w-72 font-sans p-0 overflow-hidden bg-white flex flex-col">
                        <div class="relative h-40">
                            <img src="${objek.fotos.length > 0 ? '/storage/' + objek.fotos[0].path : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1'}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between">
                                <div>
                                    <div class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">${objek.kecamatan.nama_kecamatan}</div>
                                    <h3 class="font-black text-white text-lg leading-tight">${objek.nama_objek}</h3>
                                </div>
                                ${distanceStr}
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            <p class="text-xs text-slate-500 line-clamp-2 font-medium leading-relaxed">${objek.keterangan || 'Jelajahi keindahan alam yang memukau di destinasi ini.'}</p>
                            ${aksesTransportasiHtml}
                            <div class="grid grid-cols-2 gap-3">
                                <a href="/destinasi/${objek.slug}" class="flex items-center justify-center gap-2 bg-slate-900 text-white py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">
                                    <span>Detail</span>
                                </a>
                                <button onclick="window.startRouting(${objek.id})" class="flex items-center justify-center gap-2 bg-blue-600 text-white py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">
                                    <span>Rute</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            };

            marker.bindPopup(getPopupContent, { maxWidth: 320, className: 'custom-popup-box' });
            marker.on('popupopen', () => marker.setPopupContent(getPopupContent()));
            clusterGroup.value.addLayer(marker);
            markers.value.push({ id: objek.id, data: objek, marker });
        });
    }

    map.value.addLayer(clusterGroup.value);

    // ── UMKM markers (orange / amber) ────────────────────────────
    umkmClusterGroup.value = L.markerClusterGroup({
        showCoverageOnHover: false,
        spiderfyOnMaxZoom: true,
        zoomToBoundsOnClick: true,
        maxClusterRadius: 50,
        polygonOptions: { fillColor: '#f59e0b', color: '#f59e0b', weight: 2, opacity: 1, fillOpacity: 0.1 },
        iconCreateFunction: (cluster) => {
            return L.divIcon({
                html: `<div style="background:#f59e0b;color:#fff;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:13px;border:3px solid #fff;box-shadow:0 4px 14px rgba(245,158,11,0.5)">${cluster.getChildCount()}</div>`,
                className: '',
                iconSize: [40, 40]
            });
        }
    });

    if (props.umkms) {
        const umkmIconMap = {
            'Warung makan': '🍽️',
            'Kios': '🛒',
            'Villa': '🏡',
            'Hotel': '🏨',
            'Penginapan': '🛏️',
        };

        props.umkms.forEach(umkm => {
            const imgSrc = (umkm.fotos && umkm.fotos.length > 0)
                ? '/storage/' + umkm.fotos[0].path
                : null;
            const categoryEmoji = umkmIconMap[umkm.kategori] || '🏪';

            const icon = L.divIcon({
                className: 'custom-marker-umkm',
                html: `
                    <div style="position:relative;width:52px;height:66px;cursor:pointer;">
                        <!-- Pin circle -->
                        <div style="
                            position:absolute;top:0;left:0;
                            width:52px;height:52px;
                            border-radius:50%;
                            background:#f59e0b;
                            box-shadow:0 6px 20px rgba(245,158,11,0.55);
                            display:flex;align-items:center;justify-content:center;
                            border:3px solid #fff;
                        ">
                            ${imgSrc
                                ? `<div style="width:44px;height:44px;border-radius:50%;overflow:hidden;"><img src="${imgSrc}" style="width:100%;height:100%;object-fit:cover;"></div>`
                                : `<span style="font-size:22px;line-height:1;">${categoryEmoji}</span>`
                            }
                        </div>
                        <!-- Tail -->
                        <div style="
                            position:absolute;bottom:0;left:50%;transform:translateX(-50%);
                            width:0;height:0;
                            border-left:8px solid transparent;
                            border-right:8px solid transparent;
                            border-top:16px solid #fff;
                        "></div>
                    </div>
                `,
                iconSize: [52, 66],
                iconAnchor: [26, 66],
                popupAnchor: [0, -68]
            });

            const marker = L.marker([umkm.latitude, umkm.longitude], { icon });

            const popupContent = `
                <div class="w-72 font-sans p-0 overflow-hidden bg-white flex flex-col">
                    ${imgSrc ? `<div class="relative h-32"><img src="${imgSrc}" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div><div class="absolute bottom-3 left-4"><div class="text-[9px] font-black text-amber-400 uppercase tracking-widest mb-0.5">UMKM</div><h3 class="font-black text-white text-base leading-tight">${umkm.nama_umkm}</h3></div></div>` : ''}
                    <div class="p-5 space-y-3">
                        ${!imgSrc ? `<h3 class="font-black text-slate-900 text-base">${umkm.nama_umkm}</h3>` : ''}
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-wider">${umkm.kategori}</div>
                        <p class="text-xs text-slate-500 line-clamp-2 font-medium leading-relaxed">${umkm.alamat || ''}</p>
                        ${umkm.keterangan ? `<p class="text-xs text-slate-400 line-clamp-2">${umkm.keterangan}</p>` : ''}
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent, { maxWidth: 320, className: 'custom-popup-box' });
            umkmClusterGroup.value.addLayer(marker);
            umkmMarkers.value.push({ id: umkm.id, data: umkm, marker });
        });
    }

    map.value.addLayer(umkmClusterGroup.value);

    // ── Handler lokasi user ──────────────────────────────────────
    let userLocationMarker = null;

    // Get initial user location silently for routing distance calc
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            userLocation.value = L.latLng(position.coords.latitude, position.coords.longitude);
        }, () => {
            userLocation.value = L.latLng(-8.4905, 119.8783);
            userLocationName.value = "Labuan Bajo (Default)";
        });
    }

    // Expose userLocationMarker for locateUser()
    window.__mapRef__ = { map: map.value, getMarker: () => userLocationMarker, setMarker: (m) => { userLocationMarker = m; } };
});

const startRouting = (id) => {
    const item = markers.value.find(m => m.id === id);
    if (!item || !userLocation.value) return;

    selectedObjek.value = item.data;
    isRouting.value = true;

    if (routingControl.value) {
        map.value.removeControl(routingControl.value);
    }

    routingControl.value = L.Routing.control({
        waypoints: [
            userLocation.value,
            L.latLng(item.data.latitude, item.data.longitude)
        ],
        routeWhileDragging: false,
        lineOptions: {
            styles: [{ color: '#2563EB', weight: 6, opacity: 0.8 }]
        },
        createMarker: function() { return null; }, // Don't show routing marks
        addWaypoints: false,
        draggableWaypoints: false,
        fitSelectedRoutes: true,
        show: false // Don't show the default instructions panel
    }).addTo(map.value);

    item.marker.closePopup();
};
window.startRouting = startRouting;

const stopRouting = () => {
    if (routingControl.value) {
        map.value.removeControl(routingControl.value);
        routingControl.value = null;
    }
    isRouting.value = false;
    selectedObjek.value = null;
};

const focusToMarker = (objek) => {
    const item = markers.value.find(m => m.id === objek.id);
    if (item) {
        map.value.flyTo([objek.latitude, objek.longitude], 15, {
            duration: 1.5,
            easeLinearity: 0.25
        });
        setTimeout(() => { item.marker.openPopup(); }, 1000);
    }
};

const focusToUmkmMarker = (umkm) => {
    const item = umkmMarkers.value.find(m => m.id === umkm.id);
    if (item) {
        map.value.flyTo([umkm.latitude, umkm.longitude], 16, {
            duration: 1.5,
            easeLinearity: 0.25
        });
        setTimeout(() => { item.marker.openPopup(); }, 1000);
    }
};
</script>

<style>
@reference "../../../css/app.css";

.custom-popup-box .leaflet-popup-content-wrapper {
    @apply rounded-[2rem] shadow-2xl border-none p-0 overflow-hidden bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl;
}
.custom-popup-box .leaflet-popup-content {
    margin: 0 !important;
    width: auto !important;
}
.custom-popup-box .leaflet-popup-tip-container {
    @apply hidden;
}
.leaflet-container {
    @apply font-sans;
}
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Marker Animations */
@keyframes marker-bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

/* User Location Pulse */
@keyframes locatePulse {
    0%   { transform: scale(1);   opacity: 0.8; }
    70%  { transform: scale(2.8); opacity: 0; }
    100% { transform: scale(2.8); opacity: 0; }
}
.custom-marker:hover .marker-pulse {
    animation: marker-bounce 1s infinite ease-in-out;
}

.custom-marker {
    @apply transition-transform duration-300;
}
.custom-marker:hover {
    @apply z-[1000];
}

/* Routing Machine Improvements */
.leaflet-routing-container {
    display: none !important; /* We use our own UI */
}
.leaflet-routing-alt {
    @apply hidden;
}
</style>
