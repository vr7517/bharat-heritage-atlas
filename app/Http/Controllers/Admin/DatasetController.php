<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatasetController extends Controller
{
    protected array $datasetTypes = [
        'GEOJSON' => 'GeoJSON Spatial Coordinates & Boundaries',
        'CSV' => 'Tabular Archaeological & Epigraphical CSV',
        'JSON' => 'Structured JSON Research Corpus',
        'SHAPEFILE' => 'ESRI Shapefile GIS Archive (ZIP)',
        'EPIGRAPHICAL_CORPUS' => 'Palaeographic & Diplomatic Text Corpus',
        'STRATIGRAPHY_CHRONOLOGY' => 'Stratigraphic Horizons & Radiocarbon Calibrations',
        'DOCUMENTATION' => 'Excavation Report & Monograph PDF',
    ];

    protected array $licenses = [
        'Creative Commons CC0 1.0 (Public Domain)' => 'Creative Commons CC0 1.0 (Public Domain / Universal)',
        'Open Data Commons Open Database License (ODbL)' => 'Open Data Commons Open Database License (ODbL)',
        'Creative Commons Attribution 4.0 (CC BY 4.0)' => 'Creative Commons Attribution 4.0 (CC BY 4.0)',
        'Creative Commons Attribution-ShareAlike 4.0 (CC BY-SA 4.0)' => 'Creative Commons Attribution-ShareAlike 4.0 (CC BY-SA 4.0)',
        'Government Open Data License - India (GODL)' => 'Government Open Data License - India (GODL)',
        'Academic Research & Educational Use Only' => 'Academic Research & Non-Commercial Use Only',
    ];

    /**
     * Display a paginated listing of open heritage datasets.
     */
    public function index(Request $request): View
    {
        $query = Dataset::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('source_organization', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($type = $request->query('dataset_type')) {
            $query->where('dataset_type', $type);
        }

        if ($status = $request->query('status')) {
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            } elseif ($status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        $datasets = $query->orderBy('updated_at', 'desc')->paginate(12)->withQueryString();

        $counts = [
            'total' => Dataset::count(),
            'published' => Dataset::where('is_published', true)->count(),
            'draft' => Dataset::where('is_published', false)->count(),
            'featured' => Dataset::where('is_featured', true)->count(),
        ];

        $datasetTypes = $this->datasetTypes;

        return view('admin.datasets.index', compact('datasets', 'counts', 'datasetTypes'));
    }

    /**
     * Show the form for creating a new dataset.
     */
    public function create(): View
    {
        $datasetTypes = $this->datasetTypes;
        $licenses = $this->licenses;

        return view('admin.datasets.create', compact('datasetTypes', 'licenses'));
    }

    /**
     * Store a newly created dataset in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->hasFile('dataset_file') && ! $request->file('dataset_file')->isValid()) {
            $errorCode = $request->file('dataset_file')->getError();
            $errorMessage = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE => 'The dataset file exceeds the server upload limit. Please upload files under 50MB.',
                UPLOAD_ERR_FORM_SIZE => 'The dataset file exceeds the form upload limit.',
                UPLOAD_ERR_PARTIAL => 'The dataset file was only partially uploaded. Please try uploading again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Temporary directory missing on server.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write uploaded file to disk. Check disk space or permissions.',
                default => 'The dataset file failed to upload (PHP upload error code: ' . $errorCode . ').',
            };

            return back()->withInput()->withErrors(['dataset_file' => $errorMessage]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:datasets,slug'],
            'source_organization' => ['required', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:500'],
            'dataset_type' => ['required', 'string', 'max:50'],
            'version' => ['required', 'string', 'max:50'],
            'release_date' => ['nullable', 'date'],
            'license' => ['required', 'string', 'max:255'],
            'record_count' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'dataset_file' => ['nullable', 'file', 'max:51200'],
        ], [
            'dataset_file.max' => 'The dataset file size may not exceed 50MB.',
            'dataset_file.uploaded' => 'The dataset file failed to upload. Please ensure the file is under 50MB.',
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        $baseSlug = $slug;
        $counter = 1;
        while (Dataset::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $filePath = null;
        $fileName = null;
        $fileSize = null;
        $fileMime = null;

        if ($request->hasFile('dataset_file')) {
            Storage::disk('public')->makeDirectory('datasets');
            $file = $request->file('dataset_file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileMime = $file->getMimeType();
            $filePath = $file->store('datasets', 'public');
        }

        $dataset = Dataset::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'source_organization' => $validated['source_organization'],
            'source_url' => $validated['source_url'] ?? null,
            'dataset_type' => $validated['dataset_type'],
            'version' => $validated['version'],
            'release_date' => $validated['release_date'] ?? null,
            'license' => $validated['license'],
            'record_count' => $validated['record_count'] ?? null,
            'description' => $validated['description'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size_bytes' => $fileSize,
            'file_mime_type' => $fileMime,
            'is_published' => $request->boolean('is_published', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return redirect()->route('admin.datasets.index')
            ->with('success', "Dataset '{$dataset->name}' registered successfully.");
    }

    /**
     * Show the form for editing the specified dataset.
     */
    public function edit(int $id): View
    {
        $dataset = Dataset::findOrFail($id);
        $datasetTypes = $this->datasetTypes;
        $licenses = $this->licenses;

        return view('admin.datasets.edit', compact('dataset', 'datasetTypes', 'licenses'));
    }

    /**
     * Update the specified dataset in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $dataset = Dataset::findOrFail($id);

        if ($request->hasFile('dataset_file') && ! $request->file('dataset_file')->isValid()) {
            $errorCode = $request->file('dataset_file')->getError();
            $errorMessage = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE => 'The dataset file exceeds the server upload limit. Please upload files under 50MB.',
                UPLOAD_ERR_FORM_SIZE => 'The dataset file exceeds the form upload limit.',
                UPLOAD_ERR_PARTIAL => 'The dataset file was only partially uploaded. Please try uploading again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Temporary directory missing on server.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write uploaded file to disk. Check disk space or permissions.',
                default => 'The dataset file failed to upload (PHP upload error code: ' . $errorCode . ').',
            };

            return back()->withInput()->withErrors(['dataset_file' => $errorMessage]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:datasets,slug,' . $id],
            'source_organization' => ['required', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:500'],
            'dataset_type' => ['required', 'string', 'max:50'],
            'version' => ['required', 'string', 'max:50'],
            'release_date' => ['nullable', 'date'],
            'license' => ['required', 'string', 'max:255'],
            'record_count' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'dataset_file' => ['nullable', 'file', 'max:51200'],
        ], [
            'dataset_file.max' => 'The dataset file size may not exceed 50MB.',
            'dataset_file.uploaded' => 'The dataset file failed to upload. Please ensure the file is under 50MB.',
        ]);

        $filePath = $dataset->file_path;
        $fileName = $dataset->file_name;
        $fileSize = $dataset->file_size_bytes;
        $fileMime = $dataset->file_mime_type;

        if ($request->hasFile('dataset_file')) {
            // Delete old file if present
            if ($dataset->file_path && Storage::disk('public')->exists($dataset->file_path)) {
                Storage::disk('public')->delete($dataset->file_path);
            }

            Storage::disk('public')->makeDirectory('datasets');
            $file = $request->file('dataset_file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileMime = $file->getMimeType();
            $filePath = $file->store('datasets', 'public');
        }

        $dataset->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'source_organization' => $validated['source_organization'],
            'source_url' => $validated['source_url'] ?? null,
            'dataset_type' => $validated['dataset_type'],
            'version' => $validated['version'],
            'release_date' => $validated['release_date'] ?? null,
            'license' => $validated['license'],
            'record_count' => $validated['record_count'] ?? null,
            'description' => $validated['description'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size_bytes' => $fileSize,
            'file_mime_type' => $fileMime,
            'is_published' => $request->boolean('is_published'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.datasets.index')
            ->with('success', "Dataset '{$dataset->name}' updated successfully.");
    }

    /**
     * Remove the specified dataset from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $dataset = Dataset::findOrFail($id);

        if ($dataset->file_path && Storage::disk('public')->exists($dataset->file_path)) {
            Storage::disk('public')->delete($dataset->file_path);
        }

        $name = $dataset->name;
        $dataset->delete();

        return redirect()->route('admin.datasets.index')
            ->with('success', "Dataset '{$name}' permanently removed.");
    }

    /**
     * Toggle the public visibility status of a dataset.
     */
    public function togglePublish(int $id): RedirectResponse
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->is_published = ! $dataset->is_published;
        $dataset->save();

        $status = $dataset->is_published ? 'published' : 'reverted to draft';

        return redirect()->back()
            ->with('success', "Dataset '{$dataset->name}' has been {$status}.");
    }

    /**
     * Toggle the featured status of a dataset on the public atlas.
     */
    public function toggleFeatured(int $id): RedirectResponse
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->is_featured = ! $dataset->is_featured;
        $dataset->save();

        $status = $dataset->is_featured ? 'marked as featured' : 'unmarked from featured';

        return redirect()->back()
            ->with('success', "Dataset '{$dataset->name}' is now {$status}.");
    }
}
