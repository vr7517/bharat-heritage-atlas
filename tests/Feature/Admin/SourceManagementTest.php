<?php

namespace Tests\Feature\Admin;

use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SourceManagementTest extends TestCase
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
            'name' => 'Regular Researcher',
            'email' => 'researcher@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => false,
        ]);
    }

    public function test_admin_can_view_sources_index(): void
    {
        Source::create([
            'title' => 'The Monuments of Sanchi',
            'authors' => 'Marshall, John; Foucher, Alfred',
            'publication_year' => 1940,
            'source_type' => 'ACADEMIC_MONOGRAPH',
            'publisher' => 'Government of India Press, Calcutta',
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/sources');

        $response->assertStatus(200);
        $response->assertSee('Bibliographic Sources');
        $response->assertSee('The Monuments of Sanchi');
        $response->assertSee('Marshall, John; Foucher, Alfred');
    }

    public function test_admin_can_filter_sources_by_typology_and_search(): void
    {
        Source::create([
            'title' => 'Epigraphia Indica Vol. VIII',
            'authors' => 'Hultzsch, E.',
            'publication_year' => 1905,
            'source_type' => 'EPIGRAPHIC_CORPUS',
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
        ]);

        Source::create([
            'title' => 'Early Indian Architecture',
            'authors' => 'Coomaraswamy, Ananda K.',
            'publication_year' => 1930,
            'source_type' => 'ACADEMIC_MONOGRAPH',
            'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
        ]);

        // Search
        $searchRes = $this->actingAs($this->admin)->get('/admin/sources?search=Epigraphia');
        $searchRes->assertSee('Epigraphia Indica Vol. VIII');
        $searchRes->assertDontSee('Early Indian Architecture');

        // Filter type
        $filterRes = $this->actingAs($this->admin)->get('/admin/sources?source_type=ACADEMIC_MONOGRAPH');
        $filterRes->assertSee('Early Indian Architecture');
        $filterRes->assertDontSee('Epigraphia Indica Vol. VIII');
    }

    public function test_admin_can_render_source_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/sources/create');

        $response->assertStatus(200);
        $response->assertSee('Register Scholarly Source');
        $response->assertSee('Full Work Title');
    }

    public function test_admin_can_register_new_bibliographic_source(): void
    {
        $payload = [
            'title' => 'Puratattva: Bulletin of the Indian Archaeological Society',
            'authors' => 'Sarma, I. K.',
            'publication_year' => 1974,
            'source_type' => 'PEER_REVIEWED_JOURNAL',
            'journal_or_series' => 'Puratattva',
            'volume_issue' => 'No. 7',
            'pages' => 'pp. 97–100',
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
            'notes' => 'Preliminary report announcing the Gudimallam Phase I discovery.',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/sources', $payload);

        $response->assertRedirect('/admin/sources');
        $this->assertDatabaseHas('sources', [
            'title' => 'Puratattva: Bulletin of the Indian Archaeological Society',
            'authors' => 'Sarma, I. K.',
            'publication_year' => 1974,
        ]);
    }

    public function test_admin_can_update_existing_source(): void
    {
        $source = Source::create([
            'title' => 'Initial Source Draft',
            'authors' => 'Archaeologist A',
            'publication_year' => 1950,
            'source_type' => 'GOVERNMENT_SURVEY',
            'reliability_tier' => 'TIER_3_HISTORICAL_SECONDARY',
        ]);

        $payload = [
            'title' => 'Revised Authoritative Corpus',
            'authors' => 'Archaeologist A; Specialist B',
            'publication_year' => 1952,
            'source_type' => 'ARCHAEOLOGICAL_REPORT',
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/sources/{$source->id}", $payload);

        $response->assertRedirect('/admin/sources');
        $this->assertDatabaseHas('sources', [
            'id' => $source->id,
            'title' => 'Revised Authoritative Corpus',
            'authors' => 'Archaeologist A; Specialist B',
            'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
        ]);
    }

    public function test_admin_can_delete_source(): void
    {
        $source = Source::create([
            'title' => 'To Be Removed',
            'authors' => 'Test Author',
            'publication_year' => 2000,
            'source_type' => 'SECONDARY_HISTORICAL',
            'reliability_tier' => 'TIER_4_TERTIARY_COMPILATION',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/sources/{$source->id}");

        $response->assertRedirect('/admin/sources');
        $this->assertDatabaseMissing('sources', ['id' => $source->id]);
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin/sources')->assertRedirect('/admin/login');
        $this->post('/admin/sources', [])->assertRedirect('/admin/login');
    }

    public function test_regular_user_is_forbidden_with_403(): void
    {
        $this->actingAs($this->regularUser)->get('/admin/sources')->assertStatus(403);
        $this->actingAs($this->regularUser)->post('/admin/sources', [])->assertStatus(403);
    }
}
