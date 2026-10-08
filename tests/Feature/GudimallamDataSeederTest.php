<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Evidence;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Location;
use App\Models\Period;
use App\Models\Source;
use Database\Seeders\GudimallamSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GudimallamDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_gudimallam_seeder_populates_verified_heritage_entities(): void
    {
        $this->seed(GudimallamSeeder::class);

        // 1. Verify Site and Location
        $site = HeritageSite::where('slug', 'parasurameshwara-temple-gudimallam')->first();
        $this->assertNotNull($site, 'Gudimallam site must exist.');
        $this->assertEquals('Temple Complex', $site->site_type);
        $this->assertTrue($site->is_dating_uncertain);
        $this->assertEquals(-300, $site->start_year);
        $this->assertEquals(1200, $site->end_year);

        $location = $site->location;
        $this->assertNotNull($location);
        $this->assertEquals('Gudimallam', $location->name);
        $this->assertEquals('Tirupati', $location->district);
        $this->assertEquals('Andhra Pradesh', $location->state);
        $this->assertContains('Tiru-vippirambedu', $location->historical_names);

        // 2. Verify Period and Dynasty
        $this->assertEquals('early-historic', $site->primaryPeriod->slug);
        $this->assertEquals('Satavahana', $site->primaryDynasty->name);

        // 3. Verify Object
        $object = HeritageObject::where('slug', 'gudimallam-anthropomorphic-shiva-linga')->first();
        $this->assertNotNull($object);
        $this->assertEquals($site->id, $object->heritage_site_id);
        $this->assertEquals('Linga', $object->object_type);
        $this->assertEquals(-300, $object->start_year);
        $this->assertEquals(-50, $object->end_year);
        $this->assertTrue($object->is_dating_uncertain);
        $this->assertStringContainsString('dolerite', $object->material);

        // 4. Verify Inscriptions
        $inscriptions = Inscription::where('heritage_site_id', $site->id)->get();
        $this->assertCount(2, $inscriptions);
        $this->assertTrue($inscriptions->contains('slug', 'gudimallam-bana-inscription-vijayaditya'));
        $this->assertTrue($inscriptions->contains('slug', 'gudimallam-chola-inscription-parantaka-i'));

        // 5. Verify Sources (8 Verified Records)
        $this->assertEquals(8, Source::count());
        $sarmaSource = Source::where('authors', 'like', '%Sarma, Inguva Karthikeya%')->first();
        $this->assertNotNull($sarmaSource);
        $this->assertEquals(1982, $sarmaSource->publication_year);
        $this->assertEquals('TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY', $sarmaSource->reliability_tier);

        $coomaraswamySource = Source::where('authors', 'like', '%Coomaraswamy%')->first();
        $this->assertNotNull($coomaraswamySource);
        $this->assertEquals(1927, $coomaraswamySource->publication_year);
    }

    public function test_gudimallam_claims_evidence_traceability_and_scholarly_debate(): void
    {
        $this->seed(GudimallamSeeder::class);

        $site = HeritageSite::where('slug', 'parasurameshwara-temple-gudimallam')->first();
        $object = HeritageObject::where('slug', 'gudimallam-anthropomorphic-shiva-linga')->first();

        // 1. Verify Polymorphic Claims Count
        $this->assertCount(2, $site->claims);
        $this->assertCount(3, $object->claims);
        $this->assertEquals(5, Claim::count());

        // 2. Verify Stratigraphic Claim on Site
        $stratigraphyClaim = $site->claims->first(fn ($c) => str_contains($c->statement, 'Phase I'));
        $this->assertNotNull($stratigraphyClaim);
        $this->assertEquals('STRONG_CONSENSUS', $stratigraphyClaim->consensus_status);
        $this->assertCount(2, $stratigraphyClaim->evidence);

        $excavationEvidence = $stratigraphyClaim->evidence->first(fn ($e) => str_contains($e->title, 'ASI Stratigraphic Excavation'));
        $this->assertNotNull($excavationEvidence);

        $this->assertEquals('STRONG_EVIDENCE', $excavationEvidence->classification);
        $this->assertEquals('ARCHAEOLOGICAL', $excavationEvidence->evidence_type);
        $this->assertCount(2, $excavationEvidence->sources);

        // Verify page-level citation in pivot
        $pivotSource = $excavationEvidence->sources->firstWhere('publication_year', 1982);
        $this->assertNotNull($pivotSource);
        $this->assertEquals('pp. 43–58, Plates 10–18', $pivotSource->pivot->specific_pages);
        $this->assertNotEmpty($pivotSource->pivot->direct_quotation_or_data);

        // 3. Verify Scholarly Debate Claim on Object
        $chronologyDebateClaim = $object->claims->firstWhere('claim_type', 'CHRONOLOGY');
        $this->assertNotNull($chronologyDebateClaim);
        $this->assertEquals('ONGOING_DEBATE', $chronologyDebateClaim->consensus_status);
        $this->assertCount(2, $chronologyDebateClaim->evidence);

        $supportingEvidence = $chronologyDebateClaim->evidence->firstWhere('pivot.relationship_type', 'SUPPORTS');
        $this->assertNotNull($supportingEvidence);
        $this->assertEquals('STRONG_EVIDENCE', $supportingEvidence->classification);

        $complicatingEvidence = $chronologyDebateClaim->evidence->firstWhere('pivot.relationship_type', 'COMPLICATES');
        $this->assertNotNull($complicatingEvidence);
        $this->assertEquals('SCHOLARLY_DEBATE', $complicatingEvidence->classification);
        $this->assertCount(1, $complicatingEvidence->sources);
        $this->assertStringContainsString('Coomaraswamy', $complicatingEvidence->sources->first()->authors);
        $this->assertEquals('pp. 67–68, Fig. 66', $complicatingEvidence->sources->first()->pivot->specific_pages);

        // 4. Verify Iconographic Claim
        $iconographyClaim = $object->claims->first(fn ($c) => str_contains($c->statement, 'Vedic'));
        $this->assertNotNull($iconographyClaim);
        $this->assertEquals('STRONG_CONSENSUS', $iconographyClaim->consensus_status);
        $this->assertCount(1, $iconographyClaim->evidence);
        $this->assertEquals('LITERARY', $iconographyClaim->evidence->first()->evidence_type);
        $this->assertEquals('GOOD_EVIDENCE', $iconographyClaim->evidence->first()->classification);
    }
}
