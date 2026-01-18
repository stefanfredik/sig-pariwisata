<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kelola Fasilitas Wisata</h2>
                    <p class="mt-1 text-sm text-gray-600">Daftar sarana dan prasarana di objek wisata</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a :href="route('admin.fasilitas-wisata.create')" class="btn-primary group">
                        <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Fasilitas
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-xl p-4 border border-gray-100">
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            v-model="filterForm.search"
                            type="text"
                            placeholder="Cari nama fasilitas..."
                            class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm transition-all bg-gray-50/50 focus:bg-white"
                        />
                    </div>
                    <div>
                        <select
                            v-model="filterForm.id_objek"
                            class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm transition-all bg-gray-50/50 focus:bg-white"
                        >
                            <option value="">Semua Objek Wisata</option>
                            <option v-for="objek in objekWisatas" :key="objek.id" :value="objek.id">
                                {{ objek.nama_objek }}
                            </option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1 justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a :href="route('admin.fasilitas-wisata.index')" class="btn-secondary whitespace-nowrap">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 uppercase tracking-wider text-[10px] font-bold">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-500">Nama Fasilitas</th>
                            <th class="px-6 py-3 text-left text-gray-500">Objek Wisata</th>
                            <th class="px-6 py-3 text-left text-gray-500">Koordinat</th>
                            <th class="px-6 py-3 text-right text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in fasilitas.data" :key="item.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center border border-gray-100 text-primary">
                                       <span class="text-xs">{{ item.icon || '📍' }}</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ item.nama_fasilitas }}</div>
                                        <div class="text-[10px] text-gray-400 truncate max-w-[200px]">{{ item.deskripsi || 'Tidak ada deskripsi' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-[10px] border border-emerald-100 uppercase font-semibold">
                                    {{ item.objek_wisata.nama_objek }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-400 text-xs">
                                {{ item.latitude }}, {{ item.longitude }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a :href="route('admin.fasilitas-wisata.edit', item.id)" class="inline-flex items-center text-primary hover:text-primary/70 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <button @click="confirmDelete(item)" class="inline-flex items-center text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="fasilitas.data.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="text-sm">Tidak ada data fasilitas ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="fasilitas.links.length > 3" class="bg-white px-4 py-3 border-t border-gray-50 sm:px-6">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ fasilitas.from || 0 }}</span> 
                                sampai <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ fasilitas.to || 0 }}</span> 
                                dari <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ fasilitas.total || 0 }}</span> data
                            </p>
                        </div>
                        <Pagination :links="fasilitas.links" />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    fasilitas: Object,
    objekWisatas: Array,
    filters: Object,
});

const filterForm = reactive({
    search: props.filters.search || '',
    id_objek: props.filters.id_objek || '',
});

const applyFilters = () => {
    router.get(route('admin.fasilitas-wisata.index'), filterForm, {
        preserveState: true,
        replace: true,
    });
};

const confirmDelete = (item) => {
    if (confirm(`Yakin ingin menghapus fasilitas "${item.nama_fasilitas}"?`)) {
        router.delete(route('admin.fasilitas-wisata.destroy', item.id));
    }
};
</script>
