<script setup>
import { onMounted, ref, computed } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import Textarea from "@/Components/ui/textarea/Textarea.vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import {
    Save,
    UploadCloud,
    X,
    Mountain,
    MapPin,
    Phone,
    Clock,
    DollarSign,
} from "lucide-vue-next";
import AdminFormLayout from "@/Components/Admin/AdminFormLayout.vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

const props = defineProps({
    kecamatans: Array,
});

const form = useForm({
    id_kecamatan: "",
    nama_objek: "",
    alamat: "",
    no_telepon: "",
    keterangan: "",
    daya_tarik_utama: "",
    akses_transportasi: [],
    jam_operasional: "",
    harga_tiket: "",
    harga_tiket_lokal: "",
    harga_tiket_domestik: "",
    harga_tiket_asing: "",
    harga_parkir_motor: "",
    harga_parkir_mobil: "",
    latitude: -8.5,
    longitude: 119.88,
    fotos: [],
});

// ── Jam Operasional ─────────────────────────────────────────
const waktu_buka = ref("08:00");
const waktu_tutup = ref("17:00");

const aksesTransportasiInput = ref("");

const jamOptions = computed(() => {
    const opts = [];
    for (let h = 0; h < 24; h++) {
        for (let m of [0, 30]) {
            const hh = String(h).padStart(2, "0");
            const mm = String(m).padStart(2, "0");
            opts.push(`${hh}:${mm}`);
        }
    }
    return opts;
});

// ── Harga Tiket ─────────────────────────────────────────────
const hargaLokalDisplay = ref("");
const hargaDomestikDisplay = ref("");
const hargaAsingDisplay = ref("");
const hargaParkirMotorDisplay = ref("");
const hargaParkirMobilDisplay = ref("");

const formatRupiah = (val) => {
    const num = String(val).replace(/\D/g, "");
    if (!num) return "";
    return new Intl.NumberFormat("id-ID").format(Number(num));
};

const onHargaLokalInput = (e) => {
    const raw = e.target.value.replace(/\D/g, "");
    hargaLokalDisplay.value = formatRupiah(raw);
    form.harga_tiket_lokal = raw ? Number(raw) : "";
};

const onHargaDomestikInput = (e) => {
    const raw = e.target.value.replace(/\D/g, "");
    hargaDomestikDisplay.value = formatRupiah(raw);
    form.harga_tiket_domestik = raw ? Number(raw) : "";
};

const onHargaAsingInput = (e) => {
    const raw = e.target.value.replace(/\D/g, "");
    hargaAsingDisplay.value = formatRupiah(raw);
    form.harga_tiket_asing = raw ? Number(raw) : "";
};

const onHargaParkirMotorInput = (e) => {
    const raw = e.target.value.replace(/\D/g, "");
    hargaParkirMotorDisplay.value = formatRupiah(raw);
    form.harga_parkir_motor = raw ? Number(raw) : "";
};

const onHargaParkirMobilInput = (e) => {
    const raw = e.target.value.replace(/\D/g, "");
    hargaParkirMobilDisplay.value = formatRupiah(raw);
    form.harga_parkir_mobil = raw ? Number(raw) : "";
};

const previews = ref([]);
let map = null;
let marker = null;

onMounted(() => {
    map = L.map("map").setView([form.latitude, form.longitude], 12);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    marker = L.marker([form.latitude, form.longitude], {
        draggable: true,
    }).addTo(map);

    marker.on("dragend", (e) => {
        const { lat, lng } = e.target.getLatLng();
        form.latitude = lat.toFixed(6);
        form.longitude = lng.toFixed(6);
    });

    map.on("click", (e) => {
        const { lat, lng } = e.latlng;
        marker.setLatLng([lat, lng]);
        form.latitude = lat.toFixed(6);
        form.longitude = lng.toFixed(6);
    });
});

const handleFileUpload = (e) => {
    const files = Array.from(e.target.files);
    form.fotos = [...form.fotos, ...files];

    files.forEach((file) => {
        const reader = new FileReader();
        reader.onload = (e) => previews.value.push(e.target.result);
        reader.readAsDataURL(file);
    });
};

const removePhoto = (index) => {
    form.fotos.splice(index, 1);
    previews.value.splice(index, 1);
};

