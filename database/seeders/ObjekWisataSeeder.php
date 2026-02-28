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
                'jam_operasional' => '08:00 - 17:00',
                'harga_tiket' => 'Rp 20.000',
                'latitude' => -8.441111,
                'longitude' => 119.923889,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Gua Batu Cermin',
                'alamat' => 'Batu Cermin, Labuan Bajo, Kec. Komodo',
                'no_telepon' => '081234567891',
                'keterangan' => 'Gua yang memiliki dinding yang memantulkan cahaya seperti cermin.',
                'jam_operasional' => '09:00 - 18:00',
                'harga_tiket' => 'Rp 50.000',
                'latitude' => -8.484444,
                'longitude' => 119.896111,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Bukit Raja',
                'alamat' => 'Labuan Bajo, Kec. Komodo',
                'no_telepon' => '081234567892',
                'keterangan' => 'Bukit dengan pemandangan sunset yang spektakuler di Labuan Bajo.',
                'jam_operasional' => '24 Jam',
                'harga_tiket' => 'Gratis',
                'latitude' => -8.491667,
                'longitude' => 119.883333,
            ],
            [
                'id_kecamatan' => $sano->id ?? 2,
                'nama_objek' => 'Cunca Pilas',
                'alamat' => 'Desa Wae Lolos, Kec. Sano Nggoang',
                'no_telepon' => '081234567893',
                'keterangan' => 'Air terjun yang dikelilingi oleh hutan hijau yang asri.',
                'jam_operasional' => '08:00 - 16:00',
                'harga_tiket' => 'Rp 10.000',
                'latitude' => -8.621111,
                'longitude' => 120.012500,
            ],
            [
                'id_kecamatan' => $sano->id ?? 2,
                'nama_objek' => 'Cunca Rami',
                'alamat' => 'Desa Golo Loni, Kec. Sano Nggoang',
                'no_telepon' => '081234567894',
                'keterangan' => 'Air terjun tertinggi di Manggarai Barat dengan debit air yang besar.',
                'jam_operasional' => '08:00 - 17:00',
                'harga_tiket' => 'Rp 20.000',
                'latitude' => -8.654722,
                'longitude' => 120.086111,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Pantai Pede',
                'alamat' => 'Jl. Pantai Pede, Labuan Bajo, Kec. Komodo',
                'no_telepon' => '081234567895',
                'keterangan' => 'Pantai publik di Labuan Bajo yang sering digunakan untuk bersantai.',
                'jam_operasional' => '24 Jam',
                'harga_tiket' => 'Gratis',
                'latitude' => -8.508333,
                'longitude' => 119.875000,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Pulau Komodo',
                'alamat' => 'Taman Nasional Komodo, Kec. Komodo',
                'no_telepon' => '081234567896',
                'keterangan' => 'Habitat asli hewan purba Komodo yang dilindungi dunia.',
                'jam_operasional' => '07:00 - 16:00',
                'harga_tiket' => 'Rp 150.000 - Rp 250.000',
                'latitude' => -8.583333,
                'longitude' => 119.500000,
            ],
            [
                'id_kecamatan' => $boleng->id ?? 6,
                'nama_objek' => 'Pulau Gili Laba',
                'alamat' => 'Kec. Boleng, Manggarai Barat',
                'no_telepon' => '081234567897',
                'keterangan' => 'Pulau cantik dengan bukit savana yang menawarkan pemandangan laut 360 derajat.',
                'jam_operasional' => '24 Jam',
                'harga_tiket' => 'Termasuk Tiket TNK',
                'latitude' => -8.383333,
                'longitude' => 119.633333,
            ],
            [
                'id_kecamatan' => $komodo->id ?? 1,
                'nama_objek' => 'Pulau Padar',
                'alamat' => 'Taman Nasional Komodo, Kec. Komodo',
                'no_telepon' => '081234567898',
                'keterangan' => 'Pulau ikonik dengan tiga teluk dengan warna pasir yang berbeda.',
                'jam_operasional' => '05:30 - 17:00',
                'harga_tiket' => 'Termasuk Tiket TNK',
                'latitude' => -8.650000,
                'longitude' => 119.566667,
            ],
            [
                'id_kecamatan' => $boleng->id ?? 6,
                'nama_objek' => 'Pulau Kanawa',
                'alamat' => 'Kec. Boleng, Manggarai Barat',
                'no_telepon' => '081234567899',
                'keterangan' => 'Pulau dengan taman bawah laut yang sangat indah untuk snorkeling.',
                'jam_operasional' => '08:00 - 17:00',
                'harga_tiket' => 'Rp 50.000 (Sandar Kapal)',
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
