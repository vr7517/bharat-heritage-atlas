<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Dynasty;
use App\Models\Evidence;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Location;
use App\Models\Media;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeritageModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_and_link_core_heritage_entities(): void
    {
        $location = Location::create([
            'name' => 'Gudimallam',
            'historical_names' => ['Gudimallam', 'Tirupati region'],
            'state' => 'Andhra Pradesh',
            'district' => 'Tirupati',
            'taluk_tehsil' => 'Yerpedu',
            'latitude' => 13.5822,
            'longitude' => 79.5847,
            'altitude_meters' => 110,
            'description' => 'Village on the banks of Suvarnamukhi river.',
        ]);

        $period = Period::create([
            'name' => 'Early Historic / Mauryan-Satavahana Transition',
            'slug' => 'early-historic',
            'dating_statement' => 'c. 3rd century BCE to 2nd century CE',
            'start_year' => -300,
            'end_year' => 200,
            'start_era' => 'BCE',
            'end_era' => 'CE',
            'description' => 'Formative phase of early historic iconography.',
        ]);

        $dynasty = Dynasty::create([
            'name' => 'Satavahana',
            'slug' => 'satavahana',
            'dating_statement' => 'c. 1st century BCE – 2nd century CE',
            'start_year' => -100,
            'end_year' => 200,
            'region' => 'Deccan and Coastal Andhra',
        ]);

        $site = HeritageSite::create([
            'name' => 'Parasurameshwara Temple Complex, Gudimallam',
            'slug' => 'gudimallam-parasurameshwara',
            'location_id' => $location->id,
            'primary_period_id' => $period->id,
            'primary_dynasty_id' => $dynasty->id,
            'site_type' => 'Temple Complex',
            'dating_statement' => 'Phase I: c. 3rd–2nd c. BCE; Phase II: c. 2nd–3rd c. CE',
            'start_year' => -300,
            'end_year' => 300,
            'is_dating_uncertain' => true,
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Site of the earliest extant sculptured anthropomorphic linga in India.',
        ]);

        $this->assertNotNull($site->id);
        $this->assertEquals('Gudimallam', $site->location->name);
        $this->assertEquals('early-historic', $site->primaryPeriod->slug);
        $this->assertEquals('Satavahana', $site->primaryDynasty->name);
    }

    public function test_can_link_objects_inscriptions_and_polymorphic_media(): void
    {
        $site = HeritageSite::create([
            'name' => 'Besnagar Heliodorus Pillar Site',
            'slug' => 'besnagar-heliodorus-pillar',
            'site_type' => 'Archaeological Site',
            'dating_statement' => 'c. late 2nd century BCE (c. 113 BCE)',
            'start_year' => -113,
            'end_year' => -113,
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Free-standing monolithic Garuda pillar.',
        ]);

        $object = HeritageObject::create([
            'name' => 'Heliodorus Garuda Pillar',
            'slug' => 'heliodorus-garuda-pillar',
            'heritage_site_id' => $site->id,
            'object_type' => 'Monolithic Pillar',
            'material' => 'Sandstone',
            'current_repository' => 'In situ (Besnagar, Vidisha)',
            'dating_statement' => 'c. 113 BCE',
            'start_year' => -113,
            'end_year' => -113,
            'description' => 'Octagonal and sixteen-sided stone column with lotus bell capital.',
        ]);

        $inscription = Inscription::create([
            'title' => 'Besnagar Garuda Pillar Inscription of Heliodoros',
            'slug' => 'besnagar-heliodoros-inscription-a',
            'heritage_site_id' => $site->id,
            'object_id' => $object->id,
            'language' => 'Prakrit',
            'script' => 'Middle Brahmi',
            'dating_statement' => '14th regnal year of King Bhagabhadra (c. 113 BCE)',
            'start_year' => -113,
            'end_year' => -113,
            'donor' => 'Heliodoros, son of Dion',
            'ruler_mentioned' => 'King Kasiputra Bhagabhadra; King Antialkidas',
            'translation' => 'This Garuda-standard of Vasudeva, the god of gods, was erected here by Heliodoros, a Bhagavata...',
        ]);

        $siteMedia = Media::create([
            'mediable_type' => HeritageSite::class,
            'mediable_id' => $site->id,
            'file_path' => 'media/sites/besnagar-pillar.jpg',
            'caption' => 'General view of the Heliodorus Pillar at Besnagar',
            'alt_text' => 'Ancient stone pillar in open landscape',
            'media_type' => 'PHOTOGRAPH',
            'license' => 'CC BY-SA 4.0',
            'source_credit' => 'Archaeological Survey of India Archive',
        ]);

        $this->assertCount(1, $site->objects);
        $this->assertCount(1, $site->inscriptions);
        $this->assertEquals($object->id, $inscription->object->id);
        $this->assertCount(1, $site->media);
        $this->assertEquals('media/sites/besnagar-pillar.jpg', $site->media->first()->file_path);
    }

    public function test_evidence_engine_claim_evidence_and_source_traceability(): void
    {
        $site = HeritageSite::create([
            'name' => 'Gudimallam Temple',
            'slug' => 'gudimallam',
            'site_type' => 'Temple',
            'dating_statement' => 'c. 3rd c. BCE to 10th c. CE',
            'protection_status' => 'ASI Protected',
            'summary' => 'Ancient Shaiva shrine.',
        ]);

        // 1. Create Source
        $source1 = Source::create([
            'title' => 'The Development of Early Saiva Art and Architecture',
            'authors' => 'Sarma, Inguva Karthikeya',
            'publication_year' => 1982,
            'source_type' => 'ACADEMIC_MONOGRAPH',
            'publisher' => 'Sundeep Prakashan, Delhi',
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
            'notes' => 'Contains primary stratigraphic excavation reports of 1973-74 at Gudimallam.',
        ]);

        $source2 = Source::create([
            'title' => 'History of Indian and Indonesian Art',
            'authors' => 'Coomaraswamy, Ananda K.',
            'publication_year' => 1927,
            'source_type' => 'ACADEMIC_MONOGRAPH',
            'publisher' => 'Edward Goldston, London',
            'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
            'notes' => 'Early stylistic dating proposal placing linga in 1st century BCE.',
        ]);

        // 2. Create Evidence records
        $evidenceStratigraphy = Evidence::create([
            'title' => 'ASI Stratigraphic Excavation in Gudimallam Sanctum (1973–74)',
            'evidence_type' => 'ARCHAEOLOGICAL',
            'classification' => 'STRONG_EVIDENCE',
            'description' => 'Excavation revealed the linga was set in Phase I (c. 3rd–2nd c. BCE) within a two-tiered square stone railing and brick floor.',
            'stratigraphic_context' => 'Period I, Floor level 1 (silvers of Megalithic Black-and-Red ware)',
            'methodology_applied' => 'Controlled stratigraphic excavation with pottery typology',
        ]);

        $evidenceStylistic = Evidence::create([
            'title' => 'Stylistic Comparison with Bharhut Yakshas',
            'evidence_type' => 'ART_HISTORICAL',
            'classification' => 'SCHOLARLY_DEBATE',
            'description' => 'Drapery, turban, and ear ornaments compared stylistically to 1st century BCE Sunga figures.',
            'methodology_applied' => 'Comparative iconographic and stylistic analysis',
        ]);

        // 3. Link Evidence to Sources via pivot
        $evidenceStratigraphy->sources()->attach($source1->id, [
            'specific_pages' => 'pp. 43–68, Plates 12–19',
            'direct_quotation_or_data' => 'The linga was in situ on a square floor of burnt bricks datable to Phase I.',
            'citation_context' => 'Primary excavation report detailing sanctum foundation.',
        ]);

        $evidenceStylistic->sources()->attach($source2->id, [
            'specific_pages' => 'p. 67, Fig. 66',
            'direct_quotation_or_data' => 'The figure exhibits stylistic affinities with later Sunga sculptures.',
            'citation_context' => 'Early art historical stylistic assessment prior to 1973 excavations.',
        ]);

        // 4. Create Claim attached polymorphically to HeritageSite
        $claim = Claim::create([
            'claimable_type' => HeritageSite::class,
            'claimable_id' => $site->id,
            'claim_type' => 'CHRONOLOGY',
            'statement' => 'The original erect linga at Gudimallam dates to the 3rd–2nd century BCE in its primary foundation phase.',
            'consensus_status' => 'STRONG_CONSENSUS',
            'summary_justification' => 'Corroborated by ASI stratigraphic excavations under I.K. Sarma.',
        ]);

        // 5. Link Claim to Evidence (both supporting and conflicting/debated)
        $claim->evidence()->attach($evidenceStratigraphy->id, [
            'relationship_type' => 'SUPPORTS',
            'scholarly_weight' => 'PRIMARY',
            'analysis_notes' => 'Physical in situ stratigraphy confirms pre-Christian foundation.',
        ]);

        $claim->evidence()->attach($evidenceStylistic->id, [
            'relationship_type' => 'COMPLICATES',
            'scholarly_weight' => 'HISTORICAL_ALTERNATIVE',
            'analysis_notes' => 'Purely stylistic analysis suggests late 1st century BCE, but is superseded by physical excavation strata.',
        ]);

        // 6. Assert Traceability Chain
        $this->assertCount(1, $site->claims);
        $retrievedClaim = $site->claims->first();
        $this->assertEquals('CHRONOLOGY', $retrievedClaim->claim_type);

        $this->assertCount(2, $retrievedClaim->evidence);

        $supportingEvidence = $retrievedClaim->evidence->firstWhere('pivot.relationship_type', 'SUPPORTS');
        $this->assertNotNull($supportingEvidence);
        $this->assertEquals('STRONG_EVIDENCE', $supportingEvidence->classification);
        $this->assertCount(1, $supportingEvidence->sources);
        $this->assertEquals('Sarma, Inguva Karthikeya', $supportingEvidence->sources->first()->authors);
        $this->assertEquals('pp. 43–68, Plates 12–19', $supportingEvidence->sources->first()->pivot->specific_pages);

        $complicatingEvidence = $retrievedClaim->evidence->firstWhere('pivot.relationship_type', 'COMPLICATES');
        $this->assertNotNull($complicatingEvidence);
        $this->assertEquals('Coomaraswamy, Ananda K.', $complicatingEvidence->sources->first()->authors);
    }
}
