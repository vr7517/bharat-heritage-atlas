<?php

namespace Tests\Feature\Admin;

use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InscriptionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'BHA Admin Epigraphist',
            'email' => 'admin@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => true,
        ]);

        $this->regularUser = User::create([
            'name' => 'Epigraphy Student',
            'email' => 'student@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => false,
        ]);
    }

    public function test_admin_can_view_inscriptions_index_listing(): void
    {
        $inscription = Inscription::create([
            'title' => 'Besnagar Garuda Pillar Inscription',
            'slug' => 'besnagar-garuda-pillar-inscription',
            'language' => 'Prakrit',
            'script' => 'Ashokan Brahmi',
            'dating_statement' => 'c. 113 BCE',
            'start_year' => -115,
            'end_year' => -110,
            'epigraphic_reference' => 'CII Vol. I; Epigraphia Indica Vol. II, p. 87',
            'raw_text' => 'Devadevasa va[sude]vasa garudadhvaje ayam karite...',
            'translation' => 'This Garuda-standard of Vasudeva was erected by Heliodorus.',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/inscriptions');

        $response->assertStatus(200);
        $response->assertSee('Epigraphical Corpus', false);
        $response->assertSee('Besnagar Garuda Pillar Inscription');
        $response->assertSee('Ashokan Brahmi');
        $response->assertSee('Prakrit');
        $response->assertSee('Published');
    }

    public function test_admin_can_filter_inscriptions_by_search_and_status(): void
    {
        Inscription::create([
            'title' => 'Junagadh Rock Inscription of Rudradaman',
            'slug' => 'junagadh-rock-inscription-rudradaman',
            'language' => 'Sanskrit',
            'script' => 'Gupta Brahmi',
            'dating_statement' => 'c. 150 CE',
            'epigraphic_reference' => 'Epigraphia Indica Vol. VIII, pp. 36–49',
            'translation' => 'Record of Sudarshana lake embankment repair by Mahakshatrapa Rudradaman.',
            'is_published' => true,
        ]);

        Inscription::create([
            'title' => 'Unpublished Brahmi Fragment Draft',
            'slug' => 'unpublished-brahmi-fragment-draft',
            'language' => 'Prakrit',
            'script' => 'Early Historic Brahmi',
            'dating_statement' => 'c. 100 BCE',
            'epigraphic_reference' => 'Unpublished Field Diary',
            'translation' => 'Unverified fragmented donor record.',
            'is_published' => false,
        ]);

        $searchResponse = $this->actingAs($this->admin)->get('/admin/inscriptions?search=Rudradaman');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Junagadh Rock Inscription of Rudradaman');
        $searchResponse->assertDontSee('Unpublished Brahmi Fragment Draft');

        $draftResponse = $this->actingAs($this->admin)->get('/admin/inscriptions?status=draft');
        $draftResponse->assertStatus(200);
        $draftResponse->assertSee('Unpublished Brahmi Fragment Draft');
        $draftResponse->assertDontSee('Junagadh Rock Inscription of Rudradaman');
    }

    public function test_admin_can_view_create_inscription_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/inscriptions/create');

        $response->assertStatus(200);
        $response->assertSee('Register Epigraphical Record');
        $response->assertSee('Epigraph Nomenclature & Language', false);
    }

    public function test_admin_can_create_inscription_with_estampage_image(): void
    {
        Storage::fake('public');

        $site = HeritageSite::create([
            'name' => 'Vidisha Archaeological Site',
            'slug' => 'vidisha-archaeological-site',
            'site_type' => 'Archaeological Site',
            'dating_statement' => 'c. 2nd century BCE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Ancient Besnagar / Vidisha archaeological settlement and pillar complex.',
            'is_published' => true,
        ]);

        $estampageImage = UploadedFile::fake()->image('pillar_squeeze.jpg', 800, 600);

        $payload = [
            'title' => 'Heliodorus Pillar Dedication',
            'slug' => 'heliodorus-pillar-dedication',
            'heritage_site_id' => $site->id,
            'language' => 'Prakrit',
            'script' => 'Ashokan Brahmi',
            'dating_statement' => 'c. 113 BCE (14th regnal year of Bhagabhadra)',
            'start_year' => -115,
            'end_year' => -110,
            'donor' => 'Heliodorus, son of Dion, Greek ambassador from Taxila',
            'ruler_mentioned' => 'Kasiputra Bhagabhadra',
            'epigraphic_reference' => 'Epigraphia Indica Vol. II, p. 87',
            'raw_text' => 'Devadevasa va[sude]vasa garudadhvaje ayam karite ia heliodorena bhagavatena...',
            'translation' => 'This Garuda-standard of Vasudeva, god of gods, was erected by devotee Heliodorus...',
            'interpretation_notes' => 'Crucial early evidence for Vaishnava worship in central India.',
            'is_published' => 1,
            'cover_image' => $estampageImage,
            'cover_caption' => 'Ink squeeze estampage of the Brahmi inscription',
            'cover_credit' => 'Epigraphia Indica / ASI Epigraphy Branch',
            'cover_license' => 'Creative Commons (CC BY-SA 4.0)',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/inscriptions', $payload);

        $response->assertRedirect('/admin/inscriptions');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inscriptions', [
            'title' => 'Heliodorus Pillar Dedication',
            'slug' => 'heliodorus-pillar-dedication',
            'heritage_site_id' => $site->id,
            'ruler_mentioned' => 'Kasiputra Bhagabhadra',
            'is_published' => true,
        ]);

        $createdInscription = Inscription::where('slug', 'heliodorus-pillar-dedication')->firstOrFail();

        $this->assertDatabaseHas('media', [
            'mediable_type' => 'App\Models\Inscription',
            'mediable_id' => $createdInscription->id,
            'caption' => 'Ink squeeze estampage of the Brahmi inscription',
            'is_cover' => true,
        ]);

        $coverMedia = $createdInscription->coverImage();
        $this->assertNotNull($coverMedia);
        Storage::disk('public')->assertExists($coverMedia->file_path);
    }

    public function test_admin_can_view_edit_inscription_form(): void
    {
        $inscription = Inscription::create([
            'title' => 'Hathigumpha Inscription of Kharavela',
            'slug' => 'hathigumpha-inscription-kharavela',
            'language' => 'Prakrit',
            'script' => 'Ashokan Brahmi',
            'dating_statement' => 'c. 1st century BCE',
            'epigraphic_reference' => 'Epigraphia Indica Vol. XX, pp. 71–89',
            'translation' => 'Biographical record of Kalinga King Kharavela.',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/inscriptions/{$inscription->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Epigraphical Record');
        $response->assertSee('Hathigumpha Inscription of Kharavela');
    }

    public function test_admin_can_update_inscription_record(): void
    {
        $inscription = Inscription::create([
            'title' => 'Original Inscription Title',
            'slug' => 'original-inscription-title',
            'language' => 'Prakrit',
            'script' => 'Ashokan Brahmi',
            'dating_statement' => 'c. 3rd c. BCE',
            'epigraphic_reference' => 'CII Vol. I',
            'translation' => 'Original translation.',
            'is_published' => false,
        ]);

        $payload = [
            'title' => 'Minor Rock Edict of Ashoka (Bairat)',
            'slug' => 'ashoka-bairat-minor-rock-edict',
            'language' => 'Prakrit',
            'script' => 'Ashokan Brahmi',
            'dating_statement' => 'c. 250 BCE',
            'start_year' => -255,
            'end_year' => -245,
            'ruler_mentioned' => 'Devanampiya Piyadasi Ashoka',
            'epigraphic_reference' => 'Corpus Inscriptionum Indicarum Vol. I, p. 172',
            'translation' => 'King Priyadarsin of Magadha greets the Sangha and wishes them good health...',
            'is_published' => 1,
        ];

        $response = $this->actingAs($this->admin)->put("/admin/inscriptions/{$inscription->id}", $payload);

        $response->assertRedirect('/admin/inscriptions');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inscriptions', [
            'id' => $inscription->id,
            'title' => 'Minor Rock Edict of Ashoka (Bairat)',
            'slug' => 'ashoka-bairat-minor-rock-edict',
            'ruler_mentioned' => 'Devanampiya Piyadasi Ashoka',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_toggle_publish_status_and_it_reflects_on_public_show(): void
    {
        $inscription = Inscription::create([
            'title' => 'Aihole Inscription of Pulakeshin II',
            'slug' => 'aihole-inscription-pulakeshin-ii',
            'language' => 'Sanskrit',
            'script' => 'Southern Brahmi',
            'dating_statement' => '634 CE',
            'start_year' => 634,
            'end_year' => 634,
            'epigraphic_reference' => 'Epigraphia Indica Vol. VI, pp. 1–12',
            'translation' => 'Eulogy composed by Ravikirti mentioning Kalidasa and Bharavi.',
            'is_published' => false,
        ]);

        // Prior to publishing: public route should return 404
        $publicCheck = $this->get('/inscriptions/aihole-inscription-pulakeshin-ii');
        $publicCheck->assertStatus(404);

        // Admin toggles publish status
        $toggleResponse = $this->actingAs($this->admin)->post("/admin/inscriptions/{$inscription->id}/toggle-publish");
        $toggleResponse->assertRedirect();
        $toggleResponse->assertSessionHas('success');

        $this->assertTrue($inscription->fresh()->is_published);

        // After publishing: public route should return 200 with inscription details
        $publicResponse = $this->get('/inscriptions/aihole-inscription-pulakeshin-ii');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Aihole Inscription of Pulakeshin II');
        $publicResponse->assertSee('Ravikirti');
    }

    public function test_admin_can_delete_inscription(): void
    {
        $inscription = Inscription::create([
            'title' => 'Temporary Inscription Record To Delete',
            'slug' => 'temporary-inscription-record-to-delete',
            'language' => 'Prakrit',
            'script' => 'Ashokan Brahmi',
            'dating_statement' => 'c. 200 BCE',
            'epigraphic_reference' => 'Draft Paper',
            'translation' => 'Temporary test record.',
            'is_published' => false,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/inscriptions/{$inscription->id}");

        $response->assertRedirect('/admin/inscriptions');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('inscriptions', [
            'id' => $inscription->id,
        ]);
    }

    public function test_guest_cannot_access_inscription_management(): void
    {
        $response = $this->get('/admin/inscriptions');
        $response->assertRedirect('/admin/login');
    }

    public function test_regular_user_cannot_access_inscription_management(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/inscriptions');
        $response->assertStatus(403);
    }
}
