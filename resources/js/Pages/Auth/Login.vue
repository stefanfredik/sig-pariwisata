<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    SIG Wisata Manggarai Barat
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Masuk ke akun Anda
                </p>
            </div>
            
            <form class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow-xl" @submit.prevent="submit">
                <div v-if="form.errors.username" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ form.errors.username }}
                </div>

                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            Username
                        </label>
                        <input
                            id="username"
                            v-model="form.username"
                            type="text"
                            required
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm"
                            placeholder="Masukkan username"
                        />
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm"
                            placeholder="Masukkan password"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                        />
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50"
                    >
                        <span v-if="form.processing">Memproses...</span>
                        <span v-else>Masuk</span>
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <a :href="route('register')" class="font-medium text-primary hover:text-primary/80">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </form>

            <div class="text-center text-xs text-gray-500">
                <p>Demo Credentials:</p>
                <p>Admin: <code class="bg-gray-100 px-2 py-1 rounded">admin</code> / <code class="bg-gray-100 px-2 py-1 rounded">password</code></p>
                <p>User: <code class="bg-gray-100 px-2 py-1 rounded">user</code> / <code class="bg-gray-100 px-2 py-1 rounded">password</code></p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>
