<template>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Sidebar for Desktop -->
        <aside 
            :class="[
                'fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0',
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div class="h-full flex flex-col">
                <!-- Sidebar Header -->
                <div class="h-16 flex items-center px-6 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-lg shadow-primary/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L16 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <h1 class="text-lg font-black text-gray-900 tracking-tight">SIG Wisata</h1>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <p class="px-2 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Main Menu</p>
                    
                    <a :href="route('admin.dashboard')" 
                       :class="[
                           'flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group',
                           isActive('admin.dashboard') 
                           ? 'bg-blue-50 text-primary shadow-sm shadow-blue-100/50' 
                           : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
                       ]"
                    >
                        <svg :class="['w-5 h-5 mr-3 transition-colors', isActive('admin.dashboard') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-600']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <a :href="route('admin.kecamatan.index')" 
                       :class="[
                           'flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group',
                           isActive('admin.kecamatan.*') 
                           ? 'bg-blue-50 text-primary shadow-sm shadow-blue-100/50' 
                           : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
                       ]"
                    >
                        <svg :class="['w-5 h-5 mr-3 transition-colors', isActive('admin.kecamatan.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-600']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Data Kecamatan
                    </a>

                    <a :href="route('admin.objek-wisata.index')" 
                       :class="[
                           'flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group',
                           isActive('admin.objek-wisata.*') 
                           ? 'bg-blue-50 text-primary shadow-sm shadow-blue-100/50' 
                           : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
                       ]"
                    >
                        <svg :class="['w-5 h-5 mr-3 transition-colors', isActive('admin.objek-wisata.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-600']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        Objek Wisata
                    </a>

                    <div class="pt-4 pb-2 border-t border-gray-50">
                        <p class="px-2 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Layanan & Fitur</p>
                        
                    <a :href="route('admin.events.index')" 
                       :class="[
                           'flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group',
                           isActive('admin.events.*') 
                           ? 'bg-blue-50 text-primary shadow-sm shadow-blue-100/50' 
                           : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
                       ]"
                    >
                        <svg :class="['w-5 h-5 mr-3 transition-colors', isActive('admin.events.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-600']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                        </svg>
                        Events
                    </a>
                        
                    <a :href="route('admin.fasilitas-wisata.index')" 
                       :class="[
                           'flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group',
                           isActive('admin.fasilitas-wisata.*') 
                           ? 'bg-blue-50 text-primary shadow-sm shadow-blue-100/50' 
                           : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
                       ]"
                    >
                        <svg :class="['w-5 h-5 mr-3 transition-colors', isActive('admin.fasilitas-wisata.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-600']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Fasilitas
                    </a>

                    <a :href="route('admin.reviews.index')" 
                       :class="[
                           'flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group',
                           isActive('admin.reviews.*') 
                           ? 'bg-blue-50 text-primary shadow-sm shadow-blue-100/50' 
                           : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
                       ]"
                    >
                        <svg :class="['w-5 h-5 mr-3 transition-colors', isActive('admin.reviews.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-600']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.518 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Moderasi Review
                    </a>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-gray-100 m-2 bg-gray-50 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary font-bold">
                            {{ $page.props.auth.user.name.charAt(0) }}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $page.props.auth.user.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Navbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 z-40 sticky top-0">
                <button @click="toggleSidebar" class="p-2 mr-4 text-gray-600 lg:hidden hover:bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex-1 lg:flex hidden">
                    <!-- Search or other controls can go here -->
                </div>

                <div class="flex items-center space-x-4">
                    <form @submit.prevent="logout">
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all flex items-center gap-2 group">
                            <span class="text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">Logout</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Flash Messages -->
                    <transition name="fade">
                        <div v-if="$page.props.flash.message" class="mb-6 flex items-center p-4 text-emerald-800 bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm shadow-emerald-100/50">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            <span class="text-sm font-semibold">{{ $page.props.flash.message }}</span>
                        </div>
                    </transition>
                    
                    <transition name="fade">
                        <div v-if="$page.props.flash.error" class="mb-6 flex items-center p-4 text-rose-800 bg-rose-50 rounded-2xl border border-rose-100 shadow-sm shadow-rose-100/50">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                            <span class="text-sm font-semibold">{{ $page.props.flash.error }}</span>
                        </div>
                    </transition>

                    <slot />
                </div>
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div v-if="isSidebarOpen" @click="toggleSidebar" class="fixed inset-0 bg-gray-900/30 backdrop-blur-sm z-40 lg:hidden"></div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const isActive = (routeName) => {
    return route().current(routeName);
};

const logout = () => {
    router.post('/logout');
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
    width: 4px;
}
nav::-webkit-scrollbar-thumb {
    background: #f1f1f1;
    border-radius: 10px;
}
nav::-webkit-scrollbar-thumb:hover {
    background: #e5e5e5;
}
</style>
