<?php

namespace Tests\Feature\Admin;

use App\Models\Claim;
use App\Models\Evidence;
use App\Models\HeritageSite;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClaimAndEvidenceManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;
    protected HeritageSite $site;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'BHA Admin Curator',
            'email' => 'admin@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => true,
        ]);

        $this->regularUser = User::create([
            'name' => 'Regular Researcher',
            'email' => 'researcher@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => false,
        ]);

        $this->site = HeritageSite::create([
            'name' => 'Sanchi Stupa 1',
            'slug' => 'sanchi-stupa-1',
            'site_type' => 'Buddhist Stupa Complex',
            'dating_statement' => 'c. 3rd century BCE – 1st century CE',
            'start_year' => -300,
            'end_year' => 100,
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Principal Great Stupa at Sanchi with monumental carved toranas.',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_add_scholarly_claim_to_heritage_site(): void
    {
        $payload = [
            'claimable_type' => 'site',
            'claimable_id' => $this->site->id,
            'claim_type' => 'CHRONOLOGY',
            'statement' => 'The hemispherical core was initiated during the reign of Ashoka Maurya.',
            'consensus_status' => 'STRONG_CONSENSUS',
            'summary_justification' => 'Corroborated by the nearby Ashokan monolithic pillar and early burnt-brick layer.',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/claims', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('claims', [
            'claimable_type' => HeritageSite::class,
            'claimable_id' => $this->site->id,
            'claim_type' => 'CHRONOLOGY',
            'statement' => 'The hemispherical core was initiated during the reign of Ashoka Maurya.',
            'consensus_status' => 'STRONG_CONSENSUS',
        ]);
    }

    public function test_admin_can_update_existing_claim(): void
    {
        $claim = $this->site->claims()->create([
            'claim_type' => 'AUTHORSHIP_PATRONAGE',
            'statement' => 'Preliminary patronage attribution.',
            'consensus_status' => 'PROBABLE',
            'summary_justification' => 'Early assessment.',
        ]);

        $payload = [
            'claim_type' => 'AUTHORSHIP_PATRONAGE',
            'statement' => 'Confirmed Satavahana-era guild collective patronage for the Southern Torana.',
            'consensus_status' => 'STRONG_CONSENSUS',
            'summary_justification' => 'Confirmed by donor epigraph of the ivory-carvers of Vidisha.',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/claims/{$claim->id}", $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('claims', [
            'id' => $claim->id,
            'statement' => 'Confirmed Satavahana-era guild collective patronage for the Southern Torana.',
            'consensus_status' => 'STRONG_CONSENSUS',
        ]);
    }

    public function test_admin_can_create_new_evidence_and_link_to_claim_with_source_citation(): void
    {
        $claim = $this->site->claims()->create([
            'claim_type' => 'CHRONOLOGY',
            'statement' => 'Core brick stupa dates to the 3rd century BCE.',
            'consensus_status' => 'STRONG_CONSENSUS',
        ]);

        $source = Source::create([
            'title' => 'The Monuments of Sanchi: Volume I',
            'authors' => 'Marshall, John; Foucher, Alfred',
            'publication_year' => 1940,
            'source_type' => 'ACADEMIC_MONOGRAPH',
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
        ]);

        $payload = [
            'evidence_mode' => 'new',
            'new_evidence_title' => 'In Situ Core Burnt-Brick Horizon (Trench S-1)',
            'new_evidence_type' => 'ARCHAEOLOGICAL',
            'new_classification' => 'STRONG_EVIDENCE',
            'new_description' => 'Uncovered large burnt bricks (40 x 25 x 7 cm) typical of the Mauryan period forming the inner core.',
            'new_stratigraphic_context' => 'Trench S-1, Layer 5, directly encasing relic core',
            'new_methodology_applied' => 'Archaeological stratigraphy and brick metrology',
            'relationship_type' => 'SUPPORTS',
            'scholarly_weight' => 'PRIMARY',
            'analysis_notes' => 'Direct primary physical stratum.',
            'source_id' => $source->id,
            'specific_pages' => 'pp. 23–27, Plate VII',
            'direct_quotation_or_data' => 'Bricks of the core stupa measured exactly to Ashokan standards.',
            'citation_context' => 'Primary excavation findings.',
        ];

        $response = $this->actingAs($this->admin)->post("/admin/claims/{$claim->id}/evidence", $payload);

        $response->assertRedirect();

        // Verify evidence created
        $evidence = Evidence::where('title', 'In Situ Core Burnt-Brick Horizon (Trench S-1)')->firstOrFail();
        $this->assertEquals('ARCHAEOLOGICAL', $evidence->evidence_type);

        // Verify pivot claim_evidence
        $this->assertDatabaseHas('claim_evidence', [
            'claim_id' => $claim->id,
            'evidence_id' => $evidence->id,
            'relationship_type' => 'SUPPORTS',
            'scholarly_weight' => 'PRIMARY',
        ]);

        // Verify pivot evidence_source
        $this->assertDatabaseHas('evidence_source', [
            'evidence_id' => $evidence->id,
            'source_id' => $source->id,
            'specific_pages' => 'pp. 23–27, Plate VII',
        ]);
    }

    public function test_admin_can_attach_existing_evidence_to_claim(): void
    {
        $claim = $this->site->claims()->create([
            'claim_type' => 'ARCHITECTURAL_STYLE',
            'statement' => 'Torana gateways exhibit carpenters joinery in sandstone.',
            'consensus_status' => 'STRONG_CONSENSUS',
        ]);

        $evidence = Evidence::create([
            'title' => 'Tenon-and-Mortise Joinery in Sanchi Stone Railings',
            'evidence_type' => 'ARCHITECTURAL_JOINERY',
            'classification' => 'CONFIRMED',
            'description' => 'Stone beams fitted using woodworking joinery techniques transposed to stone.',
        ]);

        $payload = [
            'evidence_mode' => 'existing',
            'existing_evidence_id' => $evidence->id,
            'relationship_type' => 'SUPPORTS',
            'scholarly_weight' => 'PRIMARY',
            'analysis_notes' => 'Technological transfer from timber to lithic architecture.',
        ];

        $response = $this->actingAs($this->admin)->post("/admin/claims/{$claim->id}/evidence", $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('claim_evidence', [
            'claim_id' => $claim->id,
            'evidence_id' => $evidence->id,
            'relationship_type' => 'SUPPORTS',
        ]);
    }

    public function test_admin_can_detach_evidence_node(): void
    {
        $claim = $this->site->claims()->create([
            'claim_type' => 'CHRONOLOGY',
            'statement' => 'Sample test claim',
            'consensus_status' => 'PROBABLE',
        ]);

        $evidence = Evidence::create([
            'title' => 'Test Evidence',
            'evidence_type' => 'TEXTUAL',
            'classification' => 'PROBABLE',
            'description' => 'Textual note.',
        ]);

        $claim->evidence()->attach($evidence->id, [
            'relationship_type' => 'SUPPORTS',
            'scholarly_weight' => 'CORROBORATIVE',
        ]);

        $this->assertDatabaseHas('claim_evidence', ['claim_id' => $claim->id, 'evidence_id' => $evidence->id]);

        $response = $this->actingAs($this->admin)->delete("/admin/claims/{$claim->id}/evidence/{$evidence->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('claim_evidence', ['claim_id' => $claim->id, 'evidence_id' => $evidence->id]);
        // Evidence entity itself still exists in database
        $this->assertDatabaseHas('evidence', ['id' => $evidence->id]);
    }

    public function test_admin_can_delete_claim(): void
    {
        $claim = $this->site->claims()->create([
            'claim_type' => 'CHRONOLOGY',
            'statement' => 'Obsolete claim statement',
            'consensus_status' => 'DISPUTED',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/claims/{$claim->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('claims', ['id' => $claim->id]);
    }

    public function test_admin_created_claim_dynamically_appears_on_public_monument_page(): void
    {
        // Add claim via admin endpoint
        $this->actingAs($this->admin)->post('/admin/claims', [
            'claimable_type' => 'site',
            'claimable_id' => $this->site->id,
            'claim_type' => 'AUTHORSHIP_PATRONAGE',
            'statement' => 'Donative inscription on South Gateway records ivory carvers of Vidisha.',
            'consensus_status' => 'STRONG_CONSENSUS',
            'summary_justification' => 'Direct epigraphical testimony carved on the stone architrave.',
        ]);

        // Request public page as unauthenticated guest
        $publicResponse = $this->get("/sites/{$this->site->slug}");

        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Donative inscription on South Gateway records ivory carvers of Vidisha.');
        $publicResponse->assertSee('STRONG CONSENSUS');
        $publicResponse->assertSee('Verified Evidentiary Claims', false);
    }

    public function test_guest_and_regular_user_cannot_mutate_claims_or_evidence(): void
    {
        $payload = [
            'claimable_type' => 'site',
            'claimable_id' => $this->site->id,
            'claim_type' => 'CHRONOLOGY',
            'statement' => 'Unauthorized claim',
            'consensus_status' => 'PROBABLE',
        ];

        // Guest attempts
        $this->post('/admin/claims', $payload)->assertRedirect('/admin/login');
        $this->put('/admin/claims/1', $payload)->assertRedirect('/admin/login');
        $this->delete('/admin/claims/1')->assertRedirect('/admin/login');

        // Regular user attempts
        $this->actingAs($this->regularUser)->post('/admin/claims', $payload)->assertStatus(403);
        $this->actingAs($this->regularUser)->put('/admin/claims/1', $payload)->assertStatus(403);
        $this->actingAs($this->regularUser)->delete('/admin/claims/1')->assertStatus(403);
    }
}
