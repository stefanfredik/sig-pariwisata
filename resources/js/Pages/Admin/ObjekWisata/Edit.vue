```html
<template>
    <AdminLayout>
        <div class="max-w-4xl">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Objek Wisata</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Perbarui data {{ objekWisata.nama_objek }}</p>
                </div>
                <Button variant="outline" as-child>
                    <Link :href="route('admin.objek-wisata.index')">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Kembali
                    </Link>
                </Button>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Basic Info -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-6 space-y-6 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Objek Wisata <span class="text-red-500">*</span></label>
                            <Input
                                v-model="form.nama_objek"
                                type="text"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.nama_objek }"
                            />
                            <p v-if="form.errors.nama_objek" class="mt-1 text-sm text-red-600">{{ form.errors.nama_objek }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                            <select
                                v-model="form.id_kecamatan"
                                class="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-slate-300"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.id_kecamatan }"
                            >
                                <option v-for="kec in kecamatans" :key="kec.id" :value="kec.id">
                                    {{ kec.nama_kecamatan }}
                                </option>
                            </select>
                            <p v-if="form.errors.id_kecamatan" class="mt-1 text-sm text-red-600">{{ form.errors.id_kecamatan }}</p>
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <Input
                                v-model="form.alamat"
                                type="text"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.alamat }"
                            />
                            <p v-if="form.errors.alamat" class="mt-1 text-sm text-red-600">{{ form.errors.alamat }}</p>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                No. Telepon
                            </label>
                            <Input
                                v-model="form.no_telepon"
                                type="text"
                            />
                        </div>

                        <!-- Jam Operasional -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Jam Operasional
                            </label>
                            <Input
                                v-model="form.jam_operasional"
                                type="text"
                            />
                        </div>

                        <!-- Harga Tiket -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Harga Tiket
                            </label>
                            <Input
                                v-model="form.harga_tiket"
                                type="text"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Keterangan <span class="text-red-500">*</span></label>
                        <Textarea
                            v-model="form.keterangan"
                            rows="4"
                            :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.keterangan }"
                        />
                        <p v-if="form.errors.keterangan" class="mt-1 text-sm text-red-600">{{ form.errors.keterangan }}</p>
                    </div>
                </div>

                <!-- Location -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-6 space-y-6 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Lokasi Geografis</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-500 mb-1 uppercase tracking-wider">Latitude</label>
                            <Input v-model="form.latitude" type="text" class="bg-slate-50 dark:bg-slate-900" readonly />
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1 uppercase tracking-wider">Longitude</label>
                            <Input v-model="form.longitude" type="text" class="bg-slate-50 dark:bg-slate-900" readonly />
                        </div>
                    </div>
                    <div id="map" class="h-80 w-full rounded-lg border border-slate-300 dark:border-slate-700 shadow-inner"></div>
                </div>

                <!-- Photo Management -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-6 space-y-6 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2 flex items-center gap-2">
                        <ImageIcon class="w-5 h-5 text-slate-400" />
                        Manajemen Foto
                    </h3>
                    
                    <!-- Existing Photos -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="foto in objekWisata.fotos" :key="foto.id" class="relative group aspect-video rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all">
                            <img :src="'/storage/' + foto.path" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            <div v-if="foto.is_primary" class="absolute top-0 left-0 bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-br font-bold uppercase tracking-wider shadow z-10">Primary</div>
                            
                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 z-20">
                                <button 
                                    v-if="!foto.is_primary" 
                                    @click.prevent="setPrimary(foto.id)" 
                                    class="bg-white text-slate-900 p-2 rounded-full hover:bg-blue-50 hover:text-blue-600 transition-colors shadow-lg active:scale-90" 
                                    title="Set as Primary"
                                >
                                    <Star class="w-4 h-4" />
                                </button>
                                <button 
                                    @click.prevent="deletePhoto(foto.id)" 
                                    class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors shadow-lg active:scale-90" 
                                    title="Hapus Photo"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- New Photo Upload -->
                    <div class="mt-8 border-t border-slate-200 dark:border-slate-700 pt-8">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Tambah Foto Baru</label>
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-md hover:border-primary transition-colors group">
                            <div class="space-y-1 text-center">
                                <UploadCloud class="h-12 w-12 text-slate-400 group-hover:text-primary transition-colors" />
                                <div class="flex text-sm text-slate-600 dark:text-slate-400">
                                    <label for="file-upload" class="relative cursor-pointer bg-transparent rounded-md font-medium text-primary hover:text-primary/80">
                                        <span>Upload foto extra</span>
                                        <input id="file-upload" name="file-upload" type="file" class="sr-only" @change="handleFileUpload" multiple accept="image/*" />
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                            </div>
                        </div>
                        <div v-if="previews.length" class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div v-for="(src, index) in previews" :key="index" class="relative group aspect-video rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm transition-all hover:shadow-md">
                                <img :src="src" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <button 
                                    @click.prevent="removePreview(index)" 
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:bg-red-700 active:scale-90"
                                >
                                    <X class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <Button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-primary hover:bg-primary/90 text-white"
                    >
                        <Save class="w-4 h-4 mr-2" />
                        <span v-if="form.processing">Memperbarui...</span>
                        <span v-else>Update Objek Wisata</span>
                    </Button>
                    <Button
                        variant="outline"
                        as-child
                    >
                        <Link :href="route('admin.objek-wisata.index')">
                            <ArrowLeft class="w-4 h-4 mr-2" />
                            Batal
                        </Link>
                    </Button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { Save, ArrowLeft, Image as ImageIcon, Star, Trash2, UploadCloud, X } from 'lucide-vue-next';
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

<style>
.leaflet-container {
    mask-image: none !important;
    z-index: 0;
}
</style>
```
