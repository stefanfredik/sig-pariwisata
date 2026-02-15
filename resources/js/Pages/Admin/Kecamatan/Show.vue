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
    Building2,
    Users,
    Navigation,
    Info
} from 'lucide-vue-next'

const props = defineProps<{
    kecamatan: any
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
    <Head :title="`Detail Kecamatan - ${kecamatan.nama_kecamatan}`" />

    <AdminLayout>
        <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.kecamatan.index')">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ kecamatan.nama_kecamatan }}</h1>
                        <p class="text-sm text-slate-500">Administrative district details.</p>
                    </div>
                </div>
                <Button as-child variant="outline">
                    <Link :href="route('admin.kecamatan.edit', kecamatan.id)">
                        <Pencil class="mr-2 h-4 w-4" /> Edit District
                    </Link>
                </Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <!-- Destinations List -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Tourism Destinations</CardTitle>
                            <CardDescription>Tourism objects located in {{ kecamatan.nama_kecamatan }}.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div v-if="kecamatan.objek_wisatas?.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="objek in kecamatan.objek_wisatas" :key="objek.id" class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all group">
                                    <div class="font-bold text-slate-900 dark:text-white mb-2 group-hover:text-primary transition-colors">
                                        {{ objek.nama_objek }}
                                    </div>
                                    <div class="flex items-center justify-between mt-auto">
                                        <Badge variant="secondary" class="text-[10px]">{{ objek.kategori?.nama_kategori || 'Destinasi' }}</Badge>
                                        <Link :href="route('admin.objek-wisata.show', objek.id)" class="text-xs font-medium text-primary hover:underline flex items-center gap-1">
                                            View Details
                                            <Navigation class="h-3 w-3" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12 text-slate-500 italic">
                                No tourism destinations registered in this district.
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div class="space-y-8">
                    <!-- General Stats -->
                    <Card>
                        <CardHeader>
                            <CardTitle>District Statistics</CardTitle>
                        </CardHeader>
                        <CardContent class="p-0">
                             <div class="flex items-center gap-4 p-6 border-b">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <LayoutGrid class="h-6 w-6" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Total Objects</p>
                                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ kecamatan.objek_wisatas?.length || 0 }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Profile Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Profile</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-start gap-3">
                                <Building2 class="h-4 w-4 text-slate-400 mt-1" />
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase">Region Name</p>
                                    <p class="text-sm">{{ kecamatan.nama_kecamatan }}</p>
                                </div>
                            </div>
                             <div class="flex items-start gap-3">
                                <Info class="h-4 w-4 text-slate-400 mt-1" />
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase">Kecamatan ID</p>
                                    <p class="text-sm font-mono">{{ kecamatan.id }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
