<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { 
    Map as MapIcon, 
    Mountain, 
    Calendar, 
    MessageSquare, 
    Users, 
    AlertCircle, 
    ExternalLink 
} from 'lucide-vue-next'
import VisitorChart from '@/Components/Dashboard/VisitorChart.vue'
import TopDestinationsChart from '@/Components/Dashboard/TopDestinationsChart.vue'

defineProps<{
    stats: {
        kecamatan: number
        objek_wisata: number
        event: number
        user: number
        review_pending: number
        review_approved: number
    }
    visitorTrends: Object
    topDestinations: Array<any>
    recentReviews: Array<any>
    upcomingEvents: Array<any>
}>()
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Dashboard</h2>
                <p class="text-muted-foreground">Selamat datang di panel admin SIG Wisata Manggarai Barat</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Kecamatan</CardTitle>
                        <MapIcon class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.kecamatan }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Objek Wisata</CardTitle>
                        <Mountain class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.objek_wisata }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Events</CardTitle>
                        <Calendar class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.event }}</div>
                    </CardContent>
                </Card>
                 <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total User</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.user }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Review Approved</CardTitle>
                        <MessageSquare class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.review_approved }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Review Pending</CardTitle>
                        <AlertCircle class="h-4 w-4 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-destructive">{{ stats.review_pending }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Charts -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <Card class="col-span-4">
                    <CardHeader>
                        <CardTitle>Trend Pengunjung</CardTitle>
                    </CardHeader>
                    <CardContent class="pl-2">
                        <VisitorChart :data="visitorTrends" />
                    </CardContent>
                </Card>
                <Card class="col-span-3">
                    <CardHeader>
                        <CardTitle>Destinasi Populer</CardTitle>
                    </CardHeader>
                    <CardContent>
                         <TopDestinationsChart :destinations="topDestinations" />
                    </CardContent>
                </Card>
            </div>

            <!-- Recent Info -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2">
                 <Card>
                    <CardHeader>
                        <CardTitle>Review Terbaru</CardTitle>
                    </CardHeader>
                    <CardContent>
                         <div class="space-y-4">
                            <div v-for="review in recentReviews" :key="review.id" class="flex items-start gap-4 text-sm border-b pb-4 last:border-0 last:pb-0">
                                <div class="grid gap-1">
                                    <div class="font-semibold">{{ review.user.name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ review.objek_wisata.nama_objek }}</div>
                                    <div class="line-clamp-2 text-muted-foreground italic">"{{ review.komentar }}"</div>
                                </div>
                            </div>
                             <div v-if="recentReviews.length === 0" class="text-center text-sm text-muted-foreground py-4">
                                Belum ada review pendng.
                             </div>
                         </div>
                         <div class="mt-4 pt-2">
                             <Button variant="outline" class="w-full" as-child>
                                <Link :href="route('admin.reviews.index')">Lihat Semua Review</Link>
                             </Button>
                         </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Event Mendatang</CardTitle>
                    </CardHeader>
                     <CardContent>
                         <div class="space-y-4">
                            <div v-for="event in upcomingEvents" :key="event.id" class="flex items-center gap-4 text-sm border-b pb-4 last:border-0 last:pb-0">
                                <div class="flex h-12 w-12 flex-col items-center justify-center rounded-lg border bg-muted">
                                    <span class="text-[10px] font-bold uppercase">{{ new Date(event.tanggal_mulai).toLocaleString('default', { month: 'short' }) }}</span>
                                    <span class="text-lg font-bold">{{ new Date(event.tanggal_mulai).getDate() }}</span>
                                </div>
                                <div class="grid gap-1">
                                    <div class="font-semibold">{{ event.nama_event }}</div>
                                    <div class="text-xs text-muted-foreground">{{ event.alamat }}</div>
                                </div>
                            </div>
                             <div v-if="upcomingEvents.length === 0" class="text-center text-sm text-muted-foreground py-4">
                                Belum ada event mendatang.
                             </div>
                         </div>
                         <div class="mt-4 pt-2">
                             <Button variant="outline" class="w-full" as-child>
                                <Link :href="route('admin.events.index')">Lihat Semua Event</Link>
                             </Button>
                         </div>
                    </CardContent>
                </Card>
            </div>
            
             <!-- Quick Actions -->
             <Card>
                <CardHeader>
                     <CardTitle>Akses Cepat</CardTitle>
                </CardHeader>
                <CardContent class="flex gap-4 flex-wrap">
                    <Button as-child>
                         <Link :href="route('admin.kecamatan.create')">
                            <MapIcon class="mr-2 h-4 w-4"/> Tambah Kecamatan
                         </Link>
                    </Button>
                    <Button variant="secondary" as-child>
                         <Link :href="route('admin.objek-wisata.create')">
                            <Mountain class="mr-2 h-4 w-4"/> Tambah Objek Wisata
                         </Link>
                    </Button>
                    <Button variant="secondary" as-child>
                         <Link :href="route('admin.events.create')">
                            <Calendar class="mr-2 h-4 w-4"/> Tambah Event
                         </Link>
                    </Button>
                </CardContent>
             </Card>
        </div>
    </AdminLayout>
</template>
