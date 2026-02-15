<script setup lang="ts">
import { ref, watch, reactive, computed } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import DataTableToolbar from '@/Components/Admin/Table/DataTableToolbar.vue'
import { Button } from '@/Components/ui/button'
import { Check, X, Trash2, Star, User, Eye } from 'lucide-vue-next'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import { debounce } from 'lodash'
import { cn } from '@/lib/utils'

const props = defineProps<{
    reviews: {
        data: Array<any>
        links: Array<any>
        from: number
        to: number
        total: number
        current_page: number
        per_page: number
    }
    filters: {
        search?: string
        status?: string
        sort_field?: string
        sort_direction?: string
    }
}>()

const params = reactive({
    search: props.filters.search || '',
    status: props.filters.status || '',
    sort_field: props.filters.sort_field || 'created_at',
    sort_direction: props.filters.sort_direction || 'desc',
})

const sortOptions = [
    { label: 'Newest', value: 'created_at,desc' },
    { label: 'Oldest', value: 'created_at,asc' },
    { label: 'Rating (High-Low)', value: 'rating,desc' },
    { label: 'Rating (Low-High)', value: 'rating,asc' },
]

const currentSort = computed(() => `${params.sort_field},${params.sort_direction}`)

watch(
    () => params.search,
    debounce(() => {
        updateParams()
    }, 500)
)

const updateParams = () => {
    router.get(
        route('admin.reviews.index'),
        params,
        { preserveState: true, replace: true }
    )
}

const handleSort = (value: string) => {
    const [field, direction] = value.split(',')
    params.sort_field = field
    params.sort_direction = direction
    updateParams()
}

const filterStatus = (status: string) => {
    params.status = status
    updateParams()
}

const resetFilters = () => {
    params.search = ''
    params.status = ''
    params.sort_field = 'created_at'
    params.sort_direction = 'desc'
    updateParams()
}

const deleteForm = useForm({});
const confirmModal = reactive({
    show: false,
    id: null as number | null,
    title: '',
    description: '',
    confirmText: 'Lanjutkan',
    variant: 'destructive' as 'destructive' | 'primary',
    loading: false,
    action: '' as 'delete' | 'approve'
});

const approveReview = (id: number) => {
    confirmModal.id = id;
    confirmModal.title = 'Setujui Ulasan';
    confirmModal.description = 'Apakah Anda yakin ingin menyetujui ulasan ini? Ulasan yang disetujui akan tampil di halaman publik.';
    confirmModal.confirmText = 'Setujui';
    confirmModal.variant = 'primary';
    confirmModal.action = 'approve';
    confirmModal.show = true;
};

const confirmDelete = (id: number) => {
    confirmModal.id = id;
    confirmModal.title = 'Hapus Ulasan';
    confirmModal.description = 'Apakah Anda yakin ingin menghapus ulasan ini? Data ini tidak dapat dikembalikan.';
    confirmModal.confirmText = 'Hapus';
    confirmModal.variant = 'destructive';
    confirmModal.action = 'delete';
    confirmModal.show = true;
};

const handleConfirm = () => {
    if (!confirmModal.id) return;
    
    confirmModal.loading = true;
    
    if (confirmModal.action === 'approve') {
        router.post(route('admin.reviews.approve', confirmModal.id), {}, {
            onSuccess: () => {
                confirmModal.show = false;
                confirmModal.loading = false;
            },
            onError: () => {
                confirmModal.loading = false;
            }
        });
    } else {
        deleteForm.delete(route('admin.reviews.destroy', confirmModal.id), {
            onSuccess: () => {
                confirmModal.show = false;
                confirmModal.loading = false;
            },
            onError: () => {
                confirmModal.loading = false;
            }
        });
    }
};
</script>

<template>
    <Head title="Moderasi Review" />

    <AdminLayout>
        <div class="max-w-7xl py-6">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Review Moderation</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage and moderate user reviews.</p>
                </div>
            </div>

            <!-- Main Card Container -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                
                <DataTableToolbar 
                    :search="params.search" 
                    :sort-options="sortOptions"
                    :current-sort="currentSort"
                    @update:search="params.search = $event" 
                    @update:sort="handleSort"
                    @reset="resetFilters"
                    placeholder="Search reviews..."
                >
                     <template #filters>
                        <div class="flex items-center bg-slate-100 dark:bg-slate-900 p-1 rounded-lg">
                            <button 
                                @click="filterStatus('')" 
                                :class="cn('px-3 py-1 rounded-md text-xs font-medium transition-all', !params.status ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100')"
                            >
                                All
                            </button>
                            <button 
                                @click="filterStatus('pending')" 
                                :class="cn('px-3 py-1 rounded-md text-xs font-medium transition-all', params.status === 'pending' ? 'bg-white dark:bg-slate-800 text-yellow-600 dark:text-yellow-500 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100')"
                            >
                                Pending
                            </button>
                            <button 
                                @click="filterStatus('approved')" 
                                :class="cn('px-3 py-1 rounded-md text-xs font-medium transition-all', params.status === 'approved' ? 'bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-500 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100')"
                            >
                                Approved
                            </button>
                        </div>
                    </template>
                </DataTableToolbar>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">User Info</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Destination</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rating & Content</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                             <tr v-for="review in reviews.data" :key="review.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-sm uppercase">
                                            {{ review.user?.name?.charAt(0) || 'U' }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ review.user?.name || 'Unknown' }}</div>
                                            <div class="text-xs text-slate-400">{{ review.user?.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        {{ review.objek_wisata?.nama_objek }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-md">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-1 text-yellow-400">
                                            <Star v-for="i in 5" :key="i" class="h-3 w-3" :class="i <= review.rating ? 'fill-current' : 'text-slate-200'" />
                                            <span class="text-xs font-bold text-slate-400 ml-1">({{ review.rating }})</span>
                                        </div>
                                        <p v-if="review.judul" class="text-sm font-bold text-slate-800 dark:text-white">{{ review.judul }}</p>
                                        <p class="text-xs text-slate-500 line-clamp-2">{{ review.komentar }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="review.status === 'pending'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-2 animate-pulse"></span> Pending
                                    </span>
                                    <span v-else-if="review.status === 'approved'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Approved
                                    </span>
                                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Rejected
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link 
                                            :href="route('admin.reviews.show', review.id)" 
                                            class="p-1.5 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors"
                                            title="Detail"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </Link>
                                        <button 
                                            v-if="review.status === 'pending'"
                                            @click="approveReview(review.id)" 
                                            class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                            title="Approve"
                                        >
                                            <Check class="h-4 w-4" />
                                        </button>
                                        <button 
                                            @click="confirmDelete(review.id)" 
                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Delete"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                             </tr>
                              <tr v-if="reviews.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                    No reviews found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="reviews.links.length > 3">
                     <Pagination :links="reviews.links" />
                </div>
            </div>
        </div>

        <ConfirmDialog 
            v-model:open="confirmModal.show"
            :title="confirmModal.title"
            :description="confirmModal.description"
            :confirm-text="confirmModal.confirmText"
            :variant="confirmModal.variant"
            :loading="confirmModal.loading"
            @confirm="handleConfirm"
        />
    </AdminLayout>
</template>
