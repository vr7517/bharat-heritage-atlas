<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Dataset;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Source;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    /**
     * Programmatic JSON search across all Indian heritage corpora.
     */
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        $entityType = $request->query('type', 'all');
        $periodSlug = $request->query('period');
        $dynastySlug = $request->query('dynasty');
        $state = $request->query('state');
        $consensus = $request->query('consensus');
        $limit = min(50, max(1, (int) $request->query('limit', 15)));

        $results = [];

        // 1. Heritage Sites
        if (in_array($entityType, ['all', 'sites'])) {
            $sitesQuery = HeritageSite::query()
                ->with(['location', 'primaryPeriod', 'primaryDynasty'])
                ->where('is_published', true);

            if ($q !== '') {
                $sitesQuery->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('summary', 'like', "%{$q}%")
                        ->orWhere('site_type', 'like', "%{$q}%")
                        ->orWhereHas('location', fn ($lq) => $lq->where('state', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%"));
                });
            }
            if ($periodSlug) {
                $sitesQuery->whereHas('primaryPeriod', fn ($pq) => $pq->where('slug', $periodSlug));
            }
            if ($dynastySlug) {
                $sitesQuery->whereHas('primaryDynasty', fn ($dq) => $dq->where('slug', $dynastySlug));
            }
            if ($state) {
                $sitesQuery->whereHas('location', fn ($lq) => $lq->where('state', 'like', "%{$state}%"));
            }

            $results['sites'] = $sitesQuery->take($limit)->get()->map(fn ($site) => [
                'id' => $site->id,
                'name' => $site->name,
                'slug' => $site->slug,
                'site_type' => $site->site_type,
                'dating_statement' => $site->dating_statement,
                'protection_status' => $site->protection_status,
                'location' => $site->location?->name . ', ' . $site->location?->state,
                'url' => url('/sites/' . $site->slug),
            ]);
        }

        // 2. Objects
        if (in_array($entityType, ['all', 'objects'])) {
            $objectsQuery = HeritageObject::query()->where('is_published', true);
            if ($q !== '') {
                $objectsQuery->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('object_type', 'like', "%{$q}%")
                        ->orWhere('material', 'like', "%{$q}%");
                });
            }
            $results['objects'] = $objectsQuery->take($limit)->get()->map(fn ($obj) => [
                'id' => $obj->id,
                'name' => $obj->name,
                'slug' => $obj->slug,
                'object_type' => $obj->object_type,
                'material' => $obj->material,
                'current_repository' => $obj->current_repository,
                'url' => url('/objects/' . $obj->slug),
            ]);
        }

        // 3. Inscriptions
        if (in_array($entityType, ['all', 'inscriptions'])) {
            $inscQuery = Inscription::query()->where('is_published', true);
            if ($q !== '') {
                $inscQuery->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('language', 'like', "%{$q}%")
                        ->orWhere('script', 'like', "%{$q}%");
                });
            }
            $results['inscriptions'] = $inscQuery->take($limit)->get()->map(fn ($insc) => [
                'id' => $insc->id,
                'title' => $insc->title,
                'slug' => $insc->slug,
                'language' => $insc->language,
                'script' => $insc->script,
                'dating_statement' => $insc->dating_statement,
                'url' => url('/inscriptions/' . $insc->slug),
            ]);
        }

        // 4. Datasets
        if (in_array($entityType, ['all', 'datasets'])) {
            $datasetsQuery = Dataset::query()->where('is_published', true);
            if ($q !== '') {
                $datasetsQuery->where('name', 'like', "%{$q}%")
                    ->orWhere('source_organization', 'like', "%{$q}%")
                    ->orWhere('dataset_type', 'like', "%{$q}%");
            }
            $results['datasets'] = $datasetsQuery->take($limit)->get()->map(fn ($ds) => [
                'id' => $ds->id,
                'name' => $ds->name,
                'slug' => $ds->slug,
                'dataset_type' => $ds->dataset_type,
                'organization' => $ds->source_organization,
                'license' => $ds->license,
                'download_url' => url('/datasets/' . $ds->slug . '/download'),
            ]);
        }

        // 5. Sources
        if (in_array($entityType, ['all', 'sources'])) {
            $sourcesQuery = Source::query();
            if ($q !== '') {
                $sourcesQuery->where('title', 'like', "%{$q}%")
                    ->orWhere('authors', 'like', "%{$q}%");
            }
            $results['sources'] = $sourcesQuery->take($limit)->get()->map(fn ($src) => [
                'id' => $src->id,
                'title' => $src->title,
                'authors' => $src->authors,
                'year' => $src->publication_year,
                'publisher' => $src->publisher,
                'url' => url('/sources/' . $src->id),
            ]);
        }

        // 6. Claims
        if (in_array($entityType, ['all', 'claims'])) {
            $claimsQuery = Claim::query();
            if ($q !== '') {
                $claimsQuery->where('statement', 'like', "%{$q}%")
                    ->orWhere('claim_type', 'like', "%{$q}%");
            }
            if ($consensus) {
                $claimsQuery->where('consensus_status', $consensus);
            }
            $results['claims'] = $claimsQuery->take($limit)->get()->map(fn ($claim) => [
                'id' => $claim->id,
                'statement' => $claim->statement,
                'claim_type' => $claim->claim_type,
                'consensus_status' => $claim->consensus_status,
                'url' => url('/verification/claims/' . $claim->id),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'query' => $q,
            'filters' => [
                'type' => $entityType,
                'period' => $periodSlug,
                'dynasty' => $dynastySlug,
                'state' => $state,
                'consensus' => $consensus,
            ],
            'results' => $results,
        ]);
    }
}
