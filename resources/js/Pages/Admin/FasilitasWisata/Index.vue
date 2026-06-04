<script setup lang="ts">
import { ref, watch, reactive, computed } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import DataTableToolbar from '@/Components/Admin/Table/DataTableToolbar.vue'
import { Button } from '@/Components/ui/button'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import { 
    MoreVertical, 
    Plus, 
    Pencil, 
    Trash2, 
    MapPin, 
    Eye, 
    Search,
    Wifi,
    Car,
    Info,
    Utensils,
    ShoppingBag,
    Bath,
    ShieldCheck,
    Bed,
    Camera,
    TreePine,
    Landmark
} from 'lucide-vue-next'
import { debounce } from 'lodash'
import ParkingIcon from "@/Components/Icons/ParkingIcon.vue";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'

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
        kategori_fasilitas?: string
        sort_field?: string
        sort_direction?: string
    }
}>()

const params = reactive({
    search: props.filters.search || '',
    id_objek: props.filters.id_objek || 'all',
    kategori_fasilitas: props.filters.kategori_fasilitas || 'all',
    sort_field: props.filters.sort_field || 'created_at',
    sort_direction: props.filters.sort_direction || 'desc',
})

const sortOptions = [
    { label: 'Terbaru', value: 'created_at,desc' },
    { label: 'Terlama', value: 'created_at,asc' },
    { label: 'Nama (A-Z)', value: 'nama_fasilitas,asc' },
    { label: 'Nama (Z-A)', value: 'nama_fasilitas,desc' },
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
        {
            ...params,
            id_objek: params.id_objek === 'all' ? '' : params.id_objek,
            kategori_fasilitas: params.kategori_fasilitas === 'all' ? '' : params.kategori_fasilitas,
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
    params.id_objek = 'all'
    params.kategori_fasilitas = 'all'
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

const confirmDelete = (id: number) => {
    confirmModal.id = id;
    confirmModal.title = 'Hapus Fasilitas Wisata';
    confirmModal.description = 'Apakah Anda yakin ingin menghapus fasilitas wisata ini? Data ini tidak dapat dikembalikan.';
    confirmModal.show = true;
};

const handleDelete = () => {
    if (!confirmModal.id) return;
    
    confirmModal.loading = true;
    deleteForm.delete(route('admin.fasilitas-wisata.destroy', confirmModal.id), {
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
const getFasilitasIcon = (kategoriFasilitas: string, namaFasilitas: string) => {
    const defaultIcon = MapPin;
    const kat = (kategoriFasilitas || '').toLowerCase();
    const nm = (namaFasilitas || '').toLowerCase();

    // Check name keywords first for specific matches
    if (nm.includes("parkir")) return ParkingIcon;
    if (nm.includes("toilet") || nm.includes("kamar mandi") || nm.includes("wc") || nm.includes("toilet/wc")) return Bath;
    if (nm.includes("mushola") || nm.includes("mesjid") || nm.includes("masjid") || nm.includes("ibadah") || nm.includes("gereja") || nm.includes("vihara") || nm.includes("pura")) return Landmark;
    if (nm.includes("wifi") || nm.includes("internet")) return Wifi;
    if (nm.includes("keamanan") || nm.includes("pos security") || nm.includes("pos satpam") || nm.includes("pos polisi")) return ShieldCheck;
    if (nm.includes("foto") || nm.includes("spot") || nm.includes("selfie")) return Camera;
    if (nm.includes("toko") || nm.includes("warung") || nm.includes("souvenir") || nm.includes("oleh-oleh") || nm.includes("kios") || nm.includes("belanja")) return ShoppingBag;
    if (nm.includes("makan") || nm.includes("restoran") || nm.includes("cafe") || nm.includes("kuliner") || nm.includes("kopi") || nm.includes("food")) return Utensils;
    if (nm.includes("hotel") || nm.includes("penginapan") || nm.includes("villa") || nm.includes("resort") || nm.includes("homestay") || nm.includes("akomodasi")) return Bed;
    if (nm.includes("taman") || nm.includes("camping") || nm.includes("outbound") || nm.includes("alam") || nm.includes("kebun")) return TreePine;
    if (nm.includes("informasi") || nm.includes("info center") || nm.includes("pusat informasi") || nm.includes("guide")) return Info;
    if (nm.includes("sampah") || nm.includes("trash") || nm.includes("bin")) return Trash2;

    // Check category fallback keywords
    if (kat.includes("akomodasi")) return Bed;
    if (kat.includes("kuliner")) return Utensils;
    if (kat.includes("transportasi")) return Car;
    if (kat.includes("parkir")) return ParkingIcon;
    if (kat.includes("sampah")) return Trash2;
    if (kat.includes("umum")) return MapPin;

    return defaultIcon;
};
</script>

<template>
    <Head title="Kelola Fasilitas Wisata" />

    <AdminLayout>
        <div class="max-w-7xl py-6">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Fasilitas Wisata</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola data fasilitas dan sarana pariwisata.</p>
                </div>
                <div class="flex items-center gap-3">
                    
                    <Button as-child class="bg-primary hover:bg-primary/90 text-white px-4 py-2">
                        <Link :href="route('admin.fasilitas-wisata.create')">
                            <Plus class="mr-2 h-4 w-4" /> Fasilitas Baru
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
                    placeholder="Cari fasilitas..."
                >
                    <template #filters>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="grid gap-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Destinasi</label>
                                <Select v-model="params.id_objek" @update:modelValue="updateParams">
                                    <SelectTrigger class="h-10 rounded-xl border-slate-200">
                                        <SelectValue placeholder="Semua Lokasi" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="all">Semua Lokasi</SelectItem>
                                            <SelectItem v-for="objek in objekWisatas" :key="objek.id" :value="String(objek.id)">
                                                {{ objek.nama_objek }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="grid gap-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Kategori</label>
                                <Select v-model="params.kategori_fasilitas" @update:modelValue="updateParams">
                                    <SelectTrigger class="h-10 rounded-xl border-slate-200">
                                        <SelectValue placeholder="Semua Kategori" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="all">Semua Kategori</SelectItem>
                                            <SelectItem value="Akomodasi">Akomodasi</SelectItem>
                                            <SelectItem value="Kuliner">Kuliner</SelectItem>
                                            <SelectItem value="Transportasi">Transportasi</SelectItem>
                                            <SelectItem value="Parkir">Parkir</SelectItem>
                                            <SelectItem value="Toilet">Toilet</SelectItem>
                                            <SelectItem value="Tempat Sampah">Tempat Sampah</SelectItem>
                                            <SelectItem value="Umum">Umum</SelectItem>
                                            <SelectItem value="Lainnya">Lainnya</SelectItem>
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
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Fasilitas</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Lokasi</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                             <tr v-for="item in fasilitas.data" :key="item.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-primary shadow-sm group-hover:scale-110 transition-transform">
                                            <component :is="getFasilitasIcon(item.kategori_fasilitas, item.nama_fasilitas)" class="w-6 h-6 text-primary" />
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900 dark:text-white text-sm tracking-tight">{{ item.nama_fasilitas }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ item.kategori_fasilitas || 'Umum' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        {{ item.objek_wisata?.nama_objek }}
                                    </span>
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
                                                <Link :href="route('admin.fasilitas-wisata.show', item.id)" class="cursor-pointer font-medium">
                                                    <Eye class="mr-2 h-4 w-4" /> Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('admin.fasilitas-wisata.edit', item.id)" class="cursor-pointer">
                                                    <Pencil class="mr-2 h-4 w-4" /> Edit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="confirmDelete(item)" class="text-destructive cursor-pointer">
                                                <Trash2 class="mr-2 h-4 w-4" /> Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </td>
                             </tr>
                              <tr v-if="fasilitas.data.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500">
                                    Tidak ada data fasilitas yang ditemukan.
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

        <ConfirmDialog 
            v-model:open="confirmModal.show"
            :title="confirmModal.title"
            :description="confirmModal.description"
            :loading="confirmModal.loading"
            @confirm="handleDelete"
        />
    </AdminLayout>
</template>
