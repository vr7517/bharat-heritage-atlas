@extends('layouts.admin')

@section('title', 'Register New Open Dataset')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.datasets.index') }}" class="hover:text-stone-900">Datasets</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold">New Dataset</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Register Open Heritage Dataset
            </h1>
        </div>

        <a href="{{ route('admin.datasets.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
            &larr; Back to Datasets
        </a>
    </div>

    <!-- Registration Form -->
    <form action="{{ route('admin.datasets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- 1. Nomenclature & Typology -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📊 1. Dataset Nomenclature & Typology</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Formal dataset title, file specification, and version designation.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Dataset Title <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        placeholder="e.g. Ancient Indian Monolithic Pillar Coordinates & Epigraphs (GeoJSON)"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-stone-400 font-normal font-sans">(leave blank to auto-generate)</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                        placeholder="e.g. ancient-indian-pillars-geojson"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="dataset_type" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Data Format / Typology <span class="text-rose-600">*</span>
                    </label>
                    <select id="dataset_type" name="dataset_type" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($datasetTypes as $val => $label)
                        <option value="{{ $val }}" @selected(old('dataset_type', 'GEOJSON') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="version" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Release Version <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="version" name="version" required value="{{ old('version', '1.0') }}"
                        placeholder="e.g. 1.0, 2026.1"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="record_count" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Estimated Record Count
                    </label>
                    <input type="number" id="record_count" name="record_count" value="{{ old('record_count') }}"
                        placeholder="e.g. 450"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="release_date" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Official Release / Publication Date
                    </label>
                    <input type="date" id="release_date" name="release_date" value="{{ old('release_date', date('Y-m-d')) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 2. Provenance & License -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🏛️ 2. Institutional Provenance & License</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Originating organization, academic repository URL, and licensing terms.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div>
                    <label for="source_organization" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Originating Organization / Laboratory <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="source_organization" name="source_organization" required value="{{ old('source_organization') }}"
                        placeholder="e.g. Archaeological Survey of India (ASI); BHA Epigraphy Collective"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="source_url" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Institutional Source URL
                    </label>
                    <input type="url" id="source_url" name="source_url" value="{{ old('source_url') }}"
                        placeholder="https://asi.nic.in/open-data"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label for="license" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Open Data License <span class="text-rose-600">*</span>
                    </label>
                    <select id="license" name="license" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($licenses as $val => $label)
                        <option value="{{ $val }}" @selected(old('license', 'Creative Commons CC0 1.0 (Public Domain)') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Documentation & Descriptions -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📜 3. Scope & Technical Documentation</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Abstract, field methodologies, coordinate reference systems (CRS), and schema notes.</p>
            </div>

            <div class="space-y-5 text-xs">
                <div>
                    <label for="description" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Executive Dataset Abstract
                    </label>
                    <textarea id="description" name="description" rows="3"
                        placeholder="High-level summary of what this dataset records, geographic bounds, and temporal epochs covered."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="notes" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Technical Notes & Schema Specification
                    </label>
                    <textarea id="notes" name="notes" rows="4"
                        placeholder="Coordinate reference system (e.g. EPSG:4326 WGS 84), attribute definitions, stratigraphical conventions, and known limitations."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4. Dataset Asset File Upload -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>💾 4. Dataset Asset File Upload</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Direct binary/text file upload for public distribution (GeoJSON, CSV, JSON, ZIP, PDF up to 50MB).</p>
            </div>

            <div>
                <label for="dataset_file" class="block font-bold text-stone-700 uppercase tracking-wider">
                    Upload Dataset File
                </label>
                <input type="file" id="dataset_file" name="dataset_file"
                    class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 cursor-pointer" />
            </div>
        </div>

        <!-- 5. Publishing Status & Featured Promotion -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                <span>🛡️ 5. Publishing & Promotion Controls</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 flex items-start gap-3">
                    <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', true))
                        class="mt-0.5 w-5 h-5 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                    <label for="is_published" class="text-xs text-stone-700 cursor-pointer select-none">
                        <strong class="text-stone-900 block text-sm font-bold">Publish on Public Open Data Portal</strong>
                        When checked, this dataset is available for download on the public atlas.
                    </label>
                </div>

                <div class="p-4 rounded-xl bg-stone-50 border border-stone-200 flex items-start gap-3">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', false))
                        class="mt-0.5 w-5 h-5 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                    <label for="is_featured" class="text-xs text-stone-700 cursor-pointer select-none">
                        <strong class="text-stone-900 block text-sm font-bold">Feature on Portal Hero</strong>
                        Pin this dataset to the top of open data explorers and homepage downloads.
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.datasets.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Register Dataset
            </button>
        </div>

    </form>
</div>
@endsection
