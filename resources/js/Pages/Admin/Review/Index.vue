<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Moderasi Review</h2>
                    <p class="mt-1 text-sm text-gray-600">Kelola dan moderasi review dari pengunjung</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-xl p-4 border border-gray-100">
                <div class="flex gap-4">
                    <button 
                        @click="filterStatus('')" 
                        :class="[
                            'px-4 py-2 rounded-xl text-sm font-bold transition-all border',
                            !statusFilter ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50'
                        ]"
                    >
                        Semua
                    </button>
                    <button 
                        @click="filterStatus('pending')" 
                        :class="[
                            'px-4 py-2 rounded-xl text-sm font-bold transition-all border',
                            statusFilter === 'pending' ? 'bg-yellow-500 text-white border-yellow-500 shadow-lg shadow-yellow-500/20' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50'
                        ]"
                    >
                        Pending
                    </button>
                    <button 
                        @click="filterStatus('approved')" 
                        :class="[
                            'px-4 py-2 rounded-xl text-sm font-bold transition-all border',
                            statusFilter === 'approved' ? 'bg-emerald-500 text-white border-emerald-500 shadow-lg shadow-emerald-500/20' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50'
                        ]"
                    >
                        Disetujui
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 uppercase tracking-wider text-[10px] font-bold">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-500">Info User</th>
                            <th class="px-6 py-3 text-left text-gray-500">Destinasi</th>
                            <th class="px-6 py-3 text-left text-gray-500">Rating & Review</th>
                            <th class="px-6 py-3 text-left text-gray-500">Status</th>
                            <th class="px-6 py-3 text-right text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="review in reviews.data" :key="review.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-primary font-black uppercase text-xs">
                                        {{ review.user.name.charAt(0) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-black text-gray-900">{{ review.user.name }}</div>
                                        <div class="text-[10px] font-bold text-gray-400">{{ review.user.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900">{{ review.objek_wisata.nama_objek }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-1">
                                        <div class="flex text-yellow-400">
                                            <template v-for="i in 5" :key="i">
                                                <svg v-if="i <= review.rating" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                <svg v-else class="w-4 h-4 text-gray-200 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </template>
                                        </div>
                                        <span class="text-xs font-black text-gray-400">({{ review.rating }}/5)</span>
                                    </div>
                                    <div class="text-sm font-black text-gray-900" v-if="review.judul">{{ review.judul }}</div>
                                    <p class="text-xs text-gray-500 line-clamp-2 max-w-md">{{ review.komentar }}</p>
                                    <div v-if="review.fotos.length > 0" class="flex gap-2 mt-2">
                                        <img v-for="foto in review.fotos" :key="foto.id" :src="'/storage/' + foto.path" class="w-10 h-10 object-cover rounded-lg border border-gray-100">
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="review.status === 'pending'" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-yellow-50 text-yellow-600 border border-yellow-100">
                                    <span class="w-1 h-1 rounded-full bg-yellow-500 mr-2 animate-pulse"></span>
                                    Pending
                                </span>
                                <span v-else-if="review.status === 'approved'" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500 mr-2"></span>
                                    Disetujui
                                </span>
                                <span v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">
                                    Ditolak
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <button 
                                    v-if="review.status === 'pending'"
                                    @click="approveReview(review.id)" 
                                    class="text-emerald-600 hover:text-emerald-800 transition-colors font-black uppercase text-[10px] tracking-widest bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 active:scale-95"
                                >
                                    Approve
                                </button>
                                <button 
                                    @click="confirmDelete(review.id)" 
                                    class="text-red-600 hover:text-red-800 transition-colors font-black uppercase text-[10px] tracking-widest bg-red-50 px-3 py-1.5 rounded-lg border border-red-100 active:scale-95"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="reviews.data.length === 0">
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.518 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-400 font-bold">Tidak ada review untuk ditampilkan.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="reviews.links.length > 3" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <Pagination :links="reviews.links" />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    reviews: Object,
    filters: Object,
});

const statusFilter = ref(props.filters?.status || '');

const filterStatus = (status) => {
    statusFilter.value = status;
    router.get(route('admin.reviews.index'), { status: status }, {
        preserveState: true,
    });
};

const approveReview = (id) => {
    if (confirm('Yakin ingin menyetujui review ini? Setelah disetujui, review akan muncul di halaman publik.')) {
        router.post(route('admin.reviews.approve', id));
    }
};

const confirmDelete = (id) => {
    if (confirm('Yakin ingin menghapus review ini? Tindakan ini tidak dapat dibatalkan.')) {
        router.delete(route('admin.reviews.destroy', id));
    }
};
</script>
