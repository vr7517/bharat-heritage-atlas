<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    /**
     * Display a paginated listing of verified epigraphical records and facsimiles.
     */
    public function index(Request $request): View
    {
        $query = Inscription::query()
            ->with(['heritageSite', 'object'])
            ->withCount('claims')
            ->where('is_published', true);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('translation', 'like', "%{$search}%")
                    ->orWhere('ruler_mentioned', 'like', "%{$search}%")
                    ->orWhere('donor', 'like', "%{$search}%")
                    ->orWhere('epigraphic_reference', 'like', "%{$search}%");
            });
        }

        if ($script = $request->query('script')) {
            $query->where('script', 'like', "%{$script}%");
        }

        if ($language = $request->query('language')) {
            $query->where('language', 'like', "%{$language}%");
        }

        $inscriptions = $query->orderBy('start_year', 'asc')->paginate(12)->withQueryString();

        $scripts = Inscription::where('is_published', true)
            ->distinct()
            ->orderBy('script')
            ->pluck('script');

        $languages = Inscription::where('is_published', true)
            ->distinct()
            ->orderBy('language')
            ->pluck('language');

        return view('inscriptions.index', compact('inscriptions', 'scripts', 'languages'));
    }

    /**
     * Display the verbatim facsimile view of a specific epigraphical record.
     */
    public function show(string $slug): View
    {
        $inscription = Inscription::with([
            'heritageSite.location',
            'object',
            'claims.evidence.sources',
        ])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('inscriptions.show', compact('inscription'));
    }
}
