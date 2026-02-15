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
            'jam_operasional' => '08:00 - 17:00',
            'harga_tiket' => 'Rp ' . fake()->numberBetween(10000, 100000),
            'latitude' => fake()->latitude(-9, -8), // Manggarai Barat approx
            'longitude' => fake()->longitude(119, 120), // Manggarai Barat approx
            'rating_avg' => fake()->randomFloat(2, 3, 5),
            'review_count' => fake()->numberBetween(0, 100),
            'view_count' => fake()->numberBetween(0, 1000),
        ];
    }
}
