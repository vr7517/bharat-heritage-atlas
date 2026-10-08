<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HeritageSite;
use App\Models\Period;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a paginated listing of verified heritage monuments.
     */
    public function index(Request $request): View
    {
        $query = HeritageSite::query()
            ->with(['location', 'primaryPeriod', 'primaryDynasty'])
            ->withCount(['objects', 'inscriptions', 'claims'])
            ->where('is_published', true);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhereHas('location', function ($lq) use ($search) {
                        $lq->where('name', 'like', "%{$search}%")
                            ->orWhere('state', 'like', "%{$search}%");
                    });
            });
        }

        if ($siteType = $request->query('site_type')) {
            $query->where('site_type', 'like', "%{$siteType}%");
        }

        if ($periodSlug = $request->query('period')) {
            $query->whereHas('primaryPeriod', function ($pq) use ($periodSlug) {
                $pq->where('slug', $periodSlug);
            });
        }

        if ($state = $request->query('state')) {
            $query->whereHas('location', function ($lq) use ($state) {
                $lq->where('state', 'like', "%{$state}%");
            });
        }

        $sites = $query->orderBy('start_year', 'asc')->paginate(12)->withQueryString();

        $siteTypes = HeritageSite::where('is_published', true)
            ->distinct()
            ->orderBy('site_type')
            ->pluck('site_type');

        $periods = Period::whereHas('heritageSites')
            ->orderBy('start_year')
            ->get(['name', 'slug']);

        return view('sites.index', compact('sites', 'siteTypes', 'periods'));
    }

    /**
     * Display the detailed monographic dossier of a specific heritage site.
     */
    public function show(string $slug): View
    {
        $site = HeritageSite::with([
            'location',
            'primaryPeriod',
            'primaryDynasty',
            'objects',
            'inscriptions',
            'claims.evidence.sources',
        ])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('sites.show', compact('site'));
    }
}
