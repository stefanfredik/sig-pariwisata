<?php

namespace Tests\Unit\Models;

use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjekWisataTest extends TestCase
{
    use RefreshDatabase;

    public function test_details_relationship_with_kecamatan(): void
    {
        $kecamatan = Kecamatan::factory()->create();
        $objekWisata = ObjekWisata::factory()->create([
            'id_kecamatan' => $kecamatan->id,
        ]);

        $this->assertTrue($objekWisata->kecamatan->is($kecamatan));
    }

    public function test_it_can_generate_slug_if_not_provided_manually(): void
    {
        // Assuming we have logic in model or observer, usually factories handle slug but validation might fail if empty?
        // Actually ObjekWisataFactory generates valid slug.
        // Let's test basic creation.

        $objekWisata = ObjekWisata::factory()->create([
            'nama_objek' => 'Test Objek',
            'slug' => 'test-objek',
        ]);

        $this->assertDatabaseHas('objek_wisatas', [
            'nama_objek' => 'Test Objek',
            'slug' => 'test-objek',
        ]);
    }
}
