<script setup lang="ts">
import { ref, watch, reactive, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import DataTableToolbar from '@/Components/Admin/Table/DataTableToolbar.vue'
import { Button } from '@/Components/ui/button'
import { MoreVertical, Plus, Pencil, Trash2, MapPin } from 'lucide-vue-next'
import { debounce } from 'lodash'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'

const props = defineProps<{
    fasilitas: {
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
        sort_field?: string
        sort_direction?: string
    }
}>()

const params = reactive({
    search: props.filters.search || '',
    id_objek: props.filters.id_objek || '',
    sort_field: props.filters.sort_field || 'created_at',
    sort_direction: props.filters.sort_direction || 'desc',
})

const sortOptions = [
    { label: 'Newest', value: 'created_at,desc' },
    { label: 'Oldest', value: 'created_at,asc' },
    { label: 'Name (A-Z)', value: 'nama_fasilitas,asc' },
    { label: 'Name (Z-A)', value: 'nama_fasilitas,desc' },
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
        route('admin.fasilitas-wisata.index'),
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
    params.sort_field = 'created_at'
    params.sort_direction = 'desc'
    updateParams()
}

const confirmDelete = (item: any) => {
    if (confirm(`Yakin ingin menghapus fasilitas "${item.nama_fasilitas}"?`)) {
        router.delete(route('admin.fasilitas-wisata.destroy', item.id))
    }
}
</script>

<template>
    <Head title="Kelola Fasilitas Wisata" />

    <AdminLayout>
        <div class="max-w-7xl py-6">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Fasilitas Wisata</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage tourism facilities and amenities.</p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="bg-white px-4 py-2 border-slate-200 text-slate-700">Export</Button>
                    <Button as-child class="bg-primary hover:bg-primary/90 text-white px-4 py-2">
                        <Link :href="route('admin.fasilitas-wisata.create')">
                            <Plus class="mr-2 h-4 w-4" /> New Facility
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
                    placeholder="Search facilities..."
                >
                    <template #filters>
                        <div class="grid gap-2">
                            <label class="text-xs font-medium text-slate-500">Location</label>
                            <select
                                v-model="params.id_objek"
                                @change="updateParams"
                                class="h-9 w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                                <option value="">All Locations</option>
                                <option v-for="objek in objekWisatas" :key="objek.id" :value="objek.id">
                                    {{ objek.nama_objek }}
                                </option>
                            </select>
                        </div>
                    </template>
                </DataTableToolbar>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Facility Name</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Location</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Coordinates</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                             <tr v-for="item in fasilitas.data" :key="item.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary text-lg">
                                            {{ item.icon || '📍' }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ item.nama_fasilitas }}</div>
                                            <div class="text-[10px] text-slate-400 truncate max-w-[200px]">{{ item.deskripsi || 'No description' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        {{ item.objek_wisata?.nama_objek }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1 text-xs font-mono text-slate-500">
                                        <MapPin class="h-3 w-3" />
                                        {{ item.latitude }}, {{ item.longitude }}
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
                                                <Link :href="route('admin.fasilitas-wisata.edit', item.id)" class="cursor-pointer">
                                                    <Pencil class="mr-2 h-4 w-4" /> Edit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="confirmDelete(item)" class="text-destructive cursor-pointer">
                                                <Trash2 class="mr-2 h-4 w-4" /> Delete
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </td>
                             </tr>
                              <tr v-if="fasilitas.data.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500">
                                    No facilities found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="fasilitas.links.length > 3">
                     <Pagination :links="fasilitas.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
