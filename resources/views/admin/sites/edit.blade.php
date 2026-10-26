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
    <form action="{{ route('admin.sites.update', $site->id) }}" method="POST" class="space-y-8">
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
                <p class="text-xs text-stone-500 mt-0.5">Dual-era dating statement and astronomical year bounds.</p>
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
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year', $site->end_year) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="primary_period_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Primary Historical Period
                    </label>
                    <select id="primary_period_id" name="primary_period_id" class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 bg-white">
                        <option value="">Select Historical Period</option>
                        @foreach($periods as $period)
                        <option value="{{ $period->id }}" @selected(old('primary_period_id', $site->primary_period_id) == $period->id)>
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
                        <option value="{{ $dynasty->id }}" @selected(old('primary_dynasty_id', $site->primary_dynasty_id) == $dynasty->id)>
                            {{ $dynasty->name }} ({{ $dynasty->region }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_dating_uncertain" value="1" @checked(old('is_dating_uncertain', $site->is_dating_uncertain))
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
                <p class="text-xs text-stone-500 mt-0.5">Physical location on the interactive web map atlas.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
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
                    <label for="taluk_tehsil" class="block font-bold text-stone-700">Taluk / Tehsil</label>
                    <input type="text" id="taluk_tehsil" name="taluk_tehsil" value="{{ old('taluk_tehsil', $site->location?->taluk_tehsil) }}"
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

        <!-- 5. Publishing Status -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                <span>🛡️ 5. Publishing & Visibility Control</span>
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
</div>
@endsection
