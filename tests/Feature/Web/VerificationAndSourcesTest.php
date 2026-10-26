<?php

namespace Tests\Feature\Web;

use App\Models\Claim;
use App\Models\Source;
use Database\Seeders\GudimallamSeeder;
use Database\Seeders\HeliodorusPillarSeeder;
use Database\Seeders\SanchiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationAndSourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed all verified heritage dossiers
        $this->seed([
            GudimallamSeeder::class,
            HeliodorusPillarSeeder::class,
            SanchiSeeder::class,
        ]);
    }

    public function test_verification_index_loads_with_status_200_and_renders_claims(): void
    {
        $response = $this->get('/verification');

        $response->assertStatus(200);
        $response->assertSee('Evidence Verification Engine');
        $response->assertSee('Settled', false);
        $response->assertSee('Debated', false);
        $response->assertSee('Total Claims', false);
        $response->assertSee('Evidence Nodes', false);
    }

    public function test_verification_index_filters_by_consensus_status(): void
    {
        $response = $this->get('/verification?consensus_status=ONGOING_DEBATE');

        $response->assertStatus(200);
        $response->assertSee('Ongoing Scholarly Debate');
    }

    public function test_verification_show_loads_with_status_200_and_renders_claim_dossier(): void
    {
        $claim = Claim::where('consensus_status', 'SETTLED_CONSENSUS')->firstOrFail();

        $response = $this->get('/verification/claims/' . $claim->id);

        $response->assertStatus(200);
        $response->assertSee('Formal Historical Claim');
        $response->assertSee('Supporting Archaeological & Epigraphical Evidence', false);
        $response->assertSee('Claim #' . $claim->id, false);
    }

    public function test_sources_index_loads_with_status_200_and_renders_sources(): void
    {
        $response = $this->get('/sources');

        $response->assertStatus(200);
        $response->assertSee('Archival Bibliographic Sources');
        $response->assertSee('Total Sources');
        $response->assertSee('Tier 1 Primary', false);
        $response->assertSee('Tier 2 Scholarly', false);
    }

    public function test_sources_index_filters_by_reliability_tier(): void
    {
        $response = $this->get('/sources?reliability_tier=TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY');

        $response->assertStatus(200);
        $response->assertSee('Tier 1 Primary', false);
    }

    public function test_sources_show_loads_with_status_200_and_renders_monograph_dossier(): void
    {
        $source = Source::has('evidence')->firstOrFail();

        $response = $this->get('/sources/' . $source->id);

        $response->assertStatus(200);
        $response->assertSee('Bibliographical Record & Publication Data', false);
        $response->assertSee('Evidentiary Contributions Powered by this Source', false);
        $response->assertSee($source->title);
    }
}
