<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Dynasty;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResearchToolsController extends Controller
{
    /**
     * Display the Academic Research Tools Workbench.
     */
    public function index(Request $request): View
    {
        $sites = HeritageSite::where('is_published', true)->orderBy('name')->take(50)->get();
        $inscriptions = Inscription::where('is_published', true)->orderBy('title')->take(50)->get();
        $sources = Source::orderBy('title')->take(50)->get();

        $periods = Period::orderBy('start_year')->get();
        $dynasties = Dynasty::with('period')->orderBy('start_year')->get();

        $claimsByConsensus = [
            'STRONG_CONSENSUS' => Claim::where('consensus_status', 'STRONG_CONSENSUS')->count(),
            'PROBABLE' => Claim::where('consensus_status', 'PROBABLE')->count(),
            'SCHOLARLY_DEBATE' => Claim::where('consensus_status', 'SCHOLARLY_DEBATE')->count(),
            'CONTESTED' => Claim::where('consensus_status', 'CONTESTED')->count(),
            'SPECULATIVE' => Claim::where('consensus_status', 'SPECULATIVE')->count(),
        ];

        $totalClaims = array_sum($claimsByConsensus);

        return view('research.index', compact(
            'sites',
            'inscriptions',
            'sources',
            'periods',
            'dynasties',
            'claimsByConsensus',
            'totalClaims'
        ));
    }

    /**
     * Generate multi-format academic citations for an entity.
     */
    public function generateCitation(Request $request): JsonResponse
    {
        $type = $request->query('type', 'site');
        $id = (int) $request->query('id', 0);

        if ($type === 'site') {
            $site = HeritageSite::with(['location', 'primaryPeriod', 'primaryDynasty'])->findOrFail($id);
            $url = url('/sites/' . $site->slug);
            $currentYear = date('Y');

            $apa = "Bharat Heritage Atlas. ({$currentYear}). {$site->name} [Archaeological Record]. Retrieved from {$url}";
            $chicago = "Bharat Heritage Atlas. \"{$site->name}.\" Digital Archaeological Gazetteer. Accessed " . date('F j, Y') . ". {$url}.";
            $harvard = "Bharat Heritage Atlas, {$currentYear}. {$site->name}, viewed " . date('d F Y') . ", <{$url}>.";
            $bibtex = "@misc{bha_site_{$site->id},\n  title = {{" . addcslashes($site->name, '{}') . "}},\n  author = {{Bharat Heritage Atlas}},\n  year = {{$currentYear}},\n  url = {{$url}},\n  note = {ASI Protection: " . addcslashes($site->protection_status ?: 'Protected', '{}') . "}\n}";
            $wikipedia = "{{cite web |title=" . $site->name . " |url=" . $url . " |website=Bharat Heritage Atlas |publisher=Bharat Heritage Atlas |access-date=" . date('Y-m-d') . "}}";

            return response()->json([
                'success' => true,
                'entity' => 'site',
                'name' => $site->name,
                'citations' => compact('apa', 'chicago', 'harvard', 'bibtex', 'wikipedia'),
            ]);
        }

        if ($type === 'inscription') {
            $insc = Inscription::with('heritageSite')->findOrFail($id);
            $url = url('/inscriptions/' . $insc->slug);
            $currentYear = date('Y');

            $apa = "Bharat Heritage Atlas. ({$currentYear}). {$insc->title} [Epigraphical Record]. Language: {$insc->language}; Script: {$insc->script}. Retrieved from {$url}";
            $chicago = "Bharat Heritage Atlas. \"{$insc->title}.\" Corpus Inscriptionum Indicarum Digital Archive. Accessed " . date('F j, Y') . ". {$url}.";
            $harvard = "Bharat Heritage Atlas, {$currentYear}. {$insc->title} ({$insc->script} script), viewed " . date('d F Y') . ", <{$url}>.";
            $bibtex = "@misc{bha_inscription_{$insc->id},\n  title = {{" . addcslashes($insc->title, '{}') . "}},\n  author = {{Bharat Heritage Atlas}},\n  year = {{$currentYear}},\n  url = {{$url}},\n  note = {Language: " . $insc->language . "; Script: " . $insc->script . "}\n}";
            $wikipedia = "{{cite web |title=" . $insc->title . " |url=" . $url . " |website=Bharat Heritage Atlas Epigraphy |access-date=" . date('Y-m-d') . "}}";

            return response()->json([
                'success' => true,
                'entity' => 'inscription',
                'name' => $insc->title,
                'citations' => compact('apa', 'chicago', 'harvard', 'bibtex', 'wikipedia'),
            ]);
        }

        if ($type === 'source') {
            $src = Source::findOrFail($id);
            $authors = $src->authors ?: 'Unknown Author';
            $year = $src->publication_year ?: 'n.d.';
            $publisher = $src->publisher ?: 'Academic Publisher';

            $apa = "{$authors} ({$year}). {$src->title}. {$publisher}.";
            $chicago = "{$authors}. {$src->title}. " . ($src->volume_issue ? "{$src->volume_issue}. " : '') . "{$publisher}, {$year}.";
            $harvard = "{$authors}, {$year}. {$src->title}. {$publisher}.";

            $firstAuthor = explode(';', explode(',', $authors)[0])[0];
            $cleanAuthor = preg_replace('/[^a-zA-Z]/', '', $firstAuthor) ?: 'BHA';
            $key = strtoupper($cleanAuthor) . ($year !== 'n.d.' ? $year : $src->id);
            $bibtex = "@book{{$key},\n  author = {" . addcslashes($authors, '{}') . "},\n  title = {{" . addcslashes($src->title, '{}') . "}},\n  year = {{$year}},\n  publisher = {" . addcslashes($publisher, '{}') . "}\n}";
            $wikipedia = "{{cite book |last=" . $authors . " |year=" . $year . " |title=" . $src->title . " |publisher=" . $publisher . "}}";

            return response()->json([
                'success' => true,
                'entity' => 'source',
                'name' => $src->title,
                'citations' => compact('apa', 'chicago', 'harvard', 'bibtex', 'wikipedia'),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid citation entity type.'], 400);
    }
}
