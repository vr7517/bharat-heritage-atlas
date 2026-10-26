<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Evidence;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Source;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the central administrative CMS dashboard.
     */
    public function index(): View
    {
        $metrics = [
            'total_sites' => HeritageSite::count(),
            'published_sites' => HeritageSite::where('is_published', true)->count(),
            'draft_sites' => HeritageSite::where('is_published', false)->count(),
            'total_objects' => HeritageObject::count(),
            'total_inscriptions' => Inscription::count(),
            'total_claims' => Claim::count(),
            'total_evidence' => Evidence::count(),
            'total_sources' => Source::count(),
        ];

        $recentSites = HeritageSite::with(['location', 'primaryPeriod'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('metrics', 'recentSites'));
    }
}
