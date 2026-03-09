<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ObjekWisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $komodo = Kecamatan::where('nama_kecamatan', 'Komodo')->first();
        $sano = Kecamatan::where('nama_kecamatan', 'Sano Nggoang')->first();
        $boleng = Kecamatan::where('nama_kecamatan', 'Boleng')->first();

        $wisatas = [
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Gua Rangko',
                'alamat' => 'Desa Rangko, Kec. Komodo',
                'no_telepon' => '081234567890',
                'keterangan' => 'Gua dengan kolam air asin berwarna biru jernih yang sangat indah.',
                'daya_tarik_utama' => 'Kolam air asin alami yang jernih di dalam gua yang bisa digunakan untuk berenang.',
                'jam_operasional' => '08:00 - 17:00 WITA',
                'harga_tiket' => '20000',
                'harga_tiket_lokal' => 20000,
                'harga_tiket_domestik' => 15000,
                'harga_tiket_asing' => 30000,
                'harga_parkir_motor' => 2000,
                'harga_parkir_mobil' => 5000,
                'akses_transportasi' => ['Kendaraan Pribadi (Motor)', 'Kendaraan Pribadi (Mobil)'],
                'latitude' => -8.4526,
                'longitude' => 119.923889,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Gua Batu Cermin',
                'alamat' => 'Batu Cermin, Labuan Bajo, Kec. Komodo',
                'no_telepon' => '081234567891',
                'keterangan' => 'Gua yang memiliki dinding yang memantulkan cahaya seperti cermin.',
                'jam_operasional' => '09:00 - 18:00 WITA',
                'harga_tiket' => '50000',
                'akses_transportasi' => ['Kendaraan Pribadi (Motor)', 'Kendaraan Pribadi (Mobil)', 'Kendaraan Umum / Angkot'],
                'latitude' => -8.484444,
                'longitude' => 119.896111,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Bukit Raja',
                'alamat' => 'Labuan Bajo, Kec. Komodo',
                'no_telepon' => '081234567892',
                'keterangan' => 'Bukit dengan pemandangan sunset yang spektakuler di Labuan Bajo.',
                'jam_operasional' => '05:00 - 19:00 WITA',
                'harga_tiket' => null,
                'akses_transportasi' => ['Kendaraan Pribadi (Motor)'],
                'latitude' => -8.491667,
                'longitude' => 119.883333,
            ],
            [
                'id_kecamatan' => $sano->id ?? 2,
                'nama_objek' => 'Cunca Pilas',
                'alamat' => 'Desa Wae Lolos, Kec. Sano Nggoang',
                'no_telepon' => '081234567893',
                'keterangan' => 'Air terjun yang dikelilingi oleh hutan hijau yang asri.',
                'jam_operasional' => '08:00 - 16:00 WITA',
                'harga_tiket' => '10000',
                'akses_transportasi' => ['Kendaraan Pribadi (Motor)'],
                'latitude' => -8.621111,
                'longitude' => 120.012500,
            ],
            [
                'id_kecamatan' => $sano->id ?? 2,
                'nama_objek' => 'Cunca Rami',
                'alamat' => 'Desa Golo Loni, Kec. Sano Nggoang',
                'no_telepon' => '081234567894',
                'keterangan' => 'Air terjun tertinggi di Manggarai Barat dengan debit air yang besar.',
                'jam_operasional' => '08:00 - 17:00 WITA',
                'harga_tiket' => '20000',
                'akses_transportasi' => ['Kendaraan Pribadi (Motor)', 'Bisa Dijangkau Jalan Kaki'],
                'latitude' => -8.654722,
                'longitude' => 120.086111,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Pantai Pede',
                'alamat' => 'Jl. Pantai Pede, Labuan Bajo, Kec. Komodo',
                'no_telepon' => '081234567895',
                'keterangan' => 'Pantai publik di Labuan Bajo yang sering digunakan untuk bersantai.',
                'jam_operasional' => '05:00 - 20:00 WITA',
                'harga_tiket' => null,
                'akses_transportasi' => ['Kendaraan Pribadi (Motor)', 'Kendaraan Pribadi (Mobil)', 'Kendaraan Umum / Angkot'],
                'latitude' => -8.508333,
                'longitude' => 119.875000,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Pulau Komodo',
                'alamat' => 'Taman Nasional Komodo, Kec. Komodo',
                'no_telepon' => '081234567896',
                'keterangan' => 'Habitat asli hewan purba Komodo yang dilindungi dunia.',
                'jam_operasional' => '07:00 - 16:00 WITA',
                'harga_tiket' => '250000',
                'akses_transportasi' => ['Bus Pariwisata', 'Lainnya (Speedboat)'],
                'latitude' => -8.583333,
                'longitude' => 119.500000,
            ],
            [
                'id_kecamatan' => $boleng->id ?? 6,
                'nama_objek' => 'Pulau Gili Laba',
                'alamat' => 'Kec. Boleng, Manggarai Barat',
                'no_telepon' => '081234567897',
                'keterangan' => 'Pulau cantik dengan bukit savana yang menawarkan pemandangan laut 360 derajat.',
                'jam_operasional' => '05:00 - 18:00 WITA',
                'harga_tiket' => null,
                'akses_transportasi' => ['Lainnya (Kapal Phinisi)'],
                'latitude' => -8.383333,
                'longitude' => 119.633333,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Pulau Padar',
                'alamat' => 'Taman Nasional Komodo, Kec. Komodo',
                'no_telepon' => '081234567898',
                'keterangan' => 'Pulau ikonik dengan tiga teluk dengan warna pasir yang berbeda.',
                'jam_operasional' => '05:00 - 17:00 WITA',
                'harga_tiket' => null,
                'akses_transportasi' => ['Lainnya (Speedboat)'],
                'latitude' => -8.650000,
                'longitude' => 119.566667,
            ],
            [
                'id_kecamatan' => $boleng->id ?? 6,
                'nama_objek' => 'Pulau Kanawa',
                'alamat' => 'Kec. Boleng, Manggarai Barat',
                'no_telepon' => '081234567899',
                'keterangan' => 'Pulau dengan taman bawah laut yang sangat indah untuk snorkeling.',
                'jam_operasional' => '08:00 - 17:00 WITA',
                'harga_tiket' => '50000',
                'akses_transportasi' => ['Lainnya (Speedboat)'],
                'latitude' => -8.497222,
                'longitude' => 119.758333,
            ],
        ];

        foreach ($wisatas as $wisata) {
            ObjekWisata::updateOrCreate(
                ['nama_objek' => $wisata['nama_objek']],
                $wisata
            );
        }
    }
}
