<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dynasty;
use App\Models\Evidence;
use App\Models\HeritageSite;
use App\Models\Location;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    /**
     * Common archaeological and monumental site typologies for quick selection.
     */
    protected array $commonSiteTypes = [
        'Buddhist Stupa Complex',
        'Monolithic Pillar & Standard',
        'Temple Complex',
        'Rock-cut Cave Complex',
        'Archaeological Mound & Stratigraphic Site',
        'Fortified Citadel & Urban Settlement',
        'Epigraphical Rock Edict',
        'Megalithic Burial Site',
    ];

    /**
     * Display a paginated listing of heritage sites with administrative filters.
     */
    public function index(Request $request): View
    {
        $query = HeritageSite::query()
            ->with(['location', 'primaryPeriod', 'primaryDynasty', 'media'])
            ->withCount(['objects', 'inscriptions', 'claims']);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhereHas('location', function ($lq) use ($search) {
                        $lq->where('name', 'like', "%{$search}%")
                            ->orWhere('state', 'like', "%{$search}%")
                            ->orWhere('district', 'like', "%{$search}%");
                    });
            });
        }

        if ($type = $request->query('site_type')) {
            $query->where('site_type', $type);
        }

        if ($status = $request->query('status')) {
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $sites = $query->orderBy('updated_at', 'desc')->paginate(12)->withQueryString();

        $counts = [
            'total' => HeritageSite::count(),
            'published' => HeritageSite::where('is_published', true)->count(),
            'draft' => HeritageSite::where('is_published', false)->count(),
        ];

        $siteTypes = HeritageSite::distinct()->orderBy('site_type')->pluck('site_type');

        return view('admin.sites.index', compact('sites', 'counts', 'siteTypes'));
    }

    /**
     * Show the form for creating a new heritage site.
     */
    public function create(): View
    {
        $periods = Period::orderBy('start_year')->get();
        $dynasties = Dynasty::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $commonSiteTypes = $this->commonSiteTypes;

        return view('admin.sites.create', compact('periods', 'dynasties', 'locations', 'commonSiteTypes'));
    }

    /**
     * Store a newly created heritage site and associated location in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->hasFile('cover_image') && ! $request->file('cover_image')->isValid()) {
            $errorCode = $request->file('cover_image')->getError();
            $errorMessage = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE => 'The cover image exceeds the server upload limit. Please compress the image below 20MB.',
                UPLOAD_ERR_FORM_SIZE => 'The cover image exceeds the form upload limit.',
                UPLOAD_ERR_PARTIAL => 'The cover image was only partially uploaded. Please try uploading again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Temporary directory missing on server.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write uploaded image to disk. Check disk space or permissions.',
                default => 'The cover image failed to upload (PHP upload error code: ' . $errorCode . ').',
            };

            return back()->withInput()->withErrors(['cover_image' => $errorMessage]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:heritage_sites,slug'],
            'site_type' => ['required', 'string', 'max:100'],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'is_dating_uncertain' => ['boolean'],
            'protection_status' => ['required', 'string', 'max:150'],
            'summary' => ['required', 'string'],
            'historical_context' => ['nullable', 'string'],
            'architectural_description' => ['nullable', 'string'],
            'is_published' => ['boolean'],
            'primary_period_id' => ['nullable', 'exists:periods,id'],
            'primary_dynasty_id' => ['nullable', 'exists:dynasties,id'],

            // Location handling
            'location_id' => ['nullable', 'exists:locations,id'],
            'new_location_name' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'taluk_tehsil' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'altitude_meters' => ['nullable', 'integer'],

            // Media upload
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:20480'],
            'cover_caption' => ['nullable', 'string', 'max:500'],
            'cover_credit' => ['nullable', 'string', 'max:255'],
            'cover_license' => ['nullable', 'string', 'max:100'],
        ], [
            'cover_image.image' => 'The cover image must be a valid image.',
            'cover_image.mimes' => 'The cover image format must be JPEG, PNG, JPG, or WebP.',
            'cover_image.max' => 'The cover image size may not exceed 20MB.',
            'cover_image.uploaded' => 'The cover image failed to upload. Please ensure the file is an uncorrupted image under 20MB.',
        ]);

        // Auto-generate slug if left empty
        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        // Ensure uniqueness if slug already exists
        $baseSlug = $slug;
        $counter = 1;
        while (HeritageSite::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        // Handle Location creation or attachment
        $locationId = $validated['location_id'] ?? null;
        if (! empty($validated['new_location_name']) && ! empty($validated['state']) && ! empty($validated['district'])) {
            $location = Location::create([
                'name' => $validated['new_location_name'],
                'state' => $validated['state'],
                'district' => $validated['district'],
                'taluk_tehsil' => $validated['taluk_tehsil'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'altitude_meters' => $validated['altitude_meters'] ?? null,
            ]);
            $locationId = $location->id;
        }

        $site = HeritageSite::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'site_type' => $validated['site_type'],
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'] ?? null,
            'end_year' => $validated['end_year'] ?? null,
            'is_dating_uncertain' => $request->boolean('is_dating_uncertain'),
            'protection_status' => $validated['protection_status'],
            'summary' => $validated['summary'],
            'historical_context' => $validated['historical_context'] ?? null,
            'architectural_description' => $validated['architectural_description'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'primary_period_id' => $validated['primary_period_id'] ?? null,
            'primary_dynasty_id' => $validated['primary_dynasty_id'] ?? null,
            'location_id' => $locationId,
        ]);

        // Handle Cover Photo upload
        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->makeDirectory('media/sites');
            $coverPath = $request->file('cover_image')->store('media/sites', 'public');
            $site->media()->create([
                'file_path' => $coverPath,
                'caption' => $request->input('cover_caption', "Primary photograph of {$site->name}"),
                'alt_text' => $site->name,
                'media_type' => 'PHOTOGRAPH',
                'license' => $request->input('cover_license', 'Creative Commons (CC BY-SA 4.0)'),
                'source_credit' => $request->input('cover_credit', 'Curatorial Heritage Archive'),
                'is_cover' => true,
                'display_order' => 0,
            ]);
        }

        return redirect()->route('admin.sites.index')
            ->with('success', "Heritage site '{$site->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified heritage site.
     */
    public function edit(int $id): View
    {
        $site = HeritageSite::with(['location', 'primaryPeriod', 'primaryDynasty', 'media', 'claims.evidence.sources'])->findOrFail($id);
        $periods = Period::orderBy('start_year')->get();
        $dynasties = Dynasty::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $commonSiteTypes = $this->commonSiteTypes;
        $sources = Source::orderBy('title')->get();
        $allEvidence = Evidence::orderBy('title')->get();

        return view('admin.sites.edit', compact('site', 'periods', 'dynasties', 'locations', 'commonSiteTypes', 'sources', 'allEvidence'));
    }

    /**
     * Update the specified heritage site in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $site = HeritageSite::findOrFail($id);

        if ($request->hasFile('cover_image') && ! $request->file('cover_image')->isValid()) {
            $errorCode = $request->file('cover_image')->getError();
            $errorMessage = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE => 'The cover image exceeds the server upload limit. Please compress the image below 20MB.',
                UPLOAD_ERR_FORM_SIZE => 'The cover image exceeds the form upload limit.',
                UPLOAD_ERR_PARTIAL => 'The cover image was only partially uploaded. Please try uploading again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Temporary directory missing on server.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write uploaded image to disk. Check disk space or permissions.',
                default => 'The cover image failed to upload (PHP upload error code: ' . $errorCode . ').',
            };

            return back()->withInput()->withErrors(['cover_image' => $errorMessage]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:heritage_sites,slug,' . $id],
            'site_type' => ['required', 'string', 'max:100'],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'is_dating_uncertain' => ['boolean'],
            'protection_status' => ['required', 'string', 'max:150'],
            'summary' => ['required', 'string'],
            'historical_context' => ['nullable', 'string'],
            'architectural_description' => ['nullable', 'string'],
            'is_published' => ['boolean'],
            'primary_period_id' => ['nullable', 'exists:periods,id'],
            'primary_dynasty_id' => ['nullable', 'exists:dynasties,id'],

            // Location handling
            'location_id' => ['nullable', 'exists:locations,id'],
            'state' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'taluk_tehsil' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'altitude_meters' => ['nullable', 'integer'],

            // Media upload
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:20480'],
            'cover_caption' => ['nullable', 'string', 'max:500'],
            'cover_credit' => ['nullable', 'string', 'max:255'],
            'cover_license' => ['nullable', 'string', 'max:100'],
        ], [
            'cover_image.image' => 'The cover image must be a valid image.',
            'cover_image.mimes' => 'The cover image format must be JPEG, PNG, JPG, or WebP.',
            'cover_image.max' => 'The cover image size may not exceed 20MB.',
            'cover_image.uploaded' => 'The cover image failed to upload. Please ensure the file is an uncorrupted image under 20MB.',
        ]);

        // If site already has a location and location attributes are edited, update it
        if ($site->location && ($request->filled('latitude') || $request->filled('longitude') || $request->filled('state'))) {
            $site->location->update([
                'state' => $validated['state'] ?? $site->location->state,
                'district' => $validated['district'] ?? $site->location->district,
                'taluk_tehsil' => $validated['taluk_tehsil'] ?? $site->location->taluk_tehsil,
                'latitude' => $validated['latitude'] ?? $site->location->latitude,
                'longitude' => $validated['longitude'] ?? $site->location->longitude,
                'altitude_meters' => $validated['altitude_meters'] ?? $site->location->altitude_meters,
            ]);
        } elseif (! empty($validated['location_id'])) {
            $site->location_id = $validated['location_id'];
        }

        $site->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'site_type' => $validated['site_type'],
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'] ?? null,
            'end_year' => $validated['end_year'] ?? null,
            'is_dating_uncertain' => $request->boolean('is_dating_uncertain'),
            'protection_status' => $validated['protection_status'],
            'summary' => $validated['summary'],
            'historical_context' => $validated['historical_context'] ?? null,
            'architectural_description' => $validated['architectural_description'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'primary_period_id' => $validated['primary_period_id'] ?? null,
            'primary_dynasty_id' => $validated['primary_dynasty_id'] ?? null,
        ]);

        // Handle Cover Photo replacement
        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->makeDirectory('media/sites');
            $coverPath = $request->file('cover_image')->store('media/sites', 'public');
            $site->media()->update(['is_cover' => false]);
            $site->media()->create([
                'file_path' => $coverPath,
                'caption' => $request->input('cover_caption', "Primary photograph of {$site->name}"),
                'alt_text' => $site->name,
                'media_type' => 'PHOTOGRAPH',
                'license' => $request->input('cover_license', 'Creative Commons (CC BY-SA 4.0)'),
                'source_credit' => $request->input('cover_credit', 'Curatorial Heritage Archive'),
                'is_cover' => true,
                'display_order' => 0,
            ]);
        }

        return redirect()->route('admin.sites.index')
            ->with('success', "Heritage site '{$site->name}' updated successfully.");
    }

    /**
     * Remove the specified heritage site from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $site = HeritageSite::findOrFail($id);
        $name = $site->name;
        $site->delete();

        return redirect()->route('admin.sites.index')
            ->with('success', "Heritage site '{$name}' deleted successfully.");
    }

    /**
     * Toggle the publication status of a heritage site.
     */
    public function togglePublish(int $id): RedirectResponse
    {
        $site = HeritageSite::findOrFail($id);
        $site->is_published = ! $site->is_published;
        $site->save();

        $statusStr = $site->is_published ? 'published and live on the Public Atlas' : 'unpublished and saved as draft';

        return back()->with('success', "Site '{$site->name}' is now {$statusStr}.");
    }
}
