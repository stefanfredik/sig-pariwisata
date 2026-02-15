<template>
    <PublicLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center">
                         <div class="p-3 rounded-lg bg-pink-50 text-pink-600 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Destinasi Favorit</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.favorites_count }}</p>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center">
                        <div class="p-3 rounded-lg bg-blue-50 text-blue-600 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Review Saya</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.reviews_count }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="mb-6 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="activeTab = 'favorites'" :class="[activeTab === 'favorites' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">
                            Destinasi Favorit
                        </button>
                        <button @click="activeTab = 'reviews'" :class="[activeTab === 'reviews' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">
                            Riwayat Review
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div v-if="activeTab === 'favorites'">
                    <div v-if="favorites.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="fav in favorites" :key="fav.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-shadow">
                            <div class="relative h-48">
                                <img :src="fav.objek_wisata?.foto_url || '/images/placeholder.jpg'" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-2 right-2">
                                    <button @click="removeFavorite(fav.objek_wisata_id)" class="p-2 bg-white/90 backdrop-blur rounded-full text-pink-500 hover:bg-pink-50 transition-colors">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 mb-1 line-clamp-1">{{ fav.objek_wisata?.nama_objek }}</h3>
                                <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ fav.objek_wisata?.alamat }}</p>
                                <a :href="route('public.objek-wisata.show', fav.objek_wisata?.slug)" class="block w-full text-center py-2 bg-primary/10 text-primary rounded-lg font-bold text-sm hover:bg-primary hover:text-white transition-colors">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                     <div v-else class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada favorit</h3>
                        <p class="mt-1 text-sm text-gray-500">Jelajahi destinasi wisata dan simpan yang Anda suka.</p>
                        <div class="mt-6">
                            <a :href="route('public.objek-wisata.index')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                Jelajahi Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div v-if="activeTab === 'reviews'">
                    <div v-if="reviews.length > 0" class="space-y-4">
                        <div v-for="review in reviews" :key="review.id" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ review.objek_wisata?.nama_objek }}</h3>
                                    <p class="text-xs text-gray-500">{{ new Date(review.created_at).toLocaleDateString() }}</p>
                                </div>
                                <span :class="['px-2.5 py-0.5 rounded-full text-xs font-medium uppercase tracking-wide', 
                                    review.status === 'approved' ? 'bg-green-100 text-green-800' : 
                                    review.status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800']">
                                    {{ review.status }}
                                </span>
                            </div>
                             <div class="flex items-center mb-3">
                                <template v-for="i in 5" :key="i">
                                    <svg :class="[i <= review.rating ? 'text-yellow-400' : 'text-gray-300', 'w-4 h-4']" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </template>
                            </div>
                            <p class="text-gray-600 text-sm italic">"{{ review.komentar }}"</p>
                        </div>
                    </div>
                     <div v-else class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada review</h3>
                        <p class="mt-1 text-sm text-gray-500">Bagikan pengalaman Anda mengunjungi destinasi wisata.</p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    reviews: Array,
    favorites: Array,
    stats: Object,
});

const activeTab = ref('favorites');

const removeFavorite = (objekId) => {
    router.post(route('favorites.toggle'), { objek_wisata_id: objekId }, {
        preserveScroll: true,
        onSuccess: () => {
            // Toast notification logic can go here
        }
    });
};
</script>
