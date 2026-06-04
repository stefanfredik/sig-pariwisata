<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Badge } from '@/Components/ui/badge'
import { 
    ChevronLeft, 
    MapPin, 
    Pencil,
    LayoutGrid,
    Info,
    Image as ImageIcon,
    Wifi,
    Car,
    Utensils,
    ShoppingBag,
    Bath,
    ShieldCheck,
    Bed,
    Camera,
    TreePine,
    Landmark,
    Trash2
} from 'lucide-vue-next'
import { ref } from 'vue'
import ImageLightbox from "@/Components/ImageLightbox.vue"
import ParkingIcon from "@/Components/Icons/ParkingIcon.vue";
import HelmetIcon from "@/Components/Icons/HelmetIcon.vue";

const props = defineProps<{
    fasilitas: any
}>()

// Lightbox State
const isLightboxOpen = ref(false)
const lightboxImages = ref<string[]>([])
const lightboxIndex = ref(0)

const openLightbox = (images: string[], index: number = 0) => {
    lightboxImages.value = images
    lightboxIndex.value = index
    isLightboxOpen.value = true
}

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}
const getFasilitasIcon = (kategoriFasilitas: string, namaFasilitas: string) => {
    const defaultIcon = MapPin;
    const kat = (kategoriFasilitas || '').toLowerCase();
    const nm = (namaFasilitas || '').toLowerCase();

    // Check name keywords first for specific matches
    if (nm.includes("parkir")) return ParkingIcon;
    if (nm.includes("toilet") || nm.includes("kamar mandi") || nm.includes("wc") || nm.includes("toilet/wc")) return Bath;
    if (nm.includes("mushola") || nm.includes("mesjid") || nm.includes("masjid") || nm.includes("ibadah") || nm.includes("gereja") || nm.includes("vihara") || nm.includes("pura")) return Landmark;
    if (nm.includes("wifi") || nm.includes("internet")) return Wifi;
    if (nm.includes("keamanan") || nm.includes("pos security") || nm.includes("pos satpam") || nm.includes("pos polisi")) return ShieldCheck;
    if (nm.includes("foto") || nm.includes("spot") || nm.includes("selfie")) return Camera;
    if (nm.includes("toko") || nm.includes("warung") || nm.includes("souvenir") || nm.includes("oleh-oleh") || nm.includes("kios") || nm.includes("belanja")) return ShoppingBag;
    if (nm.includes("makan") || nm.includes("restoran") || nm.includes("cafe") || nm.includes("kuliner") || nm.includes("kopi") || nm.includes("food")) return Utensils;
    if (nm.includes("hotel") || nm.includes("penginapan") || nm.includes("villa") || nm.includes("resort") || nm.includes("homestay") || nm.includes("akomodasi")) return Bed;
    if (nm.includes("taman") || nm.includes("camping") || nm.includes("outbound") || nm.includes("alam") || nm.includes("kebun")) return TreePine;
    if (nm.includes("informasi") || nm.includes("info center") || nm.includes("pusat informasi") || nm.includes("guide")) return Info;
    if (nm.includes("sampah") || nm.includes("trash") || nm.includes("bin")) return Trash2;
    if (nm.includes("helm") || nm.includes("pelindung") || nm.includes("safety")) return HelmetIcon;

    // Check category fallback keywords
    if (kat.includes("akomodasi")) return Bed;
    if (kat.includes("kuliner")) return Utensils;
    if (kat.includes("transportasi")) return Car;
    if (kat.includes("parkir")) return ParkingIcon;
    if (kat.includes("sampah")) return Trash2;
    if (kat.includes("helm")) return HelmetIcon;
    if (kat.includes("umum")) return MapPin;

    return defaultIcon;
};
</script>

<template>
    <Head :title="`Detail Fasilitas - ${fasilitas.nama_fasilitas}`" />

    <AdminLayout>
        <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.fasilitas-wisata.index')">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary shadow-sm">
                            <component :is="getFasilitasIcon(fasilitas.kategori_fasilitas, fasilitas.nama_fasilitas)" class="w-6 h-6 text-primary" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ fasilitas.nama_fasilitas }}</h1>
                            <p class="text-sm text-slate-500">Facility detail information.</p>
                        </div>
                    </div>
                </div>
                <Button as-child variant="outline">
                    <Link :href="route('admin.fasilitas-wisata.edit', fasilitas.id)">
                        <Pencil class="mr-2 h-4 w-4" /> Edit Facility
                    </Link>
                </Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <Card>
                        <CardHeader>
                            <CardTitle>Description</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                {{ fasilitas.deskripsi || 'No description available for this facility.' }}
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <ImageIcon class="h-5 w-5 text-slate-400" />
                                Facility Photos
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="fasilitas.fotos?.length" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <button 
                                    v-for="(foto, index) in fasilitas.fotos" 
                                    :key="foto.id"
                                    type="button"
                                    @click="openLightbox(fasilitas.fotos.map(f => `/storage/${f.path}`), index)"
                                    class="relative aspect-square rounded-lg overflow-hidden border border-slate-200 group cursor-zoom-in"
                                >
                                    <img :src="`/storage/${foto.path}`" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                </button>
                            </div>
                            <div v-else class="text-center py-12 bg-slate-50 dark:bg-slate-900/50 rounded-lg border-2 border-dashed border-slate-200">
                                <p class="text-sm text-slate-500">No photos uploaded for this facility.</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div class="space-y-8">
                    <!-- Location Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Location Details</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold text-slate-500 uppercase">Assigned To</p>
                                <div v-if="fasilitas.objek_wisata" class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800">
                                    <div class="w-10 h-10 rounded bg-blue-500/10 flex items-center justify-center text-blue-600">
                                        <Info class="h-5 w-5" />
                                    </div>
                                    <Link :href="route('admin.objek-wisata.show', fasilitas.objek_wisata.id)" class="text-sm font-bold text-blue-700 dark:text-blue-400 hover:underline">
                                        {{ fasilitas.objek_wisata.nama_objek }}
                                    </Link>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <p class="text-xs font-semibold text-slate-500 uppercase">Coordinates</p>
                                <div class="flex items-center gap-2 text-sm font-mono p-2 bg-slate-100 dark:bg-slate-900 rounded">
                                    <MapPin class="h-4 w-4 text-red-500" />
                                    {{ fasilitas.latitude }}, {{ fasilitas.longitude }}
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardContent class="py-4">
                            <div class="flex justify-between text-xs text-slate-500">
                                <span>Facility Added</span>
                                <span>{{ formatDate(fasilitas.created_at) }}</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <ImageLightbox 
            :is-open="isLightboxOpen"
            :images="lightboxImages"
            :initial-index="lightboxIndex"
            @close="isLightboxOpen = false"
        />
    </AdminLayout>
</template>
