<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DatasetApiController extends Controller
{
    /**
     * List open heritage datasets in JSON.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Dataset::where('is_published', true);

        if ($type = $request->query('type')) {
            $query->where('dataset_type', $type);
        }

        if ($license = $request->query('license')) {
            $query->where('license', 'like', "%{$license}%");
        }

        $datasets = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $datasets->items(),
            'pagination' => [
                'current_page' => $datasets->currentPage(),
                'last_page' => $datasets->lastPage(),
                'per_page' => $datasets->perPage(),
                'total' => $datasets->total(),
            ],
        ]);
    }

    /**
     * Show JSON metadata for a specific dataset.
     */
    public function show(string $slug): JsonResponse
    {
        $dataset = Dataset::where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $dataset->id,
                'name' => $dataset->name,
                'slug' => $dataset->slug,
                'source_organization' => $dataset->source_organization,
                'source_url' => $dataset->source_url,
                'dataset_type' => $dataset->dataset_type,
                'version' => $dataset->version,
                'release_date' => $dataset->release_date?->toDateString(),
                'license' => $dataset->license,
                'file_name' => $dataset->file_name,
                'file_size_bytes' => $dataset->file_size_bytes,
                'record_count' => $dataset->record_count,
                'description' => $dataset->description,
                'notes' => $dataset->notes,
                'download_url' => url('/datasets/' . $dataset->slug . '/download'),
            ],
        ]);
    }
}
