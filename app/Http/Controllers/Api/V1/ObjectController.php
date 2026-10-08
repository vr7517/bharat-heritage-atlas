<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\HeritageObjectListResource;
use App\Http\Resources\V1\HeritageObjectResource;
use App\Models\HeritageObject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ObjectController extends Controller
{
    /**
     * Display a listing of verified heritage objects.
     */
    public function index(Request $request): JsonResponse
    {
        $query = HeritageObject::query()
            ->with(['heritageSite', 'period', 'dynasty'])
            ->withCount(['inscriptions', 'claims']);

        // Search query
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('material', 'like', "%{$search}%");
            });
        }

        // Object type filter
        if ($objectType = $request->query('object_type')) {
            $query->where('object_type', 'like', "%{$objectType}%");
        }

        // Site filter (by site slug)
        if ($siteSlug = $request->query('site')) {
            $query->whereHas('heritageSite', function ($sq) use ($siteSlug) {
                $sq->where('slug', $siteSlug);
            });
        }

        // Period filter (by period slug)
        if ($periodSlug = $request->query('period')) {
            $query->whereHas('period', function ($pq) use ($periodSlug) {
                $pq->where('slug', $periodSlug);
            });
        }

        // Dynasty filter (by dynasty slug)
        if ($dynastySlug = $request->query('dynasty')) {
            $query->whereHas('dynasty', function ($dq) use ($dynastySlug) {
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

        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);
        $paginated = $query->orderBy('start_year', 'asc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => HeritageObjectListResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    /**
     * Display the specified heritage object with complete claims and evidence graph.
     */
    public function show(string $slug): JsonResponse
    {
        $object = HeritageObject::where('slug', $slug)
            ->with([
                'heritageSite',
                'location',
                'period',
                'dynasty',
                'inscriptions',
                'claims.evidence.sources',
            ])
            ->first();

        if (! $object) {
            return response()->json([
                'success' => false,
                'message' => "Heritage object with slug '{$slug}' not found.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new HeritageObjectResource($object),
        ]);
    }
}
