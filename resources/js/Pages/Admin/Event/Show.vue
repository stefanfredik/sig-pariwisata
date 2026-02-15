<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Badge } from '@/Components/ui/badge'
import { 
    ChevronLeft, 
    Calendar,
    MapPin,
    Clock,
    Pencil,
    Globe,
    Info
} from 'lucide-vue-next'

const props = defineProps<{
    event: any
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
    <Head :title="`Detail Event - ${event.nama_event}`" />

    <AdminLayout>
        <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.events.index')">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ event.nama_event }}</h1>
                        <p class="text-sm text-slate-500 mt-1">Special events and festivities.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <Button as-child variant="outline">
                        <Link :href="route('admin.events.edit', event.id)">
                            <Pencil class="mr-2 h-4 w-4" /> Edit
                        </Link>
                    </Button>
                    <Button as-child>
                        <a :href="route('public.events.show', event.slug)" target="_blank">
                            <Globe class="mr-2 h-4 w-4" /> View Public
                        </a>
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <!-- Event Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle>About this Event</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 text-sm leading-relaxed" v-html="event.keterangan || 'No additional information.'">
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Photos -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Event Photos</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="event.fotos?.length" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div v-for="foto in event.fotos" :key="foto.id" class="relative aspect-video rounded-lg overflow-hidden border border-slate-200">
                                    <img :src="`/storage/${foto.path}`" class="w-full h-full object-cover" />
                                </div>
                            </div>
                            <div v-else class="text-center py-10 bg-slate-50 dark:bg-slate-900/50 rounded-lg border-2 border-dashed border-slate-200">
                                <p class="text-sm text-slate-500">No photos available for this event.</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div class="space-y-8">
                    <!-- Key Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Event Info</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-5">
                            <div class="flex items-start gap-3">
                                <Calendar class="h-4 w-4 text-primary mt-1" />
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase">Dates</p>
                                    <p class="text-sm font-medium">{{ formatDate(event.tanggal_mulai) }} - {{ formatDate(event.tanggal_selesai) }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <MapPin class="h-4 w-4 text-primary mt-1" />
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase">Address</p>
                                    <p class="text-sm">{{ event.alamat }}</p>
                                </div>
                            </div>
                            <div v-if="event.objek_wisata" class="pt-4 border-t">
                                <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Located at</p>
                                <div class="flex items-center gap-2">
                                    <Info class="h-4 w-4 text-blue-500" />
                                    <Link :href="route('admin.objek-wisata.show', event.objek_wisata.id)" class="text-sm font-medium text-blue-600 hover:underline">
                                        {{ event.objek_wisata.nama_objek }}
                                    </Link>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- System Info -->
                    <Card>
                        <CardContent class="py-4 space-y-2">
                             <div class="flex justify-between text-xs">
                                <span class="text-slate-500">Event ID</span>
                                <span class="font-mono">{{ event.id }}</span>
                            </div>
                             <div class="flex justify-between text-xs">
                                <span class="text-slate-500">Created</span>
                                <span>{{ formatDate(event.created_at) }}</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
