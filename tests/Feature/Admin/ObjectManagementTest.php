<?php

namespace Tests\Feature\Admin;

use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ObjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

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
            'name' => 'Researcher User',
            'email' => 'researcher@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => false,
        ]);
    }

    public function test_admin_can_view_objects_index_listing(): void
    {
        $object = HeritageObject::create([
            'name' => 'Didarganj Yakshi',
            'slug' => 'didarganj-yakshi',
            'object_type' => 'SCULPTURE',
            'material' => 'Chunar Sandstone',
            'dating_statement' => 'c. 3rd–2nd century BCE',
            'start_year' => -250,
            'end_year' => -150,
            'current_repository' => 'Bihar Museum, Patna',
            'description' => 'Life-size polished sandstone sculpture of a fly-whisk bearer.',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/objects');

        $response->assertStatus(200);
        $response->assertSee('Artifacts, Sculptures & Material Relics', false);
        $response->assertSee('Didarganj Yakshi');
        $response->assertSee('Chunar Sandstone');
        $response->assertSee('Bihar Museum, Patna');
        $response->assertSee('Published');
    }

    public function test_admin_can_filter_objects_by_search_and_status(): void
    {
        HeritageObject::create([
            'name' => 'Heliodorus Pillar Capital',
            'slug' => 'heliodorus-pillar-capital',
            'object_type' => 'ARCHITECTURAL_MEMBER',
            'material' => 'Sandstone',
            'dating_statement' => 'c. 113 BCE',
            'current_repository' => 'Gwalior Museum',
            'description' => 'Palm-capital / Garuda shaft fragment.',
            'is_published' => true,
        ]);

        HeritageObject::create([
            'name' => 'Unpublished Seal Draft',
            'slug' => 'unpublished-seal-draft',
            'object_type' => 'SEAL',
            'material' => 'Steatite',
            'dating_statement' => 'c. 2500 BCE',
            'current_repository' => 'National Museum, New Delhi',
            'description' => 'Draft unverified seal.',
            'is_published' => false,
        ]);

        $searchResponse = $this->actingAs($this->admin)->get('/admin/objects?search=Heliodorus');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Heliodorus Pillar Capital');
        $searchResponse->assertDontSee('Unpublished Seal Draft');

        $draftResponse = $this->actingAs($this->admin)->get('/admin/objects?status=draft');
        $draftResponse->assertStatus(200);
        $draftResponse->assertSee('Unpublished Seal Draft');
        $draftResponse->assertDontSee('Heliodorus Pillar Capital');
    }

    public function test_admin_can_view_create_object_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/objects/create');

        $response->assertStatus(200);
        $response->assertSee('Register Artifact Record');
        $response->assertSee('Artifact Identity & Typology', false);
    }

    public function test_admin_can_create_object_with_cover_image(): void
    {
        Storage::fake('public');

        $site = HeritageSite::create([
            'name' => 'Besnagar Archaeological Complex',
            'slug' => 'besnagar-archaeological-complex',
            'site_type' => 'Archaeological Mound',
            'dating_statement' => 'c. 3rd c. BCE – 2nd c. CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Archaeological mound and urban settlement of ancient Vidisha.',
            'is_published' => true,
        ]);

        $coverImage = UploadedFile::fake()->image('yakshi_profile.jpg', 800, 600);

        $payload = [
            'name' => 'Besnagar Yakshi Colossus',
            'slug' => 'besnagar-yakshi-colossus',
            'heritage_site_id' => $site->id,
            'object_type' => 'SCULPTURE',
            'material' => 'Buff Chunar Sandstone',
            'dimensions' => 'Height: 2.15 m',
            'current_repository' => 'Indian Museum, Kolkata',
            'accession_number' => 'IM-A24192',
            'dating_statement' => 'c. 2nd century BCE (Shunga Period)',
            'start_year' => -180,
            'end_year' => -100,
            'description' => 'Monumental free-standing sculpted female deity with prominent girdle.',
            'iconographic_notes' => 'Elaborate coiffure, broad torque, heavy spherical anklets.',
            'is_published' => 1,
            'cover_image' => $coverImage,
            'cover_caption' => 'Frontal view of Besnagar Yakshi',
            'cover_credit' => 'Archaeological Survey of India Archive',
            'cover_license' => 'Creative Commons (CC BY-SA 4.0)',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/objects', $payload);

        $response->assertRedirect('/admin/objects');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('objects', [
            'name' => 'Besnagar Yakshi Colossus',
            'slug' => 'besnagar-yakshi-colossus',
            'heritage_site_id' => $site->id,
            'current_repository' => 'Indian Museum, Kolkata',
            'is_published' => true,
        ]);

        $createdObject = HeritageObject::where('slug', 'besnagar-yakshi-colossus')->firstOrFail();

        $this->assertDatabaseHas('media', [
            'mediable_type' => 'App\Models\HeritageObject',
            'mediable_id' => $createdObject->id,
            'caption' => 'Frontal view of Besnagar Yakshi',
            'is_cover' => true,
        ]);

        $coverMedia = $createdObject->coverImage();
        $this->assertNotNull($coverMedia);
        Storage::disk('public')->assertExists($coverMedia->file_path);
    }

    public function test_admin_can_view_edit_object_form(): void
    {
        $object = HeritageObject::create([
            'name' => 'Gudimallam Lingam',
            'slug' => 'gudimallam-lingam',
            'object_type' => 'SCULPTURE',
            'material' => 'Hard Igneous Stone',
            'dating_statement' => 'c. 3rd–2nd c. BCE',
            'current_repository' => 'Parasurameshwara Temple, Gudimallam',
            'description' => 'Earliest extant anthropomorphic Shaiva sculptural figure.',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/objects/{$object->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Artifact Record');
        $response->assertSee('Gudimallam Lingam');
    }

    public function test_admin_can_update_object_record(): void
    {
        $object = HeritageObject::create([
            'name' => 'Original Object Name',
            'slug' => 'original-object-name',
            'object_type' => 'SCULPTURE',
            'material' => 'Sandstone',
            'dating_statement' => 'c. 1st c. CE',
            'current_repository' => 'Mathura Museum',
            'description' => 'Original description.',
            'is_published' => false,
        ]);

        $payload = [
            'name' => 'Updated Katra Bodhisattva',
            'slug' => 'updated-katra-bodhisattva',
            'object_type' => 'SCULPTURE',
            'material' => 'Red Sikri Sandstone',
            'current_repository' => 'Government Museum, Mathura',
            'dating_statement' => 'c. 1st–2nd c. CE (Kushana Period)',
            'start_year' => 78,
            'end_year' => 150,
            'description' => 'Seated Buddha/Bodhisattva image dedicated by Buddhist nun Amoha-asi.',
            'is_published' => 1,
        ];

        $response = $this->actingAs($this->admin)->put("/admin/objects/{$object->id}", $payload);

        $response->assertRedirect('/admin/objects');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('objects', [
            'id' => $object->id,
            'name' => 'Updated Katra Bodhisattva',
            'slug' => 'updated-katra-bodhisattva',
            'material' => 'Red Sikri Sandstone',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_toggle_publish_status_and_it_reflects_on_public_show(): void
    {
        $object = HeritageObject::create([
            'name' => 'Sultanganj Buddha',
            'slug' => 'sultanganj-buddha',
            'object_type' => 'SCULPTURE',
            'material' => 'Cast Copper',
            'dating_statement' => 'c. 500–700 CE',
            'current_repository' => 'Birmingham Museum & Art Gallery',
            'description' => 'Colossal Gupta/Pala copper Buddha figure.',
            'is_published' => false,
        ]);

        // Prior to publishing: public route should return 404
        $publicCheck = $this->get('/objects/sultanganj-buddha');
        $publicCheck->assertStatus(404);

        // Admin toggles publish status
        $toggleResponse = $this->actingAs($this->admin)->post("/admin/objects/{$object->id}/toggle-publish");
        $toggleResponse->assertRedirect();
        $toggleResponse->assertSessionHas('success');

        $this->assertTrue($object->fresh()->is_published);

        // After publishing: public route should return 200 with object details
        $publicResponse = $this->get('/objects/sultanganj-buddha');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Sultanganj Buddha');
        $publicResponse->assertSee('Cast Copper');
    }

    public function test_admin_can_delete_object(): void
    {
        $object = HeritageObject::create([
            'name' => 'Temporary Object To Delete',
            'slug' => 'temporary-object-to-delete',
            'object_type' => 'POTTERY',
            'material' => 'Terracotta',
            'dating_statement' => 'c. 300 BCE',
            'current_repository' => 'Site Store',
            'description' => 'Northern Black Polished Ware sherd.',
            'is_published' => false,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/objects/{$object->id}");

        $response->assertRedirect('/admin/objects');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('objects', [
            'id' => $object->id,
        ]);
    }

    public function test_guest_cannot_access_object_management(): void
    {
        $response = $this->get('/admin/objects');
        $response->assertRedirect('/admin/login');
    }

    public function test_regular_user_cannot_access_object_management(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/objects');
        $response->assertStatus(403);
    }
}
