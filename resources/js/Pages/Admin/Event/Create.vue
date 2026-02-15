<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Tambah Event</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Buat agenda kegiatan baru</p>
                </div>
                <Button variant="outline" as-child>
                    <Link :href="route('admin.events.index')">
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
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Informasi Dasar</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Event <span class="text-red-500">*</span></label>
                            <Input
                                v-model="form.nama_event"
                                type="text"
                                placeholder="Contoh: Festival Komodo 2026"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.nama_event }"
                            />
                            <p v-if="form.errors.nama_event" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.nama_event }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Lokasi Objek Wisata (Optional)</label>
                            <select
                                v-model="form.id_objek"
                                class="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:ring-offset-slate-950 dark:placeholder:text-slate-400 dark:focus-visible:ring-slate-300"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.id_objek }"
                            >
                                <option :value="null">Pilih Objek Wisata (Jika ada)</option>
                                <option v-for="objek in objekWisatas" :key="objek.id" :value="objek.id">
                                    {{ objek.nama_objek }}
                                </option>
                            </select>
                            <p v-if="form.errors.id_objek" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.id_objek }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <Input
                                v-model="form.alamat"
                                type="text"
                                placeholder="Jl. Soekarno Hatta..."
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.alamat }"
                            />
                            <p v-if="form.errors.alamat" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.alamat }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-emerald-600 dark:text-emerald-400 mb-2">Tanggal Mulai <span class="text-emerald-500">*</span></label>
                            <Input
                                v-model="form.tanggal_mulai"
                                type="date"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.tanggal_mulai }"
                            />
                            <p v-if="form.errors.tanggal_mulai" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.tanggal_mulai }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-rose-600 dark:text-rose-400 mb-2">Tanggal Selesai <span class="text-rose-500">*</span></label>
                            <Input
                                v-model="form.tanggal_selesai"
                                type="date"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.tanggal_selesai }"
                            />
                            <p v-if="form.errors.tanggal_selesai" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.tanggal_selesai }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Keterangan/Deskripsi <span class="text-red-500">*</span></label>
                        <Textarea
                            v-model="form.keterangan"
                            rows="4"
                            placeholder="Jelaskan detail kegiatan, acara, dsb..."
                            :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.keterangan }"
                        />
                        <p v-if="form.errors.keterangan" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.keterangan }}</p>
                    </div>
                </div>

                <!-- Foto Event -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-xl p-6 border border-slate-200 dark:border-slate-700 space-y-6">
                    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-4 mb-2">
                        <div class="w-2 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Dokumentasi Foto</h3>
                    </div>

                    <div class="space-y-4">
                        <label class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl cursor-pointer bg-slate-50/50 dark:bg-slate-900/50 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <UploadCloud class="w-10 h-10 mb-3 text-slate-400 group-hover:text-primary transition-colors" />
                                <p class="mb-2 text-sm text-slate-500 dark:text-slate-400"><span class="font-bold text-primary">Klik untuk upload</span> atau drag and drop</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">JPEG, PNG, JPG (Maks. 2MB)</p>
                            </div>
                            <input
                                type="file"
                                class="hidden"
                                multiple
                                accept="image/*"
                                @change="handleFileUpload"
                            />
                        </label>

                        <!-- Photo Previews -->
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
                        <Link :href="route('admin.events.index')">
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
                        <span v-else>Simpan Event</span>
                    </Button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { ArrowLeft, UploadCloud, X, Loader2 } from 'lucide-vue-next';

const props = defineProps({
    objekWisatas: Array,
});

const form = useForm({
    id_objek: null,
    nama_event: '',
    tanggal_mulai: '',
    tanggal_selesai: '',
    alamat: '',
    keterangan: '',
    fotos: [],
});

const photoPreviews = ref([]);

const handleFileUpload = (e) => {
    const files = Array.from(e.target.files);
    
    files.forEach(file => {
        form.fotos.push(file);
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreviews.value.push(e.target.result);
        };
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
