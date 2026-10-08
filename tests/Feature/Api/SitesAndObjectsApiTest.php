<?php

namespace Tests\Feature\Api;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitesAndObjectsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_sites_index_returns_paginated_list_with_envelope(): void
    {
        $response = $this->getJson('/api/v1/sites');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'site_type',
                        'dating_statement',
                        'chronology' => [
                            'start_year',
                            'end_year',
                            'is_dating_uncertain',
                        ],
                        'protection_status',
                        'summary',
                        'location' => [
                            'id',
                            'name',
                            'state',
                            'district',
                            'latitude',
                            'longitude',
                        ],
                        'period' => [
                            'id',
                            'name',
                            'slug',
                        ],
                        'dynasty' => [
                            'id',
                            'name',
                            'slug',
                        ],
                        'counts' => [
                            'objects',
                            'inscriptions',
                            'claims',
                        ],
                    ],
                ],
                'meta' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                ],
            ]);

        $this->assertTrue($response->json('success'));
        $this->assertEquals(3, $response->json('meta.total'));
    }

    public function test_sites_index_filters_correctly(): void
    {
        // 1. Search filter for Sanchi
        $searchRes = $this->getJson('/api/v1/sites?search=Sanchi');
        $searchRes->assertStatus(200);
        $this->assertCount(1, $searchRes->json('data'));
        $this->assertEquals('sanchi-archaeological-complex', $searchRes->json('data.0.slug'));

        // 2. Site type filter
        $typeRes = $this->getJson('/api/v1/sites?site_type=Temple');
        $typeRes->assertStatus(200);
        $this->assertCount(2, $typeRes->json('data'));

        // 3. Period filter by slug
        $periodRes = $this->getJson('/api/v1/sites?period=mauryan-period');
        $periodRes->assertStatus(200);
        $this->assertCount(1, $periodRes->json('data'));
        $this->assertEquals('sanchi-archaeological-complex', $periodRes->json('data.0.slug'));

        // 4. Dynasty filter by slug
        $dynastyRes = $this->getJson('/api/v1/sites?dynasty=shunga');
        $dynastyRes->assertStatus(200);
        $this->assertCount(1, $dynastyRes->json('data'));
        $this->assertEquals('besnagar-archaeological-complex', $dynastyRes->json('data.0.slug'));

        // 5. Year range filter
        $yearRes = $this->getJson('/api/v1/sites?from_year=-200&to_year=1300');
        $yearRes->assertStatus(200);
        // Gudimallam start is -300, Sanchi start is -250, Besnagar start is -300. None have start_year >= -200
        $this->assertCount(0, $yearRes->json('data'));

        $yearRes2 = $this->getJson('/api/v1/sites?from_year=-350&to_year=-50');
        $yearRes2->assertStatus(200);
        // Only Besnagar ends at -100 (which is <= -50). Gudimallam ends at 1200, Sanchi ends at 1100.
        $this->assertCount(1, $yearRes2->json('data'));
        $this->assertEquals('besnagar-archaeological-complex', $yearRes2->json('data.0.slug'));
    }

    public function test_sites_show_returns_detailed_graph_and_handles_404(): void
    {
        $response = $this->getJson('/api/v1/sites/besnagar-archaeological-complex');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'besnagar-archaeological-complex')
            ->assertJsonPath('data.location.name', 'Besnagar')
            ->assertJsonPath('data.primary_dynasty.name', 'Shunga');

        // Check embedded objects and inscriptions
        $this->assertNotEmpty($response->json('data.objects'));
        $this->assertEquals('heliodorus-garuda-pillar', $response->json('data.objects.0.slug'));

        $this->assertNotEmpty($response->json('data.inscriptions'));
        $this->assertNotEmpty($response->json('data.claims'));

        // Check 404 for nonexistent slug
        $notFound = $this->getJson('/api/v1/sites/non-existent-site');
        $notFound->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_objects_index_returns_paginated_list_and_filters(): void
    {
        $response = $this->getJson('/api/v1/objects');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertEquals(5, $response->json('meta.total'));

        // Filter by site slug
        $sanchiObjRes = $this->getJson('/api/v1/objects?site=sanchi-archaeological-complex');
        $sanchiObjRes->assertStatus(200);
        $this->assertCount(3, $sanchiObjRes->json('data'));

        // Filter by object type
        $pillarRes = $this->getJson('/api/v1/objects?object_type=Dhvaja-Stambha');
        $pillarRes->assertStatus(200);
        $this->assertCount(1, $pillarRes->json('data'));
        $this->assertEquals('heliodorus-garuda-pillar', $pillarRes->json('data.0.slug'));

        // Filter by material search
        $searchRes = $this->getJson('/api/v1/objects?search=dolerite');
        $searchRes->assertStatus(200);
        $this->assertCount(1, $searchRes->json('data'));
        $this->assertEquals('gudimallam-anthropomorphic-shiva-linga', $searchRes->json('data.0.slug'));
    }

    public function test_objects_show_returns_full_evidence_graph_and_handles_404(): void
    {
        $response = $this->getJson('/api/v1/objects/gudimallam-anthropomorphic-shiva-linga');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'gudimallam-anthropomorphic-shiva-linga')
            ->assertJsonPath('data.object_type', 'Linga')
            ->assertJsonPath('data.site.slug', 'parasurameshwara-temple-gudimallam');

        // Check claims, evidence, and primary sources
        $claims = $response->json('data.claims');
        $this->assertNotEmpty($claims);
        $this->assertCount(3, $claims);

        $firstClaim = $claims[0];
        $this->assertArrayHasKey('evidence', $firstClaim);
        $this->assertNotEmpty($firstClaim['evidence']);

        $firstEvidence = $firstClaim['evidence'][0];
        $this->assertArrayHasKey('sources', $firstEvidence);
        $this->assertNotEmpty($firstEvidence['sources']);

        // Check 404 for nonexistent object
        $notFound = $this->getJson('/api/v1/objects/unknown-relic');
        $notFound->assertStatus(404)
            ->assertJsonPath('success', false);
    }
}
