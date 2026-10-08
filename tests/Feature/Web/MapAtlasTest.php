<?php

namespace Tests\Feature\Web;

use App\Models\HeritageSite;
use Database\Seeders\GudimallamSeeder;
use Database\Seeders\HeliodorusPillarSeeder;
use Database\Seeders\SanchiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapAtlasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed all three verified heritage dossiers
        $this->seed([
            GudimallamSeeder::class,
            HeliodorusPillarSeeder::class,
            SanchiSeeder::class,
        ]);
    }

    public function test_map_page_loads_with_status_200_and_renders_layout(): void
    {
        $response = $this->get('/map');

        $response->assertStatus(200);
        $response->assertSee('Geospatial Heritage Atlas');
        $response->assertSee('Spatial distribution of in situ ancient Indian archaeological monuments');
        $response->assertSee('id="heritage-map"', false);
    }

    public function test_map_page_renders_spatial_filter_controls_and_options(): void
    {
        $response = $this->get('/map');

        $response->assertStatus(200);
        $response->assertSee('Spatial & Historical Filters', false);
        $response->assertSee('Search Monument');
        $response->assertSee('Modern State');
        $response->assertSee('Architectural Typology');
        $response->assertSee('Historical Era / Period');
        $response->assertSee('Spatial Uncertainty');

        // Seeded states
        $response->assertSee('Madhya Pradesh');
        $response->assertSee('Andhra Pradesh');

        // Seeded site typologies
        $response->assertSee('Temple Complex');
    }

    public function test_map_page_embeds_leaflet_assets_and_geojson_endpoint(): void
    {
        $response = $this->get('/map');

        $response->assertStatus(200);
        $response->assertSee('leaflet.css');
        $response->assertSee('leaflet.js');
        $response->assertSee('/api/v1/geo/sites');
        $response->assertSee('EPSG:4326 (WGS 84)');
    }

    public function test_map_page_displays_verified_sites_count_badge(): void
    {
        $sitesCount = HeritageSite::where('is_published', true)
            ->whereHas('location', function ($q) {
                $q->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->count();

        $this->assertEquals(3, $sitesCount);

        $response = $this->get('/map');

        $response->assertStatus(200);
        $response->assertSee("{$sitesCount} Verified Sites");
    }

    public function test_geojson_endpoint_supplies_data_for_map_markers(): void
    {
        $response = $this->getJson('/api/v1/geo/sites');

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'FeatureCollection',
        ]);

        $features = $response->json('features');
        $this->assertCount(3, $features);

        // Verify GeoJSON structure for Leaflet consumption
        foreach ($features as $feature) {
            $this->assertEquals('Feature', $feature['type']);
            $this->assertEquals('Point', $feature['geometry']['type']);
            $this->assertCount(2, $feature['geometry']['coordinates']);
            $this->assertArrayHasKey('name', $feature['properties']);
            $this->assertArrayHasKey('dating_statement', $feature['properties']);
            $this->assertArrayHasKey('uncertainty_radius_meters', $feature['properties']['location']);
        }
    }
}
