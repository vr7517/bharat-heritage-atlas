<?php

namespace Tests\Feature\Web;

use App\Models\Dataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatasetPublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_public_user_can_view_published_datasets_index(): void
    {
        Dataset::create([
            'name' => 'Centrally Protected Monuments Geospatial Points',
            'slug' => 'centrally-protected-monuments',
            'source_organization' => 'Archaeological Survey of India',
            'dataset_type' => 'GEOJSON',
            'version' => '2026.1',
            'license' => 'Government Open Data License - India (GODL)',
            'record_count' => 3693,
            'is_published' => true,
            'is_featured' => true,
        ]);

        Dataset::create([
            'name' => 'Unpublished Internal Excavation Trench Data',
            'slug' => 'internal-trench-data',
            'source_organization' => 'Internal Fieldwork Unit',
            'dataset_type' => 'CSV',
            'version' => '0.1',
            'license' => 'Academic Research & Educational Use Only',
            'is_published' => false,
            'is_featured' => false,
        ]);

        $response = $this->get('/datasets');

        $response->assertStatus(200);
        $response->assertSee('Open Heritage Datasets');
        $response->assertSee('Centrally Protected Monuments Geospatial Points');
        $response->assertSee('Archaeological Survey of India');
        $response->assertDontSee('Unpublished Internal Excavation Trench Data');
    }

    public function test_public_user_can_filter_datasets_by_type(): void
    {
        Dataset::create([
            'name' => 'Ajanta Cave Inscriptions Corpus',
            'slug' => 'ajanta-cave-inscriptions',
            'source_organization' => 'Epigraphia Indica',
            'dataset_type' => 'CSV',
            'version' => '1.0',
            'license' => 'Creative Commons CC0 1.0 (Public Domain)',
            'is_published' => true,
        ]);

        Dataset::create([
            'name' => 'Deccan Fortifications GIS Shapefiles',
            'slug' => 'deccan-fortifications-gis',
            'source_organization' => 'Historical GIS Laboratory',
            'dataset_type' => 'GEOJSON',
            'version' => '2.0',
            'license' => 'Creative Commons Attribution 4.0 (CC BY 4.0)',
            'is_published' => true,
        ]);

        $response = $this->get('/datasets?type=GEOJSON');

        $response->assertStatus(200);
        $response->assertSee('Deccan Fortifications GIS Shapefiles');
        $response->assertDontSee('Ajanta Cave Inscriptions Corpus');
    }

    public function test_public_user_can_view_dataset_detail_page(): void
    {
        $dataset = Dataset::create([
            'name' => 'Chalukyan Temple Architectural Proportions',
            'slug' => 'chalukyan-temple-proportions',
            'source_organization' => 'Dravidian Architecture Society',
            'source_url' => 'https://example.org/chalukya-corpus',
            'dataset_type' => 'CSV',
            'version' => '1.4',
            'release_date' => '2026-02-14',
            'license' => 'Open Data Commons Open Database License (ODbL)',
            'record_count' => 150,
            'description' => 'Precision photogrammetric and dimensional ground plan metrics of Aihole, Badami, and Pattadakal temples.',
            'notes' => 'Metric units in meters; precision +/- 2 cm.',
            'is_published' => true,
            'is_featured' => true,
        ]);

        $response = $this->get("/datasets/{$dataset->slug}");

        $response->assertStatus(200);
        $response->assertSee('Chalukyan Temple Architectural Proportions');
        $response->assertSee('Dravidian Architecture Society');
        $response->assertSee('Aihole, Badami, and Pattadakal');
        $response->assertSee('ODbL', false);
        $response->assertSee('150 items', false);
    }

    public function test_draft_dataset_cannot_be_viewed_by_public(): void
    {
        $dataset = Dataset::create([
            'name' => 'Confidential Stratigraphy Report',
            'slug' => 'confidential-stratigraphy',
            'source_organization' => 'Classified Field Lab',
            'dataset_type' => 'DOCUMENTATION',
            'version' => '0.1',
            'license' => 'Academic Research & Educational Use Only',
            'is_published' => false,
        ]);

        $response = $this->get("/datasets/{$dataset->slug}");

        $response->assertStatus(404);
    }

    public function test_public_user_can_download_dataset_file(): void
    {
        $file = UploadedFile::fake()->create('monuments_gis.geojson', 512, 'application/geo+json');
        $path = $file->store('datasets', 'public');

        $dataset = Dataset::create([
            'name' => 'Centrally Protected Monuments GeoJSON',
            'slug' => 'asi-monuments-download',
            'source_organization' => 'Archaeological Survey of India',
            'dataset_type' => 'GEOJSON',
            'version' => '1.0',
            'license' => 'Government Open Data License - India (GODL)',
            'file_path' => $path,
            'file_name' => 'monuments_gis.geojson',
            'file_size_bytes' => 512 * 1024,
            'file_mime_type' => 'application/geo+json',
            'is_published' => true,
        ]);

        $response = $this->get("/datasets/{$dataset->slug}/download");

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=monuments_gis.geojson');
    }

    public function test_draft_dataset_cannot_be_downloaded(): void
    {
        $file = UploadedFile::fake()->create('private_data.csv', 100, 'text/csv');
        $path = $file->store('datasets', 'public');

        $dataset = Dataset::create([
            'name' => 'Internal Working CSV',
            'slug' => 'internal-working-csv',
            'source_organization' => 'Internal Unit',
            'dataset_type' => 'CSV',
            'version' => '0.1',
            'license' => 'Academic Research & Educational Use Only',
            'file_path' => $path,
            'file_name' => 'private_data.csv',
            'is_published' => false,
        ]);

        $response = $this->get("/datasets/{$dataset->slug}/download");

        $response->assertStatus(404);
    }
}
