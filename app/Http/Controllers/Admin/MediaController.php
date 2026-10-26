<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Store and attach an uploaded media asset to a heritage entity.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mediable_type' => ['required', 'string'],
            'mediable_id' => ['required', 'integer'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'caption' => ['required', 'string', 'max:500'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'media_type' => ['required', 'string', 'max:50'],
            'license' => ['required', 'string', 'max:100'],
            'source_credit' => ['required', 'string', 'max:255'],
            'photographer_or_draughtsman' => ['nullable', 'string', 'max:150'],
            'capture_year' => ['nullable', 'integer'],
            'is_cover' => ['boolean'],
        ]);

        $modelClass = match ($validated['mediable_type']) {
            'site', HeritageSite::class => HeritageSite::class,
            'object', 'artifact', HeritageObject::class => HeritageObject::class,
            'inscription', Inscription::class => Inscription::class,
            default => abort(400, 'Invalid entity type for media attachment.'),
        };

        $entity = $modelClass::findOrFail($validated['mediable_id']);

        $subfolder = match ($modelClass) {
            HeritageSite::class => 'media/sites',
            HeritageObject::class => 'media/objects',
            Inscription::class => 'media/inscriptions',
            default => 'media/general',
        };

        $path = $request->file('image')->store($subfolder, 'public');

        $isCover = $request->boolean('is_cover');

        if ($isCover) {
            $entity->media()->update(['is_cover' => false]);
        }

        $entity->media()->create([
            'file_path' => $path,
            'caption' => $validated['caption'],
            'alt_text' => $validated['alt_text'] ?? $validated['caption'],
            'media_type' => $validated['media_type'],
            'license' => $validated['license'],
            'source_credit' => $validated['source_credit'],
            'photographer_or_draughtsman' => $validated['photographer_or_draughtsman'] ?? null,
            'capture_year' => $validated['capture_year'] ?? null,
            'is_cover' => $isCover,
            'display_order' => $entity->media()->count() + 1,
        ]);

        return back()->with('success', 'Media asset uploaded and linked successfully.');
    }

    /**
     * Mark the specified media item as the designated cover photo.
     */
    public function setCover(int $id): RedirectResponse
    {
        $media = Media::findOrFail($id);

        if ($media->mediable) {
            $media->mediable->media()->update(['is_cover' => false]);
        }

        $media->update(['is_cover' => true]);

        return back()->with('success', 'Designated as primary cover photograph.');
    }

    /**
     * Remove the specified media asset and delete its physical storage file.
     */
    public function destroy(int $id): RedirectResponse
    {
        $media = Media::findOrFail($id);

        if (! str_starts_with($media->file_path, 'http://') && ! str_starts_with($media->file_path, 'https://')) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return back()->with('success', 'Media asset removed successfully.');
    }
}
