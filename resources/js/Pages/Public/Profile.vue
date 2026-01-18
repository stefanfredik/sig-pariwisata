<template>
    <PublicLayout :transparent="false">
        <div class="h-20 bg-gray-900"></div>

        <section class="py-16 bg-white min-h-[60vh]">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    
                    <!-- Left: Profile Sidebar -->
                    <div class="lg:col-span-4 space-y-8">
                        <div class="bg-gray-50 rounded-[3rem] p-10 border border-gray-100 shadow-sm text-center relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 blur-[60px] -mr-16 -mt-16 group-hover:bg-primary/20 transition-all duration-700"></div>
                            
                            <div class="relative mb-6 inline-block">
                                <div class="w-24 h-24 rounded-[2rem] bg-primary flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-primary/30 mx-auto">
                                    {{ user.name.charAt(0) }}
                                </div>
                                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 rounded-xl border-4 border-white flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" /></svg>
                                </div>
                            </div>

                            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ user.name }}</h2>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mt-1">{{ user.role }}</p>

                            <div class="grid grid-cols-2 gap-4 mt-10">
                                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                                    <div class="text-xs font-black text-primary uppercase tracking-widest mb-1">{{ reviews.total }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase">Reviews</div>
                                </div>
                                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                                    <div class="text-xs font-black text-emerald-500 uppercase tracking-widest mb-1">{{ formatDateShort(user.created_at) }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase">Member</div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Settings Form -->
                        <div class="bg-white rounded-[3rem] p-10 border border-gray-100 shadow-sm space-y-6">
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em] mb-4">Pengaturan Akun</h3>
                            <form @submit.prevent="updateProfile" class="space-y-4">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2 mb-2 block">Nama Lengkap</label>
                                    <input v-model="form.name" type="text" class="w-full bg-gray-50 border-transparent rounded-xl px-4 py-3 font-bold text-sm focus:bg-white focus:ring-primary focus:border-primary transition-all">
                                    <p v-if="form.errors.name" class="mt-1 text-[10px] text-red-500 font-bold px-2">{{ form.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2 mb-2 block">Username</label>
                                    <input v-model="form.username" type="text" class="w-full bg-gray-50 border-transparent rounded-xl px-4 py-3 font-bold text-sm focus:bg-white focus:ring-primary focus:border-primary transition-all">
                                    <p v-if="form.errors.username" class="mt-1 text-[10px] text-red-500 font-bold px-2">{{ form.errors.username }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2 mb-2 block">Email</label>
                                    <input v-model="form.email" type="email" class="w-full bg-gray-50 border-transparent rounded-xl px-4 py-3 font-bold text-sm focus:bg-white focus:ring-primary focus:border-primary transition-all">
                                    <p v-if="form.errors.email" class="mt-1 text-[10px] text-red-500 font-bold px-2">{{ form.errors.email }}</p>
                                </div>
                                <button type="submit" :disabled="form.processing" class="w-full bg-gray-900 text-white py-4 rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-gray-900/10 hover:scale-[1.02] transition-all disabled:opacity-50">
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right: Reviews List -->
                    <div class="lg:col-span-8 space-y-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-1 bg-primary rounded-full"></div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]">Riwayat Ulasan Anda</h3>
                            </div>
                        </div>

                        <div v-if="reviews.data.length > 0" class="space-y-6">
                            <div v-for="review in reviews.data" :key="review.id" class="p-8 bg-white border border-gray-100 rounded-[2.5rem] shadow-sm hover:shadow-md transition-all group">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    <div class="flex gap-4">
                                        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-100 flex-shrink-0">
                                            <img v-if="review.objek_wisata.fotos && review.objek_wisata.fotos.length > 0" :src="'/storage/' + review.objek_wisata.fotos[0].path" class="w-full h-full object-cover">
                                            <div v-else class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" /></svg>
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <h4 class="font-black text-gray-900 group-hover:text-primary transition-colors">{{ review.objek_wisata.nama_objek }}</h4>
                                            <div class="flex items-center gap-2">
                                                <div class="flex text-yellow-400">
                                                    <svg v-for="i in 5" :key="i" class="w-3 h-3 fill-current" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-200'" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </div>
                                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ formatDate(review.created_at) }}</span>
                                            </div>
                                            <!-- Status Tag -->
                                            <span v-if="review.status === 'pending'" class="inline-flex items-center text-[8px] font-black uppercase tracking-widest bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded-full border border-yellow-100">Pending Moderasi</span>
                                            <span v-else-if="review.status === 'approved'" class="inline-flex items-center text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full border border-emerald-100 text-xs">Aktif</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a :href="route('public.objek-wisata.show', review.objek_wisata.slug)" class="p-2 rounded-xl bg-gray-50 text-gray-400 hover:text-primary hover:bg-primary/5 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" /></svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-4">
                                     <p class="text-sm font-black text-gray-900 mb-1" v-if="review.judul">{{ review.judul }}</p>
                                     <p class="text-gray-600 text-sm font-medium leading-relaxed">"{{ review.komentar }}"</p>
                                </div>
                                <div v-if="review.fotos.length > 0" class="flex gap-2 mt-4">
                                    <img v-for="foto in review.fotos" :key="foto.id" :src="'/storage/' + foto.path" class="w-12 h-12 object-cover rounded-xl border border-gray-100">
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="pt-6">
                                <Pagination :links="reviews.links" />
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="py-24 text-center bg-gray-50 rounded-[3rem] border border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.518 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke-width="2" /></svg>
                            </div>
                            <p class="text-gray-400 font-black uppercase tracking-widest text-sm">Anda belum memberikan ulasan apapun.</p>
                            <a :href="route('public.objek-wisata.index')" class="text-primary font-black uppercase tracking-widest text-[10px] mt-4 inline-block hover:underline italic">Jelajahi destinasi &raquo;</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    user: Object,
    reviews: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    username: props.user.username,
});

const updateProfile = () => {
    form.put(route('public.profile.update'), {
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const formatDateShort = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        month: 'short',
        year: 'numeric'
    });
};
</script>
