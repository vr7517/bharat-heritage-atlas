<?php

namespace Tests\Feature\Api;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineAndGeoApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_timeline_endpoint_returns_chronological_stream(): void
    {
        $response = $this->getJson('/api/v1/timeline');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'entity_type',
                        'id',
                        'name',
                        'slug',
                        'category',
                        'chronology' => [
                            'start_year',
                            'end_year',
                            'dating_statement',
                            'is_dating_uncertain',
                        ],
                        'summary',
                        'api_url',
                    ],
                ],
                'meta' => [
                    'count',
                    'total_matching',
                    'limit',
                    'range' => [
                        'earliest_year',
                        'latest_year',
                    ],
                ],
            ]);

        $this->assertTrue($response->json('success'));
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        // Verify chronological monotonic ordering by start_year
        $previousYear = -999999;
        foreach ($data as $event) {
            $currentStart = $event['chronology']['start_year'];
            $this->assertGreaterThanOrEqual(
                $previousYear,
                $currentStart,
                "Event '{$event['name']}' ({$currentStart}) should be >= previous ({$previousYear})"
            );
            $previousYear = $currentStart;
        }
    }

    public function test_timeline_filters_by_entity_type_and_year_bounds(): void
    {
        // 1. Filter by entity_type=sites
        $sitesRes = $this->getJson('/api/v1/timeline?entity_type=sites');
        $sitesRes->assertStatus(200);
        $this->assertEquals(3, $sitesRes->json('meta.count'));
        foreach ($sitesRes->json('data') as $event) {
            $this->assertEquals('HeritageSite', $event['entity_type']);
        }

        // 2. Filter by entity_type=objects
        $objRes = $this->getJson('/api/v1/timeline?entity_type=objects');
        $objRes->assertStatus(200);
        $this->assertEquals(5, $objRes->json('meta.count'));
        foreach ($objRes->json('data') as $event) {
            $this->assertEquals('HeritageObject', $event['entity_type']);
        }

        // 3. Filter by entity_type=inscriptions
        $inscrRes = $this->getJson('/api/v1/timeline?entity_type=inscriptions');
        $inscrRes->assertStatus(200);
        $this->assertEquals(8, $inscrRes->json('meta.count'));
        foreach ($inscrRes->json('data') as $event) {
            $this->assertEquals('Inscription', $event['entity_type']);
        }

        // 4. Filter by year range (from_year = -120 to -100: Heliodorus Pillar era)
        $rangeRes = $this->getJson('/api/v1/timeline?from_year=-120&to_year=-100');
        $rangeRes->assertStatus(200);
        $rangeData = $rangeRes->json('data');
        $this->assertNotEmpty($rangeData);
        foreach ($rangeData as $event) {
            $this->assertGreaterThanOrEqual(-120, $event['chronology']['start_year']);
            $this->assertLessThanOrEqual(-100, $event['chronology']['start_year']);
        }
    }

    public function test_geo_sites_returns_valid_rfc7946_geojson_feature_collection(): void
    {
        $response = $this->getJson('/api/v1/geo/sites');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'type',
                'features' => [
                    '*' => [
                        'type',
                        'id',
                        'geometry' => [
                            'type',
                            'coordinates',
                        ],
                        'properties' => [
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
                                'name',
                                'state',
                                'district',
                                'altitude_meters',
                                'uncertainty_radius_meters',
                            ],
                            'period',
                            'dynasty',
                            'counts' => [
                                'objects',
                                'inscriptions',
                                'claims',
                            ],
                            'api_url',
                        ],
                    ],
                ],
            ]);

        $this->assertEquals('FeatureCollection', $response->json('type'));
        $features = $response->json('features');
        $this->assertCount(3, $features);

        // Verify coordinates format [longitude, latitude] for each feature
        foreach ($features as $feature) {
            $this->assertEquals('Feature', $feature['type']);
            $this->assertEquals('Point', $feature['geometry']['type']);
            $this->assertCount(2, $feature['geometry']['coordinates']);

            $lon = $feature['geometry']['coordinates'][0];
            $lat = $feature['geometry']['coordinates'][1];

            // Indian subcontinent bounding box sanity: Lat ~8-37 N, Long ~68-98 E
            $this->assertGreaterThan(68.0, $lon);
            $this->assertLessThan(98.0, $lon);
            $this->assertGreaterThan(8.0, $lat);
            $this->assertLessThan(37.0, $lat);

            // Spatial uncertainty metadata
            $this->assertArrayHasKey('uncertainty_radius_meters', $feature['properties']['location']);
            $this->assertGreaterThan(0, $feature['properties']['location']['uncertainty_radius_meters']);
        }
    }

    public function test_geo_sites_filters_by_state_and_dynasty(): void
    {
        // 1. State filter: Madhya Pradesh (Besnagar & Sanchi)
        $mpRes = $this->getJson('/api/v1/geo/sites?state=Madhya Pradesh');
        $mpRes->assertStatus(200);
        $this->assertCount(2, $mpRes->json('features'));

        // 2. State filter: Andhra Pradesh (Gudimallam)
        $apRes = $this->getJson('/api/v1/geo/sites?state=Andhra Pradesh');
        $apRes->assertStatus(200);
        $this->assertCount(1, $apRes->json('features'));
        $this->assertEquals('parasurameshwara-temple-gudimallam', $apRes->json('features.0.properties.slug'));

        // 3. Dynasty filter: Shunga
        $shungaRes = $this->getJson('/api/v1/geo/sites?dynasty=shunga');
        $shungaRes->assertStatus(200);
        $this->assertCount(1, $shungaRes->json('features'));
        $this->assertEquals('besnagar-archaeological-complex', $shungaRes->json('features.0.properties.slug'));
    }
}
