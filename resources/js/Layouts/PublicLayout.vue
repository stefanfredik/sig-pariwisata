<template>
    <div class="min-h-screen bg-gray-50 flex flex-col font-sans text-gray-900 overflow-x-hidden">
        <!-- Navbar -->
        <nav 
            class="fixed w-full z-50 transition-all duration-300" 
            :class="[
                (isScrolled || forceSolid)
                    ? 'bg-white/95 backdrop-blur-md shadow-lg py-3'
                    : 'bg-black/5 backdrop-blur-[2px] py-5'
            ]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-sm">
                <a :href="route('home')" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform duration-300">
                         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L16 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                </a>
                
                <!-- Search Bar -->
                <div class="hidden lg:flex flex-1 max-w-md mx-8 relative">
                    <form @submit.prevent="submitSearch" class="w-full relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" /></svg>
                        </div>
                        <input 
                            v-model="searchQuery"
                            @input="handleSearch"
                            type="text" 
                            placeholder="Cari Destinasi"
                            class="w-full border-transparent rounded-2xl py-2.5 pl-11 pr-4 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                            :class="[
                                (isScrolled || forceSolid) 
                                    ? 'bg-gray-100 text-gray-900 placeholder-gray-400' 
                                    : 'bg-white/10 text-white placeholder-white/50 focus:text-gray-900'
                            ]"
                        >
                        
                        <!-- Search Results Dropdown -->
                        <div v-if="searchResults.length > 0" class="absolute top-12 left-0 w-full bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-[110] overflow-hidden">
                            <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Hasil Pencarian</p>
                            </div>
                            <a 
                                v-for="result in searchResults" 
                                :key="result.url"
                                :href="result.url"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-all border-b border-gray-50 last:border-0"
                            >
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary">
                                    <svg v-if="result.type === 'Destinasi'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" /></svg>
                                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" /></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-gray-900 truncate">{{ result.title }}</p>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">{{ result.type }}</p>
                                </div>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8 font-bold">
                    <a :href="route('public.objek-wisata.index')" :class="[(isScrolled || forceSolid) ? 'text-gray-600 hover:text-primary' : 'text-white hover:text-white', 'transition-colors'] + ' text-sm tracking-wide uppercase font-black px-2 py-2'">
                        Objek Wisata
                    </a>
                    <a :href="route('public.events.index')" :class="[(isScrolled || forceSolid) ? 'text-gray-600 hover:text-primary' : 'text-white hover:text-white', 'transition-colors'] + ' text-sm tracking-wide uppercase font-black px-2 py-2'">
                        Event
                    </a>
                    <a :href="route('public.map')" :class="[(isScrolled || forceSolid) ? 'text-gray-600 hover:text-primary' : 'text-white hover:text-white', 'transition-colors'] + ' text-sm tracking-wide uppercase font-black px-2 py-2'">
                        Peta
                    </a>
                    
                    <div v-if="!$page.props.auth.user" class="flex gap-4 items-center">
                        <a 
                            :href="route('login')" 
                            :class="[(isScrolled || forceSolid) ? 'text-gray-600 hover:text-primary' : 'text-white hover:text-white', 'transition-colors'] + ' text-sm tracking-wide uppercase font-black px-2 py-2'"
                        >
                            Masuk
                        </a>
                        <a 
                            :href="route('register')" 
                            class="btn-primary-small !rounded-full px-6 py-2 shadow-xl shadow-primary/20"
                        >
                            Daftar
                        </a>
                    </div>
                    <div v-else class="flex gap-4 items-center relative group/user">
                        <button class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/20 hover:bg-white/20 transition-all">
                            <div class="w-8 h-8 rounded-xl bg-primary flex items-center justify-center text-white font-black text-xs uppercase">
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                            <span :class="[(isScrolled || forceSolid) ? 'text-gray-900' : 'text-white font-bold']">{{ $page.props.auth.user.name.split(' ')[0] }}</span>
                            <svg class="w-4 h-4" :class="[(isScrolled || forceSolid) ? 'text-gray-900' : 'text-white']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" /></svg>
                        </button>
                        
                        <!-- Dropdown -->
                        <div class="absolute top-12 right-0 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover/user:opacity-100 group-hover/user:visible transition-all duration-300 transform translate-y-2 group-hover/user:translate-y-0 py-2 z-[100]">
                            <div class="px-4 py-2 border-b border-gray-50 mb-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Akun Saya</p>
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>
                            <a v-if="$page.props.auth.user.role === 'admin'" :href="route('admin.dashboard')" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 hover:bg-gray-50 hover:text-primary transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" stroke-width="2" /></svg>
                                Dashboard Admin
                            </a>
                            <a :href="route('public.profile')" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 hover:bg-gray-50 hover:text-primary transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" /></svg>
                                Profil Saya
                            </a>
                            <button @click="logout" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" /></svg>
                                Keluar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="md:hidden p-2" :class="[(isScrolled || forceSolid) ? 'text-gray-900' : 'text-white']">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <transition 
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-4"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-4"
            >
                <div v-if="isMobileMenuOpen" class="md:hidden bg-white shadow-2xl absolute w-full top-full py-4 border-t border-gray-100">
                    <div class="px-4 space-y-2">
                        <a :href="route('public.objek-wisata.index')" class="block px-4 py-3 text-gray-700 font-bold hover:bg-gray-50 rounded-xl">Objek Wisata</a>
                        <a :href="route('public.events.index')" class="block px-4 py-3 text-gray-700 font-bold hover:bg-gray-50 rounded-xl">Event</a>
                        <a :href="route('public.map')" class="block px-4 py-3 text-gray-700 font-bold hover:bg-gray-50 rounded-xl">Peta</a>
                        <hr class="border-gray-100 my-2" />
                        <div v-if="!$page.props.auth.user" class="space-y-2">
                            <a :href="route('login')" class="block px-4 py-3 text-gray-700 font-bold hover:bg-gray-50 rounded-xl">Masuk</a>
                            <a :href="route('register')" class="block px-4 py-3 text-primary font-bold hover:bg-gray-50 rounded-xl">Daftar</a>
                        </div>
                        <div v-else class="space-y-2">
                            <a :href="route('public.profile')" class="block px-4 py-3 text-gray-700 font-bold hover:bg-gray-50 rounded-xl">Profil Saya</a>
                            <a v-if="$page.props.auth.user.role === 'admin'" :href="route('admin.dashboard')" class="block px-4 py-3 text-gray-700 font-bold hover:bg-gray-50 rounded-xl">Dashboard Admin</a>
                            <button @click="logout" class="w-full text-left px-4 py-3 text-red-500 font-bold hover:bg-red-50 rounded-xl">Keluar</button>
                        </div>
                    </div>
                </div>
            </transition>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="col-span-1 md:col-span-2 space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-2xl shadow-primary/40">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L16 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            <span class="text-3xl font-black tracking-tight">SIG <span class="text-primary italic">Wisata</span></span>
                        </div>
                        <p class="text-gray-400 max-w-md leading-relaxed">
                            Sistem Informasi Geografis Pemetaan Lokasi Wisata Kabupaten Manggarai Barat. Temukan destinasi liburan terbaik, agenda seru, dan fasilitas lengkap dalam satu genggaman.
                        </p>
                    </div>

                    <div class="space-y-4 font-bold">
                        <h4 class="text-sm font-black uppercase tracking-widest text-primary/80">Menu Cepat</h4>
                        <ul class="space-y-3 text-gray-400">
                            <li><a :href="route('public.objek-wisata.index')" class="hover:text-white transition-colors">Semua Objek</a></li>
                            <li><a :href="route('public.events.index')" class="hover:text-white transition-colors">Agenda Event</a></li>
                            <li><a :href="route('public.map')" class="hover:text-white transition-colors">Peta Interaktif</a></li>
                            <li><a :href="route('home')" class="hover:text-white transition-colors">Home</a></li>
                        </ul>
                    </div>

                    <div class="space-y-4 font-bold">
                        <h4 class="text-sm font-black uppercase tracking-widest text-primary/80">Kontak Kami</h4>
                        <ul class="space-y-3 text-gray-400">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Labuan Bajo, Manggarai Barat, Nusa Tenggara Timur</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>info@sig-wisata.go.id</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import debounce from 'lodash/debounce';

const props = defineProps({
    forceSolid: {
        type: Boolean,
        default: false
    }
});

const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);

const submitSearch = () => {
    if (searchQuery.value.trim().length > 0) {
        router.get(route('public.objek-wisata.index'), { search: searchQuery.value });
    }
};

const handleSearch = debounce(async () => {
    if (searchQuery.value.length < 2) {
        searchResults.value = [];
        return;
    }

    try {
        const response = await axios.get(route('public.search.suggest'), {
            params: { q: searchQuery.value }
        });
        searchResults.value = response.data;
    } catch (error) {
        console.error('Search error:', error);
    }
}, 300);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

const logout = () => {
    router.post(route('logout'));
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('click', (e) => {
        if (!e.target.closest('.group')) {
            searchResults.value = [];
        }
    });
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style>
@reference "../../css/app.css";

.btn-primary-small {
    @apply bg-primary text-white font-black text-[10px] uppercase tracking-wider rounded-lg hover:shadow-lg hover:shadow-primary/20 transition-all active:scale-95;
}
</style>
