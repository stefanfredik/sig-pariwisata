<?php

namespace Tests\Feature\Admin;

use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ObjekWisataCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_admin_can_create_objek_wisata(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kecamatan = Kecamatan::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.objek-wisata.store'), [
            'id_kecamatan' => $kecamatan->id,
            'nama_objek' => 'New Destination',
            'alamat' => 'Jl. Test No. 1',
            'keterangan' => 'Description here',
            'latitude' => -8.5,
            'longitude' => 119.5,
            'fotos' => [
                UploadedFile::fake()->image('photo1.jpg'),
            ],
        ]);

        $response->assertRedirect(route('admin.objek-wisata.index'));
        $this->assertDatabaseHas('objek_wisatas', ['nama_objek' => 'New Destination']);
    }

    public function test_non_admin_cannot_create_objek_wisata(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $kecamatan = Kecamatan::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.objek-wisata.store'), [
            'id_kecamatan' => $kecamatan->id,
            'nama_objek' => 'Unauthorized Destination',
        ]);

        $response->assertRedirect('/');
    }
}
