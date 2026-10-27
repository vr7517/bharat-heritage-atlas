<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Source;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InscriptionController extends Controller
{
    protected array $languages = [
        'Prakrit' => 'Prakrit (Middle Indo-Aryan)',
        'Sanskrit' => 'Classical / Epigraphical Sanskrit',
        'Old Tamil' => 'Old Tamil / Sangam Tamil',
        'Old Kannada' => 'Old Kannada (Halegannada)',
        'Old Telugu' => 'Old Telugu',
        'Pali' => 'Pali',
        'Bilingual' => 'Bilingual / Hybrid Inscription',
    ];

    protected array $scripts = [
        'Ashokan Brahmi' => 'Ashokan Brahmi (c. 3rd c. BCE)',
        'Early Historic Brahmi' => 'Early Historic / Shunga-Satavahana Brahmi',
        'Kushana Brahmi' => 'Kushana Brahmi (Northern)',
        'Gupta Brahmi' => 'Gupta Brahmi (c. 4th–6th c. CE)',
        'Southern Brahmi' => 'Southern Brahmi / Cave Brahmi',
        'Grantha' => 'Pallava / Chola Grantha',
        'Vatteluttu' => 'Vatteluttu Script',
        'Siddhamatrika' => 'Siddhamatrika / Kutila',
        'Nagari' => 'Early Nagari / Devanagari',
    ];

    /**
     * Display a paginated listing of cataloged epigraphical records and Brahmi rubbings.
     */
    public function index(Request $request): View
    {
        $query = Inscription::query()
            ->with(['heritageSite', 'object', 'media'])
            ->withCount('claims');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('epigraphic_reference', 'like', "%{$search}%")
                    ->orWhere('donor', 'like', "%{$search}%")
                    ->orWhere('ruler_mentioned', 'like', "%{$search}%")
                    ->orWhere('raw_text', 'like', "%{$search}%")
                    ->orWhere('translation', 'like', "%{$search}%");
            });
        }

        if ($lang = $request->query('language')) {
            $query->where('language', $lang);
        }

        if ($scr = $request->query('script')) {
            $query->where('script', $scr);
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

        $inscriptions = $query->orderBy('updated_at', 'desc')->paginate(12)->withQueryString();

        $counts = [
            'total' => Inscription::count(),
            'published' => Inscription::where('is_published', true)->count(),
            'draft' => Inscription::where('is_published', false)->count(),
        ];

        $languages = $this->languages;
        $scripts = $this->scripts;
        $sites = HeritageSite::orderBy('name')->get(['id', 'name']);

        return view('admin.inscriptions.index', compact('inscriptions', 'counts', 'languages', 'scripts', 'sites'));
    }

    /**
     * Show the form for registering a new epigraphical inscription.
     */
    public function create(): View
    {
        $sites = HeritageSite::orderBy('name')->get(['id', 'name']);
        $objects = HeritageObject::orderBy('name')->get(['id', 'name']);
        $languages = $this->languages;
        $scripts = $this->scripts;

        return view('admin.inscriptions.create', compact('sites', 'objects', 'languages', 'scripts'));
    }

    /**
     * Store a newly created inscription in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->hasFile('cover_image') && ! $request->file('cover_image')->isValid()) {
            $errorCode = $request->file('cover_image')->getError();
            $errorMessage = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE => 'The inscription rubbing image exceeds the server upload limit. Please compress the image below 20MB.',
                UPLOAD_ERR_FORM_SIZE => 'The image exceeds the form upload limit.',
                UPLOAD_ERR_PARTIAL => 'The image was only partially uploaded. Please try uploading again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Temporary directory missing on server.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write uploaded image to disk. Check disk space or permissions.',
                default => 'The rubbing image failed to upload (PHP upload error code: ' . $errorCode . ').',
            };

            return back()->withInput()->withErrors(['cover_image' => $errorMessage]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:inscriptions,slug'],
            'heritage_site_id' => ['nullable', 'exists:heritage_sites,id'],
            'object_id' => ['nullable', 'exists:objects,id'],
            'language' => ['required', 'string', 'max:100'],
            'script' => ['required', 'string', 'max:100'],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'donor' => ['nullable', 'string', 'max:255'],
            'ruler_mentioned' => ['nullable', 'string', 'max:255'],
            'epigraphic_reference' => ['required', 'string', 'max:255'],
            'raw_text' => ['nullable', 'string'],
            'translation' => ['required', 'string'],
            'interpretation_notes' => ['nullable', 'string'],
            'is_published' => ['boolean'],

            // Rubbing / Photograph Upload
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:20480'],
            'cover_caption' => ['nullable', 'string', 'max:500'],
            'cover_credit' => ['nullable', 'string', 'max:255'],
            'cover_license' => ['nullable', 'string', 'max:100'],
        ], [
            'cover_image.image' => 'The rubbing file must be a valid image.',
            'cover_image.mimes' => 'The rubbing image format must be JPEG, PNG, JPG, or WebP.',
            'cover_image.max' => 'The rubbing image size may not exceed 20MB.',
            'cover_image.uploaded' => 'The rubbing image failed to upload. Please ensure the file is an uncorrupted image under 20MB.',
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        $baseSlug = $slug;
        $counter = 1;
        while (Inscription::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $inscription = Inscription::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'heritage_site_id' => $validated['heritage_site_id'] ?? null,
            'object_id' => $validated['object_id'] ?? null,
            'language' => $validated['language'],
            'script' => $validated['script'],
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'] ?? null,
            'end_year' => $validated['end_year'] ?? null,
            'donor' => $validated['donor'] ?? null,
            'ruler_mentioned' => $validated['ruler_mentioned'] ?? null,
            'epigraphic_reference' => $validated['epigraphic_reference'],
            'raw_text' => $validated['raw_text'] ?? null,
            'translation' => $validated['translation'],
            'interpretation_notes' => $validated['interpretation_notes'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->makeDirectory('media/inscriptions');
            $coverPath = $request->file('cover_image')->store('media/inscriptions', 'public');
            $inscription->media()->create([
                'file_path' => $coverPath,
                'caption' => $request->input('cover_caption', "Rubbing / Estampage of {$inscription->title}"),
                'alt_text' => $inscription->title,
                'media_type' => 'INSCRIPTION_RUBBING',
                'license' => $request->input('cover_license', 'Creative Commons (CC BY-SA 4.0)'),
                'source_credit' => $request->input('cover_credit', 'Epigraphia Indica / ASI Archive'),
                'is_cover' => true,
                'display_order' => 0,
            ]);
        }

        return redirect()->route('admin.inscriptions.index')
            ->with('success', "Inscription '{$inscription->title}' cataloged successfully.");
    }

    /**
     * Show the form for editing the specified inscription.
     */
    public function edit(int $id): View
    {
        $inscription = Inscription::with(['heritageSite', 'object', 'media', 'claims.evidence.sources'])->findOrFail($id);
        $sites = HeritageSite::orderBy('name')->get(['id', 'name']);
        $objects = HeritageObject::orderBy('name')->get(['id', 'name']);
        $languages = $this->languages;
        $scripts = $this->scripts;
        $sources = Source::orderBy('title')->get();
        $allEvidence = Evidence::orderBy('title')->get();

        return view('admin.inscriptions.edit', compact('inscription', 'sites', 'objects', 'languages', 'scripts', 'sources', 'allEvidence'));
    }

    /**
     * Update the specified inscription in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $inscription = Inscription::findOrFail($id);

        if ($request->hasFile('cover_image') && ! $request->file('cover_image')->isValid()) {
            $errorCode = $request->file('cover_image')->getError();
            $errorMessage = match ($errorCode) {
                UPLOAD_ERR_INI_SIZE => 'The inscription rubbing image exceeds the server upload limit. Please compress the image below 20MB.',
                UPLOAD_ERR_FORM_SIZE => 'The image exceeds the form upload limit.',
                UPLOAD_ERR_PARTIAL => 'The image was only partially uploaded. Please try uploading again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Temporary directory missing on server.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write uploaded image to disk. Check disk space or permissions.',
                default => 'The rubbing image failed to upload (PHP upload error code: ' . $errorCode . ').',
            };

            return back()->withInput()->withErrors(['cover_image' => $errorMessage]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:inscriptions,slug,' . $id],
            'heritage_site_id' => ['nullable', 'exists:heritage_sites,id'],
            'object_id' => ['nullable', 'exists:objects,id'],
            'language' => ['required', 'string', 'max:100'],
            'script' => ['required', 'string', 'max:100'],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'donor' => ['nullable', 'string', 'max:255'],
            'ruler_mentioned' => ['nullable', 'string', 'max:255'],
            'epigraphic_reference' => ['required', 'string', 'max:255'],
            'raw_text' => ['nullable', 'string'],
            'translation' => ['required', 'string'],
            'interpretation_notes' => ['nullable', 'string'],
            'is_published' => ['boolean'],

            // Rubbing / Photograph Replacement
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:20480'],
            'cover_caption' => ['nullable', 'string', 'max:500'],
            'cover_credit' => ['nullable', 'string', 'max:255'],
            'cover_license' => ['nullable', 'string', 'max:100'],
        ], [
            'cover_image.image' => 'The rubbing file must be a valid image.',
            'cover_image.mimes' => 'The rubbing image format must be JPEG, PNG, JPG, or WebP.',
            'cover_image.max' => 'The rubbing image size may not exceed 20MB.',
            'cover_image.uploaded' => 'The rubbing image failed to upload. Please ensure the file is an uncorrupted image under 20MB.',
        ]);

        $inscription->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'heritage_site_id' => $validated['heritage_site_id'] ?? null,
            'object_id' => $validated['object_id'] ?? null,
            'language' => $validated['language'],
            'script' => $validated['script'],
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'] ?? null,
            'end_year' => $validated['end_year'] ?? null,
            'donor' => $validated['donor'] ?? null,
            'ruler_mentioned' => $validated['ruler_mentioned'] ?? null,
            'epigraphic_reference' => $validated['epigraphic_reference'],
            'raw_text' => $validated['raw_text'] ?? null,
            'translation' => $validated['translation'],
            'interpretation_notes' => $validated['interpretation_notes'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->makeDirectory('media/inscriptions');
            $coverPath = $request->file('cover_image')->store('media/inscriptions', 'public');
            $inscription->media()->update(['is_cover' => false]);
            $inscription->media()->create([
                'file_path' => $coverPath,
                'caption' => $request->input('cover_caption', "Rubbing / Estampage of {$inscription->title}"),
                'alt_text' => $inscription->title,
                'media_type' => 'INSCRIPTION_RUBBING',
                'license' => $request->input('cover_license', 'Creative Commons (CC BY-SA 4.0)'),
                'source_credit' => $request->input('cover_credit', 'Epigraphia Indica / ASI Archive'),
                'is_cover' => true,
                'display_order' => 0,
            ]);
        }

        return redirect()->route('admin.inscriptions.index')
            ->with('success', "Inscription '{$inscription->title}' updated successfully.");
    }

    /**
     * Remove the specified inscription from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $inscription = Inscription::findOrFail($id);
        $title = $inscription->title;
        $inscription->delete();

        return redirect()->route('admin.inscriptions.index')
            ->with('success', "Inscription '{$title}' deleted successfully.");
    }

    /**
     * Toggle the publication status of an inscription.
     */
    public function togglePublish(int $id): RedirectResponse
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->is_published = ! $inscription->is_published;
        $inscription->save();

        $statusStr = $inscription->is_published ? 'published on Public Atlas' : 'saved as draft';

        return back()->with('success', "Inscription '{$inscription->title}' is now {$statusStr}.");
    }
}
