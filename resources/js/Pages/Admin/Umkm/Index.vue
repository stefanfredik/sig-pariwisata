<script setup lang="ts">
import { ref, watch, reactive, computed } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import DataTableToolbar from '@/Components/Admin/Table/DataTableToolbar.vue'
import { Button } from '@/Components/ui/button'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import {
    Search,
    Plus,
    Pencil, Trash2, MapPin, Eye, MoreVertical } from 'lucide-vue-next'
import { debounce } from 'lodash'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'

const props = defineProps<{
    umkms: {
        data: Array<any>
        links: Array<any>
        from: number
        to: number
        total: number
        current_page: number
        per_page: number
    }
    categories: Array<string>
    filters?: {
        search?: string
        kategori?: string
        sort_field?: string
        sort_direction?: string
    }
}>()

const params = reactive({
    search: props.filters?.search || '',
    kategori: props.filters?.kategori || 'all',
    sort_field: props.filters?.sort_field || 'created_at',
    sort_direction: props.filters?.sort_direction || 'desc',
})

const sortOptions = [
    { label: 'Terbaru', value: 'created_at,desc' },
    { label: 'Terlama', value: 'created_at,asc' },
    { label: 'Nama (A-Z)', value: 'nama_umkm,asc' },
    { label: 'Nama (Z-A)', value: 'nama_umkm,desc' },
]

const currentSort = computed(() => `${params.sort_field},${params.sort_direction}`)

watch(
    () => params.search,
    debounce(() => {
        updateParams()
    }, 500)
)

const updateParams = () => {
    router.get(
        route('admin.umkm.index'),
        {
            ...params,
            kategori: params.kategori === 'all' ? '' : params.kategori,
        },
        { preserveState: true, replace: true }
    )
}

const handleSort = (value: string) => {
    const [field, direction] = value.split(',')
    params.sort_field = field
    params.sort_direction = direction
    updateParams()
}

const resetFilters = () => {
    params.search = ''
    params.kategori = 'all'
    params.sort_field = 'created_at'
    params.sort_direction = 'desc'
    updateParams()
}

const deleteForm = useForm({});
const confirmModal = reactive({
    show: false,
    id: null as number | null,
    title: '',
    description: '',
    loading: false
});

const confirmDelete = (umkm: any) => {
    confirmModal.id = umkm.id;
    confirmModal.title = 'Hapus UMKM';
    confirmModal.description = `Apakah Anda yakin ingin menghapus UMKM "${umkm.nama_umkm}"? Semua data terkait termasuk foto akan ikut terhapus.`;
    confirmModal.show = true;
};

const handleDelete = () => {
    if (!confirmModal.id) return;

    confirmModal.loading = true;
    deleteForm.delete(route('admin.umkm.destroy', confirmModal.id), {
        onSuccess: () => {
            confirmModal.show = false;
            confirmModal.id = null;
            confirmModal.loading = false;
        },
        onError: () => {
            confirmModal.loading = false;
        }
    });
};
</script>

<template>
    <Head title="Kelola UMKM" />

    <AdminLayout>
        <div class="max-w-7xl py-6">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">UMKM</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola data Usaha Mikro, Kecil, dan Menengah.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                    <Button as-child class="bg-primary hover:bg-primary/90 text-white px-4 py-2">
                        <Link :href="route('admin.umkm.create')">
                            <Plus class="mr-2 h-4 w-4" /> Tambah UMKM
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Main Card Container -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

                <DataTableToolbar
                    :search="params.search"
                    :sort-options="sortOptions"
                    :current-sort="currentSort"
                    @update:search="params.search = $event"
                    @update:sort="handleSort"
                    @reset="resetFilters"
                    placeholder="Cari UMKM..."
                >
                    <template #filters>
                         <div class="grid gap-2">
                             <label class="text-xs font-black uppercase tracking-widest text-slate-500">Kategori</label>
                             <Select v-model="params.kategori" @update:modelValue="updateParams">
                                <SelectTrigger class="h-9 rounded-lg border-slate-200">
                                    <SelectValue placeholder="Semua Kategori" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="all">Semua Kategori</SelectItem>
                                        <SelectItem v-for="cat in categories" :key="cat" :value="cat">
                                            {{ cat }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                             </Select>
                        </div>
                    </template>
                </DataTableToolbar>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama UMKM</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kategori</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Alamat</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                             <tr v-for="umkm in umkms.data" :key="umkm.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-200">
                                            <img
                                                v-if="umkm.primary_foto"
                                                :src="'/storage/' + umkm.primary_foto.path"
                                                class="w-full h-full object-cover"
                                                alt="Photo"
                                            />
                                            <span v-else class="text-primary font-bold text-xs uppercase">{{ umkm.nama_umkm.substring(0, 2) }}</span>
                                        </div>
                                        <span class="font-semibold text-slate-900 dark:text-white text-sm">
                                            {{ umkm.nama_umkm }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ umkm.kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 max-w-xs truncate">
                                    {{ umkm.alamat }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <button class="text-slate-400 hover:text-primary transition-colors outline-none">
                                                <MoreVertical class="h-5 w-5" />
                                            </button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('admin.umkm.show', umkm.id)" class="cursor-pointer font-medium">
                                                    <Eye class="mr-2 h-4 w-4" /> Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('admin.umkm.edit', umkm.id)" class="cursor-pointer">
                                                    <Pencil class="mr-2 h-4 w-4" /> Edit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="confirmDelete(umkm)" class="text-destructive cursor-pointer">
                                                <Trash2 class="mr-2 h-4 w-4" /> Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </td>
                             </tr>
                             <tr v-if="umkms.data.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    Tidak ada data UMKM yang ditemukan.
                                </td>
                             </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="umkms.links.length > 3">
                     <Pagination :links="umkms.links" />
                </div>
            </div>
        </div>

        <ConfirmDialog 
            v-model:open="confirmModal.show"
            :title="confirmModal.title"
            :description="confirmModal.description"
            :loading="confirmModal.loading"
            @confirm="handleDelete"
        />
    </AdminLayout>
</template>
