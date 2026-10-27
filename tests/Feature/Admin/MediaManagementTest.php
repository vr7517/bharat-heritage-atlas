<?php

namespace Tests\Feature\Admin;

use App\Models\HeritageSite;
use App\Models\Location;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaManagementTest extends TestCase
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

    public function test_windows_dev_server_preserves_php_upload_temp_environment(): void
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            $this->markTestSkipped('The development-server temp environment fix applies only to Windows.');
        }

        foreach (['TEMP', 'TMP'] as $variable) {
            $this->assertContains($variable, ServeCommand::$passthroughVariables);
            $this->assertSame(getenv($variable) ?: null, $_ENV[$variable] ?? null);
        }
    }

    public function test_admin_can_upload_cover_image_during_site_creation(): void
    {
        $file = UploadedFile::fake()->image('sanchi-torana.jpg', 800, 600);

        $payload = [
            'name' => 'Sanchi Stupa Visual Survey',
            'site_type' => 'Buddhist Stupa Complex',
            'dating_statement' => 'c. 3rd century BCE to 12th century CE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Comprehensive Buddhist archaeological complex with monumental toranas.',
            'is_published' => '1',
            'cover_image' => $file,
            'cover_caption' => 'South Torana gateway elevation',
            'cover_credit' => 'Archaeological Survey of India',
            'cover_license' => 'Creative Commons (CC BY-SA 4.0)',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/sites', $payload);

        $response->assertRedirect('/admin/sites');
        $site = HeritageSite::where('name', 'Sanchi Stupa Visual Survey')->firstOrFail();

        $this->assertDatabaseHas('media', [
            'mediable_type' => HeritageSite::class,
            'mediable_id' => $site->id,
            'caption' => 'South Torana gateway elevation',
            'is_cover' => true,
        ]);

        $media = $site->media()->first();
        Storage::disk('public')->assertExists($media->file_path);
        $this->assertNotNull($site->coverImage());
        $this->assertEquals($media->id, $site->coverImage()->id);
    }

    public function test_admin_can_upload_additional_gallery_media_to_site(): void
    {
        $site = HeritageSite::create([
            'name' => 'Ajanta Cave 1',
            'slug' => 'ajanta-cave-1',
            'site_type' => 'Rock-cut Cave Complex',
            'dating_statement' => 'c. 5th century CE',
            'protection_status' => 'ASI Protected',
            'summary' => 'Mahayana rock-cut vihara with Padmapani mural paintings.',
            'is_published' => true,
        ]);

        $file = UploadedFile::fake()->image('padmapani-mural.jpg', 1200, 900);

        $payload = [
            'mediable_type' => 'site',
            'mediable_id' => $site->id,
            'image' => $file,
            'caption' => 'Bodhisattva Padmapani holding blue lotus',
            'media_type' => 'PHOTOGRAPH',
            'license' => 'Creative Commons (CC BY-SA 4.0)',
            'source_credit' => 'Archaeological Survey of India',
            'photographer_or_draughtsman' => 'Survey Team',
            'capture_year' => 2023,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/media/upload', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('media', [
            'mediable_type' => HeritageSite::class,
            'mediable_id' => $site->id,
            'caption' => 'Bodhisattva Padmapani holding blue lotus',
            'media_type' => 'PHOTOGRAPH',
            'capture_year' => 2023,
        ]);

        $media = Media::where('caption', 'Bodhisattva Padmapani holding blue lotus')->first();
        Storage::disk('public')->assertExists($media->file_path);
    }

    public function test_php_upload_errors_are_reported_when_no_temporary_file_is_created(): void
    {
        $file = new UploadedFile(
            '',
            'sanchi-torana.jpg',
            'image/jpeg',
            UPLOAD_ERR_NO_TMP_DIR,
            true,
        );

        $response = $this->actingAs($this->admin)->post('/admin/media/upload', [
            'image' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'image' => 'Server error: Temporary directory missing on server.',
        ]);
    }

    public function test_admin_can_designate_image_as_primary_cover(): void
    {
        $site = HeritageSite::create([
            'name' => 'Ellora Kailasa Temple',
            'slug' => 'ellora-kailasa-temple',
            'site_type' => 'Rock-cut Monolith',
            'dating_statement' => 'c. 8th century CE',
            'protection_status' => 'ASI Protected',
            'summary' => 'Monolithic rock-cut Hindu temple complex excavated from basalt cliff.',
            'is_published' => true,
        ]);

        $media1 = $site->media()->create([
            'file_path' => 'media/sites/img1.jpg',
            'caption' => 'General view',
            'alt_text' => 'General view',
            'media_type' => 'PHOTOGRAPH',
            'license' => 'CC BY-SA 4.0',
            'source_credit' => 'ASI',
            'is_cover' => true,
            'display_order' => 1,
        ]);

        $media2 = $site->media()->create([
            'file_path' => 'media/sites/img2.jpg',
            'caption' => 'Courtyard elevation',
            'alt_text' => 'Courtyard elevation',
            'media_type' => 'PHOTOGRAPH',
            'license' => 'CC BY-SA 4.0',
            'source_credit' => 'ASI',
            'is_cover' => false,
            'display_order' => 2,
        ]);

        $this->assertEquals($media1->id, $site->coverImage()->id);

        $response = $this->actingAs($this->admin)->post("/admin/media/{$media2->id}/cover");

        $response->assertRedirect();
        $this->assertTrue($media2->fresh()->is_cover);
        $this->assertFalse($media1->fresh()->is_cover);
        $this->assertEquals($media2->id, $site->fresh()->coverImage()->id);
    }

    public function test_admin_can_delete_media_asset_and_file_is_removed_from_storage(): void
    {
        $site = HeritageSite::create([
            'name' => 'Sun Temple Modhera',
            'slug' => 'sun-temple-modhera',
            'site_type' => 'Temple Complex',
            'dating_statement' => 'c. 1026–1027 CE',
            'protection_status' => 'ASI Protected',
            'summary' => 'Solanki dynasty temple dedicated to Surya.',
            'is_published' => true,
        ]);

        // Put a fake file on the disk
        Storage::disk('public')->put('media/sites/modhera_kund.jpg', 'fake-image-bytes');

        $media = $site->media()->create([
            'file_path' => 'media/sites/modhera_kund.jpg',
            'caption' => 'Ramakunda stepped reservoir',
            'alt_text' => 'Ramakunda',
            'media_type' => 'PHOTOGRAPH',
            'license' => 'CC BY-SA 4.0',
            'source_credit' => 'ASI',
            'is_cover' => false,
            'display_order' => 1,
        ]);

        Storage::disk('public')->assertExists('media/sites/modhera_kund.jpg');

        $response = $this->actingAs($this->admin)->delete("/admin/media/{$media->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing('media/sites/modhera_kund.jpg');
    }

    public function test_guest_cannot_manage_media(): void
    {
        $file = UploadedFile::fake()->image('unauthorized.jpg');

        $this->post('/admin/media/upload', ['image' => $file])
            ->assertRedirect('/admin/login');

        $this->post('/admin/media/1/cover')
            ->assertRedirect('/admin/login');

        $this->delete('/admin/media/1')
            ->assertRedirect('/admin/login');
    }

    public function test_regular_user_cannot_manage_media(): void
    {
        $file = UploadedFile::fake()->image('unauthorized.jpg');

        $this->actingAs($this->regularUser)
            ->post('/admin/media/upload', ['image' => $file])
            ->assertStatus(403);

        $this->actingAs($this->regularUser)
            ->post('/admin/media/1/cover')
            ->assertStatus(403);

        $this->actingAs($this->regularUser)
            ->delete('/admin/media/1')
            ->assertStatus(403);
    }
}
