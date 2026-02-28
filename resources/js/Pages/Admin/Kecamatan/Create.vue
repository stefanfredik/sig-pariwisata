<template>
    <AdminFormLayout
        title="Tambah Kecamatan"
        description="Tambahkan data kecamatan baru ke dalam sistem."
        :backRoute="route('admin.kecamatan.index')"
    >
        <form @submit.prevent="submit" class="space-y-8 pb-12">
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <CardHeader class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4">
                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2">
                        <MapPin class="h-4 w-4" /> 
                        Data Kecamatan
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label for="nama_kecamatan" class="text-xs font-black uppercase tracking-widest text-slate-500">
                            Nama Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <Input
                            id="nama_kecamatan"
                            v-model="form.nama_kecamatan"
                            type="text"
                            class="rounded-xl border-slate-200 h-11"
                            :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.nama_kecamatan }"
                            placeholder="Contoh: Komodo"
                        />
                        <p v-if="form.errors.nama_kecamatan" class="text-xs text-red-500 font-medium">
                            {{ form.errors.nama_kecamatan }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <Button type="submit" :disabled="form.processing" class="px-10 font-bold uppercase tracking-widest text-xs">
                            <Save class="w-4 h-4 mr-2" />
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Kecamatan' }}
                        </Button>
                        <Button variant="ghost" class="px-8 font-bold uppercase tracking-widest text-xs" as-child>
                            <Link :href="route('admin.kecamatan.index')">Batal</Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </form>
    </AdminFormLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminFormLayout from '@/Components/Admin/AdminFormLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Save, MapPin } from 'lucide-vue-next';

const form = useForm({
    nama_kecamatan: '',
});

const submit = () => {
    form.post(route('admin.kecamatan.store'));
};
</script>
