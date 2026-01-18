<template>
    <AdminLayout>
        <div class="max-w-4xl">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Tambah Objek Wisata</h2>
                <p class="mt-1 text-sm text-gray-600">Lengkapi data destinasi wisata baru</p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white shadow rounded-lg p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Objek -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Objek Wisata <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nama_objek"
                                type="text"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                :class="{ 'border-red-500': form.errors.nama_objek }"
                                placeholder="Contoh: Pulau Komodo"
                            />
                            <p v-if="form.errors.nama_objek" class="mt-1 text-sm text-red-600">{{ form.errors.nama_objek }}</p>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.id_kecamatan"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                :class="{ 'border-red-500': form.errors.id_kecamatan }"
                            >
                                <option value="">Pilih Kecamatan</option>
                                <option v-for="kec in kecamatans" :key="kec.id" :value="kec.id">
                                    {{ kec.nama_kecamatan }}
                                </option>
                            </select>
                            <p v-if="form.errors.id_kecamatan" class="mt-1 text-sm text-red-600">{{ form.errors.id_kecamatan }}</p>
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.alamat"
                                type="text"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                :class="{ 'border-red-500': form.errors.alamat }"
                                placeholder="Contoh: Jl. Pantai Pede, Labuan Bajo"
                            />
                            <p v-if="form.errors.alamat" class="mt-1 text-sm text-red-600">{{ form.errors.alamat }}</p>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. Telepon
                            </label>
                            <input
                                v-model="form.no_telepon"
                                type="text"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                placeholder="Contoh: 08123456789"
                            />
                        </div>

                        <!-- Jam Operasional -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jam Operasional
                            </label>
                            <input
                                v-model="form.jam_operasional"
                                type="text"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                placeholder="Contoh: 08:00 - 17:00"
                            />
                        </div>

                        <!-- Harga Tiket -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Harga Tiket
                            </label>
                            <input
                                v-model="form.harga_tiket"
                                type="text"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                placeholder="Contoh: Rp 50.000 / orang"
                            />
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                            :class="{ 'border-red-500': form.errors.keterangan }"
                            placeholder="Tuliskan keterangan lengkap objek wisata..."
                        ></textarea>
                        <p v-if="form.errors.keterangan" class="mt-1 text-sm text-red-600">{{ form.errors.keterangan }}</p>
                    </div>

                    <!-- Coordinates & Map -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Lokasi Geografis (Klik pada peta untuk menentukan lokasi) <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wider">Latitude</label>
                                <input
                                    v-model="form.latitude"
                                    type="text"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    readonly
                                />
                                <p v-if="form.errors.latitude" class="mt-1 text-sm text-red-600">{{ form.errors.latitude }}</p>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wider">Longitude</label>
                                <input
                                    v-model="form.longitude"
                                    type="text"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    readonly
                                />
                                <p v-if="form.errors.longitude" class="mt-1 text-sm text-red-600">{{ form.errors.longitude }}</p>
                            </div>
                        </div>
                        <div id="map" class="h-80 w-full rounded-lg border border-gray-300 shadow-inner z-0"></div>
                    </div>

                    <!-- Foto Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Upload Foto (Minimal 1) <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-primary transition-colors group">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary/80 focus-within:outline-none">
                                        <span>Upload foto</span>
                                        <input id="file-upload" name="file-upload" type="file" class="sr-only" @change="handleFileUpload" multiple accept="image/*" />
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF sampai 2MB</p>
                            </div>
                        </div>
                        <p v-if="form.errors.fotos" class="mt-1 text-sm text-red-600">{{ form.errors.fotos }}</p>
                        
                        <!-- Preview -->
                        <div v-if="previews.length" class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div v-for="(src, index) in previews" :key="index" class="relative group aspect-video rounded-lg overflow-hidden border border-gray-200 shadow-sm transition-all hover:shadow-md">
                                <img :src="src" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <div v-if="index === 0" class="absolute top-0 left-0 bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-br font-bold uppercase tracking-wider shadow">Primary</div>
                                <button 
                                    @click.prevent="removePhoto(index)" 
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:bg-red-700 active:scale-90"
                                    title="Hapus Foto"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="btn-primary px-8 py-3"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Objek Wisata</span>
                    </button>
                    <a :href="route('admin.objek-wisata.index')" class="btn-secondary px-8 py-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batal
                    </a>
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

const props = defineProps({
    kecamatans: Array,
});

const form = useForm({
    id_kecamatan: '',
    nama_objek: '',
    alamat: '',
    no_telepon: '',
    keterangan: '',
    jam_operasional: '',
    harga_tiket: '',
    latitude: -8.5, // Default Labuan Bajo coordinate
    longitude: 119.88,
    fotos: [],
});

const previews = ref([]);
let map = null;
let marker = null;

onMounted(() => {
    // Initialize Map
    map = L.map('map').setView([form.latitude, form.longitude], 12);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
    }).addTo(map);

    marker = L.marker([form.latitude, form.longitude], { draggable: true }).addTo(map);

    // Update form when marker is moved
    marker.on('dragend', (e) => {
        const { lat, lng } = e.target.getLatLng();
        form.latitude = lat.toFixed(6);
        form.longitude = lng.toFixed(6);
    });

    // Update and move marker on click
    map.on('click', (e) => {
        const { lat, lng } = e.latlng;
        marker.setLatLng([lat, lng]);
        form.latitude = lat.toFixed(6);
        form.longitude = lng.toFixed(6);
    });
});

const handleFileUpload = (e) => {
    const files = Array.from(e.target.files);
    form.fotos = [...form.fotos, ...files];
    
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => previews.value.push(e.target.result);
        reader.readAsDataURL(file);
    });
};

const removePhoto = (index) => {
    form.fotos.splice(index, 1);
    previews.value.splice(index, 1);
};

const submit = () => {
    form.post(route('admin.objek-wisata.store'));
};
</script>

<style>
/* Ensure leaflet is above other UI but below navbar if active */
.leaflet-container {
    mask-image: none !important;
}
</style>
