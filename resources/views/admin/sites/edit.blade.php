@extends('layouts.admin')

@section('title', 'Edit ' . $site->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.sites.index') }}" class="hover:text-stone-900">Sites</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold truncate">{{ Str::limit($site->name, 30) }}</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Edit Monument Record
            </h1>
        </div>

        <div class="flex items-center gap-2">
            @if($site->is_published)
            <a href="{{ route('sites.show', $site->slug) }}" target="_blank"
                class="px-3.5 py-1.5 rounded-xl border border-emerald-300 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 transition flex items-center gap-1">
                <span>View Public</span>
                <span>&nearr;</span>
            </a>
            @endif
            <a href="{{ route('admin.sites.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                &larr; Back to Sites
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.sites.update', $site->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- 1. Identity & Classification -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                        <span>🏛️ 1. Basic Monument Identity</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Primary nomenclature and formal typology.</p>
                </div>
                <span class="text-xs font-mono text-stone-400">ID: #{{ $site->id }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Monument / Site Name <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $site->name) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" required value="{{ old('slug', $site->slug) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="site_type" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Site Typology <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="site_type" name="site_type" list="site_types_list" required value="{{ old('site_type', $site->site_type) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                    <datalist id="site_types_list">
                        @foreach($commonSiteTypes as $type)
                        <option value="{{ $type }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="sm:col-span-2">
                    <label for="protection_status" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Protection / Curatorial Status <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="protection_status" name="protection_status" required
                        value="{{ old('protection_status', $site->protection_status) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 2. Chronological Framework -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>⏳ 2. Chronology & Historical Era</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Dual-era dating statement and astronomical bounds.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="dating_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Dating Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement', $site->dating_statement) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="start_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical Start Year
                    </label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year', $site->start_year) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year', $site->end_year) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="primary_period_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Primary Historical Period
                    </label>
                    <select id="primary_period_id" name="primary_period_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- Unspecified Period --</option>
                        @foreach($periods as $period)
                        <option value="{{ $period->id }}" @selected(old('primary_period_id', $site->primary_period_id) == $period->id)>
                            {{ $period->name }} ({{ $period->dating_statement }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="primary_dynasty_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Primary Patron Dynasty
                    </label>
                    <select id="primary_dynasty_id" name="primary_dynasty_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- Unspecified Dynasty --</option>
                        @foreach($dynasties as $dynasty)
                        <option value="{{ $dynasty->id }}" @selected(old('primary_dynasty_id', $site->primary_dynasty_id) == $dynasty->id)>
                            {{ $dynasty->name }} ({{ $dynasty->dating_statement }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_dating_uncertain" name="is_dating_uncertain" value="1"
                        @checked(old('is_dating_uncertain', $site->is_dating_uncertain))
                        class="w-4 h-4 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                    <label for="is_dating_uncertain" class="text-xs text-stone-700 font-semibold cursor-pointer">
                        Mark chronology as uncertain / under ongoing epigraphical debate
                    </label>
                </div>
            </div>
        </div>

        <!-- 3. Location & Coordinates -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📍 3. Geographic Coordinates & Location</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">WGS 84 spatial coordinates for GIS Atlas mapping.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="location_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Attach Existing Registered Location
                    </label>
                    <select id="location_id" name="location_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- Keep Current / Custom Location --</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" @selected(old('location_id', $site->location_id) == $loc->id)>
                            {{ $loc->name }} — {{ $loc->district }}, {{ $loc->state }} ({{ $loc->latitude }}, {{ $loc->longitude }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="state" class="block font-bold text-stone-700">State / Region</label>
                    <input type="text" id="state" name="state" value="{{ old('state', $site->location?->state) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 bg-white" />
                </div>

                <div>
                    <label for="district" class="block font-bold text-stone-700">District</label>
                    <input type="text" id="district" name="district" value="{{ old('district', $site->location?->district) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 bg-white" />
                </div>

                <div>
                    <label for="latitude" class="block font-bold text-stone-700">Latitude (WGS 84 Decimal)</label>
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $site->location?->latitude) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 font-mono bg-white" />
                </div>

                <div>
                    <label for="longitude" class="block font-bold text-stone-700">Longitude (WGS 84 Decimal)</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $site->location?->longitude) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 font-mono bg-white" />
                </div>

                <div>
                    <label for="altitude_meters" class="block font-bold text-stone-700">Elevation (Meters)</label>
                    <input type="number" id="altitude_meters" name="altitude_meters" value="{{ old('altitude_meters', $site->location?->altitude_meters) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 font-mono bg-white" />
                </div>
            </div>
        </div>

        <!-- 4. Monographic Dossier Content -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📜 4. Archaeological & Architectural Dossier</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Authoritative monographic text.</p>
            </div>

            <div class="space-y-5 text-xs">
                <div>
                    <label for="summary" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Executive Summary <span class="text-rose-600">*</span>
                    </label>
                    <textarea id="summary" name="summary" rows="3" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('summary', $site->summary) }}</textarea>
                </div>

                <div>
                    <label for="historical_context" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Historical Context & Discovery Annals
                    </label>
                    <textarea id="historical_context" name="historical_context" rows="5"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('historical_context', $site->historical_context) }}</textarea>
                </div>

                <div>
                    <label for="architectural_description" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Architectural & Stratigraphic Breakdown
                    </label>
                    <textarea id="architectural_description" name="architectural_description" rows="5"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('architectural_description', $site->architectural_description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 5. Primary Cover Photograph Replacement -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                        <span>📸 5. Primary Cover Photograph</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Primary visual asset displayed across atlas cards, maps, and hero banners.</p>
                </div>
                @if($cover = $site->coverImage())
                <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Active Cover Set</span>
                @else
                <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-600 font-semibold">No Cover Image</span>
                @endif
            </div>

            @if($cover = $site->coverImage())
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
                        {{ $site->coverImage() ? 'Replace Cover Image' : 'Upload Cover Image' }} <span class="text-stone-400 font-normal font-sans">(JPEG, PNG, WebP up to 20MB)</span>
                    </label>
                    <input type="file" id="cover_image" name="cover_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 cursor-pointer" />
                </div>

                <div class="sm:col-span-2">
                    <label for="cover_caption" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Image Caption / Description
                    </label>
                    <input type="text" id="cover_caption" name="cover_caption" value="{{ old('cover_caption') }}"
                        placeholder="e.g. South Torana gateway elevation with Ashokan lions capital"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="cover_credit" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Source / Institutional Credit
                    </label>
                    <input type="text" id="cover_credit" name="cover_credit" value="{{ old('cover_credit', 'Archaeological Survey of India') }}"
                        placeholder="e.g. Archaeological Survey of India / Photo Archive"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="cover_license" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Copyright / License
                    </label>
                    <input type="text" id="cover_license" name="cover_license" value="{{ old('cover_license', 'Creative Commons (CC BY-SA 4.0)') }}"
                        placeholder="e.g. Creative Commons (CC BY-SA 4.0) or Public Domain"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 6. Publishing Status -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                <span>🛡️ 6. Publishing & Visibility Control</span>
            </h2>

            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 flex items-start gap-3">
                <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', $site->is_published))
                    class="mt-0.5 w-5 h-5 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                <label for="is_published" class="text-xs text-stone-700 cursor-pointer select-none">
                    <strong class="text-stone-900 block text-sm font-bold">Published on Public Atlas & APIs</strong>
                    Checked means this site is live and public. Uncheck to revert to a private draft.
                </label>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-between gap-3 pt-4">
            <a href="{{ route('admin.sites.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Update Heritage Site Record
            </button>
        </div>

    </form>

    <!-- 7. Media & Visual Archive Gallery -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
        <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🖼️ 7. Visual Archive & Multi-Image Gallery</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Manage photographic plates, architectural elevations, and stratigraphic drawings.</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-700 font-semibold">
                {{ $site->media->count() }} Attached {{ Str::plural('Asset', $site->media->count()) }}
            </span>
        </div>

        @if($site->media->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($site->media as $mediaItem)
            <div class="group relative rounded-xl border border-stone-200 bg-stone-50 overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="relative aspect-video bg-stone-200 overflow-hidden">
                        <img src="{{ $mediaItem->url }}" alt="{{ $mediaItem->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                        @if($mediaItem->is_cover)
                        <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
                            ⭐ Cover
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
                        @if($mediaItem->capture_year)
                        <p class="text-[11px] text-stone-400"><span class="font-semibold">Year:</span> {{ $mediaItem->capture_year }}</p>
                        @endif
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
                    <span class="text-[11px] font-semibold text-emerald-700">Primary Cover</span>
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
        <div class="text-center py-8 px-4 rounded-xl border border-dashed border-stone-200 bg-stone-50/50">
            <p class="text-xs text-stone-500 font-medium">No secondary gallery media uploaded yet. Use the upload form below to link photographic surveys.</p>
        </div>
        @endif

        <!-- Upload New Media Item Form -->
        <div class="mt-6 pt-6 border-t border-stone-100">
            <h3 class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-3">
                + Upload New Asset to Gallery
            </h3>

            <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                @csrf
                <input type="hidden" name="mediable_type" value="site" />
                <input type="hidden" name="mediable_id" value="{{ $site->id }}" />

                <div class="sm:col-span-2">
                    <label for="gallery_image" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Image File <span class="text-rose-600">*</span> <span class="text-stone-400 font-normal font-sans">(JPEG, PNG, WebP up to 20MB)</span>
                    </label>
                    <input type="file" id="gallery_image" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-700 bg-stone-50/50 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 cursor-pointer" />
                </div>

                <div class="sm:col-span-2">
                    <label for="gallery_caption" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Caption / Description <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="gallery_caption" name="caption" required
                        placeholder="e.g. Northern Torana pillar base showing continuous lotus medallion motifs"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="gallery_media_type" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Asset Type <span class="text-rose-600">*</span>
                    </label>
                    <select id="gallery_media_type" name="media_type" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="PHOTOGRAPH">Photograph</option>
                        <option value="ARCHITECTURAL_ELEVATION">Architectural Elevation</option>
                        <option value="SITE_PLAN">Site Plan / Contour Map</option>
                        <option value="INSCRIPTION_RUBBING">Epigraphical Rubbing / Estampage</option>
                        <option value="HISTORICAL_SKETCH">Historical Drawing / Lithograph</option>
                    </select>
                </div>

                <div>
                    <label for="gallery_license" class="block font-bold text-stone-700 uppercase tracking-wider">
                        License <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="gallery_license" name="license" required value="Creative Commons (CC BY-SA 4.0)"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="gallery_source_credit" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Source / Institutional Credit <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="gallery_source_credit" name="source_credit" required value="Archaeological Survey of India"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="gallery_photographer" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Photographer / Draughtsman
                    </label>
                    <input type="text" id="gallery_photographer" name="photographer_or_draughtsman"
                        placeholder="e.g. Archaeological Photo Survey Division"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="gallery_capture_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Capture / Survey Year
                    </label>
                    <input type="number" id="gallery_capture_year" name="capture_year"
                        placeholder="e.g. 2024"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="flex items-center gap-2 pt-5">
                    <input type="checkbox" id="gallery_is_cover" name="is_cover" value="1"
                        class="w-4 h-4 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                    <label for="gallery_is_cover" class="text-xs text-stone-700 font-semibold cursor-pointer">
                        Set as primary cover photo immediately
                    </label>
                </div>

                <div class="sm:col-span-2 pt-2">
                    <button type="submit"
                        class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-stone-800 hover:bg-stone-900 transition">
                        Upload to Visual Archive
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 8. Evidentiary Claims & Academic Consensus Graph -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
        <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>⚖️ 8. Peer-Reviewed Claims & Evidence Network</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Link historical assertions to primary stratigraphy, epigraphy, and bibliographic citations.</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-700 font-semibold">
                {{ $site->claims->count() }} Linked {{ Str::plural('Claim', $site->claims->count()) }}
            </span>
        </div>

        <!-- Existing Claims List -->
        @if($site->claims->isNotEmpty())
        <div class="space-y-6">
            @foreach($site->claims as $claim)
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
                    <h3 class="text-sm font-bold text-stone-900 leading-snug">
                        “{{ $claim->statement }}”
                    </h3>
                    @if($claim->summary_justification)
                    <p class="text-xs text-stone-600 italic mt-1 leading-relaxed">
                        {{ $claim->summary_justification }}
                    </p>
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

                            @if($evNode->stratigraphic_context)
                            <div class="text-[11px] text-stone-500 bg-stone-50 p-2 rounded-lg font-mono">
                                <strong>Stratigraphy:</strong> {{ $evNode->stratigraphic_context }}
                            </div>
                            @endif

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

                        <div class="space-y-2">
                            <span class="block font-bold text-stone-700 uppercase tracking-wider">Evidence Source Option:</span>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="evidence_mode" value="new" checked
                                        onchange="document.getElementById('new-evidence-fields-{{ $claim->id }}').style.display='grid'; document.getElementById('existing-evidence-fields-{{ $claim->id }}').style.display='none';"
                                        class="text-orange-800 focus:ring-amber-500" />
                                    <span>Create New Evidence Node</span>
                                </label>
                                @if($allEvidence->isNotEmpty())
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="evidence_mode" value="existing"
                                        onchange="document.getElementById('new-evidence-fields-{{ $claim->id }}').style.display='none'; document.getElementById('existing-evidence-fields-{{ $claim->id }}').style.display='block';"
                                        class="text-orange-800 focus:ring-amber-500" />
                                    <span>Select Existing Cataloged Evidence</span>
                                </label>
                                @endif
                            </div>
                        </div>

                        <!-- Existing selector -->
                        <div id="existing-evidence-fields-{{ $claim->id }}" style="display: none;" class="space-y-2">
                            <label for="existing_evidence_id_{{ $claim->id }}" class="block font-bold text-stone-700 uppercase tracking-wider">
                                Select Existing Evidence Node
                            </label>
                            <select id="existing_evidence_id_{{ $claim->id }}" name="existing_evidence_id"
                                class="w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white">
                                <option value="">-- Choose Existing Evidence --</option>
                                @foreach($allEvidence as $ev)
                                <option value="{{ $ev->id }}">{{ $ev->title }} ({{ $ev->evidence_type }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- New evidence form -->
                        <div id="new-evidence-fields-{{ $claim->id }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Evidence Node Title <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="new_evidence_title" placeholder="e.g. Sanctum Stratigraphic Floor 1 Burnt-Brick Horizon"
                                    class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                            </div>

                            <div>
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Evidence Type <span class="text-rose-600">*</span>
                                </label>
                                <select name="new_evidence_type" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white">
                                    <option value="ARCHAEOLOGICAL">Archaeological Stratigraphy</option>
                                    <option value="EPIGRAPHICAL">Epigraphical Inscription</option>
                                    <option value="NUMISMATIC">Numismatic (Coinage)</option>
                                    <option value="TEXTUAL">Textual / Literary Annals</option>
                                    <option value="ARCHITECTURAL_JOINERY">Architectural Joinery / Masonry</option>
                                    <option value="ART_HISTORICAL">Art-Historical / Stylistic</option>
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
                                    Evidence Description & Technical Findings <span class="text-rose-600">*</span>
                                </label>
                                <textarea name="new_description" rows="2" placeholder="Detailed physical observations and scientific excavation analysis."
                                    class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white"></textarea>
                            </div>

                            <div>
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Stratigraphic Context / Layer
                                </label>
                                <input type="text" name="new_stratigraphic_context" placeholder="e.g. Period I, Trench A2, Floor Layer 3"
                                    class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                            </div>

                            <div>
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Methodology Applied
                                </label>
                                <input type="text" name="new_methodology_applied" placeholder="e.g. Controlled stratigraphic section cutting; Radiocarbon C-14"
                                    class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                            </div>
                        </div>

                        <!-- Pivot relationship options -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-stone-200">
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
                                    <input type="text" name="specific_pages" placeholder="e.g. pp. 48–52, Plate IV"
                                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                                </div>

                                <div>
                                    <label class="block font-medium text-stone-600">Direct Scholarly Quotation / Data</label>
                                    <input type="text" name="direct_quotation_or_data" placeholder="e.g. Excavation layer yielded NBPW shards in situ."
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
            <p class="text-xs text-stone-500 font-medium">No scholarly claims registered for this monument yet. Add the first verified claim using the form below.</p>
        </div>
        @endif

        <!-- Register New Claim Form -->
        <div class="mt-6 pt-6 border-t border-stone-100">
            <h3 class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-3">
                + Register New Scholarly Claim on this Monument
            </h3>

            <form action="{{ route('admin.claims.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                @csrf
                <input type="hidden" name="claimable_type" value="site" />
                <input type="hidden" name="claimable_id" value="{{ $site->id }}" />

                <div>
                    <label for="new_claim_type" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Claim Typology <span class="text-rose-600">*</span>
                    </label>
                    <select id="new_claim_type" name="claim_type" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="CHRONOLOGY">Chronology & Dating</option>
                        <option value="AUTHORSHIP_PATRONAGE">Authorship & Royal Patronage</option>
                        <option value="RELIGIOUS_AFFILIATION">Religious & Cult Affiliation</option>
                        <option value="ARCHITECTURAL_STYLE">Architectural & Structural Tradition</option>
                        <option value="EXCAVATION_STRATIGRAPHY">Excavation Stratigraphy</option>
                        <option value="PROVENANCE">Provenance & Geochemical Quarry Origin</option>
                    </select>
                </div>

                <div>
                    <label for="new_consensus_status" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Consensus Status / Confidence <span class="text-rose-600">*</span>
                    </label>
                    <select id="new_consensus_status" name="consensus_status" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="STRONG_CONSENSUS">Scholarly Consensus / Confirmed</option>
                        <option value="PROBABLE">Probable (Strong Evidence)</option>
                        <option value="SCHOLARLY_DEBATE">Scholarly Debate / Competing Theories</option>
                        <option value="CONTESTED">Contested / Disputed</option>
                        <option value="SPECULATIVE">Speculative / Uncertain</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="new_claim_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Claim Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="new_claim_statement" name="statement" required
                        placeholder="e.g. The core stupa hemispherical mound was commissioned under the Mauryan emperor Ashoka in the 3rd century BCE."
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label for="new_summary_justification" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Basis & Justification Summary
                    </label>
                    <textarea id="new_summary_justification" name="summary_justification" rows="2"
                        placeholder="Concise academic rationale explaining the basis of this claim across epigraphy, numismatics, or stratigraphy."
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed"></textarea>
                </div>

                <div class="sm:col-span-2 pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                        Add Verified Claim to Monument
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
