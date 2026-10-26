<?php

namespace Tests\Feature\Admin;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatasetManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

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
    }

    public function test_admin_can_view_datasets_index_listing(): void
    {
        Dataset::create([
            'name' => 'Archaeological Survey of India Monument GeoJSON',
            'slug' => 'asi-monuments-geojson',
            'source_organization' => 'Archaeological Survey of India',
            'dataset_type' => 'GEOJSON',
            'version' => '2026.1',
            'release_date' => '2026-03-15',
            'license' => 'Government Open Data License - India (GODL)',
            'record_count' => 3693,
            'is_published' => true,
            'is_featured' => true,
        ]);

        Dataset::create([
            'name' => 'Draft Epigraphical Inscription Transcriptions',
            'slug' => 'draft-epigraphy',
            'source_organization' => 'Epigraphia Indica Digital Archive',
            'dataset_type' => 'EPIGRAPHICAL_CORPUS',
            'version' => '0.9',
            'license' => 'Open Data Commons Open Database License (ODbL)',
            'is_published' => false,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/datasets');

        $response->assertStatus(200);
        $response->assertSee('Open Research Datasets & Spatial Archives', false);
        $response->assertSee('Archaeological Survey of India Monument GeoJSON');
        $response->assertSee('Draft Epigraphical Inscription Transcriptions');
        $response->assertSee('Published');
        $response->assertSee('Draft');
    }

    public function test_admin_can_filter_datasets_by_search_type_and_status(): void
    {
        $d1 = Dataset::create([
            'name' => 'Badami Inscriptions Corpus',
            'slug' => 'badami-inscriptions',
            'source_organization' => 'Epigraphy Cell',
            'dataset_type' => 'CSV',
            'version' => '1.0',
            'license' => 'Creative Commons CC0 1.0 (Public Domain)',
            'is_published' => true,
            'is_featured' => false,
        ]);

        $d2 = Dataset::create([
            'name' => 'Ellora Spatial GIS Boundaries',
            'slug' => 'ellora-gis',
            'source_organization' => 'GIS Heritage Cell',
            'dataset_type' => 'GEOJSON',
            'version' => '2.0',
            'license' => 'Creative Commons Attribution 4.0 (CC BY 4.0)',
            'is_published' => false,
            'is_featured' => false,
        ]);

        $d3 = Dataset::create([
            'name' => 'Chola Bronze Metallurgy Spectrometry',
            'slug' => 'chola-metallurgy',
            'source_organization' => 'Metallurgical Research Lab',
            'dataset_type' => 'CSV',
            'version' => '1.1',
            'license' => 'Academic Research & Educational Use Only',
            'is_published' => true,
            'is_featured' => true,
        ]);

        // 1. Search filter
        $res = $this->actingAs($this->admin)->get('/admin/datasets?search=Badami');
        $res->assertStatus(200);
        $res->assertSee('Badami Inscriptions Corpus');
        $res->assertDontSee('Ellora Spatial GIS Boundaries');
        $res->assertDontSee('Chola Bronze Metallurgy Spectrometry');

        // 2. Type filter
        $res = $this->actingAs($this->admin)->get('/admin/datasets?dataset_type=GEOJSON');
        $res->assertStatus(200);
        $res->assertSee('Ellora Spatial GIS Boundaries');
        $res->assertDontSee('Badami Inscriptions Corpus');

        // 3. Status filter draft
        $res = $this->actingAs($this->admin)->get('/admin/datasets?status=draft');
        $res->assertStatus(200);
        $res->assertSee('Ellora Spatial GIS Boundaries');
        $res->assertDontSee('Chola Bronze Metallurgy Spectrometry');

        // 4. Status filter featured
        $res = $this->actingAs($this->admin)->get('/admin/datasets?status=featured');
        $res->assertStatus(200);
        $res->assertSee('Chola Bronze Metallurgy Spectrometry');
        $res->assertDontSee('Badami Inscriptions Corpus');
    }

    public function test_admin_can_view_create_dataset_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/datasets/create');

        $response->assertStatus(200);
        $response->assertSee('Register Open Heritage Dataset', false);
        $response->assertSee('Dataset Nomenclature & Typology', false);
        $response->assertSee('Dataset Asset File Upload', false);
    }

    public function test_admin_can_create_dataset_with_file_upload(): void
    {
        $file = UploadedFile::fake()->create('asi_centrally_protected.geojson', 2048, 'application/geo+json');

        $payload = [
            'name' => 'Centrally Protected Monuments GeoJSON',
            'source_organization' => 'Archaeological Survey of India (ASI)',
            'source_url' => 'https://asi.nic.in/open-data',
            'dataset_type' => 'GEOJSON',
            'version' => '1.0.0',
            'release_date' => '2026-01-20',
            'license' => 'Government Open Data License - India (GODL)',
            'record_count' => 3693,
            'description' => 'Comprehensive spatial GIS coordinate dataset of all Centrally Protected Monuments.',
            'notes' => 'Coordinate reference system: EPSG:4326 (WGS84).',
            'is_published' => '1',
            'is_featured' => '1',
            'dataset_file' => $file,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/datasets', $payload);

        $response->assertRedirect('/admin/datasets');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('datasets', [
            'name' => 'Centrally Protected Monuments GeoJSON',
            'slug' => 'centrally-protected-monuments-geojson',
            'source_organization' => 'Archaeological Survey of India (ASI)',
            'dataset_type' => 'GEOJSON',
            'record_count' => 3693,
            'file_name' => 'asi_centrally_protected.geojson',
            'is_published' => true,
            'is_featured' => true,
        ]);

        $dataset = Dataset::where('slug', 'centrally-protected-monuments-geojson')->firstOrFail();
        $this->assertNotNull($dataset->file_path);
        Storage::disk('public')->assertExists($dataset->file_path);
    }

    public function test_admin_can_view_edit_dataset_form(): void
    {
        $dataset = Dataset::create([
            'name' => 'Pala Epigraphy Corpus',
            'slug' => 'pala-epigraphy',
            'source_organization' => 'Bengal Epigraphical Society',
            'dataset_type' => 'CSV',
            'version' => '1.0',
            'license' => 'Creative Commons Attribution 4.0 (CC BY 4.0)',
            'description' => 'Copper plate inscriptions of Devapala and Dharmapala.',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/datasets/{$dataset->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Open Heritage Dataset', false);
        $response->assertSee('Pala Epigraphy Corpus');
        $response->assertSee('Copper plate inscriptions of Devapala and Dharmapala.');
    }

    public function test_admin_can_update_dataset_and_replace_file(): void
    {
        $oldFile = UploadedFile::fake()->create('old_records.csv', 100, 'text/csv');
        $oldPath = $oldFile->store('datasets', 'public');

        $dataset = Dataset::create([
            'name' => 'Initial Dataset Name',
            'slug' => 'initial-dataset-name',
            'source_organization' => 'Old Organization',
            'dataset_type' => 'CSV',
            'version' => '1.0',
            'license' => 'Creative Commons CC0 1.0 (Public Domain)',
            'file_path' => $oldPath,
            'file_name' => 'old_records.csv',
            'file_size_bytes' => 100 * 1024,
            'file_mime_type' => 'text/csv',
            'is_published' => false,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->create('updated_monuments.csv', 350, 'text/csv');

        $payload = [
            'name' => 'Revised Dataset Name',
            'slug' => 'initial-dataset-name',
            'source_organization' => 'Updated Organization',
            'source_url' => 'https://example.org/dataset',
            'dataset_type' => 'CSV',
            'version' => '2.0',
            'release_date' => '2026-02-01',
            'license' => 'Creative Commons Attribution 4.0 (CC BY 4.0)',
            'record_count' => 1250,
            'description' => 'Updated record descriptions.',
            'notes' => 'New provenance notes.',
            'is_published' => '1',
            'is_featured' => '1',
            'dataset_file' => $newFile,
        ];

        $response = $this->actingAs($this->admin)->put("/admin/datasets/{$dataset->id}", $payload);

        $response->assertRedirect('/admin/datasets');
        $response->assertSessionHas('success');

        // Verify old file was purged from disk
        Storage::disk('public')->assertMissing($oldPath);

        $dataset->refresh();
        $this->assertEquals('Revised Dataset Name', $dataset->name);
        $this->assertEquals('Updated Organization', $dataset->source_organization);
        $this->assertEquals('2.0', $dataset->version);
        $this->assertEquals(1250, $dataset->record_count);
        $this->assertTrue($dataset->is_published);
        $this->assertTrue($dataset->is_featured);
        $this->assertEquals('updated_monuments.csv', $dataset->file_name);
        Storage::disk('public')->assertExists($dataset->file_path);
    }

    public function test_admin_can_toggle_publish_status_and_it_reflects_on_public_show(): void
    {
        $dataset = Dataset::create([
            'name' => 'Sanchi Radiocarbon Calibrations',
            'slug' => 'sanchi-radiocarbon',
            'source_organization' => 'Birbal Sahni Institute',
            'dataset_type' => 'STRATIGRAPHY_CHRONOLOGY',
            'version' => '1.0',
            'license' => 'Academic Research & Educational Use Only',
            'is_published' => false,
        ]);

        // As draft, public detail page returns 404
        $publicRes = $this->get('/datasets/sanchi-radiocarbon');
        $publicRes->assertStatus(404);

        // Admin toggles publish status
        $toggleRes = $this->actingAs($this->admin)->post("/admin/datasets/{$dataset->id}/toggle-publish");
        $toggleRes->assertRedirect();
        $this->assertTrue($dataset->fresh()->is_published);

        // Now public detail page returns 200 OK!
        $publicResAfter = $this->get('/datasets/sanchi-radiocarbon');
        $publicResAfter->assertStatus(200);
        $publicResAfter->assertSee('Sanchi Radiocarbon Calibrations');
    }

    public function test_admin_can_toggle_featured_status(): void
    {
        $dataset = Dataset::create([
            'name' => 'Gupta Coin Metallography',
            'slug' => 'gupta-coin-metallography',
            'source_organization' => 'National Museum New Delhi',
            'dataset_type' => 'CSV',
            'version' => '1.0',
            'license' => 'Open Data Commons Open Database License (ODbL)',
            'is_published' => true,
            'is_featured' => false,
        ]);

        $this->assertFalse($dataset->is_featured);

        $this->actingAs($this->admin)->post("/admin/datasets/{$dataset->id}/toggle-featured");
        $this->assertTrue($dataset->fresh()->is_featured);

        $this->actingAs($this->admin)->post("/admin/datasets/{$dataset->id}/toggle-featured");
        $this->assertFalse($dataset->fresh()->is_featured);
    }

    public function test_admin_can_delete_dataset_and_clean_up_disk_file(): void
    {
        $file = UploadedFile::fake()->create('delete_me.json', 50, 'application/json');
        $path = $file->store('datasets', 'public');

        $dataset = Dataset::create([
            'name' => 'Temporary Dataset',
            'slug' => 'temporary-dataset',
            'source_organization' => 'Temporary Dept',
            'dataset_type' => 'JSON',
            'version' => '0.1',
            'license' => 'Creative Commons CC0 1.0 (Public Domain)',
            'file_path' => $path,
            'file_name' => 'delete_me.json',
            'is_published' => false,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete("/admin/datasets/{$dataset->id}");

        $response->assertRedirect('/admin/datasets');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('datasets', ['id' => $dataset->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_guest_cannot_access_dataset_management(): void
    {
        $this->get('/admin/datasets')->assertRedirect('/admin/login');
        $this->get('/admin/datasets/create')->assertRedirect('/admin/login');
        $this->post('/admin/datasets', [])->assertRedirect('/admin/login');
    }

    public function test_regular_user_cannot_access_dataset_management(): void
    {
        $this->actingAs($this->regularUser)->get('/admin/datasets')->assertStatus(403);
        $this->actingAs($this->regularUser)->get('/admin/datasets/create')->assertStatus(403);
    }
}
