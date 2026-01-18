<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center group">
                <div class="inline-flex w-20 h-20 bg-primary rounded-[2rem] items-center justify-center shadow-2xl shadow-primary/30 mb-6 group-hover:rotate-12 transition-transform duration-500">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h2 class="text-4xl font-black text-gray-900 tracking-tighter">
                    Bergabung <span class="text-primary italic">Sekarang</span>
                </h2>
                <p class="mt-2 text-sm font-bold text-gray-500 uppercase tracking-widest leading-relaxed">
                    Mulai petualangan Anda di Manggarai Barat
                </p>
            </div>
            
            <form class="mt-8 space-y-5 bg-white p-10 rounded-[3rem] shadow-2xl border border-white/20 backdrop-blur-sm" @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-2">
                            Nama Lengkap
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="block w-full px-6 py-4 bg-gray-50/50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-gray-300"
                            placeholder="Contoh: Budi Santoso"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-500 font-bold px-2">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label for="username" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-2">
                            Username
                        </label>
                        <input
                            id="username"
                            v-model="form.username"
                            type="text"
                            required
                            class="block w-full px-6 py-4 bg-gray-50/50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-gray-300"
                            placeholder="Pilih username unik"
                        />
                        <p v-if="form.errors.username" class="mt-1 text-xs text-red-500 font-bold px-2">{{ form.errors.username }}</p>
                    </div>

                    <div>
                        <label for="email" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-2">
                            Email
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="block w-full px-6 py-4 bg-gray-50/50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-gray-300"
                            placeholder="email@anda.com"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-500 font-bold px-2">{{ form.errors.email }}</p>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-2">
                            Password
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="block w-full px-6 py-4 bg-gray-50/50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-gray-300"
                            placeholder="Minimal 8 karakter"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-xs text-red-500 font-bold px-2">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-2">
                            Konfirmasi Password
                        </label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            class="block w-full px-6 py-4 bg-gray-50/50 border-transparent rounded-2xl text-gray-900 font-bold focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-gray-300"
                            placeholder="Ulangi password"
                        />
                    </div>
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-5 px-6 bg-primary text-white font-black uppercase tracking-[0.2em] text-xs rounded-2xl shadow-xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed group"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mendaftarkan...
                        </span>
                        <span v-else class="flex items-center justify-center gap-2">
                            Buat Akun 
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </button>
                </div>

                <div class="text-center pt-4 border-t border-gray-100/50 mt-6">
                    <p class="text-sm font-bold text-gray-500 tracking-tight">
                        Sudah punya akun?
                        <a :href="route('login')" class="text-primary hover:underline ml-1">Masuk sekarang</a>
                    </p>
                </div>
            </form>

            <div class="text-center space-y-4">
                <a :href="route('home')" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-primary transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2" /></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
