<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use Illuminate\Contracts\View\View;

class TimelineController extends Controller
{
    /**
     * Display the chronological timeline scrubber interface.
     */
    public function index(): View
    {
        $minYear = min(
            HeritageSite::where('is_published', true)->min('start_year') ?? -350,
            HeritageObject::where('is_published', true)->min('start_year') ?? -350,
            Inscription::where('is_published', true)->min('start_year') ?? -350
        );

        $maxYear = max(
            HeritageSite::where('is_published', true)->max('end_year') ?? 1200,
            HeritageObject::where('is_published', true)->max('end_year') ?? 1200,
            Inscription::where('is_published', true)->max('end_year') ?? 1200
        );

        $counts = [
            'sites' => HeritageSite::where('is_published', true)->count(),
            'objects' => HeritageObject::where('is_published', true)->count(),
            'inscriptions' => Inscription::where('is_published', true)->count(),
        ];

        $counts['total'] = $counts['sites'] + $counts['objects'] + $counts['inscriptions'];

        return view('timeline.index', compact('minYear', 'maxYear', 'counts'));
    }
}
