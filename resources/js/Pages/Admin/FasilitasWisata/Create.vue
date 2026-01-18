<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Fasilitas</h2>
                    <p class="mt-1 text-sm text-gray-600">Buat sarana pendukung objek wisata baru</p>
                </div>
                <a :href="route('admin.fasilitas-wisata.index')" class="btn-secondary">
                    Kembali
                </a>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Form Side -->
                    <div class="space-y-6">
                        <div class="bg-white shadow rounded-xl p-6 border border-gray-100 space-y-6">
                            <div class="flex items-center gap-2 border-b border-gray-50 pb-4 mb-2">
                                <div class="w-2 h-6 bg-primary rounded-full"></div>
                                <h3 class="text-lg font-bold text-gray-900">Informasi Fasilitas</h3>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Objek Wisata <span class="text-red-500">*</span></label>
                                    <select
                                        v-model="form.id_objek"
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                        :class="{ 'border-red-500': form.errors.id_objek }"
                                    >
                                        <option :value="null">Pilih Lokasi Utama</option>
                                        <option v-for="objek in objekWisatas" :key="objek.id" :value="objek.id">
                                            {{ objek.nama_objek }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.id_objek" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.id_objek }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Fasilitas <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.nama_fasilitas"
                                        type="text"
                                        placeholder="Contoh: Toilet Umum, Musholla, ATM"
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                        :class="{ 'border-red-500': form.errors.nama_fasilitas }"
                                    />
                                    <p v-if="form.errors.nama_fasilitas" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.nama_fasilitas }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Ikon (Emoji/Text)</label>
                                    <input
                                        v-model="form.icon"
                                        type="text"
                                        placeholder="Contoh: 🚻, 🕌, 🏧"
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                    />
                                    <p class="mt-1 text-[10px] text-gray-400 font-medium uppercase tracking-wider italic">Gunakan emoji untuk ikon yang menarik</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                                    <textarea
                                        v-model="form.deskripsi"
                                        rows="3"
                                        placeholder="Jelaskan kondisi atau posisi fasilitas..."
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow rounded-xl p-6 border border-gray-100">
                             <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Latitude</label>
                                    <input
                                        v-model="form.latitude"
                                        type="text"
                                        readonly
                                        class="w-full rounded-xl border-gray-100 bg-gray-50 text-xs font-mono text-gray-500 cursor-not-allowed"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Longitude</label>
                                    <input
                                        v-model="form.longitude"
                                        type="text"
                                        readonly
                                        class="w-full rounded-xl border-gray-100 bg-gray-50 text-xs font-mono text-gray-500 cursor-not-allowed"
                                    />
                                </div>
                            </div>
                            <p class="mt-2 text-[10px] text-blue-500 font-bold uppercase tracking-wider">Geser marker pada peta untuk mengubah posisi</p>
                        </div>
                    </div>

                    <!-- Map Side -->
                    <div class="bg-white shadow rounded-xl border border-gray-100 overflow-hidden flex flex-col h-[500px] lg:h-auto">
                        <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Lokasi Fasilitas</h3>
                            <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase">Interactive Map</span>
                        </div>
                        <div id="map" class="flex-1 z-10"></div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a :href="route('admin.fasilitas-wisata.index')" class="btn-secondary">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="btn-primary px-8"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                        <span v-else>Simpan Fasilitas</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    objekWisatas: Array,
});

const form = useForm({
    id_objek: null,
    nama_fasilitas: '',
    deskripsi: '',
    latitude: -8.5, // Default Labuan Bajo coordinate
    longitude: 119.88,
    icon: '',
});

let map;
let marker;

onMounted(() => {
    // Initialize map
    map = L.map('map').setView([form.latitude, form.longitude], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Custom Icon for marker (standard Leaflet marker has issues with absolute paths in some build setups)
    const icon = L.icon({
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
    });

    // Initial marker
    marker = L.marker([form.latitude, form.longitude], { draggable: true, icon: icon }).addTo(map);

    // Update form when marker is dragged
    marker.on('dragend', function (event) {
        const position = marker.getLatLng();
        form.latitude = position.lat.toFixed(8);
        form.longitude = position.lng.toFixed(8);
    });

    // Update marker when map is clicked
    map.on('click', function (event) {
        const { lat, lng } = event.latlng;
        marker.setLatLng([lat, lng]);
        form.latitude = lat.toFixed(8);
        form.longitude = lng.toFixed(8);
    });
});

const submit = () => {
    form.post(route('admin.fasilitas-wisata.store'));
};
</script>

<style scoped>
#map {
    width: 100%;
    min-height: 400px;
}
</style>
