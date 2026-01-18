<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Fasilitas</h2>
                    <p class="mt-1 text-sm text-gray-600">Perbarui data sarana pariwisata</p>
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
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                    />
                                    <p class="mt-1 text-[10px] text-gray-400 font-medium uppercase tracking-wider italic">Gunakan emoji untuk ikon yang menarik</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Foto Fasilitas</label>
                                    
                                    <!-- Existing Photos -->
                                    <div v-if="fasilitas.fotos && fasilitas.fotos.length > 0" class="flex flex-wrap gap-4 mb-4">
                                        <div v-for="foto in fasilitas.fotos" :key="foto.id" class="relative group w-24 h-24">
                                            <img :src="'/storage/' + foto.path" class="w-full h-full object-cover rounded-lg border border-gray-200">
                                            <button 
                                                @click.prevent="deleteExistingPhoto(foto.id)" 
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md opacity-0 group-hover:opacity-100 transition-opacity transform hover:scale-110"
                                                title="Hapus foto"
                                            >
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Upload New -->
                                    <input
                                        type="file"
                                        multiple
                                        @change="handleFileUpload"
                                        class="w-full rounded-xl border border-gray-200 shadow-sm text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                        accept="image/*"
                                    />
                                    
                                    <!-- Preview New -->
                                    <div v-if="previewUrls.length" class="grid grid-cols-4 gap-2 mt-4">
                                        <div v-for="(url, index) in previewUrls" :key="index" class="relative group">
                                            <img :src="url" class="w-full h-20 object-cover rounded-lg border border-gray-200">
                                            <button @click.prevent="removePhoto(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                                    <textarea
                                        v-model="form.deskripsi"
                                        rows="3"
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
                        </div>
                    </div>

                    <!-- Map Side -->
                    <div class="bg-white shadow rounded-xl border border-gray-100 overflow-hidden flex flex-col h-[500px] lg:h-auto">
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
                        <span v-else>Update Fasilitas</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    fasilitas: Object,
    objekWisatas: Array,
});

const form = useForm({
    id_objek: props.fasilitas.id_objek,
    nama_fasilitas: props.fasilitas.nama_fasilitas,
    deskripsi: props.fasilitas.deskripsi,
    latitude: props.fasilitas.latitude,
    longitude: props.fasilitas.longitude,
    icon: props.fasilitas.icon,
    new_fotos: [],
    _method: 'PUT',
});

const previewUrls = ref([]);

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    form.new_fotos = [...form.new_fotos, ...files];

    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrls.value.push(e.target.result);
        };
        reader.readAsDataURL(file);
    });
};

const removePhoto = (index) => {
    form.new_fotos.splice(index, 1);
    previewUrls.value.splice(index, 1);
};

const deleteExistingPhoto = (id) => {
    if (confirm('Yakin ingin menghapus foto ini?')) {
        router.delete(route('admin.fasilitas-wisata.delete-photo', id), {
            preserveScroll: true,
        });
    }
};

let map;
let marker;

onMounted(() => {
    map = L.map('map').setView([form.latitude, form.longitude], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const icon = L.icon({
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
    });

    marker = L.marker([form.latitude, form.longitude], { draggable: true, icon: icon }).addTo(map);

    marker.on('dragend', function (event) {
        const position = marker.getLatLng();
        form.latitude = position.lat.toFixed(8);
        form.longitude = position.lng.toFixed(8);
    });

    map.on('click', function (event) {
        const { lat, lng } = event.latlng;
        marker.setLatLng([lat, lng]);
        form.latitude = lat.toFixed(8);
        form.longitude = lng.toFixed(8);
    });
});

const submit = () => {
    form.post(route('admin.fasilitas-wisata.update', props.fasilitas.id));
};
</script>

<style scoped>
#map {
    width: 100%;
    min-height: 400px;
}
</style>
