<template>
    <PublicLayout>
        <Head>
            <title>Destinasi Wisata - Manggarai Barat</title>
            <meta name="description" content="Temukan berbagai destinasi wisata menarik di Kabupaten Manggarai Barat. Alam, Budaya, dan Petualangan menanti Anda." />
        </Head>
        <!-- Page Header -->
        <section class="relative pt-32 pb-20 bg-gray-900 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-b from-black/60 to-gray-900 z-10"></div>
                <img 
                    src="https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                    class="w-full h-full object-cover"
                >
            </div>

            <div class="relative z-20 max-w-7xl mx-auto px-4 text-center space-y-4">
                <div class="flex items-center justify-center gap-2">
                    <div class="w-8 h-1 bg-primary rounded-full"></div>
                    <span class="text-xs font-black text-primary uppercase tracking-[0.2em]">Destinasi</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-white px-2 py-2">Explorasi <span class="text-primary italic">Keajaiban</span></h1>
                <p class="text-gray-400 max-w-xl mx-auto font-medium">
                    Temukan destinasi wisata impian Anda di seluruh penjuru Manggarai Barat. Dari puncak bukit hingga kedalaman dasar laut.
                </p>
            </div>
        </section>

        <!-- Search & Filter Section -->
        <section class="relative z-30 -mt-12 mb-12">
            <div class="max-w-5xl mx-auto px-4">
                <div class="bg-white rounded-[2.5rem] shadow-2xl p-4 md:p-6 border border-gray-100">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-12 lg:col-span-5 relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                v-model="filterForm.search"
                                type="text"
                                placeholder="Cari nama destinasi..."
                                class="block w-full pl-12 rounded-2xl border-gray-50 bg-gray-50/50 py-4 focus:ring-primary focus:border-primary font-bold text-gray-900 placeholder-gray-400"
                            />
                        </div>
                        <div class="md:col-span-12 lg:col-span-4">
                            <select
                                v-model="filterForm.id_kecamatan"
                                class="block w-full rounded-2xl border-gray-50 bg-gray-50/50 py-4 focus:ring-primary focus:border-primary font-bold text-gray-900"
                            >
                                <option value="">Semua Kecamatan</option>
                                <option v-for="kec in kecamatans" :key="kec.id" :value="kec.id">
                                    {{ kec.nama_kecamatan }}
                                </option>
                            </select>
                        </div>
                        <div class="md:col-span-12 lg:col-span-3 flex gap-2">
                             <button type="submit" class="flex-1 bg-primary text-white p-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:scale-105 transition-transform shadow-lg shadow-primary/20">
                                Cari
                            </button>
                            <button @click="resetFilters" type="button" class="bg-gray-100 text-gray-400 p-4 rounded-2xl hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Results Grid -->
        <section class="pb-24">
            <div class="max-w-7xl mx-auto px-4">
                <div v-if="objekWisatas.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                    <div v-for="objek in objekWisatas.data" :key="objek.id" class="group bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-primary/10 transition-all duration-500 hover:-translate-y-2 flex flex-col border border-gray-100">
                        <div class="relative aspect-[4/5] overflow-hidden">
                             <img 
                                :src="objek.fotos.length > 0 ? '/storage/' + objek.fotos[0].path : 'https://images.unsplash.com/photo-1544911845-1f34a3eb46b1?q=80&w=1000'" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                :alt="objek.nama_objek"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                            
                            <!-- Rating Badge -->
                            <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-white/90 backdrop-blur-md shadow-lg flex items-center gap-1.5 border border-white/20">
                                <svg class="w-3.5 h-3.5 text-yellow-500 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-xs font-black text-gray-900">{{ objek.rating_avg ? Number(objek.rating_avg).toFixed(1) : 'NEW' }}</span>
                            </div>

                            <div class="absolute bottom-6 left-6 right-6 text-white space-y-1">
                                <div class="text-xs font-bold text-primary italic">{{ objek.kecamatan.nama_kecamatan }}</div>
                                <h3 class="text-xl font-black leading-tight">{{ objek.nama_objek }}</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed font-medium">
                                {{ objek.keterangan }}
                            </p>
                            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ objek.alamat || 'Manggarai Barat' }}</div>
                                <a :href="route('public.objek-wisata.show', objek.slug)" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary transition-colors">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="py-32 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Tidak Menemukan Hasil</h3>
                    <p class="text-gray-400 font-medium">Coba gunakan kata kunci lain atau ubah filter kecamatan.</p>
                </div>

                <!-- Pagination -->
                <div v-if="objekWisatas.links.length > 3" class="mt-20 flex justify-center">
                    <Pagination :links="objekWisatas.links" />
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
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
    router.get(route('public.objek-wisata.index'), filterForm, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    filterForm.search = '';
    filterForm.id_kecamatan = '';
    applyFilters();
};
</script>
