<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Badge } from '@/Components/ui/badge'
import { 
    ChevronLeft, 
    MapPin, 
    Phone, 
    Pencil,
} from 'lucide-vue-next'

const props = defineProps<{
    umkm: any
}>()

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}
</script>

<template>
    <Head :title="`Detail UMKM - ${umkm.nama_umkm}`" />

    <AdminLayout>
        <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header/Navigation -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.umkm.index')">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ umkm.nama_umkm }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <Badge variant="outline" class="text-xs bg-blue-50 text-blue-700 border-blue-200">
                                {{ umkm.kategori }}
                            </Badge>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <Button as-child variant="outline">
                        <Link :href="route('admin.umkm.edit', umkm.id)">
                            <Pencil class="mr-2 h-4 w-4" /> Edit
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Photos & Description -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Photos Gallery -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Galeri Foto</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="umkm.fotos?.length" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div 
                                    v-for="foto in umkm.fotos" 
                                    :key="foto.id"
                                    class="relative aspect-video rounded-lg overflow-hidden border border-slate-200"
                                    :class="{ 'ring-2 ring-primary border-transparent': foto.is_primary }"
                                >
                                    <img 
                                        :src="`/storage/${foto.path}`" 
                                        :alt="umkm.nama_umkm"
                                        class="w-full h-full object-cover"
                                    />
                                    <Badge v-if="foto.is_primary" class="absolute top-2 left-2 text-[10px] h-5">Utama</Badge>
                                </div>
                            </div>
                            <div v-else class="py-12 bg-slate-50 dark:bg-slate-900/50 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-800 text-center">
                                <p class="text-sm text-slate-500">Tidak ada foto untuk UMKM ini.</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Description -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Keterangan</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed whitespace-pre-line">
                                {{ umkm.keterangan || 'Tidak ada keterangan tambahan.' }}
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Column: Location & Info -->
                <div class="space-y-8">
                    <!-- Location Detail -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Informasi Lokasi</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-start gap-3">
                                <MapPin class="h-4 w-4 text-slate-400 mt-0.5" />
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Alamat</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ umkm.alamat }}</p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-center">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Koordinat</p>
                                <code class="text-[11px] bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded">
                                    {{ umkm.latitude }}, {{ umkm.longitude }}
                                </code>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- System Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Informasi Sistem</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium">Dibuat pada</span>
                                <span class="text-slate-700 dark:text-slate-300">{{ formatDate(umkm.created_at) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium">Update terakhir</span>
                                <span class="text-slate-700 dark:text-slate-300">{{ formatDate(umkm.updated_at) }}</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
