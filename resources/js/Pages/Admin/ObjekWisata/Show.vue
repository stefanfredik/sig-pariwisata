<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from "@/Components/ui/card";
import { Badge } from "@/Components/ui/badge";
import {
    ChevronLeft,
    ChevronRight,
    MapPin,
    Calendar,
    Phone,
    Globe,
    Clock,
    DollarSign,
    Pencil,
    Star,
    Plus,
    Trash2,
    Image as ImageIcon,
    Upload,
    Loader2
} from "lucide-vue-next";
import { reactive, ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";
import ImageLightbox from "@/Components/ImageLightbox.vue";

const props = defineProps<{
    objekWisata: any;
}>();

// --- Slider State ---
const currentSlide = ref(0);
const nextSlide = () => {
    if (props.objekWisata.fotos?.length) {
        currentSlide.value = (currentSlide.value + 1) % props.objekWisata.fotos.length;
    }
};
const prevSlide = () => {
    if (props.objekWisata.fotos?.length) {
        currentSlide.value = (currentSlide.value - 1 + props.objekWisata.fotos.length) % props.objekWisata.fotos.length;
    }
};

// --- Photo Upload State ---
const fileInput = ref<HTMLInputElement | null>(null);
const uploadForm = useForm({
    fotos: [] as File[],
});

const triggerUpload = () => fileInput.value?.click();

const handleFileChange = (e: Event) => {
    const files = (e.target as HTMLInputElement).files;
    if (files && files.length > 0) {
        uploadForm.fotos = Array.from(files);
        uploadForm.post(route('admin.objek-wisata.add-photo', props.objekWisata.id), {
            onSuccess: () => {
                uploadForm.reset();
            },
        });
    }
};

// --- Lightbox State ---
const isLightboxOpen = ref(false);
const lightboxImages = ref<string[]>([]);
const lightboxIndex = ref(0);

const openLightbox = (images: string[], index: number = 0) => {
    lightboxImages.value = images;
    lightboxIndex.value = index;
    isLightboxOpen.value = true;
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const confirmModal = reactive({
    show: false,
    id: null as number | null,
    type: "facility" as "facility" | "photo",
    title: "",
    description: "",
    loading: false,
});

const deleteFacility = (id: number) => {
    confirmModal.id = id;
    confirmModal.type = "facility";
    confirmModal.title = "Hapus Fasilitas";
    confirmModal.description =
        "Apakah Anda yakin ingin menghapus fasilitas ini? Tindakan ini tidak dapat dibatalkan.";
    confirmModal.show = true;
};

const confirmDeletePhoto = (id: number) => {
    confirmModal.id = id;
    confirmModal.type = "photo";
    confirmModal.title = "Hapus Foto";
    confirmModal.description =
        "Apakah Anda yakin ingin menghapus foto ini secara permanen?";
    confirmModal.show = true;
};

const setPrimaryPhoto = (fotoId: number) => {
    router.post(
        route("admin.objek-wisata.set-primary-photo", {
            objekId: props.objekWisata.id,
            fotoId,
        }),
    );
};

const handleConfirmDelete = () => {
    if (!confirmModal.id) return;

    confirmModal.loading = true;

    if (confirmModal.type === "facility") {
        router.delete(
            route("admin.fasilitas-wisata.destroy", confirmModal.id),
            {
                onSuccess: () => {
                    confirmModal.show = false;
                    confirmModal.loading = false;
                },
                onError: () => {
                    confirmModal.loading = false;
                },
            },
        );
    } else {
        router.delete(
            route("admin.objek-wisata.delete-photo", confirmModal.id),
            {
                onSuccess: () => {
                    confirmModal.show = false;
                    confirmModal.loading = false;
                    if (currentSlide.value >= (props.objekWisata.fotos?.length || 0)) {
                        currentSlide.value = Math.max(0, (props.objekWisata.fotos?.length || 0) - 1);
                    }
                },
                onError: () => {
                    confirmModal.loading = false;
                },
            },
        );
    }
};
</script>

<template>
    <Head :title="`Detail - ${objekWisata.nama_objek}`" />

    <AdminLayout>
        <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header/Navigation -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.objek-wisata.index')">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                            {{ objekWisata.nama_objek }}
                        </h1>
                        <div class="flex items-center gap-2 mt-1">
                            <Badge variant="outline" class="text-xs">
                                {{ objekWisata.kategori?.nama_kategori || "Umum" }}
                            </Badge>
                            <span class="text-sm text-slate-500">{{ objekWisata.kecamatan?.nama_kecamatan }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button as-child variant="outline" size="sm">
                        <Link :href="route('admin.objek-wisata.edit', objekWisata.id)">
                            <Pencil class="mr-2 h-4 w-4" /> Edit
                        </Link>
                    </Button>
                    <Button as-child size="sm">
                        <a :href="route('public.objek-wisata.show', objekWisata.slug)" target="_blank">
                            <Globe class="mr-2 h-4 w-4" /> Tinjau Publik
                        </a>
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Primary Info -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Photos Gallery Slider -->
                    <Card class="overflow-hidden border-none shadow-lg">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0">
                            <div>
                                <CardTitle>Galeri Wisata</CardTitle>
                                <CardDescription>Visualisasi destinasi wisata.</CardDescription>
                            </div>
                            <div class="flex items-center gap-2">
                                <input 
                                    type="file" 
                                    ref="fileInput" 
                                    class="hidden" 
                                    multiple 
                                    accept="image/*"
                                    @change="handleFileChange"
                                />
                                <Button 
                                    variant="outline" 
                                    size="sm" 
                                    class="h-8 gap-1" 
                                    @click="triggerUpload"
                                    :disabled="uploadForm.processing"
                                >
                                    <Loader2 v-if="uploadForm.processing" class="h-3.5 w-3.5 animate-spin" />
                                    <Upload v-else class="h-3.5 w-3.5" />
                                    <span>Tambah Foto</span>
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent class="p-0 relative group">
                            <div v-if="objekWisata.fotos?.length" class="relative aspect-video bg-slate-900 overflow-hidden">
                                <!-- Main Slide -->
                                <div class="w-full h-full flex items-center justify-center">
                                    <img
                                        :src="`/storage/${objekWisata.fotos[currentSlide].path}`"
                                        :alt="objekWisata.nama_objek"
                                        class="w-full h-full object-cover transition-all duration-500"
                                        @click="openLightbox(objekWisata.fotos.map(f => `/storage/${f.path}`), currentSlide)"
                                    />
                                    
                                    <!-- Slide Info Overlay -->
                                    <div class="absolute bottom-4 left-4 flex gap-2">
                                        <Badge v-if="objekWisata.fotos[currentSlide].is_primary" class="bg-primary hover:bg-primary">Foto Utama</Badge>
                                        <Badge class="bg-black/50 backdrop-blur-md border-none">{{ currentSlide + 1 }} / {{ objekWisata.fotos.length }}</Badge>
                                    </div>

                                    <!-- Slide Actions -->
                                    <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Button 
                                            size="icon" 
                                            variant="secondary" 
                                            class="h-8 w-8 bg-white/90"
                                            @click="setPrimaryPhoto(objekWisata.fotos[currentSlide].id)"
                                            v-if="!objekWisata.fotos[currentSlide].is_primary"
                                        >
                                            <Star class="h-4 w-4" />
                                        </Button>
                                        <Button 
                                            size="icon" 
                                            variant="destructive" 
                                            class="h-8 w-8"
                                            @click="confirmDeletePhoto(objekWisata.fotos[currentSlide].id)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>

                                <!-- Navigation Arrows -->
                                <button 
                                    v-if="objekWisata.fotos.length > 1"
                                    @click="prevSlide"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/20 hover:bg-black/40 text-white transition-all opacity-0 group-hover:opacity-100"
                                >
                                    <ChevronLeft class="h-6 w-6" />
                                </button>
                                <button 
                                    v-if="objekWisata.fotos.length > 1"
                                    @click="nextSlide"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/20 hover:bg-black/40 text-white transition-all opacity-0 group-hover:opacity-100"
                                >
                                    <ChevronRight class="h-6 w-6" />
                                </button>
                            </div>
                            
                            <!-- Thumbnails Strip -->
                            <div v-if="objekWisata.fotos?.length" class="p-4 flex gap-3 overflow-x-auto no-scrollbar bg-slate-50 border-t">
                                <button 
                                    v-for="(foto, index) in objekWisata.fotos" 
                                    :key="foto.id"
                                    @click="currentSlide = index"
                                    class="relative flex-shrink-0 w-24 aspect-video rounded-md overflow-hidden border-2 transition-all"
                                    :class="currentSlide === index ? 'border-primary ring-2 ring-primary/20 scale-105' : 'border-transparent opacity-60 hover:opacity-100'"
                                >
                                    <img :src="`/storage/${foto.path}`" class="w-full h-full object-cover" />
                                </button>
                            </div>

                            <div v-else class="py-20 text-center bg-slate-50">
                                <ImageIcon class="h-12 w-12 text-slate-300 mx-auto mb-3" />
                                <p class="text-sm text-slate-500 font-medium">Belum ada foto yang diunggah.</p>
                                <Button variant="link" size="sm" @click="triggerUpload">Unggah Sekarang</Button>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Improved Description Layout -->
                    <Card class="border-none shadow-lg">
                        <CardHeader class="pb-2">
                            <div class="flex items-center gap-2 text-primary">
                                <div class="p-2 rounded-lg bg-primary/10">
                                    <ImageIcon class="h-5 w-5" />
                                </div>
                                <CardTitle class="text-xl">Tentang Destinasi</CardTitle>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="bg-slate-50/50 rounded-2xl p-6 border border-slate-100">
                                <div
                                    class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed font-medium text-[15px]"
                                    v-html="objekWisata.keterangan || 'Tidak ada deskripsi yang tersedia.'"
                                ></div>
                                
                                <div v-if="objekWisata.daya_tarik_utama" class="mt-8 pt-6 border-t border-slate-200">
                                    <h4 class="text-sm font-black uppercase tracking-widest text-primary mb-3">Daya Tarik Utama</h4>
                                    <p class="text-slate-600 bg-white p-4 rounded-xl border border-slate-100 shadow-sm leading-relaxed">
                                        {{ objekWisata.daya_tarik_utama }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Facilities -->
                    <Card class="border-none shadow-lg">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-4">
                            <div>
                                <CardTitle>Fasilitas & Sarana</CardTitle>
                                <CardDescription>Kelola fasilitas di lokasi.</CardDescription>
                            </div>
                            <Button size="sm" class="h-8 gap-1" as-child>
                                <Link :href="route('admin.fasilitas-wisata.create', { objek_id: objekWisata.id })">
                                    <Plus class="h-3.5 w-3.5" />
                                    <span>Tambah</span>
                                </Link>
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <div v-if="objekWisata.fasilitas?.length" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div
                                    v-for="facility in objekWisata.fasilitas"
                                    :key="facility.id"
                                    class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50/30 group hover:bg-white hover:border-primary/20 hover:shadow-md transition-all"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-primary text-2xl border border-slate-100 shadow-sm">
                                            {{ facility.icon || "📍" }}
                                        </div>
                                        <div>
                                            <span class="text-sm font-black text-slate-900">{{ facility.nama_fasilitas }}</span>
                                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ facility.kategori_fasilitas || "Umum" }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                        <Button variant="ghost" size="icon" class="h-8 w-8 text-slate-400 hover:text-primary hover:bg-primary/5" as-child>
                                            <Link :href="route('admin.fasilitas-wisata.edit', facility.id)">
                                                <Pencil class="h-3.5 w-3.5" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="icon" class="h-8 w-8 text-slate-400 hover:text-red-500 hover:bg-red-50" @click="deleteFacility(facility.id)">
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                <p class="text-sm text-slate-500 font-medium italic">Belum ada fasilitas terdaftar.</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Column -->
                <div class="space-y-8">
                    <!-- Location Detail -->
                    <Card class="border-none shadow-lg">
                        <CardHeader>
                            <CardTitle>Info Lokasi & Harga</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="p-2 rounded-lg bg-red-100 text-red-600">
                                    <MapPin class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat</p>
                                    <p class="text-sm text-slate-700 mt-1 font-medium leading-relaxed">{{ objekWisata.alamat }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl border border-slate-100 space-y-3">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Harga Tiket</p>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center p-2 rounded-lg bg-white border border-slate-50">
                                            <span class="text-xs font-bold text-slate-500">Lokal</span>
                                            <span class="text-sm font-black text-emerald-600">{{ objekWisata.harga_tiket_lokal ? "Rp " + Number(objekWisata.harga_tiket_lokal).toLocaleString("id-ID") : "-" }}</span>
                                        </div>
                                        <div class="flex justify-between items-center p-2 rounded-lg bg-white border border-slate-50">
                                            <span class="text-xs font-bold text-slate-500">Domestik</span>
                                            <span class="text-sm font-black text-emerald-600">{{ objekWisata.harga_tiket_domestik ? "Rp " + Number(objekWisata.harga_tiket_domestik).toLocaleString("id-ID") : "-" }}</span>
                                        </div>
                                        <div class="flex justify-between items-center p-2 rounded-lg bg-white border border-slate-50">
                                            <span class="text-xs font-bold text-slate-500">Asing</span>
                                            <span class="text-sm font-black text-emerald-600">{{ objekWisata.harga_tiket_asing ? "Rp " + Number(objekWisata.harga_tiket_asing).toLocaleString("id-ID") : "-" }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 rounded-2xl border border-slate-100 space-y-3">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Biaya Parkir</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="p-3 rounded-xl bg-white border border-slate-50 text-center">
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">Motor</p>
                                            <p class="text-sm font-black text-primary mt-1">{{ objekWisata.harga_parkir_motor ? "Rp " + Number(objekWisata.harga_parkir_motor).toLocaleString("id-ID") : "-" }}</p>
                                        </div>
                                        <div class="p-3 rounded-xl bg-white border border-slate-50 text-center">
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">Mobil</p>
                                            <p class="text-sm font-black text-primary mt-1">{{ objekWisata.harga_parkir_mobil ? "Rp " + Number(objekWisata.harga_parkir_mobil).toLocaleString("id-ID") : "-" }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Stats -->
                    <Card class="border-none shadow-lg bg-primary text-white">
                        <CardContent class="pt-6">
                            <div class="flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-primary-foreground/70 text-xs font-black uppercase tracking-widest">Rating Objek</p>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-black">{{ objekWisata.rating_avg || "0.0" }}</span>
                                        <Star class="h-5 w-5 fill-yellow-400 text-yellow-400" />
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-black">{{ objekWisata.reviews_count || 0 }}</p>
                                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Ulasan</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <ConfirmDialog
            v-slot:default
            v-model:open="confirmModal.show"
            :title="confirmModal.title"
            :description="confirmModal.description"
            :loading="confirmModal.loading"
            @confirm="handleConfirmDelete"
        />

        <ImageLightbox 
            :is-open="isLightboxOpen"
            :images="lightboxImages"
            :initial-index="lightboxIndex"
            @close="isLightboxOpen = false"
        />
    </AdminLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
