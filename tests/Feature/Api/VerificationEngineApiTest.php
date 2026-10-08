<?php

namespace Tests\Feature\Api;

use App\Models\Claim;
use App\Models\Evidence;
use App\Models\Source;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationEngineApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_inscriptions_index_returns_paginated_list_and_filters(): void
    {
        // 1. Base listing
        $response = $this->getJson('/api/v1/inscriptions');
        $response->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertEquals(8, $response->json('meta.total'));

        // 2. Filter by script
        $brahmiRes = $this->getJson('/api/v1/inscriptions?script=Brahmi');
        $brahmiRes->assertStatus(200);
        $this->assertEquals(6, $brahmiRes->json('meta.total'));

        // 3. Filter by language (Tamil)
        $tamilRes = $this->getJson('/api/v1/inscriptions?language=Tamil');
        $tamilRes->assertStatus(200);
        $this->assertEquals(2, $tamilRes->json('meta.total'));

        // 4. Filter by ruler mentioned
        $rulerRes = $this->getJson('/api/v1/inscriptions?ruler=Antialkidas');
        $rulerRes->assertStatus(200);
        $this->assertCount(1, $rulerRes->json('data'));
        $this->assertEquals('heliodorus-pillar-inscription-a-dedicatory', $rulerRes->json('data.0.slug'));

        // 5. Keyword search in raw text
        $searchRes = $this->getJson('/api/v1/inscriptions?search=damtakārehi');
        $searchRes->assertStatus(200);
        $this->assertCount(1, $searchRes->json('data'));
        $this->assertEquals('sanchi-vidisha-ivory-carvers-inscription', $searchRes->json('data.0.slug'));
    }

    public function test_inscriptions_show_returns_verbatim_text_and_relations(): void
    {
        $response = $this->getJson('/api/v1/inscriptions/heliodorus-pillar-inscription-a-dedicatory');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'heliodorus-pillar-inscription-a-dedicatory')
            ->assertJsonPath('data.script', 'Brahmi')
            ->assertJsonPath('data.site.slug', 'besnagar-archaeological-complex')
            ->assertJsonPath('data.object.slug', 'heliodorus-garuda-pillar');

        $this->assertStringContainsString('Devadevasa Vā[sude]vasa', $response->json('data.raw_text'));
        $this->assertStringContainsString('Garuda-standard of Vasudeva', $response->json('data.translation'));

        // 404 for nonexistent slug
        $notFound = $this->getJson('/api/v1/inscriptions/non-existent-epigraph');
        $notFound->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_sources_index_returns_paginated_list_and_tier_filtering(): void
    {
        $response = $this->getJson('/api/v1/sources');
        $response->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertEquals(26, $response->json('meta.total'));

        // Filter by reliability tier
        $tier1Res = $this->getJson('/api/v1/sources?reliability_tier=TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY');
        $tier1Res->assertStatus(200);
        $this->assertGreaterThan(0, $tier1Res->json('meta.total'));

        // Filter by author
        $authorRes = $this->getJson('/api/v1/sources?author=Cunningham');
        $authorRes->assertStatus(200);
        $this->assertCount(2, $authorRes->json('data')); // Cunningham 1854 (Sanchi) and Cunningham 1880 (Besnagar)

        // Filter by source type
        $typeRes = $this->getJson('/api/v1/sources?source_type=EPIGRAPHIC_CORPUS');
        $typeRes->assertStatus(200);
        $this->assertGreaterThan(0, $typeRes->json('meta.total'));
    }

    public function test_sources_show_returns_details_and_handles_404(): void
    {
        $source = Source::where('authors', 'like', '%Marshall%')->first();
        $this->assertNotNull($source);

        $response = $this->getJson("/api/v1/sources/{$source->id}");
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $source->id)
            ->assertJsonPath('data.reliability_tier', $source->reliability_tier);

        // 404 for nonexistent ID
        $notFound = $this->getJson('/api/v1/sources/99999');
        $notFound->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_claims_show_returns_verification_breakdown_with_evidence(): void
    {
        $claim = Claim::where('statement', 'like', '%Antialkidas%')->first();
        $this->assertNotNull($claim);

        $response = $this->getJson("/api/v1/claims/{$claim->id}");
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $claim->id)
            ->assertJsonPath('data.consensus_status', 'SETTLED_CONSENSUS')
            ->assertJsonPath('data.entity.type', 'HeritageObject');

        $evidence = $response->json('data.evidence');
        $this->assertNotEmpty($evidence);

        // Verify backing sources and citation pages inside evidence
        $firstEv = $evidence[0];
        $this->assertNotEmpty($firstEv['sources']);
        $firstSource = $firstEv['sources'][0];
        $this->assertArrayHasKey('citation', $firstSource);
        $this->assertNotNull($firstSource['citation']['specific_pages']);

        // 404 for nonexistent ID
        $notFound = $this->getJson('/api/v1/claims/99999');
        $notFound->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_evidence_show_returns_methodology_and_sources(): void
    {
        $evidence = Evidence::where('title', 'like', '%Stratigraphic%')->first();
        $this->assertNotNull($evidence);

        $response = $this->getJson("/api/v1/evidence/{$evidence->id}");
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $evidence->id)
            ->assertJsonPath('data.evidence_type', 'ARCHAEOLOGICAL');

        $this->assertNotEmpty($response->json('data.sources'));

        // 404 for nonexistent ID
        $notFound = $this->getJson('/api/v1/evidence/99999');
        $notFound->assertStatus(404)
            ->assertJsonPath('success', false);
    }
}
