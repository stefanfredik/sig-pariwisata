<?php

namespace Database\Seeders;

use App\Models\Umkm;
use Illuminate\Database\Seeder;

class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $umkms = [
            [
                'nama_umkm' => 'Warung Makan Mama Komodo',
                'kategori' => 'Warung makan',
                'alamat' => 'Jl. Soekarno Hatta No. 1, Labuan Bajo',
                'latitude' => -8.492222,
                'longitude' => 119.882222,
                'keterangan' => 'Warung makan dengan menu andalan Ikan Kuah Asam yang segar khas Bajo.',
            ],
            [
                'nama_umkm' => 'Kios Kerajinan Tangan Bajo',
                'kategori' => 'Kios',
                'alamat' => 'Pasar Kuliner Kampung Ujung, Labuan Bajo',
                'latitude' => -8.487222,
                'longitude' => 119.885222,
                'keterangan' => 'Menyediakan berbagai souvenir khas Manggarai seperti Kain Songke dan kerajinan kerang.',
            ],
            [
                'nama_umkm' => 'Villa Sunset Hill',
                'kategori' => 'Villa',
                'alamat' => 'Puncak Waringin, Labuan Bajo',
                'latitude' => -8.495222,
                'longitude' => 119.878222,
                'keterangan' => 'Villa eksklusif dengan pemandangan langsung ke pelabuhan Labuan Bajo.',
            ],
            [
                'nama_umkm' => 'Hotel Meruorah',
                'kategori' => 'Hotel',
                'alamat' => 'Kawasan Marina Labuan Bajo',
                'latitude' => -8.490556,
                'longitude' => 119.881667,
                'keterangan' => 'Hotel bintang 5 premium di pusat kota Labuan Bajo.',
            ],
            [
                'nama_umkm' => 'Penginapan Bajo Homestay',
                'kategori' => 'Penginapan',
                'alamat' => 'Jl. Mutiara, Labuan Bajo',
                'latitude' => -8.498222,
                'longitude' => 119.886222,
                'keterangan' => 'Penginapan murah dan nyaman untuk para backpacker.',
            ],
            [
                'nama_umkm' => 'Bakso Ikan Segar Bajo',
                'kategori' => 'Warung makan',
                'alamat' => 'Jl. Trans Flores, Labuan Bajo',
                'latitude' => -8.502222,
                'longitude' => 119.892222,
                'keterangan' => 'Bakso ikan segar yang dibuat setiap hari dari hasil tangkapan nelayan.',
            ],
            [
                'nama_umkm' => 'Kios Buah Lokal Manggarai',
                'kategori' => 'Kios',
                'alamat' => 'Dekat Bandara Komodo',
                'latitude' => -8.484222,
                'longitude' => 119.891222,
                'keterangan' => 'Menyual buah-buahan lokal seperti nenas, pisang, dan jeruk Manggarai.',
            ],
            [
                'nama_umkm' => 'Villa Paradise Cove',
                'kategori' => 'Villa',
                'alamat' => 'Waecicu, Kec. Komodo',
                'latitude' => -8.452222,
                'longitude' => 119.873222,
                'keterangan' => 'Villa tenang di pinggir pantai Waecicu.',
            ],
            [
                'nama_umkm' => 'Hotel Ayana Komodo',
                'kategori' => 'Hotel',
                'alamat' => 'Pantai Waecicu, Labuan Bajo',
                'latitude' => -8.448556,
                'longitude' => 119.871667,
                'keterangan' => 'Resort mewah pertama di Labuan Bajo dengan fasilitas lengkap.',
            ],
            [
                'nama_umkm' => 'Wisma Melati Labuan Bajo',
                'kategori' => 'Penginapan',
                'alamat' => 'Jl. Yohanes Sehadun, Labuan Bajo',
                'latitude' => -8.489222,
                'longitude' => 119.895222,
                'keterangan' => 'Penginapan bersih dan terjangkau dekat bandara.',
            ],
        ];

        foreach ($umkms as $umkm) {
            Umkm::updateOrCreate(
                ['nama_umkm' => $umkm['nama_umkm']],
                $umkm
            );
        }
    }
}
