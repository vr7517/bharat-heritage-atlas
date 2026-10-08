<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TimelineController extends Controller
{
    /**
     * Display a unified chronological stream of verified cultural heritage milestones.
     */
    public function index(Request $request): JsonResponse
    {
        $fromYear = $request->query('from_year') !== null ? (int) $request->query('from_year') : null;
        $toYear = $request->query('to_year') !== null ? (int) $request->query('to_year') : null;
        $entityType = strtolower($request->query('entity_type', 'all'));
        $limit = min(max((int) $request->query('limit', 50), 1), 200);

        $events = new Collection();

        // 1. Heritage Sites
        if (in_array($entityType, ['all', 'sites'])) {
            $siteQuery = HeritageSite::with(['location', 'primaryPeriod', 'primaryDynasty']);

            if ($fromYear !== null) {
                $siteQuery->where('start_year', '>=', $fromYear);
            }
            if ($toYear !== null) {
                $siteQuery->where('start_year', '<=', $toYear);
            }

            foreach ($siteQuery->get() as $site) {
                $events->push([
                    'entity_type' => 'HeritageSite',
                    'id' => $site->id,
                    'name' => $site->name,
                    'slug' => $site->slug,
                    'category' => $site->site_type,
                    'chronology' => [
                        'start_year' => $site->start_year,
                        'end_year' => $site->end_year,
                        'dating_statement' => $site->dating_statement,
                        'is_dating_uncertain' => (bool) $site->is_dating_uncertain,
                    ],
                    'summary' => $site->summary,
                    'location' => $site->location ? [
                        'name' => $site->location->name,
                        'state' => $site->location->state,
                        'district' => $site->location->district,
                    ] : null,
                    'period' => $site->primaryPeriod ? [
                        'name' => $site->primaryPeriod->name,
                        'slug' => $site->primaryPeriod->slug,
                    ] : null,
                    'dynasty' => $site->primaryDynasty ? [
                        'name' => $site->primaryDynasty->name,
                        'slug' => $site->primaryDynasty->slug,
                    ] : null,
                    'api_url' => route('api.v1.sites.show', ['slug' => $site->slug], false),
                ]);
            }
        }

        // 2. Heritage Objects
        if (in_array($entityType, ['all', 'objects'])) {
            $objQuery = HeritageObject::with(['heritageSite', 'location', 'period', 'dynasty']);

            if ($fromYear !== null) {
                $objQuery->where('start_year', '>=', $fromYear);
            }
            if ($toYear !== null) {
                $objQuery->where('start_year', '<=', $toYear);
            }

            foreach ($objQuery->get() as $obj) {
                $events->push([
                    'entity_type' => 'HeritageObject',
                    'id' => $obj->id,
                    'name' => $obj->name,
                    'slug' => $obj->slug,
                    'category' => $obj->object_type,
                    'chronology' => [
                        'start_year' => $obj->start_year,
                        'end_year' => $obj->end_year,
                        'dating_statement' => $obj->dating_statement,
                        'is_dating_uncertain' => (bool) $obj->is_dating_uncertain,
                    ],
                    'summary' => $obj->description,
                    'site' => $obj->heritageSite ? [
                        'name' => $obj->heritageSite->name,
                        'slug' => $obj->heritageSite->slug,
                    ] : null,
                    'location' => $obj->location ? [
                        'name' => $obj->location->name,
                        'state' => $obj->location->state,
                        'district' => $obj->location->district,
                    ] : null,
                    'period' => $obj->period ? [
                        'name' => $obj->period->name,
                        'slug' => $obj->period->slug,
                    ] : null,
                    'dynasty' => $obj->dynasty ? [
                        'name' => $obj->dynasty->name,
                        'slug' => $obj->dynasty->slug,
                    ] : null,
                    'api_url' => route('api.v1.objects.show', ['slug' => $obj->slug], false),
                ]);
            }
        }

        // 3. Inscriptions
        if (in_array($entityType, ['all', 'inscriptions'])) {
            $inscrQuery = Inscription::with(['heritageSite', 'object']);

            if ($fromYear !== null) {
                $inscrQuery->where('start_year', '>=', $fromYear);
            }
            if ($toYear !== null) {
                $inscrQuery->where('start_year', '<=', $toYear);
            }

            foreach ($inscrQuery->get() as $inscr) {
                $events->push([
                    'entity_type' => 'Inscription',
                    'id' => $inscr->id,
                    'name' => $inscr->title,
                    'slug' => $inscr->slug,
                    'category' => "Inscription ({$inscr->script} / {$inscr->language})",
                    'chronology' => [
                        'start_year' => $inscr->start_year,
                        'end_year' => $inscr->end_year,
                        'dating_statement' => $inscr->dating_statement,
                        'is_dating_uncertain' => false,
                    ],
                    'summary' => $inscr->translation,
                    'site' => $inscr->heritageSite ? [
                        'name' => $inscr->heritageSite->name,
                        'slug' => $inscr->heritageSite->slug,
                    ] : null,
                    'object' => $inscr->object ? [
                        'name' => $inscr->object->name,
                        'slug' => $inscr->object->slug,
                    ] : null,
                    'api_url' => route('api.v1.inscriptions.show', ['slug' => $inscr->slug], false),
                ]);
            }
        }

        // Sort chronologically: primary by start_year, secondary by end_year
        $sorted = $events->sortBy([
            ['chronology.start_year', 'asc'],
            ['chronology.end_year', 'asc'],
        ])->values();

        $sliced = $sorted->take($limit);

        return response()->json([
            'success' => true,
            'data' => $sliced,
            'meta' => [
                'count' => $sliced->count(),
                'total_matching' => $sorted->count(),
                'limit' => $limit,
                'range' => [
                    'earliest_year' => $sliced->min('chronology.start_year'),
                    'latest_year' => $sliced->max('chronology.end_year'),
                ],
            ],
        ]);
    }
}
