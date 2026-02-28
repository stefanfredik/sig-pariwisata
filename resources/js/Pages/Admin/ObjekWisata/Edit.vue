<script setup>
import { onMounted, ref, reactive } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Save, UploadCloud, X, Mountain, MapPin, Phone, Clock, Trash2, Star, Image as ImageIcon } from 'lucide-vue-next';
import AdminFormLayout from '@/Components/Admin/AdminFormLayout.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    objekWisata: Object,
    kecamatans: Array,
});

const form = useForm({
    id_kecamatan: String(props.objekWisata.id_kecamatan),
    nama_objek: props.objekWisata.nama_objek,
    alamat: props.objekWisata.alamat,
    no_telepon: props.objekWisata.no_telepon,
    keterangan: props.objekWisata.keterangan || '',
    akses_transportasi: props.objekWisata.akses_transportasi || [],
    jam_operasional: props.objekWisata.jam_operasional,
    harga_tiket: props.objekWisata.harga_tiket,
    latitude: props.objekWisata.latitude,
    longitude: props.objekWisata.longitude,
    new_fotos: [],
    _method: 'PUT',
});

const previews = ref([]);
const confirmModal = reactive({
    show: false,
    id: null,
    title: '',
    description: '',
    loading: false
});
let map = null;
let marker = null;

// ── Transportasi Options ──────────────────────────────────────
const transportOptions = ['Kendaraan Pribadi (Mobil)', 'Kendaraan Pribadi (Motor)', 'Kendaraan Umum / Angkot', 'Bus Pariwisata', 'Bisa Dijangkau Jalan Kaki'];

// ── Jam Operasional Options ──────────────────────────────────
const jamOptions = [
    '05:00', '06:00', '07:00', '08:00', '09:00', '10:00',
    '11:00', '12:00', '13:00', '14:00', '15:00', '16:00',
    '17:00', '18:00', '19:00', '20:00', '21:00', '22:00',
    '23:00', '24:00'
];

let initialBuka = '08:00';
let initialTutup = '17:00';

if (props.objekWisata.jam_operasional) {
    const parts = props.objekWisata.jam_operasional.split(' - ');
    if (parts.length === 2) {
        initialBuka = parts[0].trim();
        initialTutup = parts[1].replace('WITA', '').trim();
    }
}

const waktu_buka = ref(initialBuka);
const waktu_tutup = ref(initialTutup);

// ── Format Harga Tiket ────────────────────────────────────────
const formatRupiah = (angka) => {
    if (!angka) return '';
    const number_string = angka.toString().replace(/[^,\d]/g, '');
    const split = number_string.split(',');
    const sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        const separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
};

const hargaDisplay = ref(formatRupiah(form.harga_tiket));

const onHargaInput = (e) => {
    let rawValue = e.target.value.replace(/[^,\d]/g, '');
    let parsedRupiah = formatRupiah(rawValue);
    hargaDisplay.value = parsedRupiah;
    form.harga_tiket = rawValue ? parseInt(rawValue.replace(/\./g, '')) : null;
};

onMounted(() => {
    map = L.map('map').setView([form.latitude, form.longitude], 12);
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
    confirmModal.id = fotoId;
    confirmModal.title = 'Hapus Foto';
    confirmModal.description = 'Apakah Anda yakin ingin menghapus foto ini secara permanen?';
    confirmModal.show = true;
};

const handleConfirmDelete = () => {
    if (!confirmModal.id) return;
    confirmModal.loading = true;
    router.delete(route('admin.objek-wisata.delete-photo', confirmModal.id), {
        onSuccess: () => {
            confirmModal.show = false;
            confirmModal.loading = false;
        },
        onError: () => {
            confirmModal.loading = false;
        }
    });
};

const submit = () => {
    // Gabungkan waktu buka & tutup
    form.jam_operasional = `${waktu_buka.value} - ${waktu_tutup.value} WITA`;

    form.post(route('admin.objek-wisata.update', props.objekWisata.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminFormLayout
        :title="'Edit: ' + objekWisata.nama_objek"
        description="Perbarui informasi objek wisata Anda agar tetap akurat bagi pengunjung."
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
                        <Input v-model="form.nama_objek" class="rounded-xl border-slate-200 h-11" />
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
                        <Input v-model="form.alamat" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.alamat" class="text-xs text-red-500 font-medium">{{ form.errors.alamat }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 border-t pt-4 block mt-4">Keterangan / Deskripsi <span class="text-red-500">*</span></label>
                        <Textarea v-model="form.keterangan" rows="4" class="rounded-xl border-slate-200" />
                        <p v-if="form.errors.keterangan" class="text-xs text-red-500 font-medium">{{ form.errors.keterangan }}</p>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 border-t pt-4 block mt-4">Akses Transportasi</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label v-for="opsi in transportOptions" :key="opsi" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                <input type="checkbox" :value="opsi" v-model="form.akses_transportasi" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary dark:border-slate-600 dark:bg-slate-700" />
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ opsi }}</span>
                            </label>
                        </div>
                        <p v-if="form.errors.akses_transportasi" class="text-xs text-red-500 font-medium">{{ form.errors.akses_transportasi }}</p>
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
                        <Input v-model="form.no_telepon" class="rounded-xl border-slate-200 h-11" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Jam Operasional</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Buka</label>
                                <select
                                    v-model="waktu_buka"
                                    class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm font-bold text-slate-800 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                >
                                    <option v-for="jam in jamOptions" :key="jam" :value="jam">{{ jam }}</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Tutup</label>
                                <select
                                    v-model="waktu_tutup"
                                    class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm font-bold text-slate-800 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                >
                                    <option v-for="jam in jamOptions" :key="jam" :value="jam">{{ jam }}</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Akan disimpan sebagai: <span class="font-black text-slate-600">{{ waktu_buka }} - {{ waktu_tutup }} WITA</span></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Harga Tiket</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-black text-slate-500 select-none">Rp</span>
                            <input
                                :value="hargaDisplay"
                                @input="onHargaInput"
                                inputmode="numeric"
                                placeholder="0"
                                class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-10 pr-4 text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                            />
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Kosongkan jika gratis</p>
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
                            <div v-for="foto in objekWisata.fotos" :key="foto.id" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm transition-all hover:shadow-md">
                                <img :src="'/storage/' + foto.path" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[2px]">
                                    <button 
                                        type="button"
                                        @click.prevent="setPrimaryPhoto(foto.id)"
                                        class="bg-white text-primary p-2 rounded-md hover:bg-primary hover:text-white transition-all active:scale-95" 
                                        :class="{ 'ring-2 ring-primary': foto.is_primary }"
                                        title="Jadikan Foto Utama"
                                    >
                                        <Star class="w-4 h-4" :class="{ 'fill-current': foto.is_primary }" />
                                    </button>
                                    <button 
                                        type="button"
                                        @click.prevent="confirmDeletePhoto(foto.id)"
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
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">PNG, JPG hingga 10MB</p>
                            </div>
                        </div>

                        <!-- Previews Foto Baru -->
                        <div v-if="previews.length" class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4">
                            <div v-for="(src, index) in previews" :key="index" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <img :src="src" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                <button @click.prevent="removePreview(index)" class="absolute top-2 right-2 bg-red-600/90 text-white rounded-md p-1.5 group-hover:opacity-100 opacity-0 transition-opacity backdrop-blur-sm">
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
                    <Link :href="route('admin.objek-wisata.index')">Batal</Link>
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
