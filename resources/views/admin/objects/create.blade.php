@extends('layouts.admin')

@section('title', 'Register New Artifact')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.objects.index') }}" class="hover:text-stone-900">Artifacts</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold">New Artifact</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Register New Artifact or Sculpture
            </h1>
        </div>

        <a href="{{ route('admin.objects.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
            &larr; Back to Artifacts
        </a>
    </div>

    <!-- Creation Form -->
    <form action="{{ route('admin.objects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- 1. Identity & Typology -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🏺 1. Artifact Identity & Typology</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Formal nomenclature and archaeological object classification.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Artifact / Sculpture Name <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        placeholder="e.g. Anthropomorphic Parasurameshwara Linga of Gudimallam"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-stone-400 font-normal font-sans">(leave blank to auto-generate)</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                        placeholder="e.g. gudimallam-linga"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="object_type" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Object Typology <span class="text-rose-600">*</span>
                    </label>
                    <select id="object_type" name="object_type" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($objectTypes as $val => $label)
                        <option value="{{ $val }}" @selected(old('object_type') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="material" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Material & Medium <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="material" name="material" list="materials_list" required value="{{ old('material') }}"
                        placeholder="e.g. Hard igneous stone / Dolerite"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                    <datalist id="materials_list">
                        @foreach($commonMaterials as $mat)
                        <option value="{{ $mat }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label for="dimensions" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Physical Dimensions
                    </label>
                    <input type="text" id="dimensions" name="dimensions" value="{{ old('dimensions') }}"
                        placeholder="e.g. Height: 1.5 m (above floor); Thickness: 30 cm"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="current_repository" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Current Repository / Curatorial Custody <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="current_repository" name="current_repository" required
                        value="{{ old('current_repository', 'In Situ') }}"
                        placeholder="e.g. In Situ — Parasurameshwara Temple Sanctum; National Museum, New Delhi"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="accession_number" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Accession / Inventory Number
                    </label>
                    <input type="text" id="accession_number" name="accession_number" value="{{ old('accession_number') }}"
                        placeholder="e.g. ASI-M-1974-12"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 2. Chronological & Cultural Context -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>⏳ 2. Chronology & Provenance</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Historical period, dating bounds, and association with monuments.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="dating_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Dating Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement') }}"
                        placeholder="e.g. c. 3rd–2nd century BCE (Phase I in situ foundation)"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="start_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical Start Year
                    </label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year') }}"
                        placeholder="-300"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year') }}"
                        placeholder="-100"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="heritage_site_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Associated Heritage Monument / Site
                    </label>
                    <select id="heritage_site_id" name="heritage_site_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- No Direct Site Association (Independent Relic) --</option>
                        @foreach($sites as $site)
                        <option value="{{ $site->id }}" @selected(old('heritage_site_id') == $site->id)>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="period_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Historical Period
                    </label>
                    <select id="period_id" name="period_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- Unspecified Period --</option>
                        @foreach($periods as $period)
                        <option value="{{ $period->id }}" @selected(old('period_id') == $period->id)>{{ $period->name }} ({{ $period->dating_statement }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="dynasty_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Patron Dynasty
                    </label>
                    <select id="dynasty_id" name="dynasty_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- Unspecified Dynasty --</option>
                        @foreach($dynasties as $dynasty)
                        <option value="{{ $dynasty->id }}" @selected(old('dynasty_id') == $dynasty->id)>{{ $dynasty->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="location_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Findspot Geographic Coordinates
                    </label>
                    <select id="location_id" name="location_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- Inherit from Site or Unspecified --</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" @selected(old('location_id') == $loc->id)>{{ $loc->name }} ({{ $loc->district }}, {{ $loc->state }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_dating_uncertain" name="is_dating_uncertain" value="1" @checked(old('is_dating_uncertain'))
                        class="w-4 h-4 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                    <label for="is_dating_uncertain" class="text-xs text-stone-700 font-semibold cursor-pointer">
                        Mark chronology as uncertain / under ongoing scholarly debate
                    </label>
                </div>
            </div>
        </div>

        <!-- 3. Monographic Descriptions -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📜 3. Curatorial Description & Iconography</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Authoritative academic descriptions and iconographical breakdown.</p>
            </div>

            <div class="space-y-5 text-xs">
                <div>
                    <label for="description" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Curatorial Description <span class="text-rose-600">*</span>
                    </label>
                    <textarea id="description" name="description" rows="3" required
                        placeholder="Concise overview of the artifact, discovery layer, and physical attributes."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="iconographic_notes" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Iconographic Breakdown & Attributes
                    </label>
                    <textarea id="iconographic_notes" name="iconographic_notes" rows="4"
                        placeholder="Detailed attributes: mudras, attributes held, facial features, drapery style, jewelry, or textural details."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('iconographic_notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4. Cover Photograph Upload -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📸 4. Primary Photograph & Visual Asset</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">High-resolution photographic plate for the relic card and monograph view.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="cover_image" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Cover Image File <span class="text-stone-400 font-normal font-sans">(JPEG, PNG, WebP up to 20MB)</span>
                    </label>
                    <input type="file" id="cover_image" name="cover_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 cursor-pointer" />
                </div>

                <div class="sm:col-span-2">
                    <label for="cover_caption" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Image Caption / Description
                    </label>
                    <input type="text" id="cover_caption" name="cover_caption" value="{{ old('cover_caption') }}"
                        placeholder="e.g. Frontal elevation showing anthropomorphic standing figure carved in high relief"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="cover_credit" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Source / Institutional Credit
                    </label>
                    <input type="text" id="cover_credit" name="cover_credit" value="{{ old('cover_credit', 'Archaeological Survey of India') }}"
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
                <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', true))
                    class="mt-0.5 w-5 h-5 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                <label for="is_published" class="text-xs text-stone-700 cursor-pointer select-none">
                    <strong class="text-stone-900 block text-sm font-bold">Publish Immediately on Public Atlas</strong>
                    When checked, this artifact will immediately be visible on public artifact catalogs, linked monuments, and APIs. Uncheck to save as a draft.
                </label>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.objects.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Register Artifact Record
            </button>
        </div>

    </form>
</div>
@endsection
