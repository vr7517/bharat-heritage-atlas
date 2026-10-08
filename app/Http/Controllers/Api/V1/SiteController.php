<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\HeritageSiteListResource;
use App\Http\Resources\V1\HeritageSiteResource;
use App\Models\HeritageSite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of verified heritage sites.
     */
    public function index(Request $request): JsonResponse
    {
        $query = HeritageSite::query()
            ->with(['location', 'primaryPeriod', 'primaryDynasty'])
            ->withCount(['objects', 'inscriptions', 'claims']);

        // Search query
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhereHas('location', function ($lq) use ($search) {
                        $lq->where('name', 'like', "%{$search}%")
                            ->orWhere('district', 'like', "%{$search}%")
                            ->orWhere('state', 'like', "%{$search}%");
                    });
            });
        }

        // Site type filter
        if ($siteType = $request->query('site_type')) {
            $query->where('site_type', 'like', "%{$siteType}%");
        }

        // Period filter (by period slug)
        if ($periodSlug = $request->query('period')) {
            $query->whereHas('primaryPeriod', function ($pq) use ($periodSlug) {
                $pq->where('slug', $periodSlug);
            });
        }

        // Dynasty filter (by dynasty slug)
        if ($dynastySlug = $request->query('dynasty')) {
            $query->whereHas('primaryDynasty', function ($dq) use ($dynastySlug) {
                $dq->where('slug', $dynastySlug);
            });
        }

        // Chronological range filters
        if ($fromYear = $request->query('from_year')) {
            $query->where('start_year', '>=', (int) $fromYear);
        }

        if ($toYear = $request->query('to_year')) {
            $query->where('end_year', '<=', (int) $toYear);
        }

        // State filter (via location)
        if ($state = $request->query('state')) {
            $query->whereHas('location', function ($lq) use ($state) {
                $lq->where('state', 'like', "%{$state}%");
            });
        }

        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);
        $paginated = $query->orderBy('start_year', 'asc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => HeritageSiteListResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    /**
     * Display the specified heritage site with complete evidence graph.
     */
    public function show(string $slug): JsonResponse
    {
        $site = HeritageSite::where('slug', $slug)
            ->with([
                'location',
                'primaryPeriod',
                'primaryDynasty',
                'objects.period',
                'objects.dynasty',
                'inscriptions',
                'claims.evidence.sources',
            ])
            ->first();

        if (! $site) {
            return response()->json([
                'success' => false,
                'message' => "Heritage site with slug '{$slug}' not found.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new HeritageSiteResource($site),
        ]);
    }
}
