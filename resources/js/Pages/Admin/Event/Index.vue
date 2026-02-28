<script setup lang="ts">
import { ref, watch, reactive, computed } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import DataTableToolbar from '@/Components/Admin/Table/DataTableToolbar.vue'
import { Button } from '@/Components/ui/button'
import { MoreVertical, Plus, Pencil, Trash2, Calendar, MapPin, Eye } from 'lucide-vue-next'
import { debounce } from 'lodash'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
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
    events: {
        data: Array<any>
        links: Array<any>
        from: number
        to: number
        total: number
        current_page: number
        per_page: number
    }
    objekWisatas: Array<any>
    filters: {
        search?: string
        id_objek?: string
        status?: string
        sort_field?: string
        sort_direction?: string
    }
}>()

const params = reactive({
    search: props.filters.search || '',
    id_objek: props.filters.id_objek || '',
    status: props.filters.status || '',
    sort_field: props.filters.sort_field || 'tanggal_mulai',
    sort_direction: props.filters.sort_direction || 'desc',
})

const sortOptions = [
    { label: 'Terbaru', value: 'tanggal_mulai,desc' },
    { label: 'Terlama', value: 'tanggal_mulai,asc' },
    { label: 'Nama (A-Z)', value: 'nama_event,asc' },
    { label: 'Nama (Z-A)', value: 'nama_event,desc' },
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
        route('admin.events.index'),
        params,
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
    params.id_objek = ''
    params.status = ''
    params.sort_field = 'tanggal_mulai'
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

const confirmDelete = (id: number) => {
    confirmModal.id = id;
    confirmModal.title = 'Hapus Event';
    confirmModal.description = 'Apakah Anda yakin ingin menghapus event ini? Data ini tidak dapat dikembalikan.';
    confirmModal.show = true;
};

const handleDelete = () => {
    if (!confirmModal.id) return;
    
    confirmModal.loading = true;
    deleteForm.delete(route('admin.events.destroy', confirmModal.id), {
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

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}
</script>

<template>
    <Head title="Kelola Event" />

    <AdminLayout>
        <div class="max-w-7xl py-6">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Event & Acara</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola data event dan jadwal pariwisata.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                    <Button as-child class="bg-primary hover:bg-primary/90 text-white px-4 py-2">
                        <Link :href="route('admin.events.create')">
                            <Plus class="mr-2 h-4 w-4" /> Event Baru
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
                    placeholder="Cari event..."
                >
                    <template #filters>
                        <div class="grid gap-4 md:grid-cols-2">
                             <div class="grid gap-2">
                                <label class="text-xs font-black uppercase tracking-widest text-slate-500">Lokasi</label>
                                <Select v-model="params.id_objek" @update:modelValue="updateParams">
                                    <SelectTrigger class="h-9 rounded-lg border-slate-200">
                                        <SelectValue placeholder="Semua Lokasi" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem :value="''">Semua Lokasi</SelectItem>
                                            <SelectItem v-for="objek in objekWisatas" :key="objek.id" :value="String(objek.id)">
                                                {{ objek.nama_objek }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>

                             <div class="grid gap-2">
                                <label class="text-xs font-black uppercase tracking-widest text-slate-500">Status</label>
                                <Select v-model="params.status" @update:modelValue="updateParams">
                                    <SelectTrigger class="h-9 rounded-lg border-slate-200">
                                        <SelectValue placeholder="Semua Status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem :value="''">Semua Status</SelectItem>
                                            <SelectItem value="upcoming">Akan Datang</SelectItem>
                                            <SelectItem value="ongoing">Sedang Berlangsung</SelectItem>
                                            <SelectItem value="past">Selesai</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </template>
                </DataTableToolbar>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Event</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rentang Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Lokasi</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                             <tr v-for="event in events.data" :key="event.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                            EV
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ event.nama_event }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ event.slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                        <Calendar class="h-4 w-4 text-slate-400" />
                                        <span>{{ formatDate(event.tanggal_mulai) }} - {{ formatDate(event.tanggal_selesai) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span v-if="event.objek_wisata" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                                            <MapPin class="h-3 w-3 mr-1" />
                                            {{ event.objek_wisata.nama_objek }}
                                        </span>
                                        <span v-else class="text-xs text-slate-400 italic">Lokasi Umum</span>
                                    </div>
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
                                                <Link :href="route('admin.events.show', event.id)" class="cursor-pointer font-medium">
                                                    <Eye class="mr-2 h-4 w-4" /> Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('admin.events.edit', event.id)" class="cursor-pointer">
                                                    <Pencil class="mr-2 h-4 w-4" /> Edit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="confirmDelete(event.id)" class="text-destructive cursor-pointer">
                                                <Trash2 class="mr-2 h-4 w-4" /> Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </td>
                             </tr>
                              <tr v-if="events.data.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500">
                                    Tidak ada data event yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="events.links.length > 3">
                     <Pagination :links="events.links" />
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
