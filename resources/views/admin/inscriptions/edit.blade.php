@extends('layouts.admin')

@section('title', 'Edit ' . $inscription->title)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.inscriptions.index') }}" class="hover:text-stone-900">Epigraphy</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold truncate">{{ Str::limit($inscription->title, 30) }}</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Edit Epigraphical Record
            </h1>
        </div>

        <div class="flex items-center gap-2">
            @if($inscription->is_published)
            <a href="{{ route('inscriptions.show', $inscription->slug) }}" target="_blank"
                class="px-3.5 py-1.5 rounded-xl border border-emerald-300 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 transition flex items-center gap-1">
                <span>View Public</span>
                <span>&nearr;</span>
            </a>
            @endif
            <a href="{{ route('admin.inscriptions.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                &larr; Back to Epigraphy
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.inscriptions.update', $inscription->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- 1. Epigraph Nomenclature & Language -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                        <span>📜 1. Epigraph Nomenclature & Language</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Formal epigraph title, palaeographic script, and language medium.</p>
                </div>
                <span class="text-xs font-mono text-stone-400">ID: #{{ $inscription->id }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="title" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Epigraph Title / Inscription Identifier <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="title" name="title" required value="{{ old('title', $inscription->title) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" required value="{{ old('slug', $inscription->slug) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="epigraphic_reference" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Epigraphical Publication Reference <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="epigraphic_reference" name="epigraphic_reference" required value="{{ old('epigraphic_reference', $inscription->epigraphic_reference) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="language" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Language Medium <span class="text-rose-600">*</span>
                    </label>
                    <select id="language" name="language" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($languages as $val => $label)
                        <option value="{{ $val }}" @selected(old('language', $inscription->language) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="script" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Palaeographic Script <span class="text-rose-600">*</span>
                    </label>
                    <select id="script" name="script" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($scripts as $val => $label)
                        <option value="{{ $val }}" @selected(old('script', $inscription->script) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="donor" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Donor / Dedicator Mentioned
                    </label>
                    <input type="text" id="donor" name="donor" value="{{ old('donor', $inscription->donor) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="ruler_mentioned" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Reigning Monarch / Sovereign Mentioned
                    </label>
                    <input type="text" id="ruler_mentioned" name="ruler_mentioned" value="{{ old('ruler_mentioned', $inscription->ruler_mentioned) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 2. Provenance & Chronology -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>⏳ 2. Dating & Physical Association</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Chronological dating statement and linkage to sites or objects.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="dating_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Dating Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement', $inscription->dating_statement) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="start_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical Start Year
                    </label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year', $inscription->start_year) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year', $inscription->end_year) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="heritage_site_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Associated Heritage Monument / Site
                    </label>
                    <select id="heritage_site_id" name="heritage_site_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- No Direct Site Association (Independent Edict) --</option>
                        @foreach($sites as $site)
                        <option value="{{ $site->id }}" @selected(old('heritage_site_id', $inscription->heritage_site_id) == $site->id)>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="object_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Associated Inscribed Artifact
                    </label>
                    <select id="object_id" name="object_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- No Direct Object Association (Lithic Rock / Wall Inscription) --</option>
                        @foreach($objects as $obj)
                        <option value="{{ $obj->id }}" @selected(old('object_id', $inscription->object_id) == $obj->id)>{{ $obj->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Diplomatic Text & Translation -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🔤 3. Epigraphical Text & Translation</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Diplomatic transliteration and English academic translation.</p>
            </div>

            <div class="space-y-5 text-xs">
                <div>
                    <label for="raw_text" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Diplomatic Transliteration / Original Brahmi Text
                    </label>
                    <textarea id="raw_text" name="raw_text" rows="4"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-900 text-amber-200 leading-relaxed">{{ old('raw_text', $inscription->raw_text) }}</textarea>
                </div>

                <div>
                    <label for="translation" class="block font-bold text-stone-700 uppercase tracking-wider">
                        English Academic Translation <span class="text-rose-600">*</span>
                    </label>
                    <textarea id="translation" name="translation" rows="4" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('translation', $inscription->translation) }}</textarea>
                </div>

                <div>
                    <label for="interpretation_notes" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Epigraphical Commentary & Palaeographical Notes
                    </label>
                    <textarea id="interpretation_notes" name="interpretation_notes" rows="3"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('interpretation_notes', $inscription->interpretation_notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4. Rubbing / Estampage Photograph Upload -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                        <span>📸 4. Primary Rubbing / Estampage Facsimile</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">High-resolution photographic plate or ink squeeze estampage.</p>
                </div>
                @if($cover = $inscription->coverImage())
                <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Active Estampage Set</span>
                @else
                <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-600 font-semibold">No Image Attached</span>
                @endif
            </div>

            @if($cover = $inscription->coverImage())
            <div class="flex flex-col sm:flex-row items-start gap-4 p-4 rounded-xl bg-stone-50 border border-stone-200">
                <img src="{{ $cover->url }}" alt="{{ $cover->alt_text }}" class="w-32 h-24 object-cover rounded-lg border border-stone-300 shadow-2xs" />
                <div class="text-xs space-y-1 text-stone-600 flex-1">
                    <p class="font-bold text-stone-900">{{ $cover->caption }}</p>
                    <p><span class="font-semibold text-stone-500">Credit:</span> {{ $cover->source_credit }}</p>
                    <p><span class="font-semibold text-stone-500">License:</span> {{ $cover->license }}</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="cover_image" class="block font-bold text-stone-700 uppercase tracking-wider">
                        {{ $inscription->coverImage() ? 'Replace Estampage / Photo' : 'Upload Estampage Image' }} <span class="text-stone-400 font-normal font-sans">(JPEG, PNG, WebP up to 10MB)</span>
                    </label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 cursor-pointer" />
                </div>

                <div class="sm:col-span-2">
                    <label for="cover_caption" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Image Caption / Description
                    </label>
                    <input type="text" id="cover_caption" name="cover_caption" value="{{ old('cover_caption') }}"
                        placeholder="e.g. Ink squeeze estampage of the 8-line Brahmi dedication on pillar shaft"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="cover_credit" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Source / Institutional Credit
                    </label>
                    <input type="text" id="cover_credit" name="cover_credit" value="{{ old('cover_credit', 'Epigraphia Indica / ASI Archive') }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="cover_license" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Copyright / License
                    </label>
                    <input type="text" id="cover_license" name="cover_license" value="{{ old('cover_license', 'Creative Commons (CC BY-SA 4.0)') }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 5. Publishing Status -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                <span>🛡️ 5. Publishing & Visibility Control</span>
            </h2>

            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 flex items-start gap-3">
                <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', $inscription->is_published))
                    class="mt-0.5 w-5 h-5 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                <label for="is_published" class="text-xs text-stone-700 cursor-pointer select-none">
                    <strong class="text-stone-900 block text-sm font-bold">Published on Public Epigraphy Explorer</strong>
                    Checked means this inscription is live on the public explorer. Uncheck to save as a draft.
                </label>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-between gap-3 pt-4">
            <a href="{{ route('admin.inscriptions.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Update Epigraphical Record
            </button>
        </div>

    </form>

    <!-- 6. Media & Rubbings Gallery -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
        <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🖼️ 6. Epigraphical Estampages & Rubbings Gallery</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Manage additional photographic plates, rubbings, and eye-copies.</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-700 font-semibold">
                {{ $inscription->media->count() }} Attached {{ Str::plural('Asset', $inscription->media->count()) }}
            </span>
        </div>

        @if($inscription->media->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($inscription->media as $mediaItem)
            <div class="group relative rounded-xl border border-stone-200 bg-stone-50 overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="relative aspect-video bg-stone-200 overflow-hidden">
                        <img src="{{ $mediaItem->url }}" alt="{{ $mediaItem->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                        @if($mediaItem->is_cover)
                        <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
                            ⭐ Estampage
                        </span>
                        @endif
                        <span class="absolute top-2 right-2 bg-stone-900/70 text-white text-[10px] font-medium px-2 py-0.5 rounded backdrop-blur-xs">
                            {{ $mediaItem->media_type }}
                        </span>
                    </div>

                    <div class="p-3 text-xs space-y-1">
                        <p class="font-bold text-stone-800 line-clamp-2">{{ $mediaItem->caption }}</p>
                        <p class="text-[11px] text-stone-500 truncate"><span class="font-semibold">Credit:</span> {{ $mediaItem->source_credit }}</p>
                        <p class="text-[11px] text-stone-400 truncate"><span class="font-semibold">License:</span> {{ $mediaItem->license }}</p>
                    </div>
                </div>

                <div class="p-3 pt-0 flex items-center justify-between border-t border-stone-200/60 mt-2">
                    @if(!$mediaItem->is_cover)
                    <form action="{{ route('admin.media.set-cover', $mediaItem->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-[11px] font-semibold text-amber-800 hover:text-amber-950 hover:underline">
                            Set as Cover
                        </button>
                    </form>
                    @else
                    <span class="text-[11px] font-semibold text-emerald-700">Primary Rubbing</span>
                    @endif

                    <form action="{{ route('admin.media.destroy', $mediaItem->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to permanently delete this media asset?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[11px] font-semibold text-rose-600 hover:text-rose-800 hover:underline">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-6 px-4 rounded-xl border border-dashed border-stone-200 bg-stone-50/50">
            <p class="text-xs text-stone-500 font-medium">No secondary gallery rubbings uploaded yet.</p>
        </div>
        @endif

        <!-- Upload New Media Item Form -->
        <div class="mt-6 pt-6 border-t border-stone-100">
            <h3 class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-3">
                + Upload New Estampage / Photographic Plate
            </h3>

            <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                @csrf
                <input type="hidden" name="mediable_type" value="inscription" />
                <input type="hidden" name="mediable_id" value="{{ $inscription->id }}" />

                <div class="sm:col-span-2">
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Image File <span class="text-rose-600">*</span>
                    </label>
                    <input type="file" name="image" accept="image/*" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-700 bg-stone-50/50 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 cursor-pointer" />
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Caption / Description <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="caption" required
                        placeholder="e.g. Detailed view of lines 4–6 showing Brahmi conjunct consonants"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Asset Type <span class="text-rose-600">*</span>
                    </label>
                    <select name="media_type" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="INSCRIPTION_RUBBING">Inscription Rubbing (Estampage)</option>
                        <option value="PHOTOGRAPH">In-situ Epigraph Photograph</option>
                        <option value="ORTHOGRAPHIC_DRAWING">Palaeographical Eye-Copy / Facsimile</option>
                        <option value="HISTORICAL_PLATE">Historical Archival Plate</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        License <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="license" required value="Creative Commons (CC BY-SA 4.0)"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Source / Institutional Credit <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="source_credit" required value="Epigraphia Indica / ASI Archive"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="flex items-center gap-2 pt-5">
                    <input type="checkbox" id="insc_is_cover" name="is_cover" value="1"
                        class="w-4 h-4 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                    <label for="insc_is_cover" class="text-xs text-stone-700 font-semibold cursor-pointer">
                        Set as primary estampage photo immediately
                    </label>
                </div>

                <div class="sm:col-span-2 pt-2">
                    <button type="submit"
                        class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-stone-800 hover:bg-stone-900 transition">
                        Upload to Epigraphical Archive
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 7. Evidentiary Claims & Epigraphical Consensus Graph -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
        <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>⚖️ 7. Palaeographic & Historical Claims Network</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Link historical readings, ruler attributions, and genealogical claims to evidence nodes.</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-700 font-semibold">
                {{ $inscription->claims->count() }} Linked {{ Str::plural('Claim', $inscription->claims->count()) }}
            </span>
        </div>

        @if($inscription->claims->isNotEmpty())
        <div class="space-y-6">
            @foreach($inscription->claims as $claim)
            <div class="rounded-xl border border-stone-200 bg-stone-50/70 p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-stone-200/80 pb-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-stone-200 text-stone-800 uppercase tracking-wider">
                            {{ $claim->claim_type }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ in_array($claim->consensus_status, ['SETTLED_CONSENSUS', 'STRONG_CONSENSUS']) ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-amber-100 text-amber-900 border border-amber-200' }}">
                            {{ str_replace('_', ' ', $claim->consensus_status) }}
                        </span>
                        <span class="text-xs font-mono text-stone-400">Claim #{{ $claim->id }}</span>
                    </div>

                    <form action="{{ route('admin.claims.destroy', $claim->id) }}" method="POST"
                        onsubmit="return confirm('Permanently remove this claim and detach its evidence graph?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold hover:underline">
                            Delete Claim
                        </button>
                    </form>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-stone-900 leading-snug">“{{ $claim->statement }}”</h3>
                    @if($claim->summary_justification)
                    <p class="text-xs text-stone-600 italic mt-1 leading-relaxed">{{ $claim->summary_justification }}</p>
                    @endif
                </div>

                <!-- Attached Evidence Nodes -->
                <div class="space-y-3 pt-2">
                    <h4 class="text-[11px] font-bold text-stone-700 uppercase tracking-wider flex items-center gap-1.5">
                        <span>🔬 Attached Evidence Nodes</span>
                        <span class="text-stone-400 font-normal">({{ $claim->evidence->count() }})</span>
                    </h4>

                    @if($claim->evidence->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($claim->evidence as $evNode)
                        <div class="bg-white rounded-xl border border-stone-200 p-4 text-xs space-y-2.5 shadow-2xs">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $evNode->pivot->relationship_type === 'SUPPORTS' ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">
                                            {{ $evNode->pivot->relationship_type }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-stone-100 text-stone-700 uppercase">
                                            {{ $evNode->pivot->scholarly_weight }} WEIGHT
                                        </span>
                                        <span class="text-[10px] text-stone-400 font-mono">{{ $evNode->evidence_type }}</span>
                                    </div>
                                    <h5 class="font-bold text-stone-900">{{ $evNode->title }}</h5>
                                </div>

                                <form action="{{ route('admin.claims.evidence.detach', [$claim->id, $evNode->id]) }}" method="POST"
                                    onsubmit="return confirm('Detach this evidence node from this claim?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] text-stone-400 hover:text-rose-600 font-medium">
                                        Detach &times;
                                    </button>
                                </form>
                            </div>

                            <p class="text-stone-600 leading-relaxed">{{ $evNode->description }}</p>

                            @if($evNode->sources->isNotEmpty())
                            <div class="pt-2 border-t border-stone-100 space-y-1">
                                <span class="text-[10px] font-bold text-stone-500 uppercase tracking-wider block">Cited Primary Sources:</span>
                                @foreach($evNode->sources as $source)
                                <div class="text-[11px] text-stone-700 flex items-center justify-between gap-2">
                                    <span class="truncate">
                                        📖 <strong>{{ $source->authors }}</strong> ({{ $source->publication_year }}): <em>{{ $source->title }}</em>
                                        @if($source->pivot->specific_pages) [{{ $source->pivot->specific_pages }}] @endif
                                    </span>
                                    <a href="{{ route('admin.sources.edit', $source->id) }}" class="text-amber-800 hover:underline shrink-0 text-[10px] font-semibold">
                                        View Source &rarr;
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-stone-400 italic">No evidence nodes linked to this claim yet.</p>
                    @endif
                </div>

                <!-- Collapsible Attach Evidence Form -->
                <details class="group mt-3 border border-stone-200 bg-white rounded-xl overflow-hidden">
                    <summary class="px-4 py-2.5 text-xs font-bold text-stone-700 cursor-pointer select-none hover:bg-stone-50 flex items-center justify-between">
                        <span>+ Attach / Create Evidence for this Claim</span>
                        <span class="group-open:rotate-180 transition">&darr;</span>
                    </summary>

                    <form action="{{ route('admin.claims.evidence.attach', $claim->id) }}" method="POST" class="p-4 border-t border-stone-200 text-xs space-y-4 bg-stone-50/50">
                        @csrf
                        <input type="hidden" name="evidence_mode" value="new" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Evidence Node Title <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="new_evidence_title" required placeholder="e.g. Epigraphical Squeeze & Comparative Palaeography"
                                    class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                            </div>

                            <div>
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Evidence Type <span class="text-rose-600">*</span>
                                </label>
                                <select name="new_evidence_type" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white">
                                    <option value="EPIGRAPHICAL" selected>Epigraphical Inscription</option>
                                    <option value="PALEOGRAPHIC">Comparative Palaeography</option>
                                    <option value="HISTORICAL_DOCUMENT">Historical Textual Record</option>
                                    <option value="ARCHAEOLOGICAL">Archaeological Stratigraphy</option>
                                    <option value="NUMISMATIC">Numismatic Comparison</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Classification / Confidence <span class="text-rose-600">*</span>
                                </label>
                                <select name="new_classification" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white">
                                    <option value="CONFIRMED">Confirmed</option>
                                    <option value="STRONG_EVIDENCE" selected>Strong Evidence</option>
                                    <option value="PROBABLE">Probable</option>
                                    <option value="UNCERTAIN">Uncertain</option>
                                    <option value="DISPUTED">Disputed</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Evidence Findings & Description <span class="text-rose-600">*</span>
                                </label>
                                <textarea name="new_description" rows="2" required placeholder="Detailed palaeographic ligatures and epigraphical commentary."
                                    class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white"></textarea>
                            </div>

                            <div>
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Relationship to Claim <span class="text-rose-600">*</span>
                                </label>
                                <select name="relationship_type" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white">
                                    <option value="SUPPORTS">SUPPORTS (Validates the claim)</option>
                                    <option value="COMPLICATES">COMPLICATES (Questions or challenges the claim)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Scholarly Weight <span class="text-rose-600">*</span>
                                </label>
                                <select name="scholarly_weight" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white">
                                    <option value="PRIMARY">PRIMARY (Direct physical proof)</option>
                                    <option value="CORROBORATIVE">CORROBORATIVE (Supporting secondary proof)</option>
                                    <option value="CONTEXTUAL">CONTEXTUAL (Indirect background evidence)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Optional Source Citation Linking -->
                        @if($sources->isNotEmpty())
                        <div class="pt-2 border-t border-stone-200 space-y-3">
                            <span class="block font-bold text-stone-700 uppercase tracking-wider">
                                📖 Link Bibliographic Source Citation (Optional)
                            </span>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block font-medium text-stone-600">Select Source</label>
                                    <select name="source_id" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white">
                                        <option value="">-- No Source Citation --</option>
                                        @foreach($sources as $src)
                                        <option value="{{ $src->id }}">{{ $src->authors }} ({{ $src->publication_year }}): {{ $src->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-medium text-stone-600">Specific Page(s)</label>
                                    <input type="text" name="specific_pages" placeholder="e.g. Vol. II, pp. 87–89"
                                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                                </div>

                                <div>
                                    <label class="block font-medium text-stone-600">Direct Scholarly Quotation</label>
                                    <input type="text" name="direct_quotation_or_data" placeholder="e.g. The character of the script conforms exactly to the Besnagar pillar."
                                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="pt-2">
                            <button type="submit"
                                class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                                Attach Evidence to Claim
                            </button>
                        </div>
                    </form>
                </details>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-6 px-4 rounded-xl border border-dashed border-stone-200 bg-stone-50/50">
            <p class="text-xs text-stone-500 font-medium">No scholarly claims registered for this inscription yet.</p>
        </div>
        @endif

        <!-- Register New Claim Form -->
        <div class="mt-6 pt-6 border-t border-stone-100">
            <h3 class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-3">
                + Register New Epigraphical Claim on this Record
            </h3>

            <form action="{{ route('admin.claims.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                @csrf
                <input type="hidden" name="claimable_type" value="inscription" />
                <input type="hidden" name="claimable_id" value="{{ $inscription->id }}" />

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Claim Typology <span class="text-rose-600">*</span>
                    </label>
                    <select name="claim_type" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="CHRONOLOGY">Chronology & Dating</option>
                        <option value="AUTHORSHIP_PATRONAGE">Royal Patronage / Dynasty Link</option>
                        <option value="EPIGRAPHY_READING">Linguistic Reading / Transliteration Debate</option>
                        <option value="RELIGIOUS_AFFILIATION">Sectarian / Religious Affiliation</option>
                        <option value="HISTORICAL_EVENT">Historical Campaign / Synchronism</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Consensus Status / Confidence <span class="text-rose-600">*</span>
                    </label>
                    <select name="consensus_status" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="STRONG_CONSENSUS">Scholarly Consensus / Confirmed</option>
                        <option value="PROBABLE">Probable (Strong Evidence)</option>
                        <option value="SCHOLARLY_DEBATE">Scholarly Debate / Competing Theories</option>
                        <option value="CONTESTED">Contested / Disputed</option>
                        <option value="SPECULATIVE">Speculative / Uncertain</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Claim Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" name="statement" required
                        placeholder="e.g. Provides earliest epigraphical confirmation of Bhagavata / Vaishnava worship by a Greek envoy in Central India."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Basis & Justification Summary
                    </label>
                    <textarea name="summary_justification" rows="2"
                        placeholder="Concise academic rationale explaining the basis of this claim across epigraphy, numismatics, or synchronisms."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed"></textarea>
                </div>

                <div class="sm:col-span-2 pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                        Add Verified Claim to Inscription
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
