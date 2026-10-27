<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dynasty;
use App\Models\Evidence;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Location;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ObjectController extends Controller
{
    protected array $objectTypes = [
        'SCULPTURE' => 'Sculpture / Anthropomorphic Figure',
        'ARCHITECTURAL_MEMBER' => 'Architectural Element / Bracket / Medallion',
        'RELIC_CASKET' => 'Sacred Relic Casket / Reliquary',
        'TERRACOTTA' => 'Terracotta Plaque / Figurine',
        'SEAL_SEALING' => 'Administrative Seal / Clay Sealing',
        'NUMISMATIC_COIN' => 'Numismatic Specimen / Punch-Marked Coin',
        'BRONZE_ICON' => 'Bronze / Metallic Cast Icon',
        'EPIGRAPHICAL_SLAB' => 'Inscribed Stone Slab / Pillar Section',
        'RITUAL_VESSEL' => 'Ritual Pottery / Ceremonial Vessel',
    ];

    protected array $commonMaterials = [
        'Polished Chunar Sandstone',
        'Vindhyan Sandstone',
        'Spotted Red Sandstone (Mathura)',
        'Basalt / Deccan Trap',
        'Greenish Schist (Gandhara)',
        'White-Spotted Buff Sandstone',
        'Ashta-dhatu / Bronze Cast',
        'Burnt Terracotta',
        'Soapstone / Steatite',
        'Granite / Charnockite',
    ];

    /**
     * Display a paginated listing of cataloged artifacts and sculptures.
     */
    public function index(Request $request): View
    {
        $query = HeritageObject::query()
            ->with(['heritageSite', 'location', 'period', 'dynasty', 'media'])
            ->withCount(['inscriptions', 'claims']);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('material', 'like', "%{$search}%")
                    ->orWhere('accession_number', 'like', "%{$search}%")
                    ->orWhere('current_repository', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type = $request->query('object_type')) {
            $query->where('object_type', $type);
        }

        if ($siteId = $request->query('site_id')) {
            $query->where('heritage_site_id', $siteId);
        }

        if ($status = $request->query('status')) {
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $objects = $query->orderBy('updated_at', 'desc')->paginate(12)->withQueryString();

        $counts = [
            'total' => HeritageObject::count(),
            'published' => HeritageObject::where('is_published', true)->count(),
            'draft' => HeritageObject::where('is_published', false)->count(),
        ];

        $objectTypes = $this->objectTypes;
        $sites = HeritageSite::orderBy('name')->get(['id', 'name']);

        return view('admin.objects.index', compact('objects', 'counts', 'objectTypes', 'sites'));
    }

    /**
     * Show the form for creating a new artifact record.
     */
    public function create(): View
    {
        $periods = Period::orderBy('start_year')->get();
        $dynasties = Dynasty::orderBy('name')->get();
        $sites = HeritageSite::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $objectTypes = $this->objectTypes;
        $commonMaterials = $this->commonMaterials;

        return view('admin.objects.create', compact('periods', 'dynasties', 'sites', 'locations', 'objectTypes', 'commonMaterials'));
    }

    /**
     * Store a newly created artifact in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->hasFile('cover_image') && ! $request->file('cover_image')->isValid()) {
            $errorCode = $request->file('cover_image')->getError();
            $errorMessage = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE => 'The artifact cover image exceeds the server upload limit. Please compress the image below 20MB.',
                UPLOAD_ERR_FORM_SIZE => 'The image exceeds the form upload limit.',
                UPLOAD_ERR_PARTIAL => 'The image was only partially uploaded. Please try uploading again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Temporary directory missing on server.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write uploaded image to disk. Check disk space or permissions.',
                default => 'The cover image failed to upload (PHP upload error code: ' . $errorCode . ').',
            };

            return back()->withInput()->withErrors(['cover_image' => $errorMessage]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:objects,slug'],
            'heritage_site_id' => ['nullable', 'exists:heritage_sites,id'],
            'period_id' => ['nullable', 'exists:periods,id'],
            'dynasty_id' => ['nullable', 'exists:dynasties,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'object_type' => ['required', 'string', 'max:50'],
            'material' => ['required', 'string', 'max:150'],
            'dimensions' => ['nullable', 'string', 'max:150'],
            'current_repository' => ['required', 'string', 'max:255'],
            'accession_number' => ['nullable', 'string', 'max:100'],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'is_dating_uncertain' => ['boolean'],
            'description' => ['required', 'string'],
            'iconographic_notes' => ['nullable', 'string'],
            'is_published' => ['boolean'],

            // Media Cover Upload
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:20480'],
            'cover_caption' => ['nullable', 'string', 'max:500'],
            'cover_credit' => ['nullable', 'string', 'max:255'],
            'cover_license' => ['nullable', 'string', 'max:100'],
        ], [
            'cover_image.image' => 'The cover photo must be a valid image.',
            'cover_image.mimes' => 'The cover photo format must be JPEG, PNG, JPG, or WebP.',
            'cover_image.max' => 'The cover photo size may not exceed 20MB.',
            'cover_image.uploaded' => 'The cover photo failed to upload. Please ensure the file is an uncorrupted image under 20MB.',
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        $baseSlug = $slug;
        $counter = 1;
        while (HeritageObject::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $object = HeritageObject::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'heritage_site_id' => $validated['heritage_site_id'] ?? null,
            'period_id' => $validated['period_id'] ?? null,
            'dynasty_id' => $validated['dynasty_id'] ?? null,
            'location_id' => $validated['location_id'] ?? null,
            'object_type' => $validated['object_type'],
            'material' => $validated['material'],
            'dimensions' => $validated['dimensions'] ?? null,
            'current_repository' => $validated['current_repository'],
            'accession_number' => $validated['accession_number'] ?? null,
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'] ?? null,
            'end_year' => $validated['end_year'] ?? null,
            'is_dating_uncertain' => $request->boolean('is_dating_uncertain'),
            'description' => $validated['description'],
            'iconographic_notes' => $validated['iconographic_notes'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->makeDirectory('media/objects');
            $coverPath = $request->file('cover_image')->store('media/objects', 'public');
            $object->media()->create([
                'file_path' => $coverPath,
                'caption' => $request->input('cover_caption', "Primary photograph of {$object->name}"),
                'alt_text' => $object->name,
                'media_type' => 'PHOTOGRAPH',
                'license' => $request->input('cover_license', 'Creative Commons (CC BY-SA 4.0)'),
                'source_credit' => $request->input('cover_credit', 'Curatorial Heritage Archive'),
                'is_cover' => true,
                'display_order' => 0,
            ]);
        }

        return redirect()->route('admin.objects.index')
            ->with('success', "Artifact '{$object->name}' registered successfully.");
    }

    /**
     * Show the form for editing the specified artifact.
     */
    public function edit(int $id): View
    {
        $object = HeritageObject::with([
            'heritageSite',
            'location',
            'period',
            'dynasty',
            'media',
            'claims.evidence.sources',
        ])->findOrFail($id);

        $periods = Period::orderBy('start_year')->get();
        $dynasties = Dynasty::orderBy('name')->get();
        $sites = HeritageSite::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $sources = Source::orderBy('title')->get();
        $allEvidence = Evidence::orderBy('title')->get();
        $objectTypes = $this->objectTypes;
        $commonMaterials = $this->commonMaterials;

        return view('admin.objects.edit', compact('object', 'periods', 'dynasties', 'sites', 'locations', 'sources', 'allEvidence', 'objectTypes', 'commonMaterials'));
    }

    /**
     * Update the specified artifact in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $object = HeritageObject::findOrFail($id);

        if ($request->hasFile('cover_image') && ! $request->file('cover_image')->isValid()) {
            $errorCode = $request->file('cover_image')->getError();
            $errorMessage = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE => 'The artifact cover image exceeds the server upload limit. Please compress the image below 20MB.',
                UPLOAD_ERR_FORM_SIZE => 'The image exceeds the form upload limit.',
                UPLOAD_ERR_PARTIAL => 'The image was only partially uploaded. Please try uploading again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Temporary directory missing on server.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write uploaded image to disk. Check disk space or permissions.',
                default => 'The cover image failed to upload (PHP upload error code: ' . $errorCode . ').',
            };

            return back()->withInput()->withErrors(['cover_image' => $errorMessage]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:objects,slug,' . $id],
            'heritage_site_id' => ['nullable', 'exists:heritage_sites,id'],
            'period_id' => ['nullable', 'exists:periods,id'],
            'dynasty_id' => ['nullable', 'exists:dynasties,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'object_type' => ['required', 'string', 'max:50'],
            'material' => ['required', 'string', 'max:150'],
            'dimensions' => ['nullable', 'string', 'max:150'],
            'current_repository' => ['required', 'string', 'max:255'],
            'accession_number' => ['nullable', 'string', 'max:100'],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'is_dating_uncertain' => ['boolean'],
            'description' => ['required', 'string'],
            'iconographic_notes' => ['nullable', 'string'],
            'is_published' => ['boolean'],

            // Media Cover Replacement
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:20480'],
            'cover_caption' => ['nullable', 'string', 'max:500'],
            'cover_credit' => ['nullable', 'string', 'max:255'],
            'cover_license' => ['nullable', 'string', 'max:100'],
        ], [
            'cover_image.image' => 'The cover photo must be a valid image.',
            'cover_image.mimes' => 'The cover photo format must be JPEG, PNG, JPG, or WebP.',
            'cover_image.max' => 'The cover photo size may not exceed 20MB.',
            'cover_image.uploaded' => 'The cover photo failed to upload. Please ensure the file is an uncorrupted image under 20MB.',
        ]);

        $object->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'heritage_site_id' => $validated['heritage_site_id'] ?? null,
            'period_id' => $validated['period_id'] ?? null,
            'dynasty_id' => $validated['dynasty_id'] ?? null,
            'location_id' => $validated['location_id'] ?? null,
            'object_type' => $validated['object_type'],
            'material' => $validated['material'],
            'dimensions' => $validated['dimensions'] ?? null,
            'current_repository' => $validated['current_repository'],
            'accession_number' => $validated['accession_number'] ?? null,
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'] ?? null,
            'end_year' => $validated['end_year'] ?? null,
            'is_dating_uncertain' => $request->boolean('is_dating_uncertain'),
            'description' => $validated['description'],
            'iconographic_notes' => $validated['iconographic_notes'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->makeDirectory('media/objects');
            $coverPath = $request->file('cover_image')->store('media/objects', 'public');
            $object->media()->update(['is_cover' => false]);
            $object->media()->create([
                'file_path' => $coverPath,
                'caption' => $request->input('cover_caption', "Primary photograph of {$object->name}"),
                'alt_text' => $object->name,
                'media_type' => 'PHOTOGRAPH',
                'license' => $request->input('cover_license', 'Creative Commons (CC BY-SA 4.0)'),
                'source_credit' => $request->input('cover_credit', 'Curatorial Heritage Archive'),
                'is_cover' => true,
                'display_order' => 0,
            ]);
        }

        return redirect()->route('admin.objects.index')
            ->with('success', "Artifact '{$object->name}' updated successfully.");
    }

    /**
     * Remove the specified artifact from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $object = HeritageObject::findOrFail($id);
        $name = $object->name;
        $object->delete();

        return redirect()->route('admin.objects.index')
            ->with('success', "Artifact '{$name}' deleted successfully.");
    }

    /**
     * Toggle the publication status of an artifact.
     */
    public function togglePublish(int $id): RedirectResponse
    {
        $object = HeritageObject::findOrFail($id);
        $object->is_published = ! $object->is_published;
        $object->save();

        $statusStr = $object->is_published ? 'published on Public Atlas' : 'saved as draft';

        return back()->with('success', "Artifact '{$object->name}' is now {$statusStr}.");
    }
}
