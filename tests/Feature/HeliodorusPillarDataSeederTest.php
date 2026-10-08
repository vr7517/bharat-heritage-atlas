<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Dynasty;
use App\Models\Evidence;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Location;
use App\Models\Period;
use App\Models\Source;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\HeliodorusPillarSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeliodorusPillarDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_heliodorus_seeder_populates_verified_heritage_entities(): void
    {
        $this->seed(HeliodorusPillarSeeder::class);

        // 1. Verify Site and Location
        $site = HeritageSite::where('slug', 'besnagar-archaeological-complex')->first();
        $this->assertNotNull($site, 'Besnagar archaeological site must exist.');
        $this->assertEquals('Archaeological Complex & Temple Compound', $site->site_type);
        $this->assertFalse($site->is_dating_uncertain);
        $this->assertEquals(-300, $site->start_year);
        $this->assertEquals(-100, $site->end_year);

        $location = $site->location;
        $this->assertNotNull($location);
        $this->assertEquals('Besnagar', $location->name);
        $this->assertEquals('Vidisha', $location->district);
        $this->assertEquals('Madhya Pradesh', $location->state);
        $this->assertContains('Vidiśā', $location->historical_names);
        $this->assertEqualsWithDelta(23.5497, $location->latitude, 0.001);
        $this->assertEqualsWithDelta(77.8233, $location->longitude, 0.001);

        // 2. Verify Period and Dynasties
        $this->assertEquals('shunga-indo-greek-period', $site->primaryPeriod->slug);
        $this->assertEquals('Shunga', $site->primaryDynasty->name);
        $indoGreek = Dynasty::where('slug', 'indo-greek')->first();
        $this->assertNotNull($indoGreek);
        $this->assertStringContainsString('Taxila', $indoGreek->capital);

        // 3. Verify Heritage Object (Heliodorus Column)
        $object = HeritageObject::where('slug', 'heliodorus-garuda-pillar')->first();
        $this->assertNotNull($object);
        $this->assertEquals($site->id, $object->heritage_site_id);
        $this->assertEquals('Dhvaja-Stambha', $object->object_type);
        $this->assertEquals(-115, $object->start_year);
        $this->assertEquals(-110, $object->end_year);
        $this->assertFalse($object->is_dating_uncertain);
        $this->assertStringContainsString('In situ', $object->current_repository);
        $this->assertStringContainsString('sandstone', $object->material);
        $this->assertStringContainsString('5.4 m', $object->dimensions);

        // 4. Verify Inscriptions A and B
        $inscriptions = Inscription::where('object_id', $object->id)->get();
        $this->assertCount(2, $inscriptions);
        $this->assertTrue($inscriptions->contains('slug', 'heliodorus-pillar-inscription-a-dedicatory'));
        $this->assertTrue($inscriptions->contains('slug', 'heliodorus-pillar-inscription-b-ethical'));

        $inscrA = $inscriptions->firstWhere('slug', 'heliodorus-pillar-inscription-a-dedicatory');
        $this->assertEquals('Brahmi', $inscrA->script);
        $this->assertStringContainsString('Antialkidas', $inscrA->ruler_mentioned);
        $this->assertStringContainsString('Kasiputra Bhagabhadra', $inscrA->ruler_mentioned);
        $this->assertStringContainsString('Heliodoros', $inscrA->donor);
        $this->assertStringContainsString('Garuda-standard of Vasudeva', $inscrA->translation);

        $inscrB = $inscriptions->firstWhere('slug', 'heliodorus-pillar-inscription-b-ethical');
        $this->assertEquals('Brahmi', $inscrB->script);
        $this->assertStringContainsString('dama', $inscrB->translation);
        $this->assertStringContainsString('apramada', $inscrB->translation);

        // 5. Verify Sources (9 Verified Records)
        $this->assertEquals(9, Source::count());

        $vogel = Source::where('authors', 'like', '%Vogel, Jean Philippe%')->first();
        $this->assertNotNull($vogel);
        $this->assertEquals(1912, $vogel->publication_year);
        $this->assertEquals('TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY', $vogel->reliability_tier);

        $cunningham = Source::where('authors', 'like', '%Cunningham, Alexander%')->first();
        $this->assertNotNull($cunningham);
        $this->assertEquals(1880, $cunningham->publication_year);

        $khare = Source::where('authors', 'like', '%Khare, M. D.%')->first();
        $this->assertNotNull($khare);
        $this->assertStringContainsString('Lalit Kalā', $khare->journal_or_series);

        $luders = Source::where('authors', 'like', '%Lüders, Heinrich%')->first();
        $this->assertNotNull($luders);
        $this->assertEquals('EPIGRAPHIC_CORPUS', $luders->source_type);

        $salomon = Source::where('authors', 'like', '%Salomon, Richard%')->first();
        $this->assertNotNull($salomon);
        $this->assertEquals('978-0-19-509984-3', $salomon->isbn_issn);
    }

    public function test_heliodorus_claims_evidence_traceability_and_scholarly_debate(): void
    {
        $this->seed(HeliodorusPillarSeeder::class);

        $site = HeritageSite::where('slug', 'besnagar-archaeological-complex')->first();
        $object = HeritageObject::where('slug', 'heliodorus-garuda-pillar')->first();

        // 1. Verify Polymorphic Claims Count
        $this->assertCount(1, $site->claims);
        $this->assertCount(5, $object->claims);
        $this->assertEquals(6, Claim::count());

        // 2. Verify Synchronism Claim (Claim 1)
        $synchronismClaim = $object->claims->first(fn ($c) => str_contains($c->statement, 'Antialkidas'));
        $this->assertNotNull($synchronismClaim);
        $this->assertEquals('CHRONOLOGY', $synchronismClaim->claim_type);
        $this->assertEquals('SETTLED_CONSENSUS', $synchronismClaim->consensus_status);
        $this->assertCount(2, $synchronismClaim->evidence);

        $syncEvidence = $synchronismClaim->evidence->first(fn ($e) => str_contains($e->title, 'Inscriptional Synchronism'));
        $this->assertNotNull($syncEvidence);
        $this->assertEquals('STRONG_EVIDENCE', $syncEvidence->classification);
        $this->assertEquals('EPIGRAPHIC', $syncEvidence->evidence_type);
        $this->assertCount(4, $syncEvidence->sources);

        // Verify page-level citation in pivot
        $vogelPivot = $syncEvidence->sources->first(fn ($s) => str_contains($s->authors, 'Vogel'));
        $this->assertNotNull($vogelPivot);
        $this->assertEquals('pp. 126–129, Plate XLV', $vogelPivot->pivot->specific_pages);
        $this->assertStringContainsString('Heliodorena bhagavatena', $vogelPivot->pivot->direct_quotation_or_data);

        // 3. Verify Greek Convert Claim (Claim 3)
        $convertClaim = $object->claims->first(fn ($c) => str_contains($c->statement, 'ethnic Greek (Yona)'));
        $this->assertNotNull($convertClaim);
        $this->assertEquals('CULTURAL_TRANSMISSION', $convertClaim->claim_type);
        $this->assertEquals('SETTLED_CONSENSUS', $convertClaim->consensus_status);

        $greekEvidence = $convertClaim->evidence->first(fn ($e) => str_contains($e->title, 'Bhagavata and Yonaduta'));
        $this->assertNotNull($greekEvidence);
        $this->assertTrue($greekEvidence->sources->contains(fn ($s) => str_contains($s->authors, 'Salomon')));

        // 4. Verify Mahabharata Ethical Triad Claim (Claim 4)
        $ethicalClaim = $object->claims->first(fn ($c) => str_contains($c->statement, 'ethical triad'));
        $this->assertNotNull($ethicalClaim);
        $this->assertEquals('PHILOLOGICAL', $ethicalClaim->claim_type);
        $this->assertEquals('SETTLED_CONSENSUS', $ethicalClaim->consensus_status);
        $this->assertCount(2, $ethicalClaim->evidence);

        // 5. Verify Elliptical Temple Claim on Site (Claim 5)
        $templeClaim = $site->claims->first(fn ($c) => str_contains($c->statement, 'elliptical temple'));
        $this->assertNotNull($templeClaim);
        $this->assertEquals('ARCHAEOLOGICAL_STRATIGRAPHY', $templeClaim->claim_type);
        $this->assertEquals('STRONG_CONSENSUS', $templeClaim->consensus_status);
        $this->assertCount(3, $templeClaim->evidence);

        $khareEvidence = $templeClaim->evidence->first(fn ($e) => str_contains($e->title, 'Khare'));
        $this->assertNotNull($khareEvidence);
        $this->assertEquals('ARCHAEOLOGICAL', $khareEvidence->evidence_type);

        $steelWedgeEvidence = $templeClaim->evidence->first(fn ($e) => str_contains($e->title, 'Steel Wedge'));
        $this->assertNotNull($steelWedgeEvidence);
        $this->assertStringContainsString('steel of fine quality', $steelWedgeEvidence->sources->first()->pivot->direct_quotation_or_data);

        // 6. Verify Scholarly Debate on King Bhagabhadra (Claim 6)
        $debateClaim = $object->claims->first(fn ($c) => str_contains($c->statement, 'historiographical debate'));
        $this->assertNotNull($debateClaim);
        $this->assertEquals('HISTORICAL_ATTRIBUTION', $debateClaim->claim_type);
        $this->assertEquals('ONGOING_DEBATE', $debateClaim->consensus_status);
        $this->assertStringContainsString('Bhandarkar', $debateClaim->summary_justification);
    }

    public function test_complete_database_seeder_executes_coexisting_heritage_sites_without_conflict(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Verify co-existence of both Gudimallam and Heliodorus Pillar records
        $this->assertEquals(2, HeritageSite::count());
        $this->assertEquals(2, HeritageObject::count());
        $this->assertEquals(4, Inscription::count());
        $this->assertEquals(17, Source::count()); // 8 Gudimallam + 9 Heliodorus
        $this->assertEquals(11, Claim::count());  // 5 Gudimallam + 6 Heliodorus

        $this->assertTrue(HeritageSite::where('slug', 'parasurameshwara-temple-gudimallam')->exists());
        $this->assertTrue(HeritageSite::where('slug', 'besnagar-archaeological-complex')->exists());
        $this->assertTrue(HeritageObject::where('slug', 'gudimallam-anthropomorphic-shiva-linga')->exists());
        $this->assertTrue(HeritageObject::where('slug', 'heliodorus-garuda-pillar')->exists());
    }
}
