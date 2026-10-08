<?php

namespace Tests\Feature\Web;

use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use Database\Seeders\GudimallamSeeder;
use Database\Seeders\HeliodorusPillarSeeder;
use Database\Seeders\SanchiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineViewTest extends TestCase
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

    public function test_timeline_page_loads_with_status_200_and_renders_layout(): void
    {
        $response = $this->get('/timeline');

        $response->assertStatus(200);
        $response->assertSee('Chronological Heritage Timeline');
        $response->assertSee('Dual-era continuous timeline synchronizing monumental architecture');
        $response->assertSee('id="timeline-stream"', false);
    }

    public function test_timeline_page_renders_dual_era_scrubber_controls_and_presets(): void
    {
        $response = $this->get('/timeline');

        $response->assertStatus(200);
        $response->assertSee('Dual Historical Dating Scrubber');
        $response->assertSee('id="slider-from-year"', false);
        $response->assertSee('id="slider-to-year"', false);
        $response->assertSee('Historical Presets:');
        $response->assertSee('Mauryan Imperial');
        $response->assertSee('Shunga & Indo-Greek', false);
        $response->assertSee('Satavahana & Early Historic', false);
    }

    public function test_timeline_page_renders_entity_filter_buttons_with_counts(): void
    {
        $sitesCount = HeritageSite::where('is_published', true)->count();
        $objectsCount = HeritageObject::where('is_published', true)->count();
        $inscriptionsCount = Inscription::where('is_published', true)->count();
        $totalCount = $sitesCount + $objectsCount + $inscriptionsCount;

        $response = $this->get('/timeline');

        $response->assertStatus(200);
        $response->assertSee("All Entities (<span id=\"count-all\">{$totalCount}</span>)", false);
        $response->assertSee("Monuments (<span id=\"count-sites\">{$sitesCount}</span>)", false);
        $response->assertSee("Artifacts & Relics (<span id=\"count-objects\">{$objectsCount}</span>)", false);
        $response->assertSee("Epigraphs (<span id=\"count-inscriptions\">{$inscriptionsCount}</span>)", false);
    }

    public function test_timeline_page_embeds_api_feed_endpoint(): void
    {
        $response = $this->get('/timeline');

        $response->assertStatus(200);
        $response->assertSee('/api/v1/timeline');
        $response->assertSee('Timeline JSON API');
    }

    public function test_timeline_api_feed_returns_chronologically_ordered_stream(): void
    {
        $response = $this->getJson('/api/v1/timeline?from_year=-400&to_year=1200&limit=100');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'entity_type',
                    'id',
                    'name',
                    'slug',
                    'category',
                    'chronology' => [
                        'start_year',
                        'end_year',
                        'dating_statement',
                    ],
                ],
            ],
            'meta' => [
                'count',
                'total_matching',
                'limit',
                'range',
            ],
        ]);

        $events = $response->json('data');
        $this->assertNotEmpty($events);

        // Verify astronomical start_year ordering ascending
        $years = array_column(array_column($events, 'chronology'), 'start_year');
        $sortedYears = $years;
        sort($sortedYears, SORT_NUMERIC);

        $this->assertEquals($sortedYears, $years, 'Timeline events must be strictly sorted by start_year ascending.');
    }
}
