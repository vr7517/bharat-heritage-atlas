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
use Database\Seeders\SanchiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SanchiDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_sanchi_seeder_populates_verified_heritage_entities(): void
    {
        $this->seed(SanchiSeeder::class);

        // 1. Verify Site and Location
        $site = HeritageSite::where('slug', 'sanchi-archaeological-complex')->first();
        $this->assertNotNull($site, 'Sanchi archaeological site must exist.');
        $this->assertEquals('Buddhist Monastic & Stupa Complex', $site->site_type);
        $this->assertFalse($site->is_dating_uncertain);
        $this->assertEquals(-250, $site->start_year);
        $this->assertEquals(1100, $site->end_year);
        $this->assertStringContainsString('UNESCO World Heritage Site', $site->protection_status);

        $location = $site->location;
        $this->assertNotNull($location);
        $this->assertEquals('Sanchi', $location->name);
        $this->assertEquals('Raisen', $location->district);
        $this->assertEquals('Madhya Pradesh', $location->state);
        $this->assertContains('Kākanāya', $location->historical_names);
        $this->assertContains('Chetiyagiri', $location->historical_names);
        $this->assertEqualsWithDelta(23.4794, $location->latitude, 0.001);
        $this->assertEqualsWithDelta(77.7394, $location->longitude, 0.001);

        // 2. Verify Periods and Dynasties
        $this->assertEquals('mauryan-period', $site->primaryPeriod->slug);
        $this->assertEquals('Mauryan Empire', $site->primaryDynasty->name);
        $this->assertTrue(Dynasty::where('slug', 'shunga')->exists());
        $this->assertTrue(Dynasty::where('slug', 'satavahana')->exists());

        // 3. Verify Heritage Objects (3 Objects)
        $objects = HeritageObject::where('heritage_site_id', $site->id)->get();
        $this->assertCount(3, $objects);

        $greatStupa = $objects->firstWhere('slug', 'sanchi-great-stupa-1');
        $this->assertNotNull($greatStupa);
        $this->assertEquals('Stupa', $greatStupa->object_type);
        $this->assertEquals(-250, $greatStupa->start_year);
        $this->assertEquals(-20, $greatStupa->end_year);
        $this->assertStringContainsString('36.6 m', $greatStupa->dimensions);
        $this->assertStringContainsString('In situ', $greatStupa->current_repository);

        $ashokanPillar = $objects->firstWhere('slug', 'sanchi-ashokan-pillar-25');
        $this->assertNotNull($ashokanPillar);
        $this->assertEquals('Monolithic Pillar', $ashokanPillar->object_type);
        $this->assertStringContainsString('mirror polish', $ashokanPillar->material);
        $this->assertEquals(-260, $ashokanPillar->start_year);

        $reliquaries = $objects->firstWhere('slug', 'sanchi-stupa-3-disciples-reliquaries');
        $this->assertNotNull($reliquaries);
        $this->assertEquals('Reliquary / Casket', $reliquaries->object_type);
        $this->assertStringContainsString('Steatite', $reliquaries->material);

        // 4. Verify Inscriptions (4 Inscriptions)
        $inscriptions = Inscription::where('heritage_site_id', $site->id)->get();
        $this->assertCount(4, $inscriptions);

        $schismEdict = $inscriptions->firstWhere('slug', 'sanchi-ashokan-schism-edict');
        $this->assertNotNull($schismEdict);
        $this->assertEquals('Brahmi', $schismEdict->script);
        $this->assertEquals('Emperor Ashoka', $schismEdict->ruler_mentioned);
        $this->assertStringContainsString('Ye saṃghe bhokhati', $schismEdict->raw_text);

        $ivoryCarvers = $inscriptions->firstWhere('slug', 'sanchi-vidisha-ivory-carvers-inscription');
        $this->assertNotNull($ivoryCarvers);
        $this->assertStringContainsString('Vedisakehi damtakārehi', $ivoryCarvers->raw_text);
        $this->assertEquals('Done by the ivory carvers of Vidisha.', $ivoryCarvers->translation);

        $satakarni = $inscriptions->firstWhere('slug', 'sanchi-satakarni-south-gateway-inscription');
        $this->assertNotNull($satakarni);
        $this->assertStringContainsString('Satakarni', $satakarni->ruler_mentioned);

        $relicInscr = $inscriptions->firstWhere('slug', 'sanchi-stupa-3-disciples-relic-inscriptions');
        $this->assertNotNull($relicInscr);
        $this->assertStringContainsString('Sāriputasa', $relicInscr->raw_text);
        $this->assertStringContainsString('Maha-Maudgalyayana', $relicInscr->translation);

        // 5. Verify Sources (9 Verified Records)
        $this->assertEquals(9, Source::count());

        $cunningham = Source::where('authors', 'like', '%Cunningham%')->first();
        $this->assertNotNull($cunningham);
        $this->assertEquals(1854, $cunningham->publication_year);

        $marshall = Source::where('title', 'like', '%Monuments of Sāñchī%')->first();
        $this->assertNotNull($marshall);
        $this->assertEquals(1940, $marshall->publication_year);

        $hultzsch = Source::where('authors', 'like', '%Hultzsch%')->first();
        $this->assertNotNull($hultzsch);
        $this->assertStringContainsString('Inscriptions of Asoka', $hultzsch->title);
    }

    public function test_sanchi_claims_evidence_traceability_and_scholarly_debate(): void
    {
        $this->seed(SanchiSeeder::class);

        $site = HeritageSite::where('slug', 'sanchi-archaeological-complex')->first();
        $greatStupa = HeritageObject::where('slug', 'sanchi-great-stupa-1')->first();
        $ashokanPillar = HeritageObject::where('slug', 'sanchi-ashokan-pillar-25')->first();
        $reliquaries = HeritageObject::where('slug', 'sanchi-stupa-3-disciples-reliquaries')->first();

        // 1. Verify Polymorphic Claims Count
        $this->assertCount(1, $site->claims);
        $this->assertCount(4, $greatStupa->claims);
        $this->assertCount(1, $ashokanPillar->claims);
        $this->assertCount(1, $reliquaries->claims);
        $this->assertEquals(7, Claim::count());

        // 2. Verify Ashokan Brick Core Stratigraphy (Claim 1)
        $brickClaim = $greatStupa->claims->first(fn ($c) => str_contains($c->statement, 'hemispherical brick tumulus'));
        $this->assertNotNull($brickClaim);
        $this->assertEquals('ARCHAEOLOGICAL_STRATIGRAPHY', $brickClaim->claim_type);
        $this->assertEquals('SETTLED_CONSENSUS', $brickClaim->consensus_status);
        $this->assertCount(1, $brickClaim->evidence);

        $brickEvidence = $brickClaim->evidence->first();
        $this->assertEquals('ARCHAEOLOGICAL', $brickEvidence->evidence_type);
        $this->assertEquals('STRONG_EVIDENCE', $brickEvidence->classification);
        $this->assertCount(2, $brickEvidence->sources);

        $marshallPivot = $brickEvidence->sources->first(fn ($s) => str_contains($s->title, 'Monuments of Sāñchī'));
        $this->assertNotNull($marshallPivot);
        $this->assertEquals('Vol. I, pp. 21–25', $marshallPivot->pivot->specific_pages);
        $this->assertStringContainsString('original stupa of Asoka was a brick structure', $marshallPivot->pivot->direct_quotation_or_data);

        // 3. Verify Vidisha Ivory Carvers Inscription (Claim 4)
        $ivoryClaim = $greatStupa->claims->first(fn ($c) => str_contains($c->statement, 'Guild of Ivory Carvers'));
        $this->assertNotNull($ivoryClaim);
        $this->assertEquals('EPIGRAPHIC', $ivoryClaim->claim_type);
        $this->assertCount(2, $ivoryClaim->evidence);

        $ivoryEvidence = $ivoryClaim->evidence->first(fn ($e) => str_contains($e->title, 'Vidisha Ivory Carvers'));
        $this->assertNotNull($ivoryEvidence);
        $ludersPivot = $ivoryEvidence->sources->first(fn ($s) => str_contains($s->authors, 'Lüders'));
        $this->assertNotNull($ludersPivot);
        $this->assertEquals('p. 36 (No. 345)', $ludersPivot->pivot->specific_pages);
        $this->assertEquals('Vedisakehi damtakarehi rupakammam katam', $ludersPivot->pivot->direct_quotation_or_data);

        // 4. Verify Aniconic Representation (Claim 5)
        $aniconicClaim = $greatStupa->claims->first(fn ($c) => str_contains($c->statement, 'aniconic symbols'));
        $this->assertNotNull($aniconicClaim);
        $this->assertEquals('ART_HISTORICAL', $aniconicClaim->claim_type);

        // 5. Verify Stupa 3 Disciples Relics (Claim 6)
        $relicClaim = $reliquaries->claims->first(fn ($c) => str_contains($c->statement, 'Sariputra and Maha-Maudgalyayana'));
        $this->assertNotNull($relicClaim);
        $this->assertEquals('RELIC_ARCHAEOLOGY', $relicClaim->claim_type);

        $relicEvidence = $relicClaim->evidence->first();
        $cunninghamPivot = $relicEvidence->sources->first(fn ($s) => str_contains($s->authors, 'Cunningham'));
        $this->assertNotNull($cunninghamPivot);
        $this->assertEquals('pp. 295–299, Plates XXI–XXII', $cunninghamPivot->pivot->specific_pages);

        // 6. Verify Historiographical Debate on Shunga Policy (Claim 7 on Site)
        $shungaDebateClaim = $site->claims->first(fn ($c) => str_contains($c->statement, 'Pushyamitra Shunga'));
        $this->assertNotNull($shungaDebateClaim);
        $this->assertEquals('HISTORICAL_ATTRIBUTION', $shungaDebateClaim->claim_type);
        $this->assertEquals('ONGOING_DEBATE', $shungaDebateClaim->consensus_status);
        $this->assertCount(2, $shungaDebateClaim->evidence);
    }

    public function test_all_three_milestone_seeders_run_cleanly_together(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Verify co-existence of all 3 foundational heritage records
        $this->assertEquals(3, HeritageSite::count(), 'All 3 heritage sites must be seeded.');
        $this->assertEquals(5, HeritageObject::count(), 'All 5 heritage objects must be seeded.');
        $this->assertEquals(8, Inscription::count(), 'All 8 primary inscriptions must be seeded.');
        $this->assertEquals(26, Source::count(), 'All 26 verified bibliographic sources must be seeded.');
        $this->assertEquals(18, Claim::count(), 'All 18 atomized historical claims must be seeded.');

        // Sites presence
        $this->assertTrue(HeritageSite::where('slug', 'parasurameshwara-temple-gudimallam')->exists());
        $this->assertTrue(HeritageSite::where('slug', 'besnagar-archaeological-complex')->exists());
        $this->assertTrue(HeritageSite::where('slug', 'sanchi-archaeological-complex')->exists());

        // Objects presence
        $this->assertTrue(HeritageObject::where('slug', 'gudimallam-anthropomorphic-shiva-linga')->exists());
        $this->assertTrue(HeritageObject::where('slug', 'heliodorus-garuda-pillar')->exists());
        $this->assertTrue(HeritageObject::where('slug', 'sanchi-great-stupa-1')->exists());
        $this->assertTrue(HeritageObject::where('slug', 'sanchi-ashokan-pillar-25')->exists());
        $this->assertTrue(HeritageObject::where('slug', 'sanchi-stupa-3-disciples-reliquaries')->exists());
    }
}
