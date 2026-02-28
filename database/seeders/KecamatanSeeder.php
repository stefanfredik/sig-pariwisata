<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = [
            'Komodo',
            'Sano Nggoang',
            'Kuwus',
            'Lembor',
            'Macang Pacar',
            'Boleng',
            'Welak',
            'Ndoso',
        ];

        foreach ($kecamatans as $kecamatan) {
            Kecamatan::updateOrCreate([
                'nama_kecamatan' => $kecamatan,
            ]);
        }
    }
}
