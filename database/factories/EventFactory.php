<?php

namespace Database\Factories;

use App\Models\ObjekWisata;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->sentence(3);
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+1 month 2 days');

        return [
            'id_objek' => ObjekWisata::factory(),
            'nama_event' => $name,
            'slug' => Str::slug($name),
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'alamat' => fake()->address(),
            'keterangan' => fake()->paragraph(),
        ];
    }
}
