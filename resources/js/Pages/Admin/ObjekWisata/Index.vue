<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kelola Objek Wisata</h2>
                    <p class="mt-1 text-sm text-gray-600">Daftar destinasi wisata di Kabupaten Manggarai Barat</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a :href="route('admin.objek-wisata.create')" class="btn-primary group">
                        <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Objek Wisata
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg p-4 border border-gray-100">
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            v-model="filterForm.search"
                            type="text"
                            placeholder="Cari nama objek..."
                            class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm"
                        />
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <select
                            v-model="filterForm.id_kecamatan"
                            class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm"
                        >
                            <option value="">Semua Kecamatan</option>
                            <option v-for="kec in kecamatans" :key="kec.id" :value="kec.id">
                                {{ kec.nama_kecamatan }}
                            </option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a :href="route('admin.objek-wisata.index')" class="btn-secondary">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 uppercase tracking-wider text-[10px] font-bold">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-500">Foto</th>
                            <th class="px-6 py-3 text-left text-gray-500">Nama Objek</th>
                            <th class="px-6 py-3 text-left text-gray-500">Kecamatan</th>
                            <th class="px-6 py-3 text-left text-gray-500 text-center">Rating</th>
                            <th class="px-6 py-3 text-right text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="objek in objekWisatas.data" :key="objek.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative h-12 w-20 group">
                                    <img 
                                        v-if="objek.primary_foto" 
                                        :src="'/storage/' + objek.primary_foto.path" 
                                        class="h-full w-full object-cover rounded shadow-sm group-hover:scale-105 transition-transform duration-200"
                                        alt="Thumbnail"
                                    />
                                    <div v-else class="h-full w-full bg-gray-50 rounded border border-dashed border-gray-300 flex items-center justify-center text-[10px] text-gray-400 italic">
                                        No Image
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="font-bold text-gray-900">{{ objek.nama_objek }}</div>
                                <div class="text-[11px] text-gray-400 font-mono">{{ objek.slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-xs border border-gray-200 uppercase font-semibold">
                                    {{ objek.kecamatan.nama_kecamatan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-50 border border-yellow-200">
                                    <span class="text-yellow-500 text-xs">★</span>
                                    <span class="ml-1 text-xs font-bold text-yellow-700">{{ Number(objek.rating_avg).toFixed(1) }}</span>
                                    <span class="ml-1 text-[10px] text-gray-400">({{ objek.review_count }})</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a :href="route('admin.objek-wisata.edit', objek.id)" class="inline-flex items-center text-primary hover:text-primary/70 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <button @click="confirmDelete(objek)" class="inline-flex items-center text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="objekWisatas.data.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data objek wisata.
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="objekWisatas.links.length > 3" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ objekWisatas.from || 0 }}</span> 
                                sampai <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ objekWisatas.to || 0 }}</span> 
                                dari <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ objekWisatas.total || 0 }}</span> data
                            </p>
                        </div>
                        <Pagination :links="objekWisatas.links" />
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
    objekWisatas: Object,
    kecamatans: Array,
    filters: Object,
});

const filterForm = reactive({
    search: props.filters.search || '',
    id_kecamatan: props.filters.id_kecamatan || '',
});

const applyFilters = () => {
    router.get(route('admin.objek-wisata.index'), filterForm, {
        preserveState: true,
        replace: true,
    });
};

const confirmDelete = (objek) => {
    if (confirm(`Yakin ingin menghapus objek wisata "${objek.nama_objek}"? Semua foto terkait akan dihapus.`)) {
        router.delete(route('admin.objek-wisata.destroy', objek.id));
    }
};
</script>
