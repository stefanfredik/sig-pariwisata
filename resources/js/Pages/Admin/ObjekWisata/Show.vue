<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from "@/Components/ui/card";
import { Badge } from "@/Components/ui/badge";
import {
    ChevronLeft,
    MapPin,
    Calendar,
    Phone,
    Globe,
    Clock,
    DollarSign,
    Pencil,
    Star,
    Plus,
    Trash2,
} from "lucide-vue-next";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";

const props = defineProps<{
    objekWisata: any;
}>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const confirmModal = reactive({
    show: false,
    id: null as number | null,
    type: "facility" as "facility" | "photo",
    title: "",
    description: "",
    loading: false,
});

const deleteFacility = (id: number) => {
    confirmModal.id = id;
    confirmModal.type = "facility";
    confirmModal.title = "Hapus Fasilitas";
    confirmModal.description =
        "Apakah Anda yakin ingin menghapus fasilitas ini? Tindakan ini tidak dapat dibatalkan.";
    confirmModal.show = true;
};

const confirmDeletePhoto = (id: number) => {
    confirmModal.id = id;
    confirmModal.type = "photo";
    confirmModal.title = "Hapus Foto";
    confirmModal.description =
        "Apakah Anda yakin ingin menghapus foto ini secara permanen?";
    confirmModal.show = true;
};

const setPrimaryPhoto = (fotoId: number) => {
    router.post(
        route("admin.objek-wisata.set-primary-photo", {
            objekId: props.objekWisata.id,
            fotoId,
        }),
    );
};

const handleConfirmDelete = () => {
    if (!confirmModal.id) return;

    confirmModal.loading = true;

    if (confirmModal.type === "facility") {
        router.delete(
            route("admin.fasilitas-wisata.destroy", confirmModal.id),
            {
                onSuccess: () => {
                    confirmModal.show = false;
                    confirmModal.loading = false;
                },
                onError: () => {
                    confirmModal.loading = false;
                },
            },
        );
    } else {
        router.delete(
            route("admin.objek-wisata.delete-photo", confirmModal.id),
            {
                onSuccess: () => {
                    confirmModal.show = false;
                    confirmModal.loading = false;
                },
                onError: () => {
                    confirmModal.loading = false;
                },
            },
        );
    }
};
</script>

