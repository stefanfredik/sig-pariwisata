<?php

namespace Database\Factories;

use App\Models\Kecamatan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ObjekWisata>
 */
class ObjekWisataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();
        return [
            'id_kecamatan' => Kecamatan::factory(),
            'nama_objek' => $name,
            'slug' => Str::slug($name),
            'alamat' => fake()->address(),
            'no_telepon' => substr(fake()->phoneNumber(), 0, 15),
            'keterangan' => fake()->paragraph(),
            'daya_tarik_utama' => fake()->realText(100),
            'jam_operasional' => '08:00 - 17:00 WITA',
            'harga_tiket' => (string)fake()->numberBetween(10000, 50000),
            'harga_tiket_lokal' => fake()->numberBetween(10000, 30000),
            'harga_tiket_domestik' => fake()->numberBetween(30000, 60000),
            'harga_tiket_asing' => fake()->numberBetween(75000, 200000),
            'harga_parkir_motor' => fake()->randomElement([2000, 3000, 5000]),
            'harga_parkir_mobil' => fake()->randomElement([5000, 10000, 15000]),
            'latitude' => fake()->latitude(-8.8, -8.3), // Manggarai Barat approx
            'longitude' => fake()->longitude(119.4, 120.1), // Manggarai Barat approx
            'rating_avg' => fake()->randomFloat(2, 3, 5),
            'review_count' => fake()->numberBetween(0, 100),
            'view_count' => fake()->numberBetween(0, 1000),
        ];
    }
}
