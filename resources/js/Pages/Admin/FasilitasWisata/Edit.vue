<script setup>
import { onMounted, ref, reactive } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Save, UploadCloud, X, Trash2, LayoutGrid, Info, ImageIcon } from 'lucide-vue-next';
import AdminFormLayout from '@/Components/Admin/AdminFormLayout.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({
    fasilitas: Object,
});

const form = useForm({
    id_objek: props.fasilitas.id_objek,
    nama_fasilitas: props.fasilitas.nama_fasilitas,
    kategori_fasilitas: props.fasilitas.kategori_fasilitas || '',
    deskripsi: props.fasilitas.deskripsi,
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

const removePhoto = (index) => {
    form.new_fotos.splice(index, 1);
    photoPreviews.value.splice(index, 1);
};

const deleteExistingPhoto = (id) => {
    confirmModal.id = id;
    confirmModal.title = 'Hapus Foto Fasilitas';
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

const submit = () => {
    form.post(route('admin.fasilitas-wisata.update', props.fasilitas.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminFormLayout
        :title="'Edit Fasilitas: ' + fasilitas.nama_fasilitas"
        description="Perbarui informasi fasilitas wisata untuk memberikan layanan terbaik."
        :backRoute="form.id_objek ? route('admin.objek-wisata.show', form.id_objek) : route('admin.objek-wisata.index')"
    >
        <form @submit.prevent="submit" class="space-y-8 pb-12">
            <!-- Informasi Fasilitas -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <LayoutGrid class="h-4 w-4" /> 
                        Detil Fasilitas
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Nama Fasilitas <span class="text-red-500">*</span></label>
                        <Input v-model="form.nama_fasilitas" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.nama_fasilitas" class="text-xs text-red-500 font-medium">{{ form.errors.nama_fasilitas }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Kategori Fasilitas <span class="text-red-500">*</span></label>
                        <Select v-model="form.kategori_fasilitas">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih Kategori" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="Akomodasi">Akomodasi</SelectItem>
                                    <SelectItem value="Kuliner">Kuliner</SelectItem>
                                    <SelectItem value="Transportasi">Transportasi</SelectItem>
                                    <SelectItem value="Umum">Umum</SelectItem>
                                    <SelectItem value="Lainnya">Lainnya</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.kategori_fasilitas" class="text-xs text-red-500 font-medium">{{ form.errors.kategori_fasilitas }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Deskripsi Singkat</label>
                        <Input v-model="form.deskripsi" class="rounded-xl border-slate-200 h-11" />
                        <p v-if="form.errors.deskripsi" class="text-xs text-red-500 font-medium">{{ form.errors.deskripsi }}</p>
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
                    <div v-if="fasilitas.fotos && fasilitas.fotos.length > 0" class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 block">Foto Saat Ini</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div v-for="foto in fasilitas.fotos" :key="foto.id" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <img :src="'/storage/' + foto.path" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                <button 
                                    @click.prevent="deleteExistingPhoto(foto.id)" 
                                    class="absolute top-2 right-2 bg-red-600/90 text-white rounded-lg p-1.5 shadow-lg group-hover:opacity-100 opacity-0 transition-opacity backdrop-blur-sm"
                                    title="Hapus"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
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
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">PNG, JPG, JPEG hingga 2MB</p>
                            </div>
                        </div>

                        <!-- Previews Foto Baru -->
                        <div v-if="photoPreviews.length" class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
                            <div v-for="(src, index) in photoPreviews" :key="index" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                <img :src="src" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                <button @click.prevent="removePhoto(index)" class="absolute top-2 right-2 bg-red-600/90 text-white rounded-lg p-1.5 shadow-lg group-hover:opacity-100 opacity-0 transition-opacity backdrop-blur-sm">
                                    <X class="w-4 h-4" />
                                </button>
                                <div class="absolute top-2 left-2 bg-primary/95 backdrop-blur px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-white shadow-sm">Baru</div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <Button type="submit" :disabled="form.processing" class="px-10 font-bold uppercase tracking-widest text-xs">
                    <Save class="w-4 h-4 mr-2" />
                    {{ form.processing ? 'Memperbarui...' : 'Simpan Perubahan' }}
                </Button>
                <Button variant="ghost" class="px-8 font-bold uppercase tracking-widest text-xs" as-child>
                    <Link :href="form.id_objek ? route('admin.objek-wisata.show', form.id_objek) : route('admin.objek-wisata.index')">Batal</Link>
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
