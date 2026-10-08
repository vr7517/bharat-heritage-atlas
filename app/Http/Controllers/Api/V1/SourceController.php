<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SourceResource;
use App\Models\Source;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /**
     * Display a listing of verified bibliographic sources.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Source::query()
            ->withCount('evidence');

        // Search query
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('authors', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Reliability tier filter
        if ($tier = $request->query('reliability_tier')) {
            $query->where('reliability_tier', $tier);
        }

        // Source type filter
        if ($type = $request->query('source_type')) {
            $query->where('source_type', $type);
        }

        // Author filter
        if ($author = $request->query('author')) {
            $query->where('authors', 'like', "%{$author}%");
        }

        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);
        $paginated = $query->orderBy('publication_year', 'asc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => SourceResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    /**
     * Display the specified bibliographic source with linked evidence nodes.
     */
    public function show(int $id): JsonResponse
    {
        $source = Source::with(['evidence.claims'])->find($id);

        if (! $source) {
            return response()->json([
                'success' => false,
                'message' => "Bibliographic source with ID {$id} not found.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new SourceResource($source),
        ]);
    }
}
