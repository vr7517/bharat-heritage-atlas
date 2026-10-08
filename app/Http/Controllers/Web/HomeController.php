<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Source;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display the Bharat Heritage Atlas portal landing page.
     */
    public function index(): View
    {
        $stats = [
            'sites_count' => HeritageSite::where('is_published', true)->count(),
            'objects_count' => HeritageObject::count(),
            'inscriptions_count' => Inscription::count(),
            'sources_count' => Source::count(),
            'claims_count' => Claim::count(),
        ];

        $featuredSites = HeritageSite::with(['location', 'primaryPeriod', 'primaryDynasty', 'objects'])
            ->where('is_published', true)
            ->orderBy('start_year', 'asc')
            ->take(3)
            ->get();

        $sampleInscriptions = Inscription::with(['heritageSite'])
            ->take(3)
            ->get();

        return view('home', compact('stats', 'featuredSites', 'sampleInscriptions'));
    }
}
