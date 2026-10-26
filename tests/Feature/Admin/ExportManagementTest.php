<?php

namespace Tests\Feature\Admin;

use App\Models\HeritageSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExportManagementTest extends TestCase
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

    public function test_admin_can_view_export_center(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/exports');

        $response->assertStatus(200);
        $response->assertSee('Administrative Data Export Center');
        $response->assertSee('Heritage Sites');
        $response->assertSee('Epigraphical Corpus');
        $response->assertSee('Artifacts');
        $response->assertSee('Open Research Datasets');
    }

    public function test_admin_can_download_table_csv_export(): void
    {
        HeritageSite::create([
            'name' => 'Hampi Virupaksha Temple',
            'slug' => 'hampi-virupaksha-temple',
            'site_type' => 'Dravidian Temple Complex',
            'dating_statement' => 'c. 7th – 16th century CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Principal active temple in sacred center of Vijayanagara.',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/exports/sites/csv');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('Hampi Virupaksha Temple', $content);
    }

    public function test_admin_can_download_table_json_export(): void
    {
        HeritageSite::create([
            'name' => 'Konark Sun Temple',
            'slug' => 'konark-sun-temple',
            'site_type' => 'Kalinga Stone Temple',
            'dating_statement' => 'c. 1250 CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Gigantic Surya chariot monument.',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/exports/sites/json');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/json', (string) $response->headers->get('content-type'));

        $json = json_decode($response->streamedContent(), true);
        $this->assertEquals('sites', $json['export_entity']);
        $this->assertGreaterThanOrEqual(1, $json['record_count']);
        $this->assertEquals('Konark Sun Temple', $json['records'][0]['name']);
    }

    public function test_guest_cannot_access_export_center(): void
    {
        $this->get('/admin/exports')->assertRedirect('/admin/login');
        $this->get('/admin/exports/sites/csv')->assertRedirect('/admin/login');
    }

    public function test_regular_user_cannot_access_export_center(): void
    {
        $this->actingAs($this->regularUser)->get('/admin/exports')->assertStatus(403);
        $this->actingAs($this->regularUser)->get('/admin/exports/sites/csv')->assertStatus(403);
    }
}
