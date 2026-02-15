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
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'

const props = defineProps<{
    objekWisatas: {
        data: Array<any>
        links: Array<any>
        from: number
        to: number
        total: number
        current_page: number
        per_page: number
    }
    kecamatans: Array<any>
    filters?: {
        search?: string
        id_kecamatan?: string
        sort_field?: string
        sort_direction?: string
    }
}>()

const params = reactive({
    search: props.filters?.search || '',
    id_kecamatan: props.filters?.id_kecamatan || '',
    sort_field: props.filters?.sort_field || 'created_at',
    sort_direction: props.filters?.sort_direction || 'desc',
})

const sortOptions = [
    { label: 'Newest', value: 'created_at,desc' },
    { label: 'Oldest', value: 'created_at,asc' },
    { label: 'Name (A-Z)', value: 'nama_objek,asc' },
    { label: 'Name (Z-A)', value: 'nama_objek,desc' },
    { label: 'Ticket Price (Low-High)', value: 'harga_tiket,asc' },
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
        route('admin.objek-wisata.index'),
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
    params.id_kecamatan = ''
    params.sort_field = 'created_at'
    params.sort_direction = 'desc'
    updateParams()
}

const confirmDelete = (objek: any) => {
    if (confirm(`Yakin ingin menghapus objek wisata "${objek.nama_objek}"?`)) {
        router.delete(route('admin.objek-wisata.destroy', objek.id))
    }
}
</script>

<template>
    <Head title="Kelola Objek Wisata" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-6">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Objek Wisata</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage tourism objects and destinations.</p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="bg-white px-4 py-2 border-slate-200 text-slate-700">Export</Button>
                    <Button as-child class="bg-primary hover:bg-primary/90 text-white px-4 py-2">
                        <Link :href="route('admin.objek-wisata.create')">
                            <Plus class="mr-2 h-4 w-4" /> New Object
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
                    placeholder="Search objects..."
                >
                    <template #filters>
                         <div class="grid gap-2">
                             <label class="text-xs font-medium text-slate-500">Location</label>
                             <select
                                v-model="params.id_kecamatan"
                                @change="updateParams"
                                class="h-9 w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                                <option value="">All Locations</option>
                                <option v-for="kec in kecamatans" :key="kec.id" :value="kec.id">
                                    {{ kec.nama_kecamatan }}
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
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Destination Name</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Owner / Location</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rating</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Completion</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                             <tr v-for="objek in objekWisatas.data" :key="objek.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                            {{ objek.nama_objek.substring(0, 2).toUpperCase() }}
                                        </div>
                                        <span class="font-semibold text-slate-900 dark:text-white text-sm">
                                            {{ objek.nama_objek }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <img 
                                            v-if="objek.primary_foto" 
                                            :src="'/storage/' + objek.primary_foto.path" 
                                            class="w-6 h-6 rounded-full object-cover"
                                            alt="Thumbnail"
                                        />
                                        <div v-else class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[8px]">NA</div>
                                        <span class="text-sm text-slate-600 dark:text-slate-300">{{ objek.kecamatan?.nama_kecamatan }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    {{ Number(objek.rating_avg).toFixed(1) }} / 5.0
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5 max-w-[100px]">
                                        <div class="bg-primary h-1.5 rounded-full" :style="{ width: (objek.rating_avg * 20) + '%' }"></div>
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
                                                <Link :href="route('admin.objek-wisata.edit', objek.id)" class="cursor-pointer">
                                                    <Pencil class="mr-2 h-4 w-4" /> Edit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="confirmDelete(objek)" class="text-destructive cursor-pointer">
                                                <Trash2 class="mr-2 h-4 w-4" /> Delete
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </td>
                             </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="objekWisatas.links.length > 3">
                     <Pagination :links="objekWisatas.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
