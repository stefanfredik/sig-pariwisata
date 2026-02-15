<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Badge } from '@/Components/ui/badge'
import { 
    ChevronLeft, 
    User,
    Star,
    Check,
    X,
    Trash2,
    Calendar,
    MessageSquare
} from 'lucide-vue-next'

const props = defineProps<{
    review: any
}>()

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const approveReview = () => {
    if (confirm('Approve this review? It will be visible to the public.')) {
        router.post(route('admin.reviews.approve', props.review.id))
    }
}

const deleteReview = () => {
    if (confirm('Are you sure you want to delete this review?')) {
        router.delete(route('admin.reviews.destroy', props.review.id))
    }
}
</script>

<template>
    <Head title="Review Detail" />

    <AdminLayout>
        <div class="max-w-4xl py-6 px-4">
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.reviews.index')">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Review Detail</h1>
                        <p class="text-sm text-slate-500">Moderating user feedback.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Status Banner -->
                <div v-if="review.status === 'pending'" class="p-4 rounded-xl bg-yellow-50 border border-yellow-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-sm font-medium text-yellow-800">This review is pending approval.</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" size="sm" class="bg-white text-emerald-600 hover:text-emerald-700" @click="approveReview">
                            <Check class="mr-2 h-4 w-4" /> Approve
                        </Button>
                        <Button variant="ghost" size="sm" class="text-red-600 hover:text-red-700" @click="deleteReview">
                            <Trash2 class="mr-2 h-4 w-4" /> Delete
                        </Button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-6">
                        <!-- Content -->
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle class="flex items-center gap-2">
                                    <MessageSquare class="h-5 w-5 text-slate-400" />
                                    Review Content
                                </CardTitle>
                                <div class="flex items-center gap-1 text-yellow-400">
                                    <Star v-for="i in 5" :key="i" class="h-4 w-4" :class="i <= review.rating ? 'fill-current' : 'text-slate-200'" />
                                </div>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ review.judul || 'No Title' }}</h3>
                                <p class="text-slate-600 dark:text-slate-300 leading-relaxed italic">
                                    "{{ review.komentar }}"
                                </p>
                            </CardContent>
                        </Card>

                        <!-- Photos -->
                        <Card v-if="review.fotos?.length">
                            <CardHeader>
                                <CardTitle>Attached Photos</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-2 gap-4">
                                    <div v-for="foto in review.fotos" :key="foto.id" class="rounded-lg overflow-hidden border">
                                        <img :src="`/storage/${foto.path}`" class="w-full h-auto object-cover" />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <div class="space-y-6">
                        <!-- User Info -->
                        <Card>
                            <CardHeader>
                                <CardTitle>User Info</CardTitle>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xl uppercase">
                                        {{ review.user?.name?.charAt(0) || 'U' }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ review.user?.name || 'Unknown' }}</div>
                                        <div class="text-xs text-slate-500">{{ review.user?.email }}</div>
                                    </div>
                                </div>
                                <div class="pt-4 border-t space-y-2">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-slate-500 font-medium tracking-tight">Review Date</span>
                                        <span class="text-slate-700">{{ formatDate(review.created_at) }}</span>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Destination Info -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Destination</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div v-if="review.objek_wisata" class="space-y-3">
                                    <div class="font-medium text-slate-900 dark:text-white">{{ review.objek_wisata.nama_objek }}</div>
                                    <Button variant="link" as-child class="p-0 h-auto text-primary">
                                        <Link :href="route('admin.objek-wisata.show', review.objek_wisata.id)">
                                            View Destination Details
                                        </Link>
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