const submit = () => {
    // Gabungkan waktu buka & tutup
    form.jam_operasional = `${waktu_buka.value} - ${waktu_tutup.value} WITA`;
    form.akses_transportasi = aksesTransportasiInput.value.trim()
        ? [aksesTransportasiInput.value.trim()]
        : [];

    form.post(route("admin.objek-wisata.store"), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminFormLayout
        title="Tambah Objek Wisata"
        description="Lengkapi data destinasi wisata baru dengan lengkap dan benar."
        :backRoute="route('admin.objek-wisata.index')"
    >
        <form @submit.prevent="submit" class="space-y-8 pb-12">
            <!-- Informasi Utama -->
            <Card
                class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden"
            >
                <CardHeader
                    class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4"
                >
                    <CardTitle
                        class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2"
                    >
                        <Mountain class="h-4 w-4" />
                        Informasi Utama
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500"
                            >Nama Objek Wisata
                            <span class="text-red-500">*</span></label
                        >
                        <Input
                            v-model="form.nama_objek"
                            placeholder="Masukkan nama objek wisata"
                            class="rounded-xl border-slate-200 h-11"
                        />
                        <p
                            v-if="form.errors.nama_objek"
                            class="text-xs text-red-500 font-medium"
                        >
                            {{ form.errors.nama_objek }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500"
                            >Kecamatan
                            <span class="text-red-500">*</span></label
                        >
                        <Select v-model="form.id_kecamatan">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih Kecamatan" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="kec in kecamatans"
                                        :key="kec.id"
                                        :value="String(kec.id)"
                                    >
                                        {{ kec.nama_kecamatan }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <p
                            v-if="form.errors.id_kecamatan"
                            class="text-xs text-red-500 font-medium"
                        >
                            {{ form.errors.id_kecamatan }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500"
                            >Alamat Lengkap
                            <span class="text-red-500">*</span></label
                        >
                        <Input
                            v-model="form.alamat"
                            placeholder="Masukkan alamat lengkap"
                            class="rounded-xl border-slate-200 h-11"
                        />
                        <p
                            v-if="form.errors.alamat"
                            class="text-xs text-red-500 font-medium"
                        >
                            {{ form.errors.alamat }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500 border-t pt-4 block mt-4"
                            >Keterangan / Deskripsi
                            <span class="text-red-500">*</span></label
                        >
                        <Textarea
                            v-model="form.keterangan"
                            rows="4"
                            placeholder="Tuliskan keterangan lengkap objek wisata..."
                            class="rounded-xl border-slate-200"
                        />
                        <p
                            v-if="form.errors.keterangan"
                            class="text-xs text-red-500 font-medium"
                        >
                            {{ form.errors.keterangan }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500"
                            >Daya Tarik Utama</label
                        >
                        <Textarea
                            v-model="form.daya_tarik_utama"
                            rows="3"
                            placeholder="Tuliskan daya tarik utama dari objek wisata ini..."
                            class="rounded-xl border-slate-200"
                        />
                        <p
                            v-if="form.errors.daya_tarik_utama"
                            class="text-xs text-red-500 font-medium"
                        >
                            {{ form.errors.daya_tarik_utama }}
                        </p>
                    </div>

                    <div class="space-y-3">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500 border-t pt-4 block mt-4"
                            >Akses Transportasi</label
                        >
                        <Textarea
                            v-model="aksesTransportasiInput"
                            placeholder="Contoh: Motor, mobil, angkot, speedboat"
                            rows="3"
                            class="rounded-xl border-slate-200"
                        />
                        <p
                            v-if="form.errors.akses_transportasi"
                            class="text-xs text-red-500 font-medium"
                        >
                            {{ form.errors.akses_transportasi }}
                        </p>
                    </div>
                </CardContent>
            </Card>

            <!-- Kontak & Operasional -->
            <Card
                class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden"
            >
                <CardHeader
                    class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4"
                >
                    <CardTitle
                        class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2"
                    >
                        <Clock class="h-4 w-4" />
                        Kontak & Operasional
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500"
                            >No. Telepon</label
                        >
                        <Input
                            v-model="form.no_telepon"
                            placeholder="Contoh: 0812..."
                            class="rounded-xl border-slate-200 h-11"
                        />
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500"
                            >Jam Operasional</label
                        >
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                    >Waktu Buka</label
                                >
                                <select
                                    v-model="waktu_buka"
                                    class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm font-bold text-slate-800 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                >
                                    <option
                                        v-for="jam in jamOptions"
                                        :key="jam"
                                        :value="jam"
                                    >
                                        {{ jam }}
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                    >Waktu Tutup</label
                                >
                                <select
                                    v-model="waktu_tutup"
                                    class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm font-bold text-slate-800 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                >
                                    <option
                                        v-for="jam in jamOptions"
                                        :key="jam"
                                        :value="jam"
                                    >
                                        {{ jam }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">
                            Akan disimpan sebagai:
                            <span class="font-black text-slate-600"
                                >{{ waktu_buka }} - {{ waktu_tutup }} WITA</span
                            >
                        </p>
                    </div>

                    <div class="space-y-4 pt-4 border-t">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500"
                            >Harga Tiket</label
                        >

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <!-- Lokal -->
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                    >Harga Lokal</label
                                >
                                <div class="relative">
                                    <span
                                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-black text-slate-500 select-none"
                                        >Rp</span
                                    >
                                    <input
                                        :value="hargaLokalDisplay"
                                        @input="onHargaLokalInput"
                                        inputmode="numeric"
                                        placeholder="0"
                                        class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-10 pr-4 text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.harga_tiket_lokal"
                                    class="text-xs text-red-500 font-medium"
                                >
                                    {{ form.errors.harga_tiket_lokal }}
                                </p>
                            </div>

                            <!-- Domestik -->
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                    >Harga Domestik</label
                                >
                                <div class="relative">
                                    <span
                                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-black text-slate-500 select-none"
                                        >Rp</span
                                    >
                                    <input
                                        :value="hargaDomestikDisplay"
                                        @input="onHargaDomestikInput"
                                        inputmode="numeric"
                                        placeholder="0"
                                        class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-10 pr-4 text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.harga_tiket_domestik"
                                    class="text-xs text-red-500 font-medium"
                                >
                                    {{ form.errors.harga_tiket_domestik }}
                                </p>
                            </div>

                            <!-- Asing -->
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                    >Harga Asing</label
                                >
                                <div class="relative">
                                    <span
                                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-black text-slate-500 select-none"
                                        >Rp</span
                                    >
                                    <input
                                        :value="hargaAsingDisplay"
                                        @input="onHargaAsingInput"
                                        inputmode="numeric"
                                        placeholder="0"
                                        class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-10 pr-4 text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.harga_tiket_asing"
                                    class="text-xs text-red-500 font-medium"
                                >
                                    {{ form.errors.harga_tiket_asing }}
                                </p>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">
                            Kosongkan jika gratis atau gunakan "Harga Tiket"
                            (Lama) di bawah jika masih diperlukan.
                        </p>

                        <!-- Harga Parkir Section -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <label
                                class="text-xs font-black uppercase tracking-widest text-slate-500"
                                >Harga Parkir</label
                            >
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Motor -->
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                        >Parkir Motor</label
                                    >
                                    <div class="relative">
                                        <span
                                            class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-black text-slate-500 select-none"
                                            >Rp</span
                                        >
                                        <input
                                            :value="hargaParkirMotorDisplay"
                                            @input="onHargaParkirMotorInput"
                                            inputmode="numeric"
                                            placeholder="0"
                                            class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-10 pr-4 text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.harga_parkir_motor"
                                        class="text-xs text-red-500 font-medium"
                                    >
                                        {{ form.errors.harga_parkir_motor }}
                                    </p>
                                </div>

                                <!-- Mobil -->
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                        >Parkir Mobil</label
                                    >
                                    <div class="relative">
                                        <span
                                            class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-black text-slate-500 select-none"
                                            >Rp</span
                                        >
                                        <input
                                            :value="hargaParkirMobilDisplay"
                                            @input="onHargaParkirMobilInput"
                                            inputmode="numeric"
                                            placeholder="0"
                                            class="w-full h-11 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 pl-10 pr-4 text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.harga_parkir_mobil"
                                        class="text-xs text-red-500 font-medium"
                                    >
                                        {{ form.errors.harga_parkir_mobil }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Field Harga Tiket Lama (Hidden/Optional?) -->
                        <div class="space-y-2 opacity-50">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"
                                >Keterangan Tiket (Lama)</label
                            >
                            <Input
                                v-model="form.harga_tiket"
                                placeholder="Contoh: Dewasa 50rb"
                                class="rounded-xl border-slate-200 h-11"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Lokasi Geografis -->
            <Card
                class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden"
            >
                <CardHeader
                    class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4"
                >
                    <CardTitle
                        class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2"
                    >
                        <MapPin class="h-4 w-4" />
                        Lokasi Geografis
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label
                                class="text-xs font-black uppercase tracking-widest text-slate-500"
                                >Latitude</label
                            >
                            <Input
                                v-model="form.latitude"
                                readonly
                                class="rounded-xl border-slate-200 h-11 bg-slate-50 dark:bg-slate-900 cursor-not-allowed"
                            />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-xs font-black uppercase tracking-widest text-slate-500"
                                >Longitude</label
                            >
                            <Input
                                v-model="form.longitude"
                                readonly
                                class="rounded-xl border-slate-200 h-11 bg-slate-50 dark:bg-slate-900 cursor-not-allowed"
                            />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-400 italic"
                            >Klik pada peta atau geser penanda untuk menentukan
                            lokasi tepat.</label
                        >
                        <div
                            id="map"
                            class="h-80 w-full rounded-2xl border border-slate-200 dark:border-slate-700 shadow-inner z-0"
                        ></div>
                    </div>
                </CardContent>
            </Card>

            <!-- Media -->
            <Card
                class="border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden"
            >
                <CardHeader
                    class="border-b bg-slate-50/50 dark:bg-slate-900/50 py-4"
                >
                    <CardTitle
                        class="text-sm font-bold uppercase tracking-widest text-primary flex items-center gap-2"
                    >
                        <UploadCloud class="h-4 w-4" />
                        Media & Foto
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <div class="space-y-4">
                        <label
                            class="text-xs font-black uppercase tracking-widest text-slate-500 block"
                            >Upload Foto (Minimal 1)
                            <span class="text-red-500">*</span></label
                        >
                        <div
                            class="mt-1 flex justify-center px-6 pt-10 pb-10 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-primary transition-all group cursor-pointer bg-slate-50/30"
                        >
                            <div class="space-y-2 text-center">
                                <UploadCloud
                                    class="mx-auto h-12 w-12 text-slate-300 group-hover:text-primary transition-colors"
                                />
                                <div
                                    class="flex text-sm text-slate-600 dark:text-slate-400 justify-center"
                                >
                                    <label
                                        class="relative cursor-pointer bg-transparent rounded-md font-black text-primary hover:text-primary/80 focus-within:outline-none"
                                    >
                                        <span>Unggah foto</span>
                                        <input
                                            type="file"
                                            class="sr-only"
                                            @change="handleFileUpload"
                                            multiple
                                            accept="image/*"
                                        />
                                    </label>
                                    <p class="pl-1">atau tarik dan lepas</p>
                                </div>
                                <p
                                    class="text-[10px] text-slate-400 font-black uppercase tracking-widest"
                                >
                                    PNG, JPG, JPEG hingga 10MB
                                </p>
                            </div>
                        </div>
                        <p
                            v-if="form.errors.fotos"
                            class="text-xs text-red-500 font-medium"
                        >
                            {{ form.errors.fotos }}
                        </p>

                        <!-- Previews -->
                        <div
                            v-if="previews.length"
                            class="grid grid-cols-2 sm:grid-cols-3 gap-4"
                        >
                            <div
                                v-for="(src, index) in previews"
                                :key="index"
                                class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm"
                            >
                                <img
                                    :src="src"
                                    class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                />
                                <button
                                    @click.prevent="removePhoto(index)"
                                    class="absolute top-2 right-2 bg-red-600/90 text-white rounded-lg p-1.5 shadow-lg group-hover:opacity-100 opacity-0 transition-opacity backdrop-blur-sm"
                                >
                                    <X class="w-4 h-4" />
                                </button>
                                <div
                                    v-if="index === 0"
                                    class="absolute top-2 left-2 bg-white/95 backdrop-blur px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-slate-900 border border-slate-100 shadow-sm"
                                >
                                    Foto Utama
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tombol Aksi -->
            <div
                class="flex items-center gap-3 pt-6 border-t border-slate-100 dark:border-slate-800"
            >
                <Button
                    type="submit"
                    :disabled="form.processing"
                    class="px-10 font-bold uppercase tracking-widest text-xs"
                >
                    <Save class="w-4 h-4 mr-2" />
                    {{
                        form.processing ? "Menyimpan..." : "Simpan Objek Wisata"
                    }}
                </Button>
                <Button
                    variant="ghost"
                    class="px-8 font-bold uppercase tracking-widest text-xs"
                    as-child
                >
                    <Link :href="route('admin.objek-wisata.index')">Batal</Link>
                </Button>
            </div>
        </form>
    </AdminFormLayout>
</template>

<style>
.leaflet-container {
    mask-image: none !important;
    z-index: 0;
}
</style>
