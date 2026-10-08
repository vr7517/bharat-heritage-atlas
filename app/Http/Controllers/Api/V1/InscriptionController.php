<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InscriptionResource;
use App\Models\Inscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    /**
     * Display a listing of verified epigraphs and inscriptions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Inscription::query()
            ->with(['heritageSite', 'object']);

        // Search query
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('donor', 'like', "%{$search}%")
                    ->orWhere('ruler_mentioned', 'like', "%{$search}%")
                    ->orWhere('raw_text', 'like', "%{$search}%")
                    ->orWhere('translation', 'like', "%{$search}%");
            });
        }

        // Language filter
        if ($language = $request->query('language')) {
            $query->where('language', 'like', "%{$language}%");
        }

        // Script filter
        if ($script = $request->query('script')) {
            $query->where('script', 'like', "%{$script}%");
        }

        // Ruler mentioned filter
        if ($ruler = $request->query('ruler')) {
            $query->where('ruler_mentioned', 'like', "%{$ruler}%");
        }

        // Site filter (by site slug)
        if ($siteSlug = $request->query('site')) {
            $query->whereHas('heritageSite', function ($sq) use ($siteSlug) {
                $sq->where('slug', $siteSlug);
            });
        }

        // Object filter (by object slug)
        if ($objectSlug = $request->query('object')) {
            $query->whereHas('object', function ($oq) use ($objectSlug) {
                $oq->where('slug', $objectSlug);
            });
        }

        // Chronological range filters
        if ($fromYear = $request->query('from_year')) {
            $query->where('start_year', '>=', (int) $fromYear);
        }

        if ($toYear = $request->query('to_year')) {
            $query->where('end_year', '<=', (int) $toYear);
        }

        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);
        $paginated = $query->orderBy('start_year', 'asc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => InscriptionResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    /**
     * Display the specified inscription with verbatim text and parent context.
     */
    public function show(string $slug): JsonResponse
    {
        $inscription = Inscription::where('slug', $slug)
            ->with([
                'heritageSite',
                'object',
                'claims.evidence.sources',
            ])
            ->first();

        if (! $inscription) {
            return response()->json([
                'success' => false,
                'message' => "Inscription with slug '{$slug}' not found.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InscriptionResource($inscription),
        ]);
    }
}
