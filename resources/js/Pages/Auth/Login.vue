<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/Components/ui/card';
import { Compass } from 'lucide-vue-next';
import { route } from 'ziggy-js';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk" />

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md flex flex-col items-center">
            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-4">
                <Compass class="w-8 h-8 text-primary-foreground" />
            </div>
            <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                SIG Pariwisata
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
                Sistem Informasi Geografis Manggarai Barat
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-[400px]">
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm">
                <CardHeader class="space-y-1 text-center pb-4">
                    <CardTitle class="text-xl font-bold">Masuk ke akun Anda</CardTitle>
                    <CardDescription>Masukkan username Anda di bawah ini untuk masuk</CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                         <div v-if="form.errors.username" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm mb-4">
                            {{ form.errors.username }}
                        </div>

                        <div class="space-y-2">
                            <Label for="username">Username</Label>
                            <Input
                                id="username"
                                type="text"
                                v-model="form.username"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan username Anda"
                                class="h-11"
                            />
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <Label for="password">Password</Label>
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-sm font-medium text-primary hover:underline"
                                >
                                    Lupa password?
                                </Link>
                            </div>
                            <Input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="h-11"
                            />
                        </div>

                        <div class="flex items-center space-x-2 pt-2">
                             <input
                                id="remember"
                                type="checkbox"
                                v-model="form.remember"
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary dark:border-slate-700 dark:bg-slate-900"
                            />
                            <label
                                for="remember"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-slate-600 dark:text-slate-400"
                            >
                                Ingat saya
                            </label>
                        </div>

                        <Button type="submit" class="w-full h-11 font-bold tracking-wide mt-2" :disabled="form.processing">
                             <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'MEMPROSES...' : 'MASUK' }}
                        </Button>
                    </form>
                </CardContent>
                <CardFooter class="flex flex-col space-y-4 border-t border-slate-100 dark:border-slate-800 pt-6">
                    <div class="text-center text-sm text-slate-600 dark:text-slate-400">
                        Belum punya akun?
                        <Link :href="route('register')" class="font-bold text-primary hover:underline">
                            Daftar sekarang
                        </Link>
                    </div>

                    <div class="text-center text-xs text-slate-500 rounded-lg bg-slate-50 dark:bg-slate-900 p-3 w-full border border-slate-100 dark:border-slate-800">
                        <p class="font-semibold mb-1">Kredensial Demo:</p>
                        <p>Admin: <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded border border-slate-200 dark:border-slate-700">admin</code> / <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded border border-slate-200 dark:border-slate-700">password</code></p>
                        <p class="mt-1">User: <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded border border-slate-200 dark:border-slate-700">user</code> / <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded border border-slate-200 dark:border-slate-700">password</code></p>
                    </div>
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
