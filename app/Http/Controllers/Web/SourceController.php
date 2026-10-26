<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /**
     * Display a listing of verified archival bibliographic sources.
     */
    public function index(Request $request): View
    {
        $query = Source::query()->withCount('evidence');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('authors', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        if ($tier = $request->query('reliability_tier')) {
            $query->where('reliability_tier', $tier);
        }

        if ($sourceType = $request->query('source_type')) {
            $query->where('source_type', $sourceType);
        }

        $sources = $query->orderBy('publication_year', 'asc')->paginate(12)->withQueryString();

        $totalSources = Source::count();
        $tier1Count = Source::where('reliability_tier', 'like', 'TIER_1%')->count();
        $tier2Count = Source::where('reliability_tier', 'like', 'TIER_2%')->count();
        $totalEvidentiaryCitations = Source::withCount('evidence')->get()->sum('evidence_count');

        $reliabilityTiers = Source::distinct()->orderBy('reliability_tier')->pluck('reliability_tier');
        $sourceTypes = Source::distinct()->orderBy('source_type')->pluck('source_type');

        return view('sources.index', compact(
            'sources',
            'totalSources',
            'tier1Count',
            'tier2Count',
            'totalEvidentiaryCitations',
            'reliabilityTiers',
            'sourceTypes'
        ));
    }

    /**
     * Display the specified bibliographic source monograph with linked evidence nodes.
     */
    public function show(int $id): View
    {
        $source = Source::with([
            'evidence.claims.claimable',
        ])->findOrFail($id);

        return view('sources.show', compact('source'));
    }
}
