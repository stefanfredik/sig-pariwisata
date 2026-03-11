<script setup>
import { onMounted, ref, reactive } from 'vue';
import { useForm, Link, Head, router } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Save, UploadCloud, X, Trash2, Check, Store, MapPin, ImageIcon, Star } from 'lucide-vue-next';
import AdminFormLayout from '@/Components/Admin/AdminFormLayout.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    umkm: Object,
    categories: Array,
});

const form = useForm({
    _method: 'PUT',
    nama_umkm: props.umkm.nama_umkm,
    kategori: props.umkm.kategori,
    alamat: props.umkm.alamat,
    keterangan: props.umkm.keterangan || '',
    latitude: props.umkm.latitude,
    longitude: props.umkm.longitude,
    new_fotos: [],
});

const newPreviews = ref([]);
const confirmModal = reactive({
    show: false,
    id: null,
    title: '',
    description: '',
    loading: false
});
let map = null;
let marker = null;

onMounted(() => {
    // Initialize Map
    map = L.map('map').setView([form.latitude, form.longitude], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    marker = L.marker([form.latitude, form.longitude], { draggable: true }).addTo(map);

    marker.on('dragend', (e) => {
        const { lat, lng } = e.target.getLatLng();
        form.latitude = lat.toFixed(8);
        form.longitude = lng.toFixed(8);
    });

    map.on('click', (e) => {
        const { lat, lng } = e.latlng;
        marker.setLatLng([lat, lng]);
        form.latitude = lat.toFixed(8);
        form.longitude = lng.toFixed(8);
    });
});

const handleFileUpload = (e) => {
    const files = Array.from(e.target.files);
    form.new_fotos = [...form.new_fotos, ...files];
    
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => newPreviews.value.push(e.target.result);
        reader.readAsDataURL(file);
    });
};

const removeNewPhoto = (index) => {
    form.new_fotos.splice(index, 1);
    newPreviews.value.splice(index, 1);
};

const deletePhoto = (id) => {
    confirmModal.id = id;
    confirmModal.title = 'Hapus Foto UMKM';
    confirmModal.description = 'Apakah Anda yakin ingin menghapus foto ini?';
    confirmModal.show = true;
};

const handleConfirmDelete = () => {
    if (!confirmModal.id) return;
    confirmModal.loading = true;
    router.delete(route('admin.umkm.delete-photo', confirmModal.id), {
        onSuccess: () => {
            confirmModal.show = false;
            confirmModal.loading = false;
        },
        onError: () => {
            confirmModal.loading = false;
        }
    });
};

const setPrimary = (fotoId) => {
    router.post(route('admin.umkm.set-primary-photo', { umkmId: props.umkm.id, fotoId }), {}, {
        preserveScroll: true,
    });
};

