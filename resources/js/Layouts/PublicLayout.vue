<template>
    <div class="min-h-screen bg-gray-50 dark:bg-slate-950 flex flex-col font-sans text-gray-900 dark:text-slate-100 overflow-x-hidden">
        <!-- Navbar -->
        <nav 
            class="fixed w-full z-50 transition-all duration-300" 
            :class="[
                (isScrolled || forceSolid)
                    ? 'bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 shadow-sm py-3'
                    : 'bg-black/5 dark:bg-black/20 backdrop-blur-[2px] py-5'
            ]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-sm">
                <a :href="route('home')" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
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
                            class="w-full border-transparent rounded-2xl py-2.5 pl-11 pr-4 text-sm font-bold focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all focus:text-gray-900 dark:focus:text-slate-100"
                            :class="[
                                (isScrolled || forceSolid) 
                                    ? 'bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500' 
                                    : 'bg-white/10 text-white placeholder-white/50'
                            ]"
                        >
                        
                        <!-- Search Results Dropdown -->
                        <div v-if="searchResults.length > 0" class="absolute top-12 left-0 w-full bg-white dark:bg-slate-900 rounded-xl shadow-md border border-gray-100 dark:border-slate-800 py-2 z-[110] overflow-hidden">
                            <div class="px-4 py-2 border-b border-gray-50 dark:border-slate-800 mb-1">
                                <p class="text-[9px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Hasil Pencarian</p>
                            </div>
                            <a 
                                v-for="result in searchResults" 
                                :key="result.url"
                                :href="result.url"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all border-b border-gray-50 dark:border-slate-800 last:border-0"
                            >
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary">
                                    <svg v-if="result.type === 'Destinasi'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" /></svg>
                                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" /></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-gray-900 dark:text-slate-100 truncate">{{ result.title }}</p>
                                    <p class="text-[9px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-tighter">{{ result.type }}</p>
                                </div>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8 font-bold">
                    <a :href="route('public.objek-wisata.index')" :class="[(isScrolled || forceSolid) ? 'text-gray-600 hover:text-primary dark:text-slate-300 dark:hover:text-white' : 'text-white hover:text-white/80', 'transition-colors', 'text-sm tracking-wide uppercase font-black px-2 py-2']">
                        Objek Wisata
                    </a>
                    <a :href="route('public.events.index')" :class="[(isScrolled || forceSolid) ? 'text-gray-600 hover:text-primary dark:text-slate-300 dark:hover:text-white' : 'text-white hover:text-white/80', 'transition-colors', 'text-sm tracking-wide uppercase font-black px-2 py-2']">
                        Event
                    </a>
                    <a :href="route('public.map')" :class="[(isScrolled || forceSolid) ? 'text-gray-600 hover:text-primary dark:text-slate-300 dark:hover:text-white' : 'text-white hover:text-white/80', 'transition-colors', 'text-sm tracking-wide uppercase font-black px-2 py-2']">
                        Peta
                    </a>

                    <!-- Theme Toggle Button -->
                    <button 
                        @click="toggleTheme" 
                        class="p-2 rounded-xl border transition-all cursor-pointer flex items-center justify-center"
                        :class="[(isScrolled || forceSolid) ? 'text-gray-700 dark:text-slate-200 border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800' : 'text-white border-white/20 bg-white/10 hover:bg-white/20']"
                        title="Toggle Tema"
                    >
                        <svg v-if="isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    
                    <div v-if="!$page.props.auth.user" class="flex gap-4 items-center">
                        <a 
                            :href="route('login')" 
                            :class="[(isScrolled || forceSolid) ? 'text-gray-600 hover:text-primary dark:text-slate-300 dark:hover:text-white' : 'text-white hover:text-white/80', 'transition-colors', 'text-sm tracking-wide uppercase font-black px-2 py-2']"
                        >
                            Masuk
                        </a>
                        <a 
                            :href="route('register')" 
                            class="btn-primary-small !rounded-full px-6 py-2"
                        >
                            Daftar
                        </a>
                    </div>
                    <div v-else class="flex gap-4 items-center relative group/user">
                        <button class="flex items-center gap-2.5 px-3 py-2 rounded-xl border transition-all" :class="[(isScrolled || forceSolid) ? 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 hover:border-gray-300 dark:hover:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-800' : 'bg-white/10 border-white/20 hover:bg-white/20']">
                            <div class="w-7 h-7 rounded-lg bg-primary flex items-center justify-center text-white font-black text-xs uppercase">
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                            <span class="text-sm font-bold" :class="[(isScrolled || forceSolid) ? 'text-gray-700 dark:text-slate-200' : 'text-white']">{{ $page.props.auth.user.name.split(' ')[0] }}</span>
                            <svg class="w-3.5 h-3.5" :class="[(isScrolled || forceSolid) ? 'text-gray-400 dark:text-slate-400' : 'text-white/70']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" /></svg>
                        </button>
                        
                        <!-- Dropdown -->
                        <div class="absolute top-11 right-0 w-52 bg-white dark:bg-slate-900 rounded-xl shadow-md border border-gray-100 dark:border-slate-800 opacity-0 invisible group-hover/user:opacity-100 group-hover/user:visible transition-all duration-200 transform translate-y-1 group-hover/user:translate-y-0 py-1.5 z-[100]">
                            <div class="px-4 py-2 border-b border-gray-50 dark:border-slate-800 mb-2">
                                <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Akun Saya</p>
                                <p class="text-xs font-bold text-gray-900 dark:text-slate-200 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>
                            <a v-if="$page.props.auth.user.role === 'admin'" :href="route('admin.dashboard')" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-primary transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" stroke-width="2" /></svg>
                                Dashboard Admin
                            </a>
                            <a :href="route('public.profile')" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-primary transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" /></svg>
                                Profil Saya
                            </a>
                            <button @click="logout" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all rounded-b-xl cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" /></svg>
                                Keluar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Actions (Theme Toggle + Menu Button) -->
                <div class="flex items-center gap-2 md:hidden">
                    <button 
                        @click="toggleTheme" 
                        class="p-2 rounded-xl border transition-all cursor-pointer flex items-center justify-center"
                        :class="[(isScrolled || forceSolid) ? 'text-gray-700 dark:text-slate-200 border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900' : 'text-white border-white/20 bg-white/10']"
                        title="Toggle Tema"
                    >
                        <svg v-if="isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="p-2" :class="[(isScrolled || forceSolid) ? 'text-gray-900 dark:text-slate-100' : 'text-white']">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
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
                <div v-if="isMobileMenuOpen" class="md:hidden bg-white dark:bg-slate-900 shadow-sm border-t border-gray-100 dark:border-slate-800 absolute w-full top-full py-3">
                    <div class="px-4 space-y-2">
                        <a :href="route('public.objek-wisata.index')" class="block px-4 py-3 text-gray-700 dark:text-slate-300 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 rounded-xl">Objek Wisata</a>
                        <a :href="route('public.events.index')" class="block px-4 py-3 text-gray-700 dark:text-slate-300 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 rounded-xl">Event</a>
                        <a :href="route('public.map')" class="block px-4 py-3 text-gray-700 dark:text-slate-300 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 rounded-xl">Peta</a>
                        <hr class="border-gray-100 dark:border-slate-800 my-2" />
                        <div v-if="!$page.props.auth.user" class="space-y-2">
                            <a :href="route('login')" class="block px-4 py-3 text-gray-700 dark:text-slate-300 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 rounded-xl">Masuk</a>
                            <a :href="route('register')" class="block px-4 py-3 text-primary font-bold hover:bg-gray-50 dark:hover:bg-slate-800 rounded-xl">Daftar</a>
                        </div>
                        <div v-else class="space-y-2">
                            <a :href="route('public.profile')" class="block px-4 py-3 text-gray-700 dark:text-slate-300 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 rounded-xl">Profil Saya</a>
                            <a v-if="$page.props.auth.user.role === 'admin'" :href="route('admin.dashboard')" class="block px-4 py-3 text-gray-700 dark:text-slate-300 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 rounded-xl">Dashboard Admin</a>
                            <button @click="logout" class="w-full text-left px-4 py-3 text-red-500 font-bold hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl cursor-pointer">Keluar</button>
                        </div>
                    </div>
                </div>
            </transition>
        </nav>

        <main class="flex-1">
            <slot />
        </main>

        <Toaster />

        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-slate-950 border-t dark:border-slate-900 text-white pt-16 pb-8">
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
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const isDark = ref(false);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};
import axios from 'axios';
import debounce from 'lodash/debounce';
import { Toaster } from '@/Components/ui/toast'
import { useToast } from '@/Components/ui/toast/use-toast'

const { toast } = useToast();
const page = usePage();

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

// Watch for flash messages
watch(() => page.props.flash, (flash) => {
    if (flash?.message) {
        toast({
            title: 'Berhasil',
            description: flash.message,
            variant: 'default',
        })
    }
    if (flash?.error) {
        toast({
            title: 'Error',
            description: flash.error,
            variant: 'destructive',
        })
    }
}, { deep: true, immediate: true })

// Watch for validation errors
watch(() => page.props.errors, (errors) => {
    if (Object.keys(errors).length > 0) {
        const errorCount = Object.keys(errors).length;
        toast({
            title: 'Terjadi Kesalahan',
            description: `Ada ${errorCount} kesalahan pada pengisian form. Silakan periksa kembali.`,
            variant: 'destructive',
        })
        console.error('Validation Errors:', errors);
    }
}, { deep: true })

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
    isDark.value = document.documentElement.classList.contains('dark');
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
