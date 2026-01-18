<template>
    <AdminLayout>
        <div class="max-w-2xl">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Tambah Kecamatan</h2>
                <p class="mt-1 text-sm text-gray-600">Tambahkan kecamatan baru</p>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <form @submit.prevent="submit">
                    <div class="space-y-4">
                        <div>
                            <label for="nama_kecamatan" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="nama_kecamatan"
                                v-model="form.nama_kecamatan"
                                type="text"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                :class="{ 'border-red-500': form.errors.nama_kecamatan }"
                                placeholder="Contoh: Komodo"
                            />
                            <p v-if="form.errors.nama_kecamatan" class="mt-1 text-sm text-red-600">
                                {{ form.errors.nama_kecamatan }}
                            </p>
                        </div>

                        <div class="flex gap-3 pt-6 border-t">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="btn-primary"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span v-if="form.processing">Menyimpan...</span>
                                <span v-else>Simpan Kecamatan</span>
                            </button>
                            <a :href="route('admin.kecamatan.index')" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    nama_kecamatan: '',
});

const submit = () => {
    form.post(route('admin.kecamatan.store'));
};
</script>
