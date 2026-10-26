<?php

namespace Tests\Feature\Web;

use App\Models\Claim;
use App\Models\Dataset;
use App\Models\Dynasty;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Location;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvancedSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_access_search_page_with_empty_state(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('Advanced Heritage Search');
        $response->assertSee('Cross-Corpus Knowledge Graph');
    }

    public function test_cross_corpus_search_returns_matches_across_sites_objects_inscriptions_and_datasets(): void
    {
        $location = Location::create([
            'name' => 'Badami',
            'state' => 'Karnataka',
            'district' => 'Bagalkot',
            'latitude' => 15.9187,
            'longitude' => 75.6766,
        ]);

        $period = Period::create([
            'name' => 'Early Chalukya Period',
            'slug' => 'early-chalukya',
            'dating_statement' => 'c. 543 – 753 CE',
            'start_year' => 543,
            'end_year' => 753,
        ]);

        $dynasty = Dynasty::create([
            'name' => 'Chalukyas of Vatapi',
            'slug' => 'chalukyas-of-vatapi',
            'dating_statement' => 'c. 543 – 753 CE',
            'period_id' => $period->id,
            'start_year' => 543,
            'end_year' => 753,
        ]);

        $site = HeritageSite::create([
            'name' => 'Badami Cave Temples',
            'slug' => 'badami-cave-temples',
            'site_type' => 'Rock-cut Cave Complex',
            'location_id' => $location->id,
            'primary_period_id' => $period->id,
            'primary_dynasty_id' => $dynasty->id,
            'dating_statement' => 'c. 6th century CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Badami monumental rock-cut shrines dedicated to Shiva and Vishnu.',
            'is_published' => true,
        ]);

        $object = HeritageObject::create([
            'name' => 'Badami Nataraja Bas-Relief',
            'slug' => 'badami-nataraja-bas-relief',
            'heritage_site_id' => $site->id,
            'period_id' => $period->id,
            'dynasty_id' => $dynasty->id,
            'object_type' => 'Bas-Relief Sculpture',
            'material' => 'Sandstone',
            'dating_statement' => 'c. 6th century CE',
            'current_repository' => 'Cave 1 Portico, Badami',
            'description' => 'Multi-armed dancing Shiva sculpture at Badami Cave 1.',
            'is_published' => true,
        ]);

        $inscription = Inscription::create([
            'title' => 'Badami Cliff Inscription of Pulakeshin I',
            'slug' => 'badami-cliff-inscription-pulakeshin-i',
            'heritage_site_id' => $site->id,
            'dynasty_id' => $dynasty->id,
            'language' => 'Sanskrit',
            'script' => 'Early Southern Brahmi',
            'dating_statement' => 'Saka 465 (543 CE)',
            'translation' => 'Commemorates the foundation of the hill-fort of Vatapi (Badami) by Pulakeshin I.',
            'is_published' => true,
        ]);

        $dataset = Dataset::create([
            'name' => 'Badami Epigraphy Transcription Dataset',
            'slug' => 'badami-epigraphy-dataset',
            'source_organization' => 'Epigraphia Indica Digital Archive',
            'dataset_type' => 'CSV',
            'version' => '1.0',
            'license' => 'Creative Commons CC0 1.0 (Public Domain)',
            'description' => 'Complete digital diplomatic transcriptions of Badami cave and cliff inscriptions.',
            'is_published' => true,
        ]);

        $source = Source::create([
            'title' => 'Archaeology and Inscriptions of Badami',
            'authors' => 'Dr. K. V. Ramesh',
            'publisher' => 'Archaeological Survey of India',
            'source_type' => 'ACADEMIC_MONOGRAPH',
            'publication_year' => 1984,
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
        ]);

        $claim = Claim::create([
            'claimable_type' => HeritageSite::class,
            'claimable_id' => $site->id,
            'claim_type' => 'HISTORICAL_EVENT',
            'statement' => 'Badami Fortification Founding by Pulakeshin I in 543 CE as capital of Chalukya dynasty.',
            'consensus_status' => 'STRONG_CONSENSUS',
            'summary_justification' => 'Corroborated by the Badami cliff inscription dated Saka 465.',
        ]);

        // Search for term "Badami" across all corpora
        $response = $this->get('/search?q=Badami');

        $response->assertStatus(200);
        $response->assertSee('Badami Cave Temples');
        $response->assertSee('Badami Nataraja Bas-Relief');
        $response->assertSee('Badami Cliff Inscription of Pulakeshin I');
        $response->assertSee('Badami Epigraphy Transcription Dataset');
        $response->assertSee('Archaeology and Inscriptions of Badami');
        $response->assertSee('Badami Fortification Founding by Pulakeshin I in 543 CE');
    }

    public function test_search_can_filter_by_period_and_dynasty(): void
    {
        $period1 = Period::create([
            'name' => 'Maurya Era',
            'slug' => 'maurya-era',
            'dating_statement' => 'c. 322 – 185 BCE',
            'start_year' => -322,
            'end_year' => -185,
        ]);

        $dynasty1 = Dynasty::create([
            'name' => 'Mauryan Empire',
            'slug' => 'mauryan-empire',
            'dating_statement' => 'c. 322 – 185 BCE',
            'period_id' => $period1->id,
            'start_year' => -322,
            'end_year' => -185,
        ]);

        $period2 = Period::create([
            'name' => 'Chola Era',
            'slug' => 'chola-era',
            'dating_statement' => 'c. 848 – 1279 CE',
            'start_year' => 848,
            'end_year' => 1279,
        ]);

        $dynasty2 = Dynasty::create([
            'name' => 'Imperial Cholas',
            'slug' => 'imperial-cholas',
            'dating_statement' => 'c. 848 – 1279 CE',
            'period_id' => $period2->id,
            'start_year' => 848,
            'end_year' => 1279,
        ]);

        $site1 = HeritageSite::create([
            'name' => 'Sanchi Great Stupa',
            'slug' => 'sanchi-great-stupa',
            'site_type' => 'Stupa Complex',
            'primary_period_id' => $period1->id,
            'primary_dynasty_id' => $dynasty1->id,
            'dating_statement' => 'c. 3rd century BCE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Mauryan Buddhist stupa founded by Ashoka.',
            'is_published' => true,
        ]);

        $site2 = HeritageSite::create([
            'name' => 'Brihadisvara Temple Thanjavur',
            'slug' => 'brihadisvara-temple',
            'site_type' => 'Granite Temple',
            'primary_period_id' => $period2->id,
            'primary_dynasty_id' => $dynasty2->id,
            'dating_statement' => '1010 CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Colossal Chola vimana dedicated to Shiva.',
            'is_published' => true,
        ]);

        // Filter by Maurya period
        $resMaurya = $this->get('/search?period=maurya-era');
        $resMaurya->assertStatus(200);
        $resMaurya->assertSee('Sanchi Great Stupa');
        $resMaurya->assertDontSee('Brihadisvara Temple Thanjavur');

        // Filter by Chola dynasty
        $resChola = $this->get('/search?dynasty=imperial-cholas');
        $resChola->assertStatus(200);
        $resChola->assertSee('Brihadisvara Temple Thanjavur');
        $resChola->assertDontSee('Sanchi Great Stupa');
    }

    public function test_search_can_filter_by_state(): void
    {
        $locOdisha = Location::create([
            'name' => 'Konark',
            'state' => 'Odisha',
            'district' => 'Puri',
        ]);

        $locTamilNadu = Location::create([
            'name' => 'Mamallapuram',
            'state' => 'Tamil Nadu',
            'district' => 'Chengalpattu',
        ]);

        HeritageSite::create([
            'name' => 'Konark Sun Temple',
            'slug' => 'konark-sun-temple',
            'site_type' => 'Kalinga Stone Temple',
            'location_id' => $locOdisha->id,
            'dating_statement' => 'c. 1250 CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Monumental Surya chariot temple.',
            'is_published' => true,
        ]);

        HeritageSite::create([
            'name' => 'Shore Temple',
            'slug' => 'shore-temple',
            'site_type' => 'Pallava Structural Temple',
            'location_id' => $locTamilNadu->id,
            'dating_statement' => 'c. 700 CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Pallava coastal shrine on the Bay of Bengal.',
            'is_published' => true,
        ]);

        $resOdisha = $this->get('/search?state=Odisha');
        $resOdisha->assertStatus(200);
        $resOdisha->assertSee('Konark Sun Temple');
        $resOdisha->assertDontSee('Shore Temple');
    }

    public function test_search_can_filter_by_consensus_rating(): void
    {
        $site = HeritageSite::create([
            'name' => 'Kailash Temple Ellora',
            'slug' => 'kailash-temple-ellora',
            'site_type' => 'Rock-cut Monolith',
            'dating_statement' => 'c. 8th century CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Monolithic rock-cut temple carved top-down.',
            'is_published' => true,
        ]);

        Claim::create([
            'claimable_type' => HeritageSite::class,
            'claimable_id' => $site->id,
            'claim_type' => 'CHRONOLOGY',
            'statement' => 'Single-Phase Excavation Timeline Claim for Kailash monolith.',
            'consensus_status' => 'CONTESTED',
        ]);

        Claim::create([
            'claimable_type' => HeritageSite::class,
            'claimable_id' => $site->id,
            'claim_type' => 'PATRONAGE',
            'statement' => 'Rashtrakuta Krishna I Patronage Inscription Attribution.',
            'consensus_status' => 'STRONG_CONSENSUS',
        ]);

        $resContested = $this->get('/search?consensus=CONTESTED');
        $resContested->assertStatus(200);
        $resContested->assertSee('Single-Phase Excavation Timeline Claim');
        $resContested->assertDontSee('Rashtrakuta Krishna I Patronage Inscription Attribution');
    }

    public function test_search_filters_by_entity_type_tab(): void
    {
        $site = HeritageSite::create([
            'name' => 'Nalanda Mahavihara',
            'slug' => 'nalanda-mahavihara',
            'site_type' => 'Monastic University Complex',
            'dating_statement' => 'c. 5th - 12th century CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Ancient international Buddhist monastery and university.',
            'is_published' => true,
        ]);

        HeritageObject::create([
            'name' => 'Nalanda Bronze Buddha',
            'slug' => 'nalanda-bronze-buddha',
            'heritage_site_id' => $site->id,
            'object_type' => 'Metal Sculpture',
            'dating_statement' => 'c. 9th century CE',
            'material' => 'Gilt Bronze',
            'current_repository' => 'Nalanda Archaeological Museum',
            'description' => 'Standing Buddha in abhaya mudra found in Monastery Site 1.',
            'is_published' => true,
        ]);

        // When type=objects tab is selected
        $resObjects = $this->get('/search?q=Nalanda&type=objects');
        $resObjects->assertStatus(200);
        $resObjects->assertSee('Nalanda Bronze Buddha');
        // Sites list should not be rendered in the objects view tab
        $resObjects->assertDontSee('Ancient international Buddhist monastery and university.');
    }

    public function test_draft_records_are_never_exposed_in_search_results(): void
    {
        HeritageSite::create([
            'name' => 'Unpublished Secret Mound',
            'slug' => 'unpublished-secret-mound',
            'site_type' => 'Archaeological Mound',
            'dating_statement' => 'Unknown',
            'protection_status' => 'Unprotected',
            'summary' => 'Sensitive unexcavated stratigraphy mound.',
            'is_published' => false,
        ]);

        $res = $this->get('/search?q=Secret');
        $res->assertStatus(200);
        $res->assertDontSee('Unpublished Secret Mound');
    }
}
