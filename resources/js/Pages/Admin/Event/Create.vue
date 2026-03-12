<script setup>
import { ref, watch } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Save, UploadCloud, X, Calendar, MapPin, Info, ImageIcon, Clock, LayoutGrid } from 'lucide-vue-next';
import AdminFormLayout from '@/Components/Admin/AdminFormLayout.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';

const props = defineProps({
    objekWisatas: Array,
});

const form = useForm({
    id_objek: null,
    nama_event: '',
    slug: '',
    tanggal_mulai: '',
    tanggal_selesai: '',
    alamat: '',
    keterangan: '',
    fotos: [],
});

// Auto-generate slug
watch(() => form.nama_event, (val) => {
    form.slug = val.toLowerCase()
        .replace(/[^a-z0-9]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
});

const photoPreviews = ref([]);

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
    form.post(route('admin.events.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminFormLayout
        title="Tambah Event Baru"
        description="Buat agenda kegiatan atau festival menarik untuk menarik lebih banyak pengunjung."
        :backRoute="route('admin.events.index')"
    >
        <form @submit.prevent="submit" class="space-y-8 pb-12">
            <!-- Informasi Dasar -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <Calendar class="h-4 w-4" /> 
                        Detail Agenda
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Nama Event <span class="text-red-500">*</span></label>
                        <Input v-model="form.nama_event" placeholder="Contoh: Festival Komodo 2026" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.nama_event" class="text-xs text-red-500 font-medium">{{ form.errors.nama_event }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Slug (URL) <span class="text-red-500">*</span></label>
                        <Input v-model="form.slug" placeholder="festival-komodo-2026" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.slug" class="text-xs text-red-500 font-medium">{{ form.errors.slug }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Lokasi Objek Wisata (Opsional)</label>
                        <Select v-model="form.id_objek">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih Objek Wisata (Jika ada)" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem :value="null">Tidak ada / Lokasi Lain</SelectItem>
                                    <SelectItem v-for="objek in objekWisatas" :key="objek.id" :value="String(objek.id)">
                                        {{ objek.nama_objek }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.id_objek" class="text-xs text-red-500 font-medium">{{ form.errors.id_objek }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <Input v-model="form.alamat" placeholder="Jl. Soekarno Hatta..." class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.alamat" class="text-xs text-red-500 font-medium">{{ form.errors.alamat }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-emerald-600">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <Input v-model="form.tanggal_mulai" type="date" class="rounded-xl border-slate-200 h-11" />
                            <p v-if="form.errors.tanggal_mulai" class="text-xs text-red-500 font-medium">{{ form.errors.tanggal_mulai }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-rose-600">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <Input v-model="form.tanggal_selesai" type="date" class="rounded-xl border-slate-200 h-11" />
                            <p v-if="form.errors.tanggal_selesai" class="text-xs text-red-500 font-medium">{{ form.errors.tanggal_selesai }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 border-t pt-4 block mt-4">Keterangan / Deskripsi <span class="text-red-500">*</span></label>
                        <Textarea v-model="form.keterangan" rows="4" placeholder="Jelaskan detail kegiatan, acara, dsb..." class="rounded-xl border-slate-200" />
                        <p v-if="form.errors.keterangan" class="text-xs text-red-500 font-medium">{{ form.errors.keterangan }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Foto Event -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <ImageIcon class="h-4 w-4" />
                        Dokumentasi Foto
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 block text-center">Upload Foto Event</label>
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
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">PNG, JPG, JPEG hingga 2MB</p>
                            </div>
                        </div>
                        <p v-if="form.errors.fotos" class="text-xs text-red-500 font-medium">{{ form.errors.fotos }}</p>

                        <!-- Previews -->
                        <div v-if="photoPreviews.length" class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
                            <div v-for="(src, index) in photoPreviews" :key="index" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <img :src="src" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                <button @click.prevent="removePhoto(index)" class="absolute top-2 right-2 bg-red-600/90 text-white rounded-lg p-1.5 shadow-lg group-hover:opacity-100 opacity-0 transition-opacity backdrop-blur-sm">
                                    <X class="w-4 h-4" />
                                </button>
                                <div v-if="index === 0" class="absolute top-2 left-2 bg-primary/95 backdrop-blur px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-white shadow-sm">Foto Utama</div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <Button type="submit" :disabled="form.processing" class="px-10 font-bold uppercase tracking-widest text-xs">
                    <Save class="w-4 h-4 mr-2" />
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Event' }}
                </Button>
                <Button variant="ghost" class="px-8 font-bold uppercase tracking-widest text-xs" as-child>
                    <Link :href="route('admin.events.index')">Batal</Link>
                </Button>
            </div>
        </form>
    </AdminFormLayout>
</template>
