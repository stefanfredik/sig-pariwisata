<script setup>
import { ref, reactive } from 'vue';
import { useForm, router, Link, Head } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Save, UploadCloud, X, Calendar, MapPin, Star, Trash2, ImageIcon, Clock, LayoutGrid } from 'lucide-vue-next';
import AdminFormLayout from '@/Components/Admin/AdminFormLayout.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({
    event: Object,
    objekWisatas: Array,
});

const form = useForm({
    id_objek: props.event.id_objek ? String(props.event.id_objek) : null,
    nama_event: props.event.nama_event,
    slug: props.event.slug,
    tanggal_mulai: props.event.tanggal_mulai.split('T')[0],
    tanggal_selesai: props.event.tanggal_selesai.split('T')[0],
    alamat: props.event.alamat,
    keterangan: props.event.keterangan || '',
    new_fotos: [],
    _method: 'PUT',
});

const photoPreviews = ref([]);
const confirmModal = reactive({
    show: false,
    id: null,
    title: '',
    description: '',
    loading: false
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

const removeNewPhoto = (index) => {
    form.new_fotos.splice(index, 1);
    photoPreviews.value.splice(index, 1);
};

const deleteExistingPhoto = (id) => {
    confirmModal.id = id;
    confirmModal.title = 'Hapus Foto';
    confirmModal.description = 'Apakah Anda yakin ingin menghapus foto ini secara permanen?';
    confirmModal.show = true;
};

const handleConfirmDelete = () => {
    if (!confirmModal.id) return;
    confirmModal.loading = true;
    router.delete(route('admin.events.delete-photo', confirmModal.id), {
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

const setPrimary = (fotoId) => {
    router.post(route('admin.events.set-primary-photo', { eventId: props.event.id, fotoId: fotoId }), {}, {
        preserveScroll: true,
    });
};

const submit = () => {
    form.post(route('admin.events.update', props.event.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminFormLayout :title="'Edit Event: ' + event.nama_event"
        description="Perbarui informasi agenda kegiatan untuk menjaga akurasi data bagi pengunjung."
        :backRoute="route('admin.events.index')">
        <form @submit.prevent="submit" class="space-y-8 pb-12">
            <!-- Informasi Utama -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <Calendar class="h-4 w-4" />
                        Detail Agenda
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Nama Event <span
                                class="text-red-500">*</span></label>
                        <Input v-model="form.nama_event" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.nama_event" class="text-xs text-red-500 font-medium">{{
                            form.errors.nama_event }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Slug (URL) <span class="text-red-500">*</span></label>
                        <Input v-model="form.slug" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.slug" class="text-xs text-red-500 font-medium">{{ form.errors.slug }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Lokasi Objek Wisata
                            (Opsional)</label>
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
                        <p v-if="form.errors.id_objek" class="text-xs text-red-500 font-medium">{{ form.errors.id_objek
                            }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Alamat Lengkap <span
                                class="text-red-500">*</span></label>
                        <Input v-model="form.alamat" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.alamat" class="text-xs text-red-500 font-medium">{{ form.errors.alamat }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-emerald-600">Tanggal Mulai
                                <span class="text-red-500">*</span></label>
                            <Input v-model="form.tanggal_mulai" type="date" class="rounded-xl border-slate-200 h-11" />
                            <p v-if="form.errors.tanggal_mulai" class="text-xs text-red-500 font-medium">{{
                                form.errors.tanggal_mulai }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-rose-600">Tanggal Selesai
                                <span class="text-red-500">*</span></label>
                            <Input v-model="form.tanggal_selesai" type="date"
                                class="rounded-xl border-slate-200 h-11" />
                            <p v-if="form.errors.tanggal_selesai" class="text-xs text-red-500 font-medium">{{
                                form.errors.tanggal_selesai }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500 border-t pt-4 block mt-4">Keterangan
                            / Deskripsi <span class="text-red-500">*</span></label>
                        <Textarea v-model="form.keterangan" rows="4" class="rounded-xl border-slate-200" />
                        <p v-if="form.errors.keterangan" class="text-xs text-red-500 font-medium">{{
                            form.errors.keterangan }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Media & Foto -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <ImageIcon class="h-4 w-4" />
                        Dokumentasi Foto
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-8">
                    <!-- Foto Saat Ini -->
                    <div v-if="event.fotos.length" class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 block">Foto Saat
                            Ini</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div v-for="foto in event.fotos" :key="foto.id"
                                class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <img :src="'/storage/' + foto.path"
                                    class="w-full h-full object-cover transition-transform group-hover:scale-105" />

                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <button v-if="!foto.is_primary" @click.prevent="setPrimary(foto.id)"
                                        class="bg-white text-primary p-2 rounded-lg hover:bg-primary hover:text-white transition-all shadow-lg active:scale-95"
                                        title="Set Utama">
                                        <Star class="w-4 h-4" />
                                    </button>
                                    <button @click.prevent="deleteExistingPhoto(foto.id)"
                                        class="bg-white text-red-600 p-2 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-lg active:scale-95"
                                        title="Hapus">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>

                                <div v-if="foto.is_primary"
                                    class="absolute top-2 left-2 bg-primary/95 backdrop-blur px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-white shadow-sm">
                                    Utama</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tambah Foto Baru -->
                    <div class="space-y-4 pt-8 border-t border-slate-100">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 block">Tambah Foto
                            Baru</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-10 pb-10 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-primary transition-all group cursor-pointer bg-slate-50/30">
                            <div class="space-y-2 text-center">
                                <UploadCloud
                                    class="mx-auto h-12 w-12 text-slate-300 group-hover:text-primary transition-colors" />
                                <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                    <label
                                        class="relative cursor-pointer bg-transparent rounded-md font-black text-primary hover:text-primary/80 focus-within:outline-none">
                                        <span>Unggah foto tambahan</span>
                                        <input type="file" class="sr-only" @change="handleFileUpload" multiple
                                            accept="image/*" />
                                    </label>
                                    <p class="pl-1">atau tarik dan lepas</p>
                                </div>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">PNG, JPG,
                                    JPEG hingga 2MB</p>
                            </div>
                        </div>

                        <!-- Previews Foto Baru -->
                        <div v-if="photoPreviews.length" class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
                            <div v-for="(src, index) in photoPreviews" :key="index"
                                class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <img :src="src"
                                    class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                <button @click.prevent="removeNewPhoto(index)"
                                    class="absolute top-2 right-2 bg-red-600/90 text-white rounded-lg p-1.5 shadow-lg group-hover:opacity-100 opacity-0 transition-opacity backdrop-blur-sm">
                                    <X class="w-4 h-4" />
                                </button>
                                <div
                                    class="absolute top-2 left-2 bg-primary/95 backdrop-blur px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-white shadow-sm">
                                    Baru</div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <Button type="submit" :disabled="form.processing"
                    class="px-10 font-bold uppercase tracking-widest text-xs">
                    <Save class="w-4 h-4 mr-2" />
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </Button>
                <Button variant="ghost" class="px-8 font-bold uppercase tracking-widest text-xs" as-child>
                    <Link :href="route('admin.events.index')">Batal</Link>
                </Button>
            </div>
        </form>

        <ConfirmDialog v-model:open="confirmModal.show" :title="confirmModal.title"
            :description="confirmModal.description" :loading="confirmModal.loading" @confirm="handleConfirmDelete" />
    </AdminFormLayout>
</template>
