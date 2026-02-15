<script setup lang="ts">
import { ref, watch, reactive, computed } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import DataTableToolbar from '@/Components/Admin/Table/DataTableToolbar.vue'
import { Button } from '@/Components/ui/button'
import { MoreVertical, Plus, Pencil, Trash2, MapPin, Eye } from 'lucide-vue-next'
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

const props = defineProps<{
    kecamatans: {
        data: Array<any>
        links: Array<any>
        from: number
        to: number
        total: number
        current_page: number
        per_page: number
    }
    filters?: {
        search?: string
        sort_field?: string
        sort_direction?: string
        has_objects?: string
    }
}>()

const params = reactive({
    search: props.filters?.search || '',
    sort_field: props.filters?.sort_field || 'created_at',
    sort_direction: props.filters?.sort_direction || 'desc',
    has_objects: props.filters?.has_objects || '',
})

const sortOptions = [
    { label: 'Newest', value: 'created_at,desc' },
    { label: 'Oldest', value: 'created_at,asc' },
    { label: 'Name (A-Z)', value: 'nama_kecamatan,asc' },
    { label: 'Name (Z-A)', value: 'nama_kecamatan,desc' },
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
        route('admin.kecamatan.index'),
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

const resetSearch = () => {
    params.search = ''
    params.sort_field = 'created_at'
    params.sort_direction = 'desc'
    params.has_objects = ''
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
    confirmModal.title = 'Hapus Kecamatan';
    confirmModal.description = 'Apakah Anda yakin ingin menghapus kecamatan ini? Objek wisata yang terkait dengan kecamatan ini akan kehilangan data lokasinya.';
    confirmModal.show = true;
};

const handleDelete = () => {
    if (!confirmModal.id) return;
    
    confirmModal.loading = true;
    deleteForm.delete(route('admin.kecamatan.destroy', confirmModal.id), {
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
    <Head title="Kelola Kecamatan" />

    <AdminLayout>
        <div class="max-w-7xl py-6">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Kelola Kecamatan</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage and monitor all active districts.</p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="bg-white px-4 py-2 border-slate-200 text-slate-700">
                        Export
                    </Button>
                    <Button as-child class="bg-primary hover:bg-primary/90 text-white px-4 py-2">
                        <Link :href="route('admin.kecamatan.create')">
                            <Plus class="mr-2 h-4 w-4" /> New Kecamatan
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
                    @reset="resetSearch" 
                    placeholder="Search kecamatan..."
                >
                    <template #filters>
                         <div class="grid gap-2">
                            <label class="text-xs font-medium text-slate-500">Object Suitability</label>
                            <select
                                v-model="params.has_objects"
                                @change="updateParams"
                                class="h-9 w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                                <option value="">All Districts</option>
                                <option value="yes">With Objects</option>
                                <option value="no">Empty / No Objects</option>
                            </select>
                        </div>
                    </template>
                </DataTableToolbar>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Kecamatan</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Objek</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Last Active</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <tr v-for="(kecamatan, index) in kecamatans.data" :key="kecamatan.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase">
                                            {{ kecamatan.nama_kecamatan.substring(0, 2) }}
                                        </div>
                                        <span class="font-semibold text-slate-900 dark:text-white text-sm capitalize">
                                            {{ kecamatan.nama_kecamatan }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-2">
                                        <span class="text-sm text-slate-600 dark:text-slate-300">{{ kecamatan.objek_wisatas_count || 0 }} objects</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    {{ new Date().toLocaleDateString() }}
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
                                                <Link :href="route('admin.kecamatan.show', kecamatan.id)" class="cursor-pointer font-medium">
                                                    <Eye class="mr-2 h-4 w-4" /> Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('admin.kecamatan.edit', kecamatan.id)" class="cursor-pointer">
                                                    <Pencil class="mr-2 h-4 w-4" /> Edit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="confirmDelete(kecamatan.id)" class="text-destructive cursor-pointer">
                                                <Trash2 class="mr-2 h-4 w-4" /> Delete
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </td>
                            </tr>
                             <tr v-if="kecamatans.data.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-500">
                                    No projects found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="kecamatans.links.length > 3">
                     <Pagination :links="kecamatans.links" />
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
