<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dynasty;
use App\Models\HeritageSite;
use App\Models\Location;
use App\Models\Period;
use Illuminate\Contracts\View\View;

class MapController extends Controller
{
    /**
     * Display the interactive geospatial web map atlas.
     */
    public function index(): View
    {
        $states = Location::whereHas('heritageSites')
            ->whereNotNull('state')
            ->distinct()
            ->orderBy('state')
            ->pluck('state');

        $siteTypes = HeritageSite::where('is_published', true)
            ->whereNotNull('site_type')
            ->distinct()
            ->orderBy('site_type')
            ->pluck('site_type');

        $periods = Period::whereHas('heritageSites')
            ->orderBy('start_year')
            ->get(['name', 'slug']);

        $totalSitesWithCoords = HeritageSite::where('is_published', true)
            ->whereHas('location', function ($q) {
                $q->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->count();

        return view('map.index', compact('states', 'siteTypes', 'periods', 'totalSitesWithCoords'));
    }
}
