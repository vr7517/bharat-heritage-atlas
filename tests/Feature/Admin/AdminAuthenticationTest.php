<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Curator Email');
        $response->assertSee('Master Password');
        $response->assertSee('Authenticate as Curator');
    }

    public function test_admin_can_authenticate_using_the_login_screen(): void
    {
        $admin = User::create([
            'name' => 'Curator Administrator',
            'email' => 'curator@bharatheritage.in',
            'password' => Hash::make('SecretAdminPass123'),
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'curator@bharatheritage.in',
            'password' => 'SecretAdminPass123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_non_admin_user_cannot_authenticate_into_admin_portal(): void
    {
        User::create([
            'name' => 'Regular Member',
            'email' => 'member@example.com',
            'password' => Hash::make('MemberPass123'),
            'is_admin' => false,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'member@example.com',
            'password' => 'MemberPass123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_unauthenticated_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_non_admin_gets_403_on_admin_dashboard(): void
    {
        $user = User::create([
            'name' => 'Regular Member',
            'email' => 'regular@example.com',
            'password' => Hash::make('MemberPass123'),
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_and_see_metrics(): void
    {
        $admin = User::create([
            'name' => 'Lead Curator',
            'email' => 'lead@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Welcome back, Lead Curator');
        $response->assertSee('CMS Management Active');
        $response->assertSee('Sites');
        $response->assertSee('Add Heritage Site');
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::create([
            'name' => 'Admin Curator',
            'email' => 'admin@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post('/admin/logout');

        $this->assertGuest();
        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_is_redirected_from_login_page_to_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Curator',
            'email' => 'admin@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/login');

        $response->assertRedirect(route('admin.dashboard'));
    }
}
