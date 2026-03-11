<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import Pagination from '@/Components/Pagination.vue'
import { 
    Map as MapIcon, 
    Mountain, 
    Eye,
    Calendar, 
    MessageSquare, 
    Users, 
    AlertCircle
} from 'lucide-vue-next'
// @ts-ignore
import VisitorChart from '@/Components/Dashboard/VisitorChart.vue'

defineProps<{
    stats: {
        kecamatan: number
        objek_wisata: number
        event: number
        user: number
        jumlah_kunjungan: number
        review_pending: number
        review_approved: number
    }
    visitorTrends: Object
    objekWisataKunjungan: Array<any>
    umkmTerdekatTable: any
    dayaTarikPerKecamatanTable: any
    recentReviews: Array<any>
    upcomingEvents: Array<any>
}>()
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Dashboard</h2>
                    <p class="text-muted-foreground">Selamat datang di panel admin SIG Wisata Manggarai Barat</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 p-3 pb-0">
                        <CardTitle class="text-sm font-medium">Kecamatan</CardTitle>
                        <MapIcon class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="p-3 pt-1">
                        <div class="text-2xl font-bold">{{ stats.kecamatan }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 p-3 pb-0">
                        <CardTitle class="text-sm font-medium">Objek Wisata</CardTitle>
                        <Mountain class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="p-3 pt-1">
                        <div class="text-2xl font-bold">{{ stats.objek_wisata }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 p-3 pb-0">
                        <CardTitle class="text-sm font-medium">Events</CardTitle>
                        <Calendar class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="p-3 pt-1">
                        <div class="text-2xl font-bold">{{ stats.event }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 p-3 pb-0">
                        <CardTitle class="text-sm font-medium">Total User</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="p-3 pt-1">
                        <div class="text-2xl font-bold">{{ stats.user }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 p-3 pb-0">
                        <CardTitle class="text-sm font-medium">Kunjungan</CardTitle>
                        <Eye class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="p-3 pt-1">
                        <div class="text-2xl font-bold">{{ stats.jumlah_kunjungan.toLocaleString('id-ID') }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 p-3 pb-0">
                        <CardTitle class="text-sm font-medium">Approved</CardTitle>
                        <MessageSquare class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="p-3 pt-1">
                        <div class="text-2xl font-bold">{{ stats.review_approved }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 p-3 pb-0">
                        <CardTitle class="text-sm font-medium">Pending</CardTitle>
                        <AlertCircle class="h-4 w-4 text-destructive" />
                    </CardHeader>
                    <CardContent class="p-3 pt-1">
                        <div class="text-2xl font-bold text-destructive">{{ stats.review_pending }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Charts -->
            <div>
                <Card>
                    <CardHeader class="p-4 pb-2">
                        <CardTitle>Trend Pengunjung</CardTitle>
                    </CardHeader>
                    <CardContent class="pl-2">
                        <VisitorChart :data="visitorTrends" />
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <Card>
                    <CardHeader class="p-4 pb-2">
                        <CardTitle>Jumlah Kunjungan per Objek Wisata</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-muted-foreground">
                                        <th class="py-3 pr-4 font-medium">No</th>
                                        <th class="py-3 pr-4 font-medium">Objek Wisata</th>
                                        <th class="py-3 pr-4 font-medium">Kecamatan</th>
                                        <th class="py-3 text-right font-medium">Jumlah Kunjungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(objek, index) in objekWisataKunjungan"
                                        :key="objek.id"
                                        class="border-b last:border-0 hover:bg-slate-50 transition-colors"
                                    >
                                        <td class="py-2 pr-4">{{ index + 1 }}</td>
                                        <td class="py-2 pr-4 font-bold">{{ objek.nama_objek }}</td>
                                        <td class="py-2 pr-4 text-[10px] font-black uppercase tracking-tighter text-muted-foreground">
                                            {{ objek.kecamatan?.nama_kecamatan ?? '-' }}
                                        </td>
                                        <td class="py-2 text-right font-black">
                                            {{ Number(objek.view_count || 0).toLocaleString('id-ID') }}
                                        </td>
                                    </tr>
                                    <tr v-if="objekWisataKunjungan.length === 0">
                                        <td colspan="4" class="py-6 text-center text-muted-foreground">
                                            Belum ada data kunjungan objek wisata.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="p-4 pb-2">
                        <CardTitle>UMKM Terdekat Berdasarkan Objek Wisata</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-muted-foreground">
                                        <th class="py-3 pr-4 font-medium">No</th>
                                        <th class="py-3 pr-4 font-medium">Objek Wisata</th>
                                        <th class="py-3 pr-4 font-medium">UMKM Terdekat</th>
                                        <th class="py-3 text-right font-medium">Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(row, index) in umkmTerdekatTable.data"
                                        :key="row.id_objek"
                                        class="border-b last:border-0 hover:bg-slate-50 transition-colors"
                                    >
                                        <td class="py-2 pr-4">
                                            {{ (umkmTerdekatTable.from ?? 1) + index }}
                                        </td>
                                        <td class="py-2 pr-4 font-bold">
                                            <Link :href="route('admin.objek-wisata.show', row.id_objek)">
                                                {{ row.nama_objek }}
                                            </Link>
                                        </td>
                                        <td class="py-2 pr-4">
                                            <Link
                                                v-if="row.umkm"
                                                :href="route('admin.umkm.show', row.umkm.id)"
                                                class="font-bold text-primary"
                                            >
                                                {{ row.umkm.nama_umkm }}
                                            </Link>
                                            <span v-else class="text-muted-foreground">-</span>
                                        </td>
                                        <td class="py-2 text-right text-[10px] font-black uppercase tracking-tighter text-muted-foreground">
                                            {{ row.umkm?.kategori ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr v-if="umkmTerdekatTable.data.length === 0">
                                        <td colspan="4" class="py-6 text-center text-muted-foreground">
                                            Belum ada data objek wisata / UMKM.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="umkmTerdekatTable.links?.length > 3">
                            <Pagination :links="umkmTerdekatTable.links" />
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader class="p-4 pb-2">
                    <CardTitle>Daya Tarik Wisata per Kecamatan</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left text-muted-foreground">
                                    <th class="py-3 pr-4 font-medium">No</th>
                                    <th class="py-3 pr-4 font-medium">Kecamatan</th>
                                    <th class="py-3 pr-4 font-medium">Jumlah Objek Wisata</th>
                                    <th class="py-3 font-medium">Daya Tarik Wisata</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(row, index) in dayaTarikPerKecamatanTable.data"
                                    :key="row.id_kecamatan"
                                    class="border-b last:border-0 align-top hover:bg-slate-50 transition-colors"
                                >
                                    <td class="py-2 pr-4">
                                        {{ (dayaTarikPerKecamatanTable.from ?? 1) + index }}
                                    </td>
                                    <td class="py-2 pr-4 font-bold">{{ row.nama_kecamatan }}</td>
                                    <td class="py-2 pr-4 font-medium">{{ Number(row.total_objek || 0).toLocaleString('id-ID') }}</td>
                                    <td class="py-2">
                                        <div v-if="row.daya_tarik_items?.length" class="space-y-2">
                                            <div
                                                v-for="item in row.daya_tarik_items"
                                                :key="item.id_objek"
                                                class="text-sm"
                                            >
                                                <Link :href="route('admin.objek-wisata.show', item.id_objek)" class="font-bold text-primary">
                                                    {{ item.nama_objek }}
                                                </Link>
                                                <p class="text-muted-foreground">{{ item.daya_tarik_utama }}</p>
                                            </div>
                                        </div>
                                        <span v-else class="text-muted-foreground">Belum ada data daya tarik.</span>
                                    </td>
                                </tr>
                                <tr v-if="dayaTarikPerKecamatanTable.data.length === 0">
                                    <td colspan="4" class="py-6 text-center text-muted-foreground">
                                        Belum ada data kecamatan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="dayaTarikPerKecamatanTable.links?.length > 3">
                        <Pagination :links="dayaTarikPerKecamatanTable.links" />
                    </div>
                </CardContent>
            </Card>

            <!-- Recent Info -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2">
                 <Card>
                    <CardHeader class="p-4 pb-2">
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
                                Belum ada ulasan yang menunggu persetujuan.
                             </div>
                         </div>
                         <div class="mt-4 pt-2">
                             <Button variant="outline" class="w-full" as-child>
                                <Link :href="route('admin.reviews.index')">Lihat Semua Ulasan</Link>
                             </Button>
                         </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="p-4 pb-2">
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
        </div>
    </AdminLayout>
</template>
