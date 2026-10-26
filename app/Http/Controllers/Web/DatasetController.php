<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DatasetController extends Controller
{
    /**
     * Display a listing of published open heritage datasets.
     */
    public function index(Request $request): View
    {
        $query = Dataset::query()->where('is_published', true);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('source_organization', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($type = $request->query('type')) {
            $query->where('dataset_type', $type);
        }

        if ($license = $request->query('license')) {
            $query->where('license', 'like', "%{$license}%");
        }

        $datasets = $query->orderBy('is_featured', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        $featuredDatasets = Dataset::where('is_published', true)
            ->where('is_featured', true)
            ->take(3)
            ->get();

        $stats = [
            'total_datasets' => Dataset::where('is_published', true)->count(),
            'total_records' => Dataset::where('is_published', true)->sum('record_count') ?? 0,
            'geojson_count' => Dataset::where('is_published', true)->where('dataset_type', 'GEOJSON')->count(),
            'csv_count' => Dataset::where('is_published', true)->where('dataset_type', 'CSV')->count(),
        ];

        $availableTypes = Dataset::where('is_published', true)
            ->distinct()
            ->orderBy('dataset_type')
            ->pluck('dataset_type');

        return view('datasets.index', compact('datasets', 'featuredDatasets', 'stats', 'availableTypes'));
    }

    /**
     * Display the detail view for a specific dataset.
     */
    public function show(string $slug): View
    {
        $dataset = Dataset::where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedDatasets = Dataset::where('is_published', true)
            ->where('id', '!=', $dataset->id)
            ->where('dataset_type', $dataset->dataset_type)
            ->take(3)
            ->get();

        return view('datasets.show', compact('dataset', 'relatedDatasets'));
    }

    /**
     * Download the dataset asset file.
     */
    public function download(string $slug): StreamedResponse
    {
        $dataset = Dataset::where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        if (! $dataset->file_path || ! Storage::disk('public')->exists($dataset->file_path)) {
            abort(404, 'The requested dataset asset file is not available on storage.');
        }

        $downloadName = $dataset->file_name ?: ($dataset->slug . '.' . strtolower($dataset->dataset_type));

        return Storage::disk('public')->download($dataset->file_path, $downloadName);
    }
}
