<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /**
     * Standard scholarly source types.
     */
    protected array $sourceTypes = [
        'EPIGRAPHIC_CORPUS' => 'Epigraphical Corpus / Inscription Rubbing',
        'ARCHAEOLOGICAL_REPORT' => 'Archaeological Excavation Report',
        'PEER_REVIEWED_JOURNAL' => 'Peer-Reviewed Academic Journal',
        'ACADEMIC_MONOGRAPH' => 'Scholarly Academic Monograph',
        'GOVERNMENT_SURVEY' => 'Official Survey / Gazetteers',
        'ARCHIVAL_MANUSCRIPT' => 'Primary Archival Manuscript',
        'MUSEUM_CATALOGUE' => 'Museum Collection Catalogue',
        'METROLOGICAL_SURVEY' => 'Material Metrology & Ceramic Analysis',
        'SECONDARY_HISTORICAL' => 'Secondary Historical Synthesis',
    ];

    /**
     * Methodological source reliability tiers.
     */
    protected array $reliabilityTiers = [
        'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY' => 'Tier 1 — Primary Excavation Stratigraphy & In Situ Epigraphy',
        'TIER_2_PEER_REVIEWED_ACADEMIC' => 'Tier 2 — Peer-Reviewed Academic & Critical Editions',
        'TIER_3_HISTORICAL_SECONDARY' => 'Tier 3 — Secondary Historical Synthesis & Chronicles',
        'TIER_4_TERTIARY_COMPILATION' => 'Tier 4 — Tertiary Compilations & Survey Surveys',
    ];

    /**
     * Display a paginated listing of bibliographic sources with search and filters.
     */
    public function index(Request $request): View
    {
        $query = Source::query()->withCount('evidence');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('authors', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%")
                    ->orWhere('isbn_issn', 'like', "%{$search}%")
                    ->orWhere('doi', 'like', "%{$search}%");
            });
        }

        if ($type = $request->query('source_type')) {
            $query->where('source_type', $type);
        }

        if ($tier = $request->query('reliability_tier')) {
            $query->where('reliability_tier', $tier);
        }

        $sources = $query->orderBy('publication_year', 'desc')->paginate(15)->withQueryString();

        $counts = [
            'total' => Source::count(),
            'tier1' => Source::where('reliability_tier', 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY')->count(),
            'peerReviewed' => Source::where('reliability_tier', 'TIER_2_PEER_REVIEWED_ACADEMIC')->count(),
        ];

        $sourceTypes = $this->sourceTypes;
        $reliabilityTiers = $this->reliabilityTiers;

        return view('admin.sources.index', compact('sources', 'counts', 'sourceTypes', 'reliabilityTiers'));
    }

    /**
     * Show the form for creating a new bibliographic source.
     */
    public function create(): View
    {
        $sourceTypes = $this->sourceTypes;
        $reliabilityTiers = $this->reliabilityTiers;

        return view('admin.sources.create', compact('sourceTypes', 'reliabilityTiers'));
    }

    /**
     * Store a newly registered source in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:500'],
            'authors' => ['required', 'string', 'max:500'],
            'publication_year' => ['nullable', 'integer', 'between:-1000,2100'],
            'source_type' => ['required', 'string', 'max:50'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'journal_or_series' => ['nullable', 'string', 'max:255'],
            'volume_issue' => ['nullable', 'string', 'max:100'],
            'pages' => ['nullable', 'string', 'max:100'],
            'doi' => ['nullable', 'string', 'max:150'],
            'isbn_issn' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:500'],
            'access_date' => ['nullable', 'date'],
            'archival_location' => ['nullable', 'string', 'max:255'],
            'reliability_tier' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $source = Source::create($validated);

        return redirect()->route('admin.sources.index')
            ->with('success', "Bibliographic source '{$source->title}' registered successfully.");
    }

    /**
     * Show the form for editing the specified source.
     */
    public function edit(int $id): View
    {
        $source = Source::withCount('evidence')->findOrFail($id);
        $sourceTypes = $this->sourceTypes;
        $reliabilityTiers = $this->reliabilityTiers;

        return view('admin.sources.edit', compact('source', 'sourceTypes', 'reliabilityTiers'));
    }

    /**
     * Update the specified source in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $source = Source::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:500'],
            'authors' => ['required', 'string', 'max:500'],
            'publication_year' => ['nullable', 'integer', 'between:-1000,2100'],
            'source_type' => ['required', 'string', 'max:50'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'journal_or_series' => ['nullable', 'string', 'max:255'],
            'volume_issue' => ['nullable', 'string', 'max:100'],
            'pages' => ['nullable', 'string', 'max:100'],
            'doi' => ['nullable', 'string', 'max:150'],
            'isbn_issn' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:500'],
            'access_date' => ['nullable', 'date'],
            'archival_location' => ['nullable', 'string', 'max:255'],
            'reliability_tier' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $source->update($validated);

        return redirect()->route('admin.sources.index')
            ->with('success', "Bibliographic source '{$source->title}' updated successfully.");
    }

    /**
     * Remove the specified source from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $source = Source::findOrFail($id);
        $title = $source->title;
        $source->delete();

        return redirect()->route('admin.sources.index')
            ->with('success', "Bibliographic source '{$title}' removed successfully.");
    }
}
