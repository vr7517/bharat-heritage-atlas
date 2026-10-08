<?php

namespace Tests\Feature\Web;

use Database\Seeders\GudimallamSeeder;
use Database\Seeders\HeliodorusPillarSeeder;
use Database\Seeders\SanchiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExplorersTest extends TestCase
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

    public function test_sites_index_loads_with_status_200_and_renders_monuments_list(): void
    {
        $response = $this->get('/sites');

        $response->assertStatus(200);
        $response->assertSee('Ancient Monuments & In Situ Complexes', false);
        $response->assertSee('Sanchi Buddhist Monument Complex');
        $response->assertSee('Parasurameshwara Temple Complex, Gudimallam');
        $response->assertSee('Besnagar Archaeological Complex');
    }

    public function test_sites_show_loads_with_status_200_and_renders_monographic_details(): void
    {
        $response = $this->get('/sites/sanchi-archaeological-complex');

        $response->assertStatus(200);
        $response->assertSee('Sanchi Buddhist Monument Complex');
        $response->assertSee('Executive Archaeological Summary');
        $response->assertSee('Architectural & Structural Breakdown', false);
        $response->assertSee('In Situ Archaeological Stratum');
        $response->assertSee('Sanchi Great Stupa (Stupa 1)');
        $response->assertSee('API Monograph');
    }

    public function test_objects_index_loads_with_status_200_and_renders_artifacts_list(): void
    {
        $response = $this->get('/objects');

        $response->assertStatus(200);
        $response->assertSee('Physical Heritage Objects & Sculptures', false);
        $response->assertSee('Heliodorus Monolithic Garuda Pillar');
        $response->assertSee('Gudimallam Anthropomorphic Shiva Linga');
        $response->assertSee('Sanchi Great Stupa (Stupa 1)');
    }

    public function test_objects_show_loads_with_status_200_and_renders_artifact_details(): void
    {
        $response = $this->get('/objects/heliodorus-garuda-pillar');

        $response->assertStatus(200);
        $response->assertSee('Heliodorus Monolithic Garuda Pillar');
        $response->assertSee('Curatorial & Physical Specifications', false);
        $response->assertSee('Archaeological Description');
        $response->assertSee('Besnagar Archaeological Complex');
    }

    public function test_inscriptions_index_loads_with_status_200_and_renders_corpus(): void
    {
        $response = $this->get('/inscriptions');

        $response->assertStatus(200);
        $response->assertSee('Epigraphical Corpus & Brahmi Facsimiles', false);
        $response->assertSee('Brahmi');
        $response->assertSee('Prakrit');
    }

    public function test_inscriptions_show_loads_with_status_200_and_renders_verbatim_facsimile(): void
    {
        $response = $this->get('/inscriptions/heliodorus-pillar-inscription-a-dedicatory');

        $response->assertStatus(200);
        $response->assertSee('Heliodorus Pillar Inscription A');
        $response->assertSee('Verbatim Ancient Script Facsimile & Transcription', false);
        $response->assertSee('Brahmi');
        $response->assertSee('Devadevasa', false);
        $response->assertSee('Epigraphical Citation');
    }
}
