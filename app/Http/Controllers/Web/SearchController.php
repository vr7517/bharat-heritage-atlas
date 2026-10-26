<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Dataset;
use App\Models\Dynasty;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Location;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Cross-corpus advanced search & filtering across Indian heritage data.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $entityType = $request->query('type', 'all');
        $periodSlug = $request->query('period');
        $dynastySlug = $request->query('dynasty');
        $state = $request->query('state');
        $consensus = $request->query('consensus');

        $sites = collect();
        $objects = collect();
        $inscriptions = collect();
        $sources = collect();
        $datasets = collect();
        $claims = collect();

        // 1. Heritage Sites Query
        $sitesQuery = HeritageSite::query()
            ->with(['location', 'primaryPeriod', 'primaryDynasty', 'media'])
            ->where('is_published', true);

        if ($q !== '') {
            $sitesQuery->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%")
                    ->orWhere('site_type', 'like', "%{$q}%")
                    ->orWhere('architectural_style', 'like', "%{$q}%")
                    ->orWhereHas('location', function ($lq) use ($q) {
                        $lq->where('name', 'like', "%{$q}%")
                            ->orWhere('district', 'like', "%{$q}%")
                            ->orWhere('state', 'like', "%{$q}%");
                    });
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

        $sitesCount = $sitesQuery->count();
        if (in_array($entityType, ['all', 'sites'])) {
            $sites = $sitesQuery->take($entityType === 'sites' ? 30 : 6)->get();
        }

        // 2. Heritage Objects / Artifacts Query
        $objectsQuery = HeritageObject::query()
            ->with(['heritageSite', 'period', 'dynasty', 'media'])
            ->where('is_published', true);

        if ($q !== '') {
            $objectsQuery->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('object_type', 'like', "%{$q}%")
                    ->orWhere('material', 'like', "%{$q}%")
                    ->orWhere('current_repository', 'like', "%{$q}%")
                    ->orWhere('accession_number', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($periodSlug) {
            $objectsQuery->whereHas('period', fn ($pq) => $pq->where('slug', $periodSlug));
        }

        if ($dynastySlug) {
            $objectsQuery->whereHas('dynasty', fn ($dq) => $dq->where('slug', $dynastySlug));
        }

        $objectsCount = $objectsQuery->count();
        if (in_array($entityType, ['all', 'objects'])) {
            $objects = $objectsQuery->take($entityType === 'objects' ? 30 : 6)->get();
        }

        // 3. Inscriptions / Epigraphy Query
        $inscriptionsQuery = Inscription::query()
            ->with(['heritageSite', 'object', 'media'])
            ->where('is_published', true);

        if ($q !== '') {
            $inscriptionsQuery->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('epigraphic_reference', 'like', "%{$q}%")
                    ->orWhere('language', 'like', "%{$q}%")
                    ->orWhere('script', 'like', "%{$q}%")
                    ->orWhere('donor', 'like', "%{$q}%")
                    ->orWhere('ruler_mentioned', 'like', "%{$q}%")
                    ->orWhere('translation', 'like', "%{$q}%");
            });
        }

        $inscriptionsCount = $inscriptionsQuery->count();
        if (in_array($entityType, ['all', 'inscriptions'])) {
            $inscriptions = $inscriptionsQuery->take($entityType === 'inscriptions' ? 30 : 6)->get();
        }

        // 4. Bibliographic Sources Query
        $sourcesQuery = Source::query();

        if ($q !== '') {
            $sourcesQuery->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('authors', 'like', "%{$q}%")
                    ->orWhere('publisher', 'like', "%{$q}%")
                    ->orWhere('source_type', 'like', "%{$q}%")
                    ->orWhere('isbn', 'like', "%{$q}%")
                    ->orWhere('doi', 'like', "%{$q}%");
            });
        }

        $sourcesCount = $sourcesQuery->count();
        if (in_array($entityType, ['all', 'sources'])) {
            $sources = $sourcesQuery->take($entityType === 'sources' ? 30 : 6)->get();
        }

        // 5. Open Datasets Query
        $datasetsQuery = Dataset::query()->where('is_published', true);

        if ($q !== '') {
            $datasetsQuery->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('source_organization', 'like', "%{$q}%")
                    ->orWhere('dataset_type', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('license', 'like', "%{$q}%");
            });
        }

        $datasetsCount = $datasetsQuery->count();
        if (in_array($entityType, ['all', 'datasets'])) {
            $datasets = $datasetsQuery->take($entityType === 'datasets' ? 30 : 6)->get();
        }

        // 6. Evidentiary Claims Query
        $claimsQuery = Claim::query()->with(['claimable', 'evidence.sources']);

        if ($q !== '') {
            $claimsQuery->where(function ($query) use ($q) {
                $query->where('statement', 'like', "%{$q}%")
                    ->orWhere('summary_justification', 'like', "%{$q}%")
                    ->orWhere('claim_type', 'like', "%{$q}%");
            });
        }

        if ($consensus) {
            $claimsQuery->where('consensus_status', $consensus);
        }

        $claimsCount = $claimsQuery->count();
        if (in_array($entityType, ['all', 'claims'])) {
            $claims = $claimsQuery->take($entityType === 'claims' ? 30 : 6)->get();
        }

        $counts = [
            'total' => $sitesCount + $objectsCount + $inscriptionsCount + $sourcesCount + $datasetsCount + $claimsCount,
            'sites' => $sitesCount,
            'objects' => $objectsCount,
            'inscriptions' => $inscriptionsCount,
            'sources' => $sourcesCount,
            'datasets' => $datasetsCount,
            'claims' => $claimsCount,
        ];

        // Available filter options
        $periods = Period::orderBy('start_year')->get(['id', 'name', 'slug', 'dating_statement']);
        $dynasties = Dynasty::orderBy('start_year')->get(['id', 'name', 'slug']);
        $states = Location::distinct()->whereNotNull('state')->orderBy('state')->pluck('state');

        return view('search.index', compact(
            'q',
            'entityType',
            'periodSlug',
            'dynastySlug',
            'state',
            'consensus',
            'sites',
            'objects',
            'inscriptions',
            'sources',
            'datasets',
            'claims',
            'counts',
            'periods',
            'dynasties',
            'states'
        ));
    }
}
