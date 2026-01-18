<template>
    <PublicLayout :transparent="false">
        <div class="h-20 bg-gray-900"></div>
        <div class="relative h-[calc(100vh-5rem)] w-full overflow-hidden">
            <!-- Sidebar for Search/Filtering -->
            <div class="absolute top-8 left-8 z-[1000] w-96 hidden lg:block">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/20 p-6 space-y-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-1 bg-primary rounded-full"></div>
                        <span class="text-[10px] font-black text-gray-900 uppercase tracking-[0.2em]">Explore Map</span>
                    </div>
                    <h1 class="text-2xl font-black text-gray-900 leading-tight">Temukan Destinasi <span class="text-primary italic">Impian</span></h1>
                    
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari destinasi..."
                            class="block w-full pl-10 rounded-2xl border-gray-100 bg-gray-50/50 py-3 focus:ring-primary focus:border-primary font-bold text-sm text-gray-900 placeholder-gray-400"
                        />
                    </div>

                    <div class="max-h-[40vh] overflow-y-auto pr-2 no-scrollbar space-y-3">
                        <div 
                            v-for="objek in filteredObjekWisatas" 
                            :key="objek.id"
                            @click="focusToMarker(objek)"
                            class="p-4 rounded-2xl border border-gray-100 bg-white hover:border-primary/30 hover:shadow-lg transition-all cursor-pointer group"
                        >
                            <div class="flex gap-4">
                                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0">
                                    <img :src="objek.fotos.length > 0 ? '/storage/' + objek.fotos[0].path : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1'" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[9px] font-black text-primary uppercase tracking-tighter mb-1">{{ objek.kecamatan.nama_kecamatan }}</div>
                                    <h3 class="font-black text-gray-900 text-sm truncate">{{ objek.nama_objek }}</h3>
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-yellow-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        <span class="text-[10px] font-bold text-gray-600">{{ Number(objek.rating_avg || 0).toFixed(1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <button 
                                        @click.stop="startRouting(objek.id)"
                                        class="p-2 rounded-xl bg-gray-50 text-primary hover:bg-primary hover:text-white transition-all shadow-sm group-hover:shadow-md"
                                        title="Dapatkan Rute"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-if="filteredObjekWisatas.length === 0" class="py-12 text-center text-gray-400 font-bold text-xs uppercase tracking-widest">
                            Tidak ditemukan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div id="main-map" class="w-full h-full z-10"></div>

            <!-- Routing Overlay (if active) -->
            <div v-if="isRouting" class="absolute top-8 right-8 z-[1000] w-80 bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/20 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-black uppercase tracking-widest text-primary">Petunjuk Arah</h4>
                    <button @click="stopRouting" class="p-2 hover:bg-gray-100 rounded-lg text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" /></svg>
                    </button>
                </div>
                <div class="space-y-3">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1">Dari</div>
                        <div class="text-xs font-bold text-gray-900 truncate">{{ userLocationName }}</div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <div class="text-[9px] font-black text-primary uppercase tracking-tighter mb-1">Ke</div>
                        <div class="text-xs font-bold text-gray-900 truncate">{{ selectedObjek?.nama_objek }}</div>
                    </div>
                </div>
                <div id="routing-details" class="max-h-40 overflow-y-auto no-scrollbar text-[10px] font-bold text-gray-500 space-y-2">
                    <!-- Leaflet Routing Machine will populate this via its container but we can also hide it and just show summary -->
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet-routing-machine';
import 'leaflet-routing-machine/dist/leaflet-routing-machine.css';

const props = defineProps({
    objekWisatas: Array,
});

const searchQuery = ref('');
const map = ref(null);
const markers = ref([]);
const clusterGroup = ref(null);
const routingControl = ref(null);
const isRouting = ref(false);
const selectedObjek = ref(null);
const userLocation = ref(null);
const userLocationName = ref('Lokasi Saya');

const filteredObjekWisatas = computed(() => {
    if (!searchQuery.value) return props.objekWisatas;
    return props.objekWisatas.filter(objek => 
        objek.nama_objek.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        objek.kecamatan.nama_kecamatan.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

onMounted(() => {
    // Initial map focus on Manggarai Barat / Labuan Bajo
    map.value = L.map('main-map', {
        zoomControl: false,
    }).setView([-8.4877, 119.8883], 11);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO'
    }).addTo(map.value);

    L.control.zoom({ position: 'bottomright' }).addTo(map.value);

    // Initialize Cluster Group
    clusterGroup.value = L.markerClusterGroup({
        showCoverageOnHover: false,
        spiderfyOnMaxZoom: true,
        zoomToBoundsOnClick: true
    });

    // Create markers
    props.objekWisatas.forEach(objek => {
        const icon = L.divIcon({
            className: 'custom-marker',
            html: `
                <div class="relative group cursor-pointer">
                    <div class="w-10 h-10 rounded-2xl bg-white border-4 border-primary shadow-2xl flex items-center justify-center overflow-hidden transition-all group-hover:scale-110">
                        <img src="${objek.fotos.length > 0 ? '/storage/' + objek.fotos[0].path : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1'}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-3 h-3 bg-primary rotate-45 transform"></div>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });

        const marker = L.marker([objek.latitude, objek.longitude], { icon });
        
        const popupContent = `
            <div class="w-64 font-sans p-2">
                <div class="aspect-video rounded-xl overflow-hidden mb-3">
                    <img src="${objek.fotos.length > 0 ? '/storage/' + objek.fotos[0].path : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1'}" class="w-full h-full object-cover">
                </div>
                <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">${objek.kecamatan.nama_kecamatan}</div>
                <h3 class="font-black text-gray-900 text-lg leading-tight mb-2">${objek.nama_objek}</h3>
                <p class="text-xs text-gray-500 line-clamp-2 mb-4 font-medium">${objek.keterangan}</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="/destinasi/${objek.slug}" class="block text-center bg-gray-900 text-white py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:shadow-lg transition-all">Detail</a>
                    <button onclick="window.startRouting(${objek.id})" class="block text-center bg-primary text-white py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:shadow-lg transition-all">Rute</button>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent, {
            maxWidth: 300,
            className: 'custom-popup-box'
        });

        clusterGroup.value.addLayer(marker);

        markers.value.push({
            id: objek.id,
            data: objek,
            marker: marker
        });
    });

    map.value.addLayer(clusterGroup.value);

    // Get User Location for Routing
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            userLocation.value = L.latLng(position.coords.latitude, position.coords.longitude);
        }, (err) => {
            console.warn("Geolocation disabled or failed:", err);
            // Default to Labuan Bajo Port area as start if failed
            userLocation.value = L.latLng(-8.4905, 119.8783);
            userLocationName.value = "Labuan Bajo (Default)";
        });
    }
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
        map.value.flyTo([objek.latitude, objek.longitude], 15);
        item.marker.openPopup();
    }
};
</script>

<style>
@reference "../../../css/app.css";

.custom-popup-box .leaflet-popup-content-wrapper {
    @apply rounded-[1.5rem] shadow-2xl border-none p-0 overflow-hidden;
}
.custom-popup-box .leaflet-popup-content {
    margin: 0 !important;
    width: auto !important;
}
.custom-popup-box .leaflet-popup-tip-container {
    @apply hidden;
}
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
