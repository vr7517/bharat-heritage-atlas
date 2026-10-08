<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HeritageSite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    /**
     * Display a standard GeoJSON FeatureCollection of verified heritage sites.
     * RFC 7946 compliant GeoJSON output for web mapping libraries (Leaflet, Mapbox, OpenLayers).
     */
    public function sites(Request $request): JsonResponse
    {
        $query = HeritageSite::query()
            ->with(['location', 'primaryPeriod', 'primaryDynasty'])
            ->withCount(['objects', 'inscriptions', 'claims'])
            ->whereHas('location', function ($lq) {
                $lq->whereNotNull('latitude')->whereNotNull('longitude');
            });

        // Site type filter
        if ($siteType = $request->query('site_type')) {
            $query->where('site_type', 'like', "%{$siteType}%");
        }

        // Period filter
        if ($periodSlug = $request->query('period')) {
            $query->whereHas('primaryPeriod', function ($pq) use ($periodSlug) {
                $pq->where('slug', $periodSlug);
            });
        }

        // Dynasty filter
        if ($dynastySlug = $request->query('dynasty')) {
            $query->whereHas('primaryDynasty', function ($dq) use ($dynastySlug) {
                $dq->where('slug', $dynastySlug);
            });
        }

        // State filter
        if ($state = $request->query('state')) {
            $query->whereHas('location', function ($lq) use ($state) {
                $lq->where('state', 'like', "%{$state}%");
            });
        }

        // Chronological range filters
        if ($fromYear = $request->query('from_year')) {
            $query->where('start_year', '>=', (int) $fromYear);
        }

        if ($toYear = $request->query('to_year')) {
            $query->where('end_year', '<=', (int) $toYear);
        }

        $sites = $query->orderBy('start_year', 'asc')->get();

        $features = $sites->map(function (HeritageSite $site) {
            return [
                'type' => 'Feature',
                'id' => $site->id,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $site->location->longitude,
                        (float) $site->location->latitude,
                    ],
                ],
                'properties' => [
                    'id' => $site->id,
                    'name' => $site->name,
                    'slug' => $site->slug,
                    'site_type' => $site->site_type,
                    'dating_statement' => $site->dating_statement,
                    'chronology' => [
                        'start_year' => $site->start_year,
                        'end_year' => $site->end_year,
                        'is_dating_uncertain' => (bool) $site->is_dating_uncertain,
                    ],
                    'protection_status' => $site->protection_status,
                    'summary' => $site->summary,
                    'location' => [
                        'name' => $site->location->name,
                        'state' => $site->location->state,
                        'district' => $site->location->district,
                        'altitude_meters' => $site->location->altitude_meters,
                        'uncertainty_radius_meters' => $site->location->uncertainty_radius_meters,
                    ],
                    'period' => $site->primaryPeriod ? [
                        'name' => $site->primaryPeriod->name,
                        'slug' => $site->primaryPeriod->slug,
                    ] : null,
                    'dynasty' => $site->primaryDynasty ? [
                        'name' => $site->primaryDynasty->name,
                        'slug' => $site->primaryDynasty->slug,
                    ] : null,
                    'counts' => [
                        'objects' => $site->objects_count,
                        'inscriptions' => $site->inscriptions_count,
                        'claims' => $site->claims_count,
                    ],
                    'api_url' => route('api.v1.sites.show', ['slug' => $site->slug], false),
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
