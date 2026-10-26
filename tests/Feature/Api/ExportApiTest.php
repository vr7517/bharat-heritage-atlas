<?php

namespace Tests\Feature\Api;

use App\Models\Dataset;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Location;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_researchers_can_export_geojson_spatial_corpus(): void
    {
        $location = Location::create([
            'name' => 'Sanchi',
            'state' => 'Madhya Pradesh',
            'district' => 'Raisen',
            'latitude' => 23.4794,
            'longitude' => 77.7397,
        ]);

        $site = HeritageSite::create([
            'name' => 'Sanchi Great Stupa',
            'slug' => 'sanchi-great-stupa',
            'site_type' => 'Buddhist Stupa Complex',
            'location_id' => $location->id,
            'dating_statement' => 'c. 3rd century BCE to 12th century CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Monumental Buddhist stupa complex.',
            'is_published' => true,
        ]);

        $response = $this->get('/api/v1/export/geojson');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/geo+json', (string) $response->headers->get('content-type'));

        $content = json_decode($response->streamedContent(), true);
        $this->assertEquals('FeatureCollection', $content['type']);
        $this->assertNotEmpty($content['features']);
        $this->assertEquals('Point', $content['features'][0]['geometry']['type']);
        $this->assertEquals(77.7397, $content['features'][0]['geometry']['coordinates'][0]);
        $this->assertEquals(23.4794, $content['features'][0]['geometry']['coordinates'][1]);
        $this->assertEquals('Sanchi Great Stupa', $content['features'][0]['properties']['name']);
    }

    public function test_researchers_can_export_bibtex_citations(): void
    {
        Source::create([
            'title' => 'The Monuments of Sanchi',
            'authors' => 'Marshall, John; Foucher, Alfred',
            'publication_year' => 1940,
            'publisher' => 'Government of India Press, Calcutta',
            'source_type' => 'ACADEMIC_MONOGRAPH',
            'citation_key' => 'MARSHALL1940',
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
        ]);

        $response = $this->get('/api/v1/export/bibtex');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/x-bibtex', (string) $response->headers->get('content-type'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('@book{MARSHALL1940', $content);
        $this->assertStringContainsString('title = {{The Monuments of Sanchi}}', $content);
        $this->assertStringContainsString('author = {Marshall, John; Foucher, Alfred}', $content);
        $this->assertStringContainsString('year = {1940}', $content);
    }

    public function test_researchers_can_export_sites_csv(): void
    {
        $site = HeritageSite::create([
            'name' => 'Ajanta Caves',
            'slug' => 'ajanta-caves',
            'site_type' => 'Rock-cut Monastic Complex',
            'dating_statement' => 'c. 2nd century BCE – 5th century CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Celebrated rock-cut Buddhist prayer halls and viharas.',
            'is_published' => true,
        ]);

        $response = $this->get('/api/v1/export/sites.csv');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('Ajanta Caves', $content);
        $this->assertStringContainsString('ajanta-caves', $content);
        $this->assertStringContainsString('ASI Protected Monument', $content);
    }

    public function test_researchers_can_export_inscriptions_csv(): void
    {
        Inscription::create([
            'title' => 'Besnagar Garuda Pillar Inscription',
            'slug' => 'besnagar-garuda-pillar-inscription',
            'language' => 'Prakrit',
            'script' => 'Ashokan Brahmi',
            'dating_statement' => 'c. 113 BCE',
            'epigraphic_reference' => 'Epigraphia Indica Vol. II, p. 87',
            'translation' => 'This Garuda-standard of Vasudeva was erected by Heliodorus.',
            'is_published' => true,
        ]);

        $response = $this->get('/api/v1/export/inscriptions.csv');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('Besnagar Garuda Pillar Inscription', $content);
        $this->assertStringContainsString('Ashokan Brahmi', $content);
        $this->assertStringContainsString('Heliodorus', $content);
    }

    public function test_researchers_can_export_objects_csv(): void
    {
        HeritageObject::create([
            'name' => 'Didarganj Yakshi',
            'slug' => 'didarganj-yakshi',
            'object_type' => 'Free-Standing Sculpture',
            'material' => 'Polished Chunar Sandstone',
            'current_repository' => 'Bihar Museum, Patna',
            'accession_number' => 'ARCH-PAT-134',
            'dating_statement' => 'c. 3rd century BCE – 1st century CE',
            'description' => 'Life-sized polished sandstone figure holding a chauri flywhisk.',
            'is_published' => true,
        ]);

        $response = $this->get('/api/v1/export/objects.csv');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('Didarganj Yakshi', $content);
        $this->assertStringContainsString('Polished Chunar Sandstone', $content);
        $this->assertStringContainsString('ARCH-PAT-134', $content);
    }

    public function test_programmatic_cross_corpus_search_api_returns_json(): void
    {
        HeritageSite::create([
            'name' => 'Badami Rock Caves',
            'slug' => 'badami-rock-caves',
            'site_type' => 'Rock-cut Complex',
            'dating_statement' => 'c. 6th century CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Chalukyan rock shrines.',
            'is_published' => true,
        ]);

        $response = $this->getJson('/api/v1/search?q=Badami');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'query' => 'Badami',
        ]);
        $response->assertJsonFragment(['name' => 'Badami Rock Caves']);
    }

    public function test_search_api_strictly_excludes_draft_records(): void
    {
        HeritageSite::create([
            'name' => 'Secret Excavation Cave',
            'slug' => 'secret-excavation-cave',
            'site_type' => 'Cave',
            'dating_statement' => 'Unknown',
            'protection_status' => 'Unprotected',
            'summary' => 'Unpublished findings.',
            'is_published' => false,
        ]);

        $response = $this->getJson('/api/v1/search?q=Secret');

        $response->assertStatus(200);
        $response->assertJsonMissing(['name' => 'Secret Excavation Cave']);
    }

    public function test_programmatic_datasets_api_returns_json(): void
    {
        $dataset = Dataset::create([
            'name' => 'ASI Monument Spatial Boundaries',
            'slug' => 'asi-monuments-boundaries',
            'source_organization' => 'Archaeological Survey of India',
            'dataset_type' => 'GEOJSON',
            'version' => '1.0',
            'license' => 'Government Open Data License - India (GODL)',
            'record_count' => 3693,
            'is_published' => true,
        ]);

        $indexRes = $this->getJson('/api/v1/datasets');
        $indexRes->assertStatus(200);
        $indexRes->assertJsonFragment(['name' => 'ASI Monument Spatial Boundaries']);

        $showRes = $this->getJson("/api/v1/datasets/{$dataset->slug}");
        $showRes->assertStatus(200);
        $showRes->assertJsonFragment([
            'slug' => 'asi-monuments-boundaries',
            'record_count' => 3693,
        ]);
    }
}
