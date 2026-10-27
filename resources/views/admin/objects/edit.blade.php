@extends('layouts.admin')

@section('title', 'Edit ' . $object->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.objects.index') }}" class="hover:text-stone-900">Artifacts</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold truncate">{{ Str::limit($object->name, 30) }}</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Edit Artifact Record
            </h1>
        </div>

        <div class="flex items-center gap-2">
            @if($object->is_published)
            <a href="{{ route('objects.show', $object->slug) }}" target="_blank"
                class="px-3.5 py-1.5 rounded-xl border border-emerald-300 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 transition flex items-center gap-1">
                <span>View Public</span>
                <span>&nearr;</span>
            </a>
            @endif
            <a href="{{ route('admin.objects.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                &larr; Back to Artifacts
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.objects.update', $object->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- 1. Identity & Typology -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                        <span>🏺 1. Artifact Identity & Typology</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Formal nomenclature and archaeological object classification.</p>
                </div>
                <span class="text-xs font-mono text-stone-400">ID: #{{ $object->id }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Artifact / Sculpture Name <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $object->name) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" required value="{{ old('slug', $object->slug) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="object_type" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Object Typology <span class="text-rose-600">*</span>
                    </label>
                    <select id="object_type" name="object_type" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($objectTypes as $val => $label)
                        <option value="{{ $val }}" @selected(old('object_type', $object->object_type) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="material" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Material & Medium <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="material" name="material" list="materials_list" required value="{{ old('material', $object->material) }}"
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
                    <input type="text" id="dimensions" name="dimensions" value="{{ old('dimensions', $object->dimensions) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="current_repository" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Current Repository / Curatorial Custody <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="current_repository" name="current_repository" required
                        value="{{ old('current_repository', $object->current_repository) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="accession_number" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Accession / Inventory Number
                    </label>
                    <input type="text" id="accession_number" name="accession_number" value="{{ old('accession_number', $object->accession_number) }}"
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
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement', $object->dating_statement) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="start_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical Start Year
                    </label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year', $object->start_year) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year', $object->end_year) }}"
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
                        <option value="{{ $site->id }}" @selected(old('heritage_site_id', $object->heritage_site_id) == $site->id)>{{ $site->name }}</option>
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
                        <option value="{{ $period->id }}" @selected(old('period_id', $object->period_id) == $period->id)>{{ $period->name }} ({{ $period->dating_statement }})</option>
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
                        <option value="{{ $dynasty->id }}" @selected(old('dynasty_id', $object->dynasty_id) == $dynasty->id)>{{ $dynasty->name }}</option>
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
                        <option value="{{ $loc->id }}" @selected(old('location_id', $object->location_id) == $loc->id)>{{ $loc->name }} ({{ $loc->district }}, {{ $loc->state }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_dating_uncertain" name="is_dating_uncertain" value="1"
                        @checked(old('is_dating_uncertain', $object->is_dating_uncertain))
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
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('description', $object->description) }}</textarea>
                </div>

                <div>
                    <label for="iconographic_notes" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Iconographic Breakdown & Attributes
                    </label>
                    <textarea id="iconographic_notes" name="iconographic_notes" rows="4"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('iconographic_notes', $object->iconographic_notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4. Cover Photograph Replacement -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                        <span>📸 4. Primary Cover Photograph</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Primary visual asset for catalog cards and hero views.</p>
                </div>
                @if($cover = $object->coverImage())
                <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Active Cover Set</span>
                @else
                <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-600 font-semibold">No Cover Image</span>
                @endif
            </div>

            @if($cover = $object->coverImage())
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
                        {{ $object->coverImage() ? 'Replace Cover Image' : 'Upload Cover Image' }} <span class="text-stone-400 font-normal font-sans">(JPEG, PNG, WebP up to 20MB)</span>
                    </label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 cursor-pointer" />
                </div>

                <div class="sm:col-span-2">
                    <label for="cover_caption" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Image Caption / Description
                    </label>
                    <input type="text" id="cover_caption" name="cover_caption" value="{{ old('cover_caption') }}"
                        placeholder="e.g. Frontal view showing anthropomorphic relief"
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
                <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', $object->is_published))
                    class="mt-0.5 w-5 h-5 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                <label for="is_published" class="text-xs text-stone-700 cursor-pointer select-none">
                    <strong class="text-stone-900 block text-sm font-bold">Published on Public Atlas & APIs</strong>
                    Checked means this artifact is live and public. Uncheck to revert to a private draft.
                </label>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-between gap-3 pt-4">
            <a href="{{ route('admin.objects.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Update Artifact Record
            </button>
        </div>

    </form>

    <!-- 6. Media & Visual Archive Gallery -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
        <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🖼️ 6. Visual Archive & Multi-Angle Gallery</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Manage photographic plates, profile angles, and macro surface details.</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-700 font-semibold">
                {{ $object->media->count() }} Attached {{ Str::plural('Asset', $object->media->count()) }}
            </span>
        </div>

        @if($object->media->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($object->media as $mediaItem)
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
        <div class="text-center py-6 px-4 rounded-xl border border-dashed border-stone-200 bg-stone-50/50">
            <p class="text-xs text-stone-500 font-medium">No secondary gallery media uploaded yet.</p>
        </div>
        @endif

        <!-- Upload New Media Item Form -->
        <div class="mt-6 pt-6 border-t border-stone-100">
            <h3 class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-3">
                + Upload New Asset to Gallery
            </h3>

            <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                @csrf
                <input type="hidden" name="mediable_type" value="object" />
                <input type="hidden" name="mediable_id" value="{{ $object->id }}" />

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
                        placeholder="e.g. Side profile showing back-slab relief curvature"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Asset Type <span class="text-rose-600">*</span>
                    </label>
                    <select name="media_type" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="PHOTOGRAPH">Photograph</option>
                        <option value="MACRO_DETAIL">Macro Surface Detail</option>
                        <option value="ORTHOGRAPHIC_DRAWING">Orthographic Drawing</option>
                        <option value="HISTORICAL_PLATE">Historical Plate</option>
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
                    <input type="text" name="source_credit" required value="Archaeological Survey of India"
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="flex items-center gap-2 pt-5">
                    <input type="checkbox" id="obj_is_cover" name="is_cover" value="1"
                        class="w-4 h-4 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                    <label for="obj_is_cover" class="text-xs text-stone-700 font-semibold cursor-pointer">
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

    <!-- 7. Evidentiary Claims & Academic Consensus Graph -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
        <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>⚖️ 7. Peer-Reviewed Claims & Evidence Network</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Link iconographic, stylistic, and material claims to verified evidence nodes.</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-stone-100 text-stone-700 font-semibold">
                {{ $object->claims->count() }} Linked {{ Str::plural('Claim', $object->claims->count()) }}
            </span>
        </div>

        @if($object->claims->isNotEmpty())
        <div class="space-y-6">
            @foreach($object->claims as $claim)
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
                                <input type="text" name="new_evidence_title" required placeholder="e.g. Petrographic & Material Thin-Section Analysis"
                                    class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                            </div>

                            <div>
                                <label class="block font-bold text-stone-700 uppercase tracking-wider">
                                    Evidence Type <span class="text-rose-600">*</span>
                                </label>
                                <select name="new_evidence_type" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white">
                                    <option value="ART_HISTORICAL">Art-Historical / Stylistic Comparison</option>
                                    <option value="ARCHAEOLOGICAL">Archaeological Stratigraphy</option>
                                    <option value="EPIGRAPHICAL">Epigraphical Inscription</option>
                                    <option value="NUMISMATIC">Numismatic Comparison</option>
                                    <option value="TEXTUAL">Textual / Canonical Alignment</option>
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
                                <textarea name="new_description" rows="2" required placeholder="Detailed physical observations and scientific iconographical breakdown."
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
                                    <input type="text" name="specific_pages" placeholder="e.g. pp. 65–68, Plate I"
                                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs bg-white" />
                                </div>

                                <div>
                                    <label class="block font-medium text-stone-600">Direct Scholarly Quotation</label>
                                    <input type="text" name="direct_quotation_or_data" placeholder="e.g. Sculptural modeling reflects indigenous Andhra school."
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
            <p class="text-xs text-stone-500 font-medium">No scholarly claims registered for this artifact yet.</p>
        </div>
        @endif

        <!-- Register New Claim Form -->
        <div class="mt-6 pt-6 border-t border-stone-100">
            <h3 class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-3">
                + Register New Scholarly Claim on this Artifact
            </h3>

            <form action="{{ route('admin.claims.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                @csrf
                <input type="hidden" name="claimable_type" value="object" />
                <input type="hidden" name="claimable_id" value="{{ $object->id }}" />

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Claim Typology <span class="text-rose-600">*</span>
                    </label>
                    <select name="claim_type" required
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="CHRONOLOGY">Chronology & Antiquity</option>
                        <option value="ICONOGRAPHY">Iconographic Tradition & Attributes</option>
                        <option value="MATERIAL_PROVENANCE">Material & Quarry Provenance</option>
                        <option value="AUTHORSHIP_PATRONAGE">Guild / Sculptor School Patronage</option>
                        <option value="RITUAL_FUNCTION">Ritual / Votive Function</option>
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
                        placeholder="e.g. Earliest extant anthropomorphic Shaiva sculptural figure in peninsular India."
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Basis & Justification Summary
                    </label>
                    <textarea name="summary_justification" rows="2"
                        placeholder="Concise academic rationale explaining the basis of this claim across epigraphy, numismatics, or stratigraphy."
                        class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed"></textarea>
                </div>

                <div class="sm:col-span-2 pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                        Add Verified Claim to Artifact
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
