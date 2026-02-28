<script setup>
import { onMounted, ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Save, UploadCloud, X, Mountain, MapPin, Phone, Clock, DollarSign } from 'lucide-vue-next';
import AdminFormLayout from '@/Components/Admin/AdminFormLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'

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
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
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
    form.post(route('admin.objek-wisata.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminFormLayout
        title="Tambah Objek Wisata"
        description="Lengkapi data destinasi wisata baru dengan lengkap dan benar."
        :backRoute="route('admin.objek-wisata.index')"
    >
        <form @submit.prevent="submit" class="space-y-8 pb-12">
            <!-- Informasi Utama -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <Mountain class="h-4 w-4" /> 
                        Informasi Utama
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Nama Objek Wisata <span class="text-red-500">*</span></label>
                        <Input v-model="form.nama_objek" placeholder="Masukkan nama objek wisata" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.nama_objek" class="text-xs text-red-500 font-medium">{{ form.errors.nama_objek }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Kecamatan <span class="text-red-500">*</span></label>
                        <Select v-model="form.id_kecamatan">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih Kecamatan" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="kec in kecamatans" :key="kec.id" :value="String(kec.id)">
                                        {{ kec.nama_kecamatan }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.id_kecamatan" class="text-xs text-red-500 font-medium">{{ form.errors.id_kecamatan }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <Input v-model="form.alamat" placeholder="Masukkan alamat lengkap" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.alamat" class="text-xs text-red-500 font-medium">{{ form.errors.alamat }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 border-t pt-4 block mt-4">Keterangan / Deskripsi <span class="text-red-500">*</span></label>
                        <Textarea v-model="form.keterangan" rows="4" placeholder="Tuliskan keterangan lengkap objek wisata..." class="rounded-xl border-slate-200" />
                        <p v-if="form.errors.keterangan" class="text-xs text-red-500 font-medium">{{ form.errors.keterangan }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Kontak & Operasional -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <Clock class="h-4 w-4" />
                        Kontak & Operasional
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">No. Telepon</label>
                        <Input v-model="form.no_telepon" placeholder="Contoh: 0812..." class="rounded-xl border-slate-200 h-11" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Jam Operasional</label>
                        <Input v-model="form.jam_operasional" placeholder="Contoh: 08:00 - 17:00" class="rounded-xl border-slate-200 h-11" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Harga Tiket</label>
                        <Input v-model="form.harga_tiket" placeholder="Contoh: Rp 50.000 / orang" class="rounded-xl border-slate-200 h-11" />
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
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400 italic">Klik pada peta atau geser penanda untuk menentukan lokasi tepat.</label>
                        <div id="map" class="h-80 w-full rounded-2xl border border-slate-200 dark:border-slate-700 shadow-inner z-0"></div>
                    </div>
                </CardContent>
            </Card>

            <!-- Media -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <UploadCloud class="h-4 w-4" />
                        Media & Foto
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 block">Upload Foto (Minimal 1) <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex justify-center px-6 pt-10 pb-10 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-primary transition-all group cursor-pointer bg-slate-50/30">
                            <div class="space-y-2 text-center">
                                <UploadCloud class="mx-auto h-12 w-12 text-slate-300 group-hover:text-primary transition-colors" />
                                <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                    <label class="relative cursor-pointer bg-transparent rounded-md font-black text-primary hover:text-primary/80 focus-within:outline-none">
                                        <span>Unggah foto</span>
                                        <input type="file" class="sr-only" @change="handleFileUpload" multiple accept="image/*" />
                                    </label>
                                    <p class="pl-1">atau tarik dan lepas</p>
                                </div>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">PNG, JPG, JPEG hingga 10MB</p>
                            </div>
                        </div>
                        <p v-if="form.errors.fotos" class="text-xs text-red-500 font-medium">{{ form.errors.fotos }}</p>

                        <!-- Previews -->
                        <div v-if="previews.length" class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div v-for="(src, index) in previews" :key="index" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <img :src="src" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                <button @click.prevent="removePhoto(index)" class="absolute top-2 right-2 bg-red-600/90 text-white rounded-lg p-1.5 shadow-lg group-hover:opacity-100 opacity-0 transition-opacity backdrop-blur-sm">
                                    <X class="w-4 h-4" />
                                </button>
                                <div v-if="index === 0" class="absolute top-2 left-2 bg-white/95 backdrop-blur px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-slate-900 border border-slate-100 shadow-sm">Foto Utama</div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <Button type="submit" :disabled="form.processing" class="px-10 font-bold uppercase tracking-widest text-xs">
                    <Save class="w-4 h-4 mr-2" />
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Objek Wisata' }}
                </Button>
                <Button variant="ghost" class="px-8 font-bold uppercase tracking-widest text-xs" as-child>
                    <Link :href="route('admin.objek-wisata.index')">Batal</Link>
                </Button>
            </div>
        </form>
    </AdminFormLayout>
</template>

<style>
.leaflet-container {
    mask-image: none !important;
    z-index: 0;
}
</style>
