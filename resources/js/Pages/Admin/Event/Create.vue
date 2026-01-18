<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Event</h2>
                    <p class="mt-1 text-sm text-gray-600">Buat agenda kegiatan baru</p>
                </div>
                <a :href="route('admin.events.index')" class="btn-secondary">
                    Kembali
                </a>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white shadow rounded-xl p-6 border border-gray-100 space-y-6">
                    <div class="flex items-center gap-2 border-b border-gray-50 pb-4 mb-2">
                        <div class="w-2 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900">Informasi Dasar</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Event <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.nama_event"
                                type="text"
                                placeholder="Contoh: Festival Komodo 2026"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                :class="{ 'border-red-500': form.errors.nama_event }"
                            />
                            <p v-if="form.errors.nama_event" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.nama_event }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi Objek Wisata (Optional)</label>
                            <select
                                v-model="form.id_objek"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                :class="{ 'border-red-500': form.errors.id_objek }"
                            >
                                <option :value="null">Pilih Objek Wisata (Jika ada)</option>
                                <option v-for="objek in objekWisatas" :key="objek.id" :value="objek.id">
                                    {{ objek.nama_objek }}
                                </option>
                            </select>
                            <p v-if="form.errors.id_objek" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.id_objek }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.alamat"
                                type="text"
                                placeholder="Jl. Soekarno Hatta..."
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                :class="{ 'border-red-500': form.errors.alamat }"
                            />
                            <p v-if="form.errors.alamat" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.alamat }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 text-emerald-600">Tanggal Mulai <span class="text-emerald-500">*</span></label>
                            <input
                                v-model="form.tanggal_mulai"
                                type="date"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                :class="{ 'border-red-500': form.errors.tanggal_mulai }"
                            />
                            <p v-if="form.errors.tanggal_mulai" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.tanggal_mulai }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 text-rose-600">Tanggal Selesai <span class="text-rose-500">*</span></label>
                            <input
                                v-model="form.tanggal_selesai"
                                type="date"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                                :class="{ 'border-red-500': form.errors.tanggal_selesai }"
                            />
                            <p v-if="form.errors.tanggal_selesai" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.tanggal_selesai }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan/Deskripsi <span class="text-red-500">*</span></label>
                        <textarea
                            v-model="form.keterangan"
                            rows="4"
                            placeholder="Jelaskan detail kegiatan, acara, dsb..."
                            class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary transition-all"
                            :class="{ 'border-red-500': form.errors.keterangan }"
                        ></textarea>
                        <p v-if="form.errors.keterangan" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.keterangan }}</p>
                    </div>
                </div>

                <!-- Foto Event -->
                <div class="bg-white shadow rounded-xl p-6 border border-gray-100 space-y-6">
                    <div class="flex items-center gap-2 border-b border-gray-50 pb-4 mb-2">
                        <div class="w-2 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900">Dokumentasi Foto</h3>
                    </div>

                    <div class="space-y-4">
                        <label class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-gray-50/50 hover:bg-gray-50 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-bold text-primary">Klik untuk upload</span> atau drag and drop</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">JPEG, PNG, JPG (Maks. 2MB)</p>
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
                            <div v-for="(preview, index) in photoPreviews" :key="index" class="relative group aspect-video rounded-xl overflow-hidden shadow-sm border border-gray-100">
                                <img :src="preview" class="h-full w-full object-cover" />
                                <button
                                    type="button"
                                    @click="removePhoto(index)"
                                    class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-all hover:bg-red-600"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div v-if="index === 0" class="absolute bottom-2 left-2 bg-primary/90 text-white text-[10px] px-2 py-0.5 rounded font-black uppercase tracking-wider">
                                    Primary
                                </div>
                            </div>
                        </div>
                        <p v-if="form.errors.fotos" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.fotos }}</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a :href="route('admin.events.index')" class="btn-secondary">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="btn-primary px-8"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                        <span v-else>Simpan Event</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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
