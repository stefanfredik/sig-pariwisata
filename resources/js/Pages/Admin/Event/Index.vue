<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kelola Event</h2>
                    <p class="mt-1 text-sm text-gray-600">Daftar agenda kegiatan pariwisata</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a :href="route('admin.events.create')" class="btn-primary group">
                        <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Event
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-xl p-4 border border-gray-100">
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            v-model="filterForm.search"
                            type="text"
                            placeholder="Cari nama event..."
                            class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm transition-all bg-gray-50/50 focus:bg-white"
                        />
                    </div>
                    <div>
                        <select
                            v-model="filterForm.id_objek"
                            class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm transition-all bg-gray-50/50 focus:bg-white"
                        >
                            <option value="">Semua Lokasi</option>
                            <option v-for="objek in objekWisatas" :key="objek.id" :value="objek.id">
                                {{ objek.nama_objek }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <select
                            v-model="filterForm.status"
                            class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm transition-all bg-gray-50/50 focus:bg-white"
                        >
                            <option value="">Semua Status</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="past">Past</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1 justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a :href="route('admin.events.index')" class="btn-secondary whitespace-nowrap">
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
                            <th class="px-6 py-3 text-left text-gray-500">Nama Event</th>
                            <th class="px-6 py-3 text-left text-gray-500">Waktu</th>
                            <th class="px-6 py-3 text-left text-gray-500">Lokasi</th>
                            <th class="px-6 py-3 text-right text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="event in events.data" :key="event.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="font-bold text-gray-900">{{ event.nama_event }}</div>
                                <div class="text-[11px] text-gray-400 font-mono">{{ event.slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                    </svg>
                                    {{ formatDate(event.tanggal_mulai) }} - {{ formatDate(event.tanggal_selesai) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span v-if="event.objek_wisata" class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] border border-blue-100 uppercase font-semibold">
                                    {{ event.objek_wisata.nama_objek }}
                                </span>
                                <span v-else class="text-gray-400 italic text-xs">Lokasi Umum</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a :href="route('admin.events.edit', event.id)" class="inline-flex items-center text-primary hover:text-primary/70 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <button @click="confirmDelete(event)" class="inline-flex items-center text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="events.data.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                    </svg>
                                    <p class="text-sm">Tidak ada data event ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="events.links.length > 3" class="bg-white px-4 py-3 border-t border-gray-50 sm:px-6">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ events.from || 0 }}</span> 
                                sampai <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ events.to || 0 }}</span> 
                                dari <span class="font-bold text-gray-900 border-b border-gray-100 px-1">{{ events.total || 0 }}</span> data
                            </p>
                        </div>
                        <Pagination :links="events.links" />
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
    events: Object,
    objekWisatas: Array,
    filters: Object,
});

const filterForm = reactive({
    search: props.filters.search || '',
    id_objek: props.filters.id_objek || '',
    status: props.filters.status || '',
});

const applyFilters = () => {
    router.get(route('admin.events.index'), filterForm, {
        preserveState: true,
        replace: true,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

const confirmDelete = (event) => {
    if (confirm(`Yakin ingin menghapus event "${event.nama_event}"?`)) {
        router.delete(route('admin.events.destroy', event.id));
    }
};
</script>
