<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Badge } from '@/Components/ui/badge'
import { 
    ChevronLeft, 
    MapPin, 
    Calendar, 
    Phone, 
    Globe, 
    Clock, 
    DollarSign,
    Pencil,
    Star
} from 'lucide-vue-next'

const props = defineProps<{
    objekWisata: any
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
    <Head :title="`Detail - ${objekWisata.nama_objek}`" />

    <AdminLayout>
        <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header/Navigation -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.objek-wisata.index')">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ objekWisata.nama_objek }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <Badge variant="outline" class="text-xs">
                                {{ objekWisata.kategori?.nama_kategori || 'General' }}
                            </Badge>
                            <span class="text-sm text-slate-500">{{ objekWisata.kecamatan?.nama_kecamatan }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <Button as-child variant="outline">
                        <Link :href="route('admin.objek-wisata.edit', objekWisata.id)">
                            <Pencil class="mr-2 h-4 w-4" /> Edit
                        </Link>
                    </Button>
                    <Button as-child>
                        <a :href="route('public.objek-wisata.show', objekWisata.slug)" target="_blank">
                            <Globe class="mr-2 h-4 w-4" /> View Public
                        </a>
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Primary Info -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Photos Gallery -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Photo Gallery</CardTitle>
                            <CardDescription>Visuals of this destination.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div v-if="objekWisata.fotos?.length" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div 
                                    v-for="foto in objekWisata.fotos" 
                                    :key="foto.id"
                                    class="relative aspect-video rounded-lg overflow-hidden border border-slate-200"
                                    :class="{ 'ring-2 ring-primary border-transparent': foto.is_primary }"
                                >
                                    <img 
                                        :src="`/storage/${foto.path}`" 
                                        :alt="objekWisata.nama_objek"
                                        class="w-full h-full object-cover"
                                    />
                                    <Badge v-if="foto.is_primary" class="absolute top-2 left-2 text-[10px] h-5">Primary</Badge>
                                </div>
                            </div>
                            <div v-else class="py-12 bg-slate-50 dark:bg-slate-900/50 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-800 text-center">
                                <p class="text-sm text-slate-500">No photos uploaded for this destination.</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Description -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Description</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 text-sm leading-relaxed" v-html="objekWisata.deskripsi || 'No description provided.'">
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Facilities -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Facilities & Amenities</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="objekWisata.fasilitas?.length" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div v-for="facility in objekWisata.fasilitas" :key="facility.id" class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20">
                                    <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary text-lg">
                                        {{ facility.icon || '📍' }}
                                    </div>
                                    <span class="text-sm font-medium">{{ facility.nama_fasilitas }}</span>
                                </div>
                            </div>
                            <div v-else class="text-center py-6 text-slate-500 text-sm italic">
                                No specific facilities listed.
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Column: Metadata & Stats -->
                <div class="space-y-8">
                    <!-- Location Detail -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Location Info</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-start gap-3">
                                <MapPin class="h-4 w-4 text-slate-400 mt-0.5" />
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Address</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ objekWisata.alamat }}</p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Coordinates</p>
                                <code class="text-[11px] bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded">
                                    {{ objekWisata.latitude }}, {{ objekWisata.longitude }}
                                </code>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Stats / Ratings -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Ratings & Stats</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-500">Average Rating</span>
                                <div class="flex items-center gap-1 text-yellow-400">
                                    <Star class="h-4 w-4 fill-current" />
                                    <span class="text-lg font-bold">{{ objekWisata.rating_avg || '0.0' }}</span>
                                    <span class="text-xs text-slate-400 ml-1">({{ objekWisata.reviews_count || 0 }} reviews)</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- System Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>System Information</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium">Created at</span>
                                <span class="text-slate-700 dark:text-slate-300">{{ formatDate(objekWisata.created_at) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium">Last updated</span>
                                <span class="text-slate-700 dark:text-slate-300">{{ formatDate(objekWisata.updated_at) }}</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
