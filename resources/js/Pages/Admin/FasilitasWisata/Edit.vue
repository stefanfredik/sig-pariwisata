<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Fasilitas Wisata</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Perbarui data fasilitas wisata</p>
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

                    <!-- Existing Photos -->
                    <div v-if="fasilitas.fotos && fasilitas.fotos.length > 0" class="flex flex-wrap gap-4 mb-4">
                        <div v-for="foto in fasilitas.fotos" :key="foto.id" class="relative group w-32 aspect-video rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
                            <img :src="'/storage/' + foto.path" class="w-full h-full object-cover">
                            <button 
                                type="button"
                                @click.prevent="deleteExistingPhoto(foto.id)" 
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-md p-1.5 shadow-md opacity-0 group-hover:opacity-100 transition-all hover:bg-red-600"
                                title="Hapus foto"
                            >
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Tambah Foto Baru</p>
                        <label class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl cursor-pointer bg-slate-50/50 dark:bg-slate-900/50 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <UploadCloud class="w-8 h-8 mb-2 text-slate-400 group-hover:text-primary transition-colors" />
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
                            </div>
                        </div>
                        <p v-if="form.errors.new_fotos" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.new_fotos }}</p>
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
                        <span v-else>Update Fasilitas</span>
                    </Button>
                </div>
            </form>
        </div>

        <ConfirmDialog 
            v-model:open="confirmModal.show"
            :title="confirmModal.title"
            :description="confirmModal.description"
            :loading="confirmModal.loading"
            @confirm="handleConfirmDelete"
        />
    </AdminLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { ArrowLeft, UploadCloud, X, Loader2, Trash2 } from 'lucide-vue-next';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import { reactive } from 'vue';
import L from 'leaflet';

const props = defineProps({
    fasilitas: Object,
    objekWisatas: Array,
});

const form = useForm({
    id_objek: props.fasilitas.id_objek,
    nama_fasilitas: props.fasilitas.nama_fasilitas,
    kategori_fasilitas: props.fasilitas.kategori_fasilitas || '',
    deskripsi: props.fasilitas.deskripsi,
    latitude: props.fasilitas.latitude,
    longitude: props.fasilitas.longitude,
    new_fotos: [],
    _method: 'PUT',
});

// Update id_objek_wisata to match select v-model
// If the original used id_objek, we should stick to what the controller expects or mapping in Create.vue
// Create.vue used id_objek_wisata. I will assume standardizing to id_objek_wisata is better if supported, 
// but looking at previous Edit.vue it had v-model="form.id_objek".
// Let's check Create.vue again to be sure. Create.vue had id_objek_wisata.
// I'll stick to id_objek_wisata in the form data if that's what Create used, 
// BUT if the backend update method expects 'id_objek', I might break it. 
// However, in Create.vue step 1367, I used id_objek_wisata.
// Let's check the old Edit.vue content again.
// Old Edit.vue line 29: v-model="form.id_objek".
// I will keep `id_objek_wisata` in the v-model but initiate it from props correctly.

const previewUrls = ref([]);
let map = null;
let marker = null;

onMounted(() => {
    map = L.map('map').setView([form.latitude, form.longitude], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

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
    
    files.forEach(file => {
        form.new_fotos.push(file);
        const reader = new FileReader();
        reader.onload = (e) => photoPreviews.value.push(e.target.result);
        reader.readAsDataURL(file);
    });
};

const removePhoto = (index) => {
    form.new_fotos.splice(index, 1);
    photoPreviews.value.splice(index, 1);
};


const confirmModal = reactive({
    show: false,
    id: null,
    title: '',
    description: '',
    loading: false
});

const deleteExistingPhoto = (id) => {
    confirmModal.id = id;
    confirmModal.title = 'Hapus Foto';
    confirmModal.description = 'Apakah Anda yakin ingin menghapus foto ini secara permanen?';
    confirmModal.show = true;
};

const handleConfirmDelete = () => {
    if (!confirmModal.id) return;
    
    confirmModal.loading = true;
    router.delete(route('admin.fasilitas-wisata.delete-photo', confirmModal.id), {
        preserveScroll: true,
        onSuccess: () => {
            confirmModal.show = false;
            confirmModal.loading = false;
        },
        onError: () => {
            confirmModal.loading = false;
        }
    });
};

const photoPreviews = ref([]);

const submit = () => {
    form.post(route('admin.fasilitas-wisata.update', props.fasilitas.id), {
        forceFormData: true,
    });
};
</script>

<style>
.leaflet-container {
    mask-image: none !important;
    z-index: 0;
}
</style>