<template>
    <Head :title="`Detail - ${objekWisata.nama_objek}`" />

    <AdminLayout>
        <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header/Navigation -->
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="outline" size="icon" as-child>
                        <Link :href="route('admin.objek-wisata.index')">
                            <ChevronLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1
                            class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white"
                        >
                            {{ objekWisata.nama_objek }}
                        </h1>
                        <div class="flex items-center gap-2 mt-1">
                            <Badge variant="outline" class="text-xs">
                                {{
                                    objekWisata.kategori?.nama_kategori ||
                                    "Umum"
                                }}
                            </Badge>
                            <span class="text-sm text-slate-500">{{
                                objekWisata.kecamatan?.nama_kecamatan
                            }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <Button as-child variant="outline">
                        <Link
                            :href="
                                route('admin.objek-wisata.edit', objekWisata.id)
                            "
                        >
                            <Pencil class="mr-2 h-4 w-4" /> Edit
                        </Link>
                    </Button>
                    <Button as-child>
                        <a
                            :href="
                                route(
                                    'public.objek-wisata.show',
                                    objekWisata.slug,
                                )
                            "
                            target="_blank"
                        >
                            <Globe class="mr-2 h-4 w-4" /> Tinjau Publik
                        </a>
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Primary Info -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Photos Gallery -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Galeri Foto</CardTitle>
                            <CardDescription
                                >Visualisasi dari destinasi wisata
                                ini.</CardDescription
                            >
                        </CardHeader>
                        <CardContent>
                            <div
                                v-if="objekWisata.fotos?.length"
                                class="grid grid-cols-2 md:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="foto in objekWisata.fotos"
                                    :key="foto.id"
                                    class="relative aspect-video rounded-lg overflow-hidden border border-slate-200 group"
                                    :class="{
                                        'ring-2 ring-primary border-transparent':
                                            foto.is_primary,
                                    }"
                                >
                                    <img
                                        :src="`/storage/${foto.path}`"
                                        :alt="objekWisata.nama_objek"
                                        class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                    />

                                    <!-- Actions overlay -->
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[2px]"
                                    >
                                        <button
                                            type="button"
                                            @click.prevent="
                                                setPrimaryPhoto(foto.id)
                                            "
                                            class="bg-white text-primary p-2 rounded-md hover:bg-primary hover:text-white transition-all active:scale-95"
                                            :class="{
                                                'ring-2 ring-primary':
                                                    foto.is_primary,
                                            }"
                                            title="Jadikan Foto Utama"
                                        >
                                            <Star
                                                class="w-4 h-4"
                                                :class="{
                                                    'fill-current':
                                                        foto.is_primary,
                                                }"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            @click.prevent="
                                                confirmDeletePhoto(foto.id)
                                            "
                                            class="bg-white text-red-600 p-2 rounded-md hover:bg-red-600 hover:text-white transition-all active:scale-95"
                                            title="Hapus Foto"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>

                                    <Badge
                                        v-if="foto.is_primary"
                                        class="absolute top-2 left-2 text-[10px] h-5 z-10"
                                        >Utama</Badge
                                    >
                                </div>
                            </div>
                            <div
                                v-else
                                class="py-12 bg-slate-50 dark:bg-slate-900/50 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-800 text-center"
                            >
                                <p class="text-sm text-slate-500">
                                    Belum ada foto yang diunggah untuk destinasi
                                    ini.
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Description -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Deskripsi</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div
                                class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 text-sm leading-relaxed"
                                v-html="
                                    objekWisata.deskripsi ||
                                    'Tidak ada deskripsi yang tersedia.'
                                "
                            ></div>
                        </CardContent>
                    </Card>

                    <!-- Facilities -->
                    <Card>
                        <CardHeader
                            class="flex flex-row items-center justify-between space-y-0 pb-4"
                        >
                            <div>
                                <CardTitle>Fasilitas & Sarana</CardTitle>
                                <CardDescription
                                    >Kelola fasilitas yang tersedia di lokasi
                                    ini.</CardDescription
                                >
                            </div>
                            <Button size="sm" class="h-8 gap-1" as-child>
                                <Link
                                    :href="
                                        route('admin.fasilitas-wisata.create', {
                                            objek_id: objekWisata.id,
                                        })
                                    "
                                >
                                    <Plus class="h-3.5 w-3.5" />
                                    <span>Tambah Fasilitas</span>
                                </Link>
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <div
                                v-if="objekWisata.fasilitas?.length"
                                class="grid grid-cols-1 gap-3"
                            >
                                <div
                                    v-for="facility in objekWisata.fasilitas"
                                    :key="facility.id"
                                    class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 group hover:border-primary/30 transition-all"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-primary text-xl border border-slate-100 dark:border-slate-700"
                                        >
                                            {{ facility.icon || "📍" }}
                                        </div>
                                        <div>
                                            <span
                                                class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1"
                                                >{{
                                                    facility.nama_fasilitas
                                                }}</span
                                            >
                                            <p
                                                class="text-[10px] text-slate-500 font-medium uppercase tracking-wider"
                                            >
                                                {{
                                                    facility.kategori_fasilitas ||
                                                    "Umum"
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                    >
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8 text-slate-400 hover:text-primary"
                                            as-child
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.fasilitas-wisata.edit',
                                                        facility.id,
                                                    )
                                                "
                                            >
                                                <Pencil class="h-3.5 w-3.5" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8 text-slate-400 hover:text-red-500"
                                            @click="deleteFacility(facility.id)"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else
                                class="text-center py-12 bg-slate-50/50 dark:bg-slate-900/50 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-800"
                            >
                                <p
                                    class="text-sm text-slate-500 font-medium italic"
                                >
                                    No facilities listed yet.
                                </p>
                                <Button
                                    variant="link"
                                    class="mt-2 text-primary text-xs"
                                    as-child
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.fasilitas-wisata.create',
                                                { objek_id: objekWisata.id },
                                            )
                                        "
                                        >Create the first one</Link
                                    >
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Column: Metadata & Stats -->
                <div class="space-y-8">
                    <!-- Location Detail -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Info Lokasi</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-start gap-3">
                                <MapPin class="h-4 w-4 text-slate-400 mt-0.5" />
                                <div>
                                    <p
                                        class="text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Alamat
                                    </p>
                                    <p
                                        class="text-sm text-slate-700 dark:text-slate-300 mt-1"
                                    >
                                        {{ objekWisata.alamat }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="pt-4 border-t border-slate-100 dark:border-slate-800"
                            >
                                <p
                                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                                >
                                    Harga Tiket
                                </p>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500"
                                            >Lokal</span
                                        >
                                        <span
                                            class="font-bold text-emerald-600"
                                            >{{
                                                objekWisata.harga_tiket_lokal
                                                    ? "Rp " +
                                                      Number(
                                                          objekWisata.harga_tiket_lokal,
                                                      ).toLocaleString("id-ID")
                                                    : "-"
                                            }}</span
                                        >
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500"
                                            >Domestik</span
                                        >
                                        <span
                                            class="font-bold text-emerald-600"
                                            >{{
                                                objekWisata.harga_tiket_domestik
                                                    ? "Rp " +
                                                      Number(
                                                          objekWisata.harga_tiket_domestik,
                                                      ).toLocaleString("id-ID")
                                                    : "-"
                                            }}</span
                                        >
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500"
                                            >Asing</span
                                        >
                                        <span
                                            class="font-bold text-emerald-600"
                                            >{{
                                                objekWisata.harga_tiket_asing
                                                    ? "Rp " +
                                                      Number(
                                                          objekWisata.harga_tiket_asing,
                                                      ).toLocaleString("id-ID")
                                                    : "-"
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        v-if="objekWisata.harga_tiket"
                                        class="mt-2 p-2 bg-slate-50 dark:bg-slate-900 rounded text-[10px] text-slate-400 italic"
                                    >
                                        Ket. Lama: {{ objekWisata.harga_tiket }}
                                    </div>
                                </div>
                            </div>
                            <div
                                class="pt-4 border-t border-slate-100 dark:border-slate-800"
                            >
                                <p
                                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                                >
                                    Harga Parkir
                                </p>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500"
                                            >Motor</span
                                        >
                                        <span
                                            class="font-bold text-emerald-600"
                                            >{{
                                                objekWisata.harga_parkir_motor
                                                    ? "Rp " +
                                                      Number(
                                                          objekWisata.harga_parkir_motor,
                                                      ).toLocaleString("id-ID")
                                                    : "-"
                                            }}</span
                                        >
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500"
                                            >Mobil</span
                                        >
                                        <span
                                            class="font-bold text-emerald-600"
                                            >{{
                                                objekWisata.harga_parkir_mobil
                                                    ? "Rp " +
                                                      Number(
                                                          objekWisata.harga_parkir_mobil,
                                                      ).toLocaleString("id-ID")
                                                    : "-"
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div
                                class="pt-4 border-t border-slate-100 dark:border-slate-800"
                            >
                                <p
                                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                                >
                                    Akses Transportasi
                                </p>
                                <div
                                    v-if="
                                        objekWisata.akses_transportasi?.length
                                    "
                                    class="flex flex-wrap gap-2"
                                >
                                    <Badge
                                        v-for="opsi in objekWisata.akses_transportasi"
                                        :key="opsi"
                                        variant="secondary"
                                        class="font-medium text-[10px]"
                                        >{{ opsi }}</Badge
                                    >
                                </div>
                                <span
                                    v-else
                                    class="text-sm text-slate-500 italic"
                                    >-</span
                                >
                            </div>
                            <div
                                class="pt-4 border-t border-slate-100 dark:border-slate-800"
                            >
                                <p
                                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                                >
                                    Koordinat
                                </p>
                                <code
                                    class="text-[11px] bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded"
                                >
                                    {{ objekWisata.latitude }},
                                    {{ objekWisata.longitude }}
                                </code>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Stats / Ratings -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Rating & Statistik</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-500"
                                    >Rata-rata Rating</span
                                >
                                <div
                                    class="flex items-center gap-1 text-yellow-400"
                                >
                                    <Star class="h-4 w-4 fill-current" />
                                    <span class="text-lg font-bold">{{
                                        objekWisata.rating_avg || "0.0"
                                    }}</span>
                                    <span class="text-xs text-slate-400 ml-1"
                                        >({{
                                            objekWisata.reviews_count || 0
                                        }}
                                        ulasan)</span
                                    >
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- System Info -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Informasi Sistem</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="space-y-2">
                                <h3 class="text-xs font-bold text-slate-400">
                                    Deskripsi
                                </h3>
                                <p
                                    class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed italic"
                                >
                                    "{{ objekWisata.keterangan }}"
                                </p>
                            </div>

                            <div v-if="objekWisata.daya_tarik_utama" class="space-y-2">
                                <h3 class="text-xs font-bold text-slate-400">
                                    Daya Tarik Utama
                                </h3>
                                <p
                                    class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"
                                >
                                    {{ objekWisata.daya_tarik_utama }}
                                </p>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium"
                                    >Dibuat pada</span
                                >
                                <span
                                    class="text-slate-700 dark:text-slate-300"
                                    >{{
                                        formatDate(objekWisata.created_at)
                                    }}</span
                                >
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium"
                                    >Terakhir diperbarui</span
                                >
                                <span
                                    class="text-slate-700 dark:text-slate-300"
                                    >{{
                                        formatDate(objekWisata.updated_at)
                                    }}</span
                                >
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <ConfirmDialog
            v-slot:default
            v-model:open="confirmModal.show"
            :title="confirmModal.title"
            :description="confirmModal.description"
            :loading="confirmModal.loading"
            @confirm="handleConfirmDelete"
        />
    </AdminLayout>
</template>
