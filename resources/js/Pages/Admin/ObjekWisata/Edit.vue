<template>
    <AdminLayout>
        <div class="max-w-4xl">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Objek Wisata</h2>
                    <p class="mt-1 text-sm text-gray-600">Perbarui data {{ objekWisata.nama_objek }}</p>
                </div>
                <a :href="route('admin.objek-wisata.index')" class="text-sm font-medium text-primary hover:text-primary/80">
                    &larr; Kembali ke Daftar
                </a>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Basic Info -->
                <div class="bg-white shadow rounded-lg p-6 space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Objek Wisata <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.nama_objek"
                                type="text"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                :class="{ 'border-red-500': form.errors.nama_objek }"
                            />
                            <p v-if="form.errors.nama_objek" class="mt-1 text-sm text-red-600">{{ form.errors.nama_objek }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                            <select
                                v-model="form.id_kecamatan"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                :class="{ 'border-red-500': form.errors.id_kecamatan }"
                            >
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
                            />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan <span class="text-red-500">*</span></label>
                        <textarea
                            v-model="form.keterangan"
                            rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                            :class="{ 'border-red-500': form.errors.keterangan }"
                        ></textarea>
                        <p v-if="form.errors.keterangan" class="mt-1 text-sm text-red-600">{{ form.errors.keterangan }}</p>
                    </div>
                </div>

                <!-- Location -->
                <div class="bg-white shadow rounded-lg p-6 space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Lokasi Geografis</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wider">Latitude</label>
                            <input v-model="form.latitude" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary bg-gray-50" readonly />
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1 uppercase tracking-wider">Longitude</label>
                            <input v-model="form.longitude" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary bg-gray-50" readonly />
                        </div>
                    </div>
                    <div id="map" class="h-80 w-full rounded-lg border border-gray-300 shadow-inner"></div>
                </div>

                <!-- Photo Management -->
                <div class="bg-white shadow rounded-lg p-6 space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Manajemen Foto
                    </h3>
                    
                    <!-- Existing Photos -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="foto in objekWisata.fotos" :key="foto.id" class="relative group aspect-video rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all">
                            <img :src="'/storage/' + foto.path" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            <div v-if="foto.is_primary" class="absolute top-0 left-0 bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-br font-bold uppercase tracking-wider shadow z-10">Primary</div>
                            
                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 z-20">
                                <button 
                                    v-if="!foto.is_primary" 
                                    @click.prevent="setPrimary(foto.id)" 
                                    class="bg-white text-gray-900 p-2 rounded-full hover:bg-blue-50 hover:text-blue-600 transition-colors shadow-lg active:scale-90" 
                                    title="Set as Primary"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button 
                                    @click.prevent="deletePhoto(foto.id)" 
                                    class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors shadow-lg active:scale-90" 
                                    title="Hapus Photo"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- New Photo Upload -->
                    <div class="mt-8 border-t pt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Tambah Foto Baru</label>
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-primary transition-colors group">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary/80">
                                        <span>Upload foto extra</span>
                                        <input id="file-upload" name="file-upload" type="file" class="sr-only" @change="handleFileUpload" multiple accept="image/*" />
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                            </div>
                        </div>
                        <div v-if="previews.length" class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div v-for="(src, index) in previews" :key="index" class="relative group aspect-video rounded-lg overflow-hidden border border-gray-200 shadow-sm transition-all hover:shadow-md">
                                <img :src="src" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <button 
                                    @click.prevent="removePreview(index)" 
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:bg-red-700 active:scale-90"
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
                        class="btn-primary px-10 py-3"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span v-if="form.processing">Memperbarui...</span>
                        <span v-else>Update Objek Wisata</span>
                    </button>
                    <a :href="route('admin.objek-wisata.index')" class="btn-secondary px-10 py-3">
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
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import L from 'leaflet';

const props = defineProps({
    objekWisata: Object,
    kecamatans: Array,
});

const form = useForm({
    id_kecamatan: props.objekWisata.id_kecamatan,
    nama_objek: props.objekWisata.nama_objek,
    alamat: props.objekWisata.alamat,
    no_telepon: props.objekWisata.no_telepon,
    keterangan: props.objekWisata.keterangan,
    jam_operasional: props.objekWisata.jam_operasional,
    harga_tiket: props.objekWisata.harga_tiket,
    latitude: props.objekWisata.latitude,
    longitude: props.objekWisata.longitude,
    new_fotos: [],
});

const previews = ref([]);
let map = null;
let marker = null;

onMounted(() => {
    map = L.map('map').setView([form.latitude, form.longitude], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    marker = L.marker([form.latitude, form.longitude], { draggable: true }).addTo(map);

    marker.on('dragend', (e) => {
        const { lat, lng } = e.target.getLatLng();
        form.latitude = lat.toFixed(6);
        form.longitude = lng.toFixed(6);
    });

    map.on('click', (e) => {
        const { lat, lng } = e.latlng;
        marker.setLatLng([lat, lng]);
        form.latitude = lat.toFixed(6);
        form.longitude = lng.toFixed(6);
    });
});

const handleFileUpload = (e) => {
    const files = Array.from(e.target.files);
    form.new_fotos = [...form.new_fotos, ...files];
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => previews.value.push(e.target.result);
        reader.readAsDataURL(file);
    });
};

const removePreview = (index) => {
    form.new_fotos.splice(index, 1);
    previews.value.splice(index, 1);
};

const setPrimary = (fotoId) => {
    router.post(route('admin.objek-wisata.set-primary-photo', { objekId: props.objekWisata.id, fotoId }));
};

const deletePhoto = (fotoId) => {
    if (confirm('Hapus foto ini permanently?')) {
        router.delete(route('admin.objek-wisata.delete-photo', fotoId));
    }
};

const submit = () => {
    // Note: Since we are uploading files, we use POST with _method PUT emulation if needed,
    // but Inertia's PUT might handle it. However, traditionally multipart/form-data doesn't work well with PUT.
    // Inertia handles this by changing it to POST and adding _method=PUT.
    form.post(route('admin.objek-wisata.update', props.objekWisata.id), {
        onBefore: () => { form._method = 'PUT'; } // Manually add method for file upload compatibility
    });
};
</script>
