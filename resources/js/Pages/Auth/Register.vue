<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/Components/ui/card';
import { Compass } from 'lucide-vue-next';
import { route } from 'ziggy-js';

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

<template>
    <Head title="Daftar" />

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md flex flex-col items-center">
            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-4">
                <Compass class="w-8 h-8 text-primary-foreground" />
            </div>
            <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                Buat sebuah akun
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
                Bergabung dengan SIG Wisata Manggarai Barat hari ini
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-[400px]">
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm">
                <CardHeader class="space-y-1 text-center pb-4">
                    <CardTitle class="text-xl font-bold">Pendaftaran</CardTitle>
                    <CardDescription>Masukkan detail Anda untuk mendaftar</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-4">
                        
                        <div class="space-y-2">
                            <Label for="name">Nama Lengkap</Label>
                            <Input
                                id="name"
                                type="text"
                                v-model="form.name"
                                required
                                autofocus
                                placeholder="Budi Santoso"
                                class="h-11"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="username">Username</Label>
                            <Input
                                id="username"
                                type="text"
                                v-model="form.username"
                                required
                                placeholder="Pilih sebuah username"
                                class="h-11"
                            />
                            <p v-if="form.errors.username" class="mt-1 text-xs text-red-500">{{ form.errors.username }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="email">Email</Label>
                            <Input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                placeholder="email@contoh.com"
                                class="h-11"
                            />
                            <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="password">Password</Label>
                            <Input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                placeholder="Min. 8 karakter"
                                class="h-11"
                            />
                            <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="password_confirmation">Konfirmasi Password</Label>
                            <Input
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                required
                                placeholder="Ulangi password"
                                class="h-11"
                            />
                        </div>

                        <Button type="submit" class="w-full h-11 font-bold tracking-wide mt-4" :disabled="form.processing">
                             <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'MEMBUAT AKUN...' : 'BUAT AKUN' }}
                        </Button>
                    </form>
                </CardContent>
                <CardFooter class="flex flex-col space-y-4 border-t border-slate-100 dark:border-slate-800 pt-6">
                    <div class="text-center text-sm text-slate-600 dark:text-slate-400">
                        Sudah punya akun?
                        <Link :href="route('login')" class="font-bold text-primary hover:underline">
                            Masuk
                        </Link>
                    </div>

                    <div class="text-center mt-4">
                        <Link :href="route('home')" class="inline-flex items-center text-xs font-medium text-slate-500 hover:text-primary transition-colors">
                            <Compass class="w-3 h-3 mr-1" />
                            Kembali ke Beranda
                        </Link>
                    </div>
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
