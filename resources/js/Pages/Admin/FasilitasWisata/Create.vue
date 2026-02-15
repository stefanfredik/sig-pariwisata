<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Tambah Fasilitas Wisata</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Tambahkan fasilitas baru ke sistem</p>
                </div>
                <Button variant="outline" as-child>
                    <Link :href="route('admin.fasilitas-wisata.index')">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Kembali
                    </Link>
                </Button>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-xl p-6 border border-slate-200 dark:border-slate-700 space-y-6">
                    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-4 mb-2">
                        <div class="w-2 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Informasi Fasilitas</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Fasilitas <span class="text-red-500">*</span></label>
                            <Input
                                v-model="form.nama_fasilitas"
                                type="text"
                                placeholder="Contoh: Toilet Umum, Parkir Luas"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.nama_fasilitas }"
                            />
                            <p v-if="form.errors.nama_fasilitas" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.nama_fasilitas }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kategori Fasilitas <span class="text-red-500">*</span></label>
                            <select
                                v-model="form.kategori_fasilitas"
                                class="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-slate-300"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.kategori_fasilitas }"
                            >
                                <option value="">Pilih Kategori</option>
                                <option value="Akomodasi">Akomodasi</option>
                                <option value="Kuliner">Kuliner</option>
                                <option value="Transportasi">Transportasi</option>
                                <option value="Umum">Umum</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <p v-if="form.errors.kategori_fasilitas" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.kategori_fasilitas }}</p>
                        </div>

                         <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Lokasi (Objek Wisata)</label>
                            <select
                                v-model="form.id_objek"
                                class="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-slate-300"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.id_objek }"
                            >
                                <option :value="null">Tidak Terikat Objek Wisata</option>
                                <option v-for="objek in objekWisatas" :key="objek.id" :value="objek.id">
                                    {{ objek.nama_objek }}
                                </option>
                            </select>
                             <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Opsional: Jika fasilitas berada di dalam objek wisata tertentu.</p>
                             <p v-if="form.errors.id_objek" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.id_objek }}</p>
                        </div>

                         <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Deskripsi Singkat</label>
                            <Input
                                v-model="form.deskripsi"
                                type="text"
                                placeholder="Keterangan tambahan..."
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.deskripsi }"
                            />
                            <p v-if="form.errors.deskripsi" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.deskripsi }}</p>
                        </div>
                    </div>
                </div>

                <!-- Peta Lokasi -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-xl p-6 border border-slate-200 dark:border-slate-700 space-y-6">
                    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-4 mb-2">
                        <div class="w-2 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Titik Koordinat</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Latitude</label>
                            <Input
                                v-model="form.latitude"
                                type="text"
                                readonly
                                class="bg-slate-50 dark:bg-slate-900 font-mono text-sm"
                            />
                            <p v-if="form.errors.latitude" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.latitude }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Longitude</label>
                            <Input
                                v-model="form.longitude"
                                type="text"
                                readonly
                                class="bg-slate-50 dark:bg-slate-900 font-mono text-sm"
                            />
                             <p v-if="form.errors.longitude" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.longitude }}</p>
                        </div>
                    </div>

                    <div class="relative w-full h-96 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-inner group">
                        <div id="map" class="w-full h-full z-0"></div>
                        <div class="absolute top-4 right-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur px-3 py-2 rounded-lg shadow-sm text-xs font-medium text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 z-[1000]">
                            Klik peta untuk ubah lokasi
                        </div>
                    </div>
                </div>

                <!-- Foto Fasilitas -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-xl p-6 border border-slate-200 dark:border-slate-700 space-y-6">
                     <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-4 mb-2">
                        <div class="w-2 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Foto Fasilitas</h3>
                    </div>

                    <div class="space-y-4">
                        <label class="relative flex flex-col items-center justify-center w-full h-40 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl cursor-pointer bg-slate-50/50 dark:bg-slate-900/50 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <UploadCloud class="w-10 h-10 mb-3 text-slate-400 group-hover:text-primary transition-colors" />
                                <p class="mb-2 text-sm text-slate-500 dark:text-slate-400"><span class="font-bold text-primary">Klik upload</span> atau drag and drop</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Maks. 2MB per file</p>
                            </div>
                            <input
                                type="file"
                                class="hidden"
                                multiple
                                accept="image/*"
                                @change="handleFileUpload"
                            />
                        </label>

                         <div v-if="photoPreviews.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div v-for="(preview, index) in photoPreviews" :key="index" class="relative group aspect-video rounded-xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700">
                                <img :src="preview" class="h-full w-full object-cover" />
                                <button
                                    type="button"
                                    @click="removePhoto(index)"
                                    class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-all hover:bg-red-600"
                                >
                                    <X class="w-4 h-4" />
                                </button>
                                <div v-if="index === 0" class="absolute bottom-2 left-2 bg-primary/90 text-white text-[10px] px-2 py-0.5 rounded font-black uppercase tracking-wider">
                                    Primary
                                </div>
                            </div>
                        </div>
                        <p v-if="form.errors.fotos" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.fotos }}</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <Button
                        variant="outline"
                        as-child
                    >
                        <Link :href="route('admin.fasilitas-wisata.index')">
                            Batal
                        </Link>
                    </Button>
                    <Button
                        type="submit"
                        class="bg-primary hover:bg-primary/90 text-white px-8"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="flex items-center">
                            <Loader2 class="animate-spin h-4 w-4 mr-2" />
                            Menyimpan...
                        </span>
                        <span v-else>Simpan Fasilitas</span>
                    </Button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { ArrowLeft, UploadCloud, X, Loader2 } from 'lucide-vue-next';
import L from 'leaflet';

const props = defineProps({
    objekWisatas: Array,
});

const form = useForm({
    nama_fasilitas: '',
    kategori_fasilitas: '',
    deskripsi: '',
    id_objek: new URLSearchParams(window.location.search).get('objek_id') ? parseInt(new URLSearchParams(window.location.search).get('objek_id')) : null,
    latitude: -8.5,
    longitude: 119.88,
    fotos: [],
});

const photoPreviews = ref([]);
let map = null;
let marker = null;

onMounted(() => {
    map = L.map('map').setView([form.latitude, form.longitude], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

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

const handleFileUpload = (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        form.fotos.push(file);
        const reader = new FileReader();
        reader.onload = (e) => photoPreviews.value.push(e.target.result);
        reader.readAsDataURL(file);
    });
};

const removePhoto = (index) => {
    form.fotos.splice(index, 1);
    photoPreviews.value.splice(index, 1);
};

const submit = () => {
    form.post(route('admin.fasilitas-wisata.store'), {
        forceFormData: true,
    });
};
</script>

<style scoped>
#map {
    width: 100%;
    min-height: 400px;
}
</style>
