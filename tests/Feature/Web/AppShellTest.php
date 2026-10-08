<?php

namespace Tests\Feature\Web;

use App\Models\Claim;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Source;
use Database\Seeders\GudimallamSeeder;
use Database\Seeders\HeliodorusPillarSeeder;
use Database\Seeders\SanchiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppShellTest extends TestCase
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

    public function test_home_page_returns_successful_response_and_renders_layout(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('भारत', false);
        $response->assertSee('Heritage Atlas');
        $response->assertSee('Evidence-Linked Digital Heritage Platform');
        $response->assertSee('ACADEMIC EVIDENCE STANDARD');
    }

    public function test_home_page_navigation_renders_all_core_module_links(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Map Atlas');
        $response->assertSee('Monuments');
        $response->assertSee('Artifacts');
        $response->assertSee('Epigraphy');
        $response->assertSee('Timeline');
        $response->assertSee('Evidence Engine');
        $response->assertSee('Sources');
        $response->assertSee('API v1');
    }

    public function test_home_page_displays_live_database_statistics(): void
    {
        $sitesCount = HeritageSite::where('is_published', true)->count();
        $objectsCount = HeritageObject::count();
        $inscriptionsCount = Inscription::count();
        $sourcesCount = Source::count();
        $claimsCount = Claim::count();

        $this->assertGreaterThan(0, $sitesCount);
        $this->assertGreaterThan(0, $objectsCount);
        $this->assertGreaterThan(0, $inscriptionsCount);
        $this->assertGreaterThan(0, $sourcesCount);
        $this->assertGreaterThan(0, $claimsCount);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee((string) $sitesCount);
        $response->assertSee((string) $objectsCount);
        $response->assertSee((string) $inscriptionsCount);
        $response->assertSee((string) $sourcesCount);
        $response->assertSee((string) $claimsCount);
    }

    public function test_home_page_renders_featured_heritage_monuments(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sanchi Buddhist Monument Complex');
        $response->assertSee('Parasurameshwara Temple Complex, Gudimallam');
        $response->assertSee('Besnagar Archaeological Complex');
    }

    public function test_home_page_renders_epigraphical_facsimile_showcase(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Verbatim Brahmi Facsimiles & Archaeological Epigraphs', false);
        $response->assertSee('Brahmi');
    }

    public function test_home_page_renders_methodology_pillars_and_footer(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dual Historical Dating');
        $response->assertSee('Multi-Tiered Evidence');
        $response->assertSee('Zero Fabrication Policy');
        $response->assertSee('Open Science API');
        $response->assertSee('Archaeological Survey of India');
    }
}