const submit = () => {
    form.post(route('admin.umkm.update', props.umkm.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminFormLayout
        :title="'Edit UMKM: ' + umkm.nama_umkm"
        description="Perbarui informasi bisnis UMKM agar tetap up-to-date di peta wisata."
        :backRoute="route('admin.umkm.index')"
    >
        <form @submit.prevent="submit" class="space-y-8 pb-12">
            <!-- Informasi Bisnis -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <Store class="h-4 w-4" /> 
                        Informasi Bisnis
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Nama UMKM <span class="text-red-500">*</span></label>
                        <Input v-model="form.nama_umkm" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.nama_umkm" class="text-xs text-red-500 font-medium">{{ form.errors.nama_umkm }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Kategori Bisnis <span class="text-red-500">*</span></label>
                        <Select v-model="form.kategori">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih Kategori" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="cat in categories" :key="cat" :value="cat">
                                        {{ cat }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.kategori" class="text-xs text-red-500 font-medium">{{ form.errors.kategori }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <Input v-model="form.alamat" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.alamat" class="text-xs text-red-500 font-medium">{{ form.errors.alamat }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 border-t pt-4 block mt-4">Keterangan Bisnis</label>
                        <Textarea v-model="form.keterangan" rows="4" class="rounded-xl border-slate-200" />
                        <p v-if="form.errors.keterangan" class="text-xs text-red-500 font-medium">{{ form.errors.keterangan }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Lokasi Geografis -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <MapPin class="h-4 w-4" />
                        Lokasi Geografis
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500">Latitude</label>
                            <Input v-model="form.latitude" readonly class="rounded-xl border-slate-200 h-11 bg-slate-50 dark:bg-slate-900 cursor-not-allowed" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500">Longitude</label>
                            <Input v-model="form.longitude" readonly class="rounded-xl border-slate-200 h-11 bg-slate-50 dark:bg-slate-900 cursor-not-allowed" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400 italic">Klik pada peta atau geser penanda untuk menyesuaikan lokasi.</label>
                        <div id="map" class="h-80 w-full rounded-2xl border border-slate-200 dark:border-slate-700 shadow-inner z-0"></div>
                    </div>
                </CardContent>
            </Card>

            <!-- Media & Foto -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <ImageIcon class="h-4 w-4" />
                        Manajemen Foto
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-8">
                    <!-- Foto Saat Ini -->
                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 block">Foto Saat Ini</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div v-for="foto in umkm.fotos" :key="foto.id" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm transition-all hover:shadow-md">
                                <img :src="'/storage/' + foto.path" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[2px]">
                                    <button 
                                        type="button"
                                        @click.prevent="setPrimary(foto.id)"
                                        class="bg-white text-primary p-2 rounded-md hover:bg-primary hover:text-white transition-all active:scale-95" 
                                        :class="{ 'ring-2 ring-primary': foto.is_primary }"
                                        title="Jadikan Foto Utama"
                                    >
                                        <Star class="w-4 h-4" :class="{ 'fill-current': foto.is_primary }" />
                                    </button>
                                    <button 
                                        type="button"
                                        @click.prevent="deletePhoto(foto.id)"
                                        class="bg-white text-red-600 p-2 rounded-md hover:bg-red-600 hover:text-white transition-all active:scale-95" 
                                        title="Hapus Foto"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>

                                <div v-if="foto.is_primary" class="absolute top-2 left-2 bg-white/95 backdrop-blur px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-slate-900 border border-slate-100 shadow-sm z-10">Utama</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tambah Foto Baru -->
                    <div class="space-y-4 pt-8 border-t border-slate-100">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 block">Tambah Foto Baru</label>
                        <div class="mt-1 flex justify-center px-6 pt-10 pb-10 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-primary transition-all group cursor-pointer bg-slate-50/30">
                            <div class="space-y-2 text-center">
                                <UploadCloud class="mx-auto h-12 w-12 text-slate-300 group-hover:text-primary transition-colors" />
                                <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                    <label class="relative cursor-pointer bg-transparent rounded-md font-black text-primary hover:text-primary/80 focus-within:outline-none">
                                        <span>Unggah foto tambahan</span>
                                        <input type="file" class="sr-only" @change="handleFileUpload" multiple accept="image/*" />
                                    </label>
                                    <p class="pl-1">atau tarik dan lepas</p>
                                </div>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">PNG, JPG hingga 25MB</p>
                            </div>
                        </div>

                        <!-- Previews Foto Baru -->
                        <div v-if="newPreviews.length" class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4">
                            <div v-for="(src, index) in newPreviews" :key="index" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <img :src="src" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                <button @click.prevent="removeNewPhoto(index)" class="absolute top-2 right-2 bg-red-600/90 text-white rounded-lg p-1.5 shadow-lg group-hover:opacity-100 opacity-0 transition-opacity backdrop-blur-sm">
                                    <X class="w-4 h-4" />
                                </button>
                                <div class="absolute top-2 left-2 bg-primary/90 backdrop-blur px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-white shadow-sm">Baru</div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <Button type="submit" :disabled="form.processing" class="px-10 font-bold uppercase tracking-widest text-xs">
                    <Save class="w-4 h-4 mr-2" />
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </Button>
                <Button variant="ghost" class="px-8 font-bold uppercase tracking-widest text-xs" as-child>
                    <Link :href="route('admin.umkm.index')">Batal</Link>
                </Button>
            </div>
        </form>

        <ConfirmDialog 
            v-model:open="confirmModal.show"
            :title="confirmModal.title"
            :description="confirmModal.description"
            :loading="confirmModal.loading"
            @confirm="handleConfirmDelete"
        />
    </AdminFormLayout>
</template>

<style>
.leaflet-container {
    mask-image: none !important;
    z-index: 0;
}
</style>
