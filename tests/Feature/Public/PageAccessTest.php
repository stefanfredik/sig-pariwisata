<?php

namespace Tests\Feature\Public;

use App\Models\Event;
use App\Models\ObjekWisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_homepage_displays_popular_objects(): void
    {
        $objek = ObjekWisata::factory()->create(['rating_avg' => 5]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($objek->nama_objek);
    }

    public function test_map_page_is_accessible(): void
    {
        $response = $this->get('/peta');
        $response->assertStatus(200);
    }

    public function test_search_endpoint_returns_results(): void
    {
        $objek = ObjekWisata::factory()->create(['nama_objek' => 'Labuan Bajo']);

        $response = $this->getJson('/api/search-suggest?q=Labuan');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Labuan Bajo',
                'type' => 'Destinasi',
            ]);
    }
}
