<template>
    <AdminLayout>
        <div class="max-w-2xl">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Kecamatan</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Ubah data kecamatan</p>
                </div>
                <Button variant="outline" as-child>
                    <Link :href="route('admin.kecamatan.index')">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Kembali
                    </Link>
                </Button>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-6 border border-slate-200 dark:border-slate-700">
                <form @submit.prevent="submit">
                    <div class="space-y-4">
                        <div>
                            <label for="nama_kecamatan" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Nama Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <Input
                                id="nama_kecamatan"
                                v-model="form.nama_kecamatan"
                                type="text"
                                :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.nama_kecamatan }"
                                placeholder="Contoh: Komodo"
                            />
                            <p v-if="form.errors.nama_kecamatan" class="mt-1 text-sm text-red-600">
                                {{ form.errors.nama_kecamatan }}
                            </p>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-primary hover:bg-primary/90 text-white"
                            >
                                <Save class="w-4 h-4 mr-2" />
                                <span v-if="form.processing">Memperbarui...</span>
                                <span v-else>Update Kecamatan</span>
                            </Button>
                            
                            <Button
                                variant="outline"
                                as-child
                            >
                                <Link :href="route('admin.kecamatan.index')">
                                    <ArrowLeft class="w-4 h-4 mr-2" />
                                    Batal
                                </Link>
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Save, ArrowLeft } from 'lucide-vue-next';

const props = defineProps({
    kecamatan: Object,
});

const form = useForm({
    nama_kecamatan: props.kecamatan.nama_kecamatan,
});

const submit = () => {
    form.put(route('admin.kecamatan.update', props.kecamatan.id));
};
</script>
