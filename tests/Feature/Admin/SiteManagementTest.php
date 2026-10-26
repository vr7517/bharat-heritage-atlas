<?php

namespace Tests\Feature\Admin;

use App\Models\HeritageSite;
use App\Models\Location;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiteManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'BHA Admin Curator',
            'email' => 'admin@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => true,
        ]);
    }

    public function test_admin_can_view_sites_index_listing(): void
    {
        $site = HeritageSite::create([
            'name' => 'Badami Cave Temples',
            'slug' => 'badami-cave-temples',
            'site_type' => 'Rock-cut Cave Complex',
            'dating_statement' => 'c. 6th century CE',
            'start_year' => 540,
            'end_year' => 600,
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Early Chalukyan rock-cut shrines in sandstone cliffs.',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/sites');

        $response->assertStatus(200);
        $response->assertSee('Heritage Sites & Monuments', false);
        $response->assertSee('Badami Cave Temples');
        $response->assertSee('Rock-cut Cave Complex');
        $response->assertSee('Published');
    }

    public function test_admin_can_filter_sites_by_status(): void
    {
        HeritageSite::create([
            'name' => 'Published Site',
            'slug' => 'published-site',
            'site_type' => 'Temple Complex',
            'dating_statement' => 'c. 8th c. CE',
            'protection_status' => 'ASI Protected',
            'summary' => 'Published complex summary.',
            'is_published' => true,
        ]);

        HeritageSite::create([
            'name' => 'Draft Excavation',
            'slug' => 'draft-excavation',
            'site_type' => 'Archaeological Mound',
            'dating_statement' => 'c. 2nd c. BCE',
            'protection_status' => 'Under Survey',
            'summary' => 'Unpublished draft summary.',
            'is_published' => false,
        ]);

        // Filter published
        $pubResponse = $this->actingAs($this->admin)->get('/admin/sites?status=published');
        $pubResponse->assertSee('Published Site');
        $pubResponse->assertDontSee('Draft Excavation');

        // Filter drafts
        $draftResponse = $this->actingAs($this->admin)->get('/admin/sites?status=draft');
        $draftResponse->assertSee('Draft Excavation');
        $draftResponse->assertDontSee('Published Site');
    }

    public function test_admin_can_render_site_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/sites/create');

        $response->assertStatus(200);
        $response->assertSee('Register New Heritage Monument');
        $response->assertSee('Monument / Site Name', false);
        $response->assertSee('Scholarly Dating Statement', false);
    }

    public function test_admin_can_create_new_heritage_site_and_it_appears_dynamically_on_public_atlas(): void
    {
        $period = Period::create([
            'name' => 'Chalukya Era',
            'slug' => 'chalukya-era',
            'dating_statement' => 'c. 6th–8th century CE',
            'start_year' => 543,
            'end_year' => 753,
            'start_era' => 'CE',
            'end_era' => 'CE',
        ]);

        $payload = [
            'name' => 'Aihole Meguti Jain Temple',
            'slug' => 'aihole-meguti-jain-temple',
            'site_type' => 'Temple Complex',
            'dating_statement' => '634 CE (Saka 556)',
            'start_year' => 634,
            'end_year' => 634,
            'is_dating_uncertain' => 0,
            'protection_status' => 'ASI Protected Monument of National Importance',
            'summary' => 'Hilltop temple bearing the famous Aihole Prashasti inscription composed by poet Ravikirti.',
            'historical_context' => 'Erected by Ravikirti during the reign of Western Chalukya Emperor Pulakeshin II.',
            'architectural_description' => 'Dravidian style stone temple perched on a rocky hill overlooking Aihole.',
            'is_published' => 1,
            'primary_period_id' => $period->id,
            'new_location_name' => 'Aihole',
            'state' => 'Karnataka',
            'district' => 'Bagalkot',
            'taluk_tehsil' => 'Hungund',
            'latitude' => 16.0197,
            'longitude' => 75.8828,
            'altitude_meters' => 590,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/sites', $payload);

        $response->assertRedirect(route('admin.sites.index'));
        $this->assertDatabaseHas('heritage_sites', [
            'name' => 'Aihole Meguti Jain Temple',
            'slug' => 'aihole-meguti-jain-temple',
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('locations', [
            'state' => 'Karnataka',
            'district' => 'Bagalkot',
        ]);

        // VERIFY ON PUBLIC ATLAS: Site appears dynamically without developer code changes!
        $publicCatalogResponse = $this->get('/sites');
        $publicCatalogResponse->assertStatus(200);
        $publicCatalogResponse->assertSee('Aihole Meguti Jain Temple');

        // VERIFY ON PUBLIC DETAIL PAGE:
        $publicShowResponse = $this->get('/sites/aihole-meguti-jain-temple');
        $publicShowResponse->assertStatus(200);
        $publicShowResponse->assertSee('Hilltop temple bearing the famous Aihole Prashasti');
        $publicShowResponse->assertSee('Karnataka');
    }

    public function test_admin_can_create_draft_site_and_it_is_hidden_from_public_atlas(): void
    {
        $payload = [
            'name' => 'Unexcavated Archaeological Mound',
            'slug' => 'unexcavated-archaeological-mound',
            'site_type' => 'Archaeological Mound',
            'dating_statement' => 'c. 1st millennium BCE',
            'protection_status' => 'Proposed State Protected',
            'summary' => 'Confidential preliminary mound pending stratigraphic excavation clearance.',
            'is_published' => 0,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/sites', $payload);

        $response->assertRedirect(route('admin.sites.index'));
        $this->assertDatabaseHas('heritage_sites', [
            'slug' => 'unexcavated-archaeological-mound',
            'is_published' => false,
        ]);

        // VERIFY PUBLIC ACCESS IS BLOCKED:
        $publicCatalogResponse = $this->get('/sites');
        $publicCatalogResponse->assertDontSee('Unexcavated Archaeological Mound');

        $publicShowResponse = $this->get('/sites/unexcavated-archaeological-mound');
        $publicShowResponse->assertStatus(404);

        // VERIFY ADMIN CAN SEE IT IN DRAFTS:
        $adminDraftsResponse = $this->actingAs($this->admin)->get('/admin/sites?status=draft');
        $adminDraftsResponse->assertSee('Unexcavated Archaeological Mound');
    }

    public function test_admin_can_edit_and_update_heritage_site(): void
    {
        $site = HeritageSite::create([
            'name' => 'Original Name',
            'slug' => 'original-slug',
            'site_type' => 'Monolithic Pillar',
            'dating_statement' => 'c. 2nd c. BCE',
            'protection_status' => 'ASI Protected',
            'summary' => 'Original summary text.',
            'is_published' => true,
        ]);

        $updatePayload = [
            'name' => 'Updated Pillar Monument',
            'slug' => 'updated-pillar-monument',
            'site_type' => 'Monolithic Pillar & Standard',
            'dating_statement' => 'c. 113 BCE',
            'protection_status' => 'ASI Protected Monument of National Importance',
            'summary' => 'Updated summary text with new curatorial annotations.',
            'is_published' => 1,
        ];

        $response = $this->actingAs($this->admin)->put("/admin/sites/{$site->id}", $updatePayload);

        $response->assertRedirect(route('admin.sites.index'));
        $this->assertDatabaseHas('heritage_sites', [
            'id' => $site->id,
            'name' => 'Updated Pillar Monument',
            'slug' => 'updated-pillar-monument',
        ]);

        // VERIFY ON PUBLIC ATLAS:
        $publicShowResponse = $this->get('/sites/updated-pillar-monument');
        $publicShowResponse->assertStatus(200);
        $publicShowResponse->assertSee('Updated Pillar Monument');
    }

    public function test_admin_can_toggle_publish_status_instantaneously(): void
    {
        $site = HeritageSite::create([
            'name' => 'Pattadakal Virupaksha Temple',
            'slug' => 'pattadakal-virupaksha-temple',
            'site_type' => 'Temple Complex',
            'dating_statement' => 'c. 740 CE',
            'protection_status' => 'UNESCO World Heritage Site',
            'summary' => 'Constructed by Queen Lokamahadevi to commemorate Vikramaditya II victory.',
            'is_published' => true,
        ]);

        // 1. Initial State: Visible on public
        $this->get('/sites/pattadakal-virupaksha-temple')->assertStatus(200);

        // 2. Unpublish via toggle
        $toggleResponse = $this->actingAs($this->admin)->post("/admin/sites/{$site->id}/toggle-publish");
        $toggleResponse->assertSessionHas('success');
        $this->assertFalse($site->fresh()->is_published);

        // 3. Public view is immediately hidden
        $this->get('/sites/pattadakal-virupaksha-temple')->assertStatus(404);

        // 4. Publish via toggle again
        $this->actingAs($this->admin)->post("/admin/sites/{$site->id}/toggle-publish");
        $this->assertTrue($site->fresh()->is_published);

        // 5. Public view is immediately restored
        $this->get('/sites/pattadakal-virupaksha-temple')->assertStatus(200);
    }

    public function test_admin_can_delete_heritage_site(): void
    {
        $site = HeritageSite::create([
            'name' => 'Temporary Survey Site',
            'slug' => 'temporary-survey-site',
            'site_type' => 'Archaeological Mound',
            'dating_statement' => 'Undated',
            'protection_status' => 'Unprotected',
            'summary' => 'Site to be deleted.',
            'is_published' => false,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/sites/{$site->id}");

        $response->assertRedirect(route('admin.sites.index'));
        $this->assertDatabaseMissing('heritage_sites', [
            'id' => $site->id,
        ]);
    }
}
