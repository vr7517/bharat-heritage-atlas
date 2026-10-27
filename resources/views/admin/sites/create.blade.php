@extends('layouts.admin')

@section('title', 'Register New Heritage Site')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.sites.index') }}" class="hover:text-stone-900">Sites</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold">New Site</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Register New Heritage Monument
            </h1>
        </div>

        <a href="{{ route('admin.sites.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
            &larr; Back to Sites
        </a>
    </div>

    <!-- Creation Form -->
    <form action="{{ route('admin.sites.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- 1. Identity & Classification -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🏛️ 1. Basic Monument Identity</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Primary nomenclature and formal typology.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Monument / Site Name <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        placeholder="e.g. Sanchi Buddhist Monument Complex & Great Stupa Compound"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-stone-400 font-normal font-sans">(leave blank to auto-generate)</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                        placeholder="e.g. sanchi-archaeological-complex"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="site_type" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Site Typology <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="site_type" name="site_type" list="site_types_list" required value="{{ old('site_type') }}"
                        placeholder="e.g. Buddhist Stupa Complex"
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
                        value="{{ old('protection_status', 'ASI Protected Monument of National Importance') }}"
                        placeholder="e.g. ASI Protected Monument of National Importance; UNESCO World Heritage Site"
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
                <p class="text-xs text-stone-500 mt-0.5">Dual-era dating statement and astronomical year bounds.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="dating_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Dating Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement') }}"
                        placeholder="e.g. c. 3rd century BCE to 12th century CE (Continuous)"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="start_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical Start Year <span class="text-stone-400 font-normal font-sans">(Negative for BCE, e.g. -250 for 250 BCE)</span>
                    </label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year') }}"
                        placeholder="-250"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year <span class="text-stone-400 font-normal font-sans">(e.g. 1200 for 1200 CE)</span>
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year') }}"
                        placeholder="1200"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="primary_period_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Primary Historical Period
                    </label>
                    <select id="primary_period_id" name="primary_period_id" class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 bg-white">
                        <option value="">Select Historical Period</option>
                        @foreach($periods as $period)
                        <option value="{{ $period->id }}" @selected(old('primary_period_id') == $period->id)>
                            {{ $period->name }} ({{ $period->start_year }} to {{ $period->end_year }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="primary_dynasty_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Associated Dynasty / Hegemony
                    </label>
                    <select id="primary_dynasty_id" name="primary_dynasty_id" class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 bg-white">
                        <option value="">Select Associated Dynasty</option>
                        @foreach($dynasties as $dynasty)
                        <option value="{{ $dynasty->id }}" @selected(old('primary_dynasty_id') == $dynasty->id)>
                            {{ $dynasty->name }} ({{ $dynasty->region }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_dating_uncertain" value="1" @checked(old('is_dating_uncertain'))
                            class="w-4 h-4 rounded border-stone-300 text-orange-800 focus:ring-amber-500" />
                        <span class="font-medium text-stone-700">Dating exhibits significant scholarly debate or stratigraphic uncertainty</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 3. Geographic & Stratigraphic Location -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📍 3. Geospatial Coordinates & Location</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Places the monument on the interactive map atlas and GIS engine.</p>
            </div>

            <div class="space-y-4">
                @if($locations->isNotEmpty())
                <div>
                    <label for="location_id" class="block font-bold text-stone-700 uppercase tracking-wider text-xs">
                        Attach Existing Location
                    </label>
                    <select id="location_id" name="location_id" class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 bg-white">
                        <option value="">Select Existing Verified Location...</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" @selected(old('location_id') == $loc->id)>
                            {{ $loc->name }} ({{ $loc->state }}, {{ $loc->district }}) — [{{ $loc->latitude }}, {{ $loc->longitude }}]
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-stone-200"></div>
                    <span class="flex-shrink mx-4 text-xs uppercase font-mono text-stone-400">Or Register New Location Coordinates</span>
                    <div class="flex-grow border-t border-stone-200"></div>
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label for="new_location_name" class="block font-bold text-stone-700">Location / Village Name</label>
                        <input type="text" id="new_location_name" name="new_location_name" value="{{ old('new_location_name') }}"
                            placeholder="e.g. Sanchi Kanakheda"
                            class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 bg-stone-50/50" />
                    </div>

                    <div>
                        <label for="state" class="block font-bold text-stone-700">State / Region</label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}"
                            placeholder="e.g. Madhya Pradesh"
                            class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 bg-stone-50/50" />
                    </div>

                    <div>
                        <label for="district" class="block font-bold text-stone-700">District</label>
                        <input type="text" id="district" name="district" value="{{ old('district') }}"
                            placeholder="e.g. Raisen"
                            class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 bg-stone-50/50" />
                    </div>

                    <div>
                        <label for="latitude" class="block font-bold text-stone-700">Latitude (WGS 84 Decimal)</label>
                        <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude') }}"
                            placeholder="23.4794"
                            class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 font-mono bg-stone-50/50" />
                    </div>

                    <div>
                        <label for="longitude" class="block font-bold text-stone-700">Longitude (WGS 84 Decimal)</label>
                        <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude') }}"
                            placeholder="77.7397"
                            class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 font-mono bg-stone-50/50" />
                    </div>

                    <div>
                        <label for="altitude_meters" class="block font-bold text-stone-700">Elevation (Meters)</label>
                        <input type="number" id="altitude_meters" name="altitude_meters" value="{{ old('altitude_meters') }}"
                            placeholder="434"
                            class="mt-1.5 w-full rounded-xl border border-stone-300 px-3 py-2 font-mono bg-stone-50/50" />
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Monographic Dossier Content -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📜 4. Archaeological & Architectural Dossier</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Comprehensive descriptions documented under academic standards.</p>
            </div>

            <div class="space-y-5 text-xs">
                <div>
                    <label for="summary" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Executive Summary <span class="text-rose-600">*</span>
                    </label>
                    <textarea id="summary" name="summary" rows="3" required
                        placeholder="Concise 2–3 sentence overview of the site, its discovery, and significance."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('summary') }}</textarea>
                </div>

                <div>
                    <label for="historical_context" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Historical Context & Discovery Annals
                    </label>
                    <textarea id="historical_context" name="historical_context" rows="5"
                        placeholder="Detailed historical background, patrons, foreign embassies, or excavation chronologies."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('historical_context') }}</textarea>
                </div>

                <div>
                    <label for="architectural_description" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Architectural & Stratigraphic Breakdown
                    </label>
                    <textarea id="architectural_description" name="architectural_description" rows="5"
                        placeholder="Structural layout, stone carpentry joinery, materials (dolerite, Chunar sandstone), gateways, and elevations."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('architectural_description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 5. Visual Archive & Cover Photograph -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📸 5. Primary Cover Photograph & Visual Asset</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Upload initial high-resolution monument photography for the Atlas hero view.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="cover_image" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Cover Image File <span class="text-stone-400 font-normal font-sans">(JPEG, PNG, WebP up to 20MB)</span>
                    </label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
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
                <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', true))
                    class="mt-0.5 w-5 h-5 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                <label for="is_published" class="text-xs text-stone-700 cursor-pointer select-none">
                    <strong class="text-stone-900 block text-sm font-bold">Publish Immediately</strong>
                    When checked, this heritage site will immediately become visible on the Public Map Atlas, Timeline Scrubber, Monuments Catalog, and REST APIs. Uncheck to save privately as a draft for editorial review.
                </label>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.sites.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Save & Register Heritage Site
            </button>
        </div>

    </form>
</div>
@endsection
