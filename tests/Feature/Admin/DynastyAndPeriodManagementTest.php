<?php

namespace Tests\Feature\Admin;

use App\Models\Dynasty;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DynastyAndPeriodManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'BHA Admin Historian',
            'email' => 'admin@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => true,
        ]);

        $this->regularUser = User::create([
            'name' => 'Standard History Student',
            'email' => 'student@bharatheritage.in',
            'password' => Hash::make('SecretPass123'),
            'is_admin' => false,
        ]);
    }

    /* -------------------------------------------------------------------------
     * DYNASTY MANAGEMENT TESTS
     * ------------------------------------------------------------------------- */

    public function test_admin_can_view_dynasties_index_listing(): void
    {
        Dynasty::create([
            'name' => 'Maurya Empire',
            'slug' => 'maurya-empire',
            'dating_statement' => 'c. 322 – 185 BCE',
            'start_year' => -322,
            'end_year' => -185,
            'capital' => 'Pataliputra',
            'region' => 'Pan-Indian / Magadha Core',
            'description' => 'Imperial dynasty founded by Chandragupta Maurya.',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dynasties');

        $response->assertStatus(200);
        $response->assertSee('Dynasties & Imperial Realms', false);
        $response->assertSee('Maurya Empire');
        $response->assertSee('Pataliputra');
        $response->assertSee('322 BCE');
    }

    public function test_admin_can_filter_dynasties_by_search(): void
    {
        Dynasty::create([
            'name' => 'Satavahana Dynasty',
            'slug' => 'satavahana-dynasty',
            'dating_statement' => 'c. 1st c. BCE – 2nd c. CE',
            'capital' => 'Pratishthana',
            'region' => 'Deccan Plateau',
        ]);

        Dynasty::create([
            'name' => 'Chola Dynasty',
            'slug' => 'chola-dynasty',
            'dating_statement' => 'c. 9th – 13th c. CE',
            'capital' => 'Tanjavur',
            'region' => 'Tamil Country / Kaveri Delta',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dynasties?search=Satavahana');

        $response->assertStatus(200);
        $response->assertSee('Satavahana Dynasty');
        $response->assertDontSee('Chola Dynasty');
    }

    public function test_admin_can_view_create_dynasty_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dynasties/create');

        $response->assertStatus(200);
        $response->assertSee('Register Imperial Realm / Dynasty');
    }

    public function test_admin_can_create_new_dynasty(): void
    {
        $payload = [
            'name' => 'Shunga Dynasty',
            'slug' => 'shunga-dynasty',
            'dating_statement' => 'c. 185 – 73 BCE',
            'start_year' => -185,
            'end_year' => -73,
            'capital' => 'Pataliputra / Vidisha',
            'region' => 'Central & Eastern India',
            'description' => 'Dynasty founded by Pushyamitra Shunga succeeding the Mauryas.',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/dynasties', $payload);

        $response->assertRedirect('/admin/dynasties');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dynasties', [
            'name' => 'Shunga Dynasty',
            'slug' => 'shunga-dynasty',
            'capital' => 'Pataliputra / Vidisha',
            'start_year' => -185,
        ]);
    }

    public function test_admin_can_view_edit_dynasty_form(): void
    {
        $dynasty = Dynasty::create([
            'name' => 'Gupta Dynasty',
            'slug' => 'gupta-dynasty',
            'dating_statement' => 'c. 320 – 550 CE',
            'capital' => 'Pataliputra',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/dynasties/{$dynasty->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Imperial Realm / Dynasty');
        $response->assertSee('Gupta Dynasty');
    }

    public function test_admin_can_update_dynasty(): void
    {
        $dynasty = Dynasty::create([
            'name' => 'Pallava Dynasty',
            'slug' => 'pallava-dynasty',
            'dating_statement' => 'c. 4th – 9th c. CE',
            'capital' => 'Kanchipuram',
        ]);

        $payload = [
            'name' => 'Imperial Pallavas of Kanchi',
            'slug' => 'imperial-pallavas-kanchi',
            'dating_statement' => 'c. 275 – 897 CE',
            'start_year' => 275,
            'end_year' => 897,
            'capital' => 'Kanchipuram / Mamallapuram',
            'region' => 'Tondaimandalam (Northern Tamil Nadu)',
            'description' => 'Patrons of rock-cut and structural Dravidian architecture.',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/dynasties/{$dynasty->id}", $payload);

        $response->assertRedirect('/admin/dynasties');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dynasties', [
            'id' => $dynasty->id,
            'name' => 'Imperial Pallavas of Kanchi',
            'slug' => 'imperial-pallavas-kanchi',
            'start_year' => 275,
        ]);
    }

    public function test_admin_can_delete_dynasty(): void
    {
        $dynasty = Dynasty::create([
            'name' => 'Ephemeral Petty Chieftaincy',
            'slug' => 'ephemeral-petty-chieftaincy',
            'dating_statement' => 'c. 10th c. CE',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/dynasties/{$dynasty->id}");

        $response->assertRedirect('/admin/dynasties');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('dynasties', [
            'id' => $dynasty->id,
        ]);
    }

    /* -------------------------------------------------------------------------
     * PERIOD MANAGEMENT TESTS
     * ------------------------------------------------------------------------- */

    public function test_admin_can_view_periods_index_listing(): void
    {
        Period::create([
            'name' => 'Mature Harappan Horizon',
            'slug' => 'mature-harappan-horizon',
            'dating_statement' => 'c. 2600 – 1900 BCE',
            'start_year' => 2600,
            'end_year' => 1900,
            'start_era' => 'BCE',
            'end_era' => 'BCE',
            'description' => 'Urban phase of the Indus Valley Civilization.',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/periods');

        $response->assertStatus(200);
        $response->assertSee('Historical Periods & Archaeological Horizons', false);
        $response->assertSee('Mature Harappan Horizon');
        $response->assertSee('2600 BCE – 1900 BCE');
    }

    public function test_admin_can_filter_periods_by_search(): void
    {
        Period::create([
            'name' => 'Early Historic Period',
            'slug' => 'early-historic-period',
            'dating_statement' => 'c. 600 BCE – 300 CE',
            'start_year' => 600,
            'end_year' => 300,
            'start_era' => 'BCE',
            'end_era' => 'CE',
        ]);

        Period::create([
            'name' => 'Late Medieval Period',
            'slug' => 'late-medieval-period',
            'dating_statement' => 'c. 1200 – 1700 CE',
            'start_year' => 1200,
            'end_year' => 1700,
            'start_era' => 'CE',
            'end_era' => 'CE',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/periods?search=Historic');

        $response->assertStatus(200);
        $response->assertSee('Early Historic Period');
        $response->assertDontSee('Late Medieval Period');
    }

    public function test_admin_can_view_create_period_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/periods/create');

        $response->assertStatus(200);
        $response->assertSee('Register Historical Period');
    }

    public function test_admin_can_create_new_period(): void
    {
        $payload = [
            'name' => 'Kushana-Satavahana Era',
            'slug' => 'kushana-satavahana-era',
            'dating_statement' => 'c. 1st c. BCE – 3rd c. CE',
            'start_year' => 100,
            'start_era' => 'BCE',
            'end_year' => 300,
            'end_era' => 'CE',
            'description' => 'Period of heightened maritime trade and rock-cut chaitya excavations.',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/periods', $payload);

        $response->assertRedirect('/admin/periods');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('periods', [
            'name' => 'Kushana-Satavahana Era',
            'slug' => 'kushana-satavahana-era',
            'start_year' => 100,
            'start_era' => 'BCE',
            'end_era' => 'CE',
        ]);
    }

    public function test_admin_can_view_edit_period_form(): void
    {
        $period = Period::create([
            'name' => 'Gupta-Vakataka Classical Epoch',
            'slug' => 'gupta-vakataka-classical-epoch',
            'dating_statement' => 'c. 320 – 550 CE',
            'start_year' => 320,
            'start_era' => 'CE',
            'end_year' => 550,
            'end_era' => 'CE',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/periods/{$period->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Historical Period');
        $response->assertSee('Gupta-Vakataka Classical Epoch');
    }

    public function test_admin_can_update_period(): void
    {
        $period = Period::create([
            'name' => 'Early Medieval Horizon',
            'slug' => 'early-medieval-horizon',
            'dating_statement' => 'c. 600 – 1200 CE',
            'start_year' => 600,
            'start_era' => 'CE',
            'end_year' => 1200,
            'end_era' => 'CE',
        ]);

        $payload = [
            'name' => 'Early Medieval Subcontinental Horizon',
            'slug' => 'early-medieval-subcontinental-horizon',
            'dating_statement' => 'c. 550 – 1200 CE',
            'start_year' => 550,
            'start_era' => 'CE',
            'end_year' => 1200,
            'end_era' => 'CE',
            'description' => 'Regional crystallization of architectural styles: Nagara, Dravida, and Vesara.',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/periods/{$period->id}", $payload);

        $response->assertRedirect('/admin/periods');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('periods', [
            'id' => $period->id,
            'name' => 'Early Medieval Subcontinental Horizon',
            'slug' => 'early-medieval-subcontinental-horizon',
            'start_year' => 550,
        ]);
    }

    public function test_admin_can_delete_period(): void
    {
        $period = Period::create([
            'name' => 'Temporary Horizon Period',
            'slug' => 'temporary-horizon-period',
            'dating_statement' => 'c. 1000 BCE',
            'start_year' => 1000,
            'start_era' => 'BCE',
            'end_year' => 900,
            'end_era' => 'BCE',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/periods/{$period->id}");

        $response->assertRedirect('/admin/periods');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('periods', [
            'id' => $period->id,
        ]);
    }

    /* -------------------------------------------------------------------------
     * ACCESS CONTROL TESTS
     * ------------------------------------------------------------------------- */

    public function test_guest_cannot_access_dynasties_or_periods(): void
    {
        $this->get('/admin/dynasties')->assertRedirect('/admin/login');
        $this->get('/admin/periods')->assertRedirect('/admin/login');
    }

    public function test_regular_user_cannot_access_dynasties_or_periods(): void
    {
        $this->actingAs($this->regularUser)->get('/admin/dynasties')->assertStatus(403);
        $this->actingAs($this->regularUser)->get('/admin/periods')->assertStatus(403);
    }
}
