@extends('layouts.app')

@section('title', $site->name . ' — ' . __('ui.brand_title'))
@section('meta_description', Str::limit($site->summary, 160))

@section('content')
@php
    $cover = $site->coverImage();
@endphp

<!-- Header Hero & Identity Section -->
<div class="relative bg-gradient-to-b from-[#f5ede0] to-[#faf8f5] py-8 sm:py-12 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('sites.index') }}" class="hover:text-orange-800 transition">{{ __('ui.nav_sites') }}</a>
            <span aria-hidden="true">/</span>
            <span class="text-stone-800 font-bold truncate">{{ $site->name }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-8">
            <div class="space-y-4 max-w-4xl">
                <!-- Badges Row -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200 shadow-2xs">
                        {{ $site->primaryPeriod?->name ?? __('ui.ancient_period') }}
                    </span>
                    @if($site->primaryDynasty)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 border border-stone-200">
                        {{ __('ui.dynasty') }}: {{ $site->primaryDynasty->name }}
                    </span>
                    @endif
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-950 border border-emerald-200">
                        {{ $site->site_type }}
                    </span>
                    @if($site->protection_status)
                    <span class="text-xs font-medium text-stone-600 bg-white px-2.5 py-1 rounded-full border border-stone-200 shadow-2xs">
                        🛡️ {{ $site->protection_status }}
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-stone-900 leading-tight">
                    {{ $site->name }}
                </h1>

                <!-- Dating Statement & Astronomical Bounds -->
                <div class="flex flex-wrap items-center gap-3 text-xs font-mono text-stone-700 pt-1">
                    <span class="inline-flex items-center gap-1.5 font-sans font-semibold text-stone-900 bg-amber-50 px-3 py-1 rounded-lg border border-amber-200 shadow-2xs">
                        <span aria-hidden="true">⏳</span>
                        <span>{{ $site->dating_statement }}</span>
                    </span>
                    <span class="text-stone-500 bg-white px-3 py-1 rounded-lg border border-stone-200 font-sans">
                        {{ __('ui.astronomical_range') }}: <strong class="text-stone-900 font-mono">{{ $site->start_year }}</strong> to <strong class="text-stone-900 font-mono">{{ $site->end_year }}</strong>
                    </span>
                    @if($site->is_dating_uncertain)
                    <span class="text-amber-800 bg-amber-100 px-2.5 py-1 rounded-lg text-[11px] font-sans font-medium">
                        Dating Uncertainty Documented
                    </span>
                    @endif
                </div>
            </div>

            <!-- API Endpoint & Actions Shortcut -->
            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ url('/api/v1/sites/' . $site->slug) }}" target="_blank" 
                   class="min-h-[44px] px-4 py-2 rounded-xl border border-stone-300 bg-white hover:bg-stone-50 text-xs font-bold text-stone-700 shadow-2xs transition flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    <span>API Monograph (JSON)</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Monographic Body -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Hero Image Banner & Visual Plate (if cover exists) -->
    @if($cover)
    <div class="rounded-3xl overflow-hidden bg-stone-900 border border-stone-200 shadow-lg relative group">
        <div class="w-full h-72 sm:h-96 lg:h-[460px] overflow-hidden">
            <img src="{{ $cover->url }}" 
                 alt="{{ $cover->alt_text ?? $site->name }}" 
                 class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500" />
        </div>
        <!-- Image Caption & Provenance Bar -->
        <div class="bg-stone-900/90 backdrop-blur-md text-stone-300 px-6 py-3 border-t border-stone-800 flex flex-wrap items-center justify-between text-xs gap-3">
            <div class="flex items-center gap-2">
                <span class="font-bold text-amber-300 font-mono">PLATE I</span>
                <span class="text-stone-200 font-medium">{{ $cover->caption ?? $site->name . ' — Primary Archaeological View' }}</span>
            </div>
            <div class="flex items-center gap-4 text-stone-400 text-[11px]">
                @if($cover->photographer_or_draughtsman)
                <span>Photo: <strong class="text-stone-200">{{ $cover->photographer_or_draughtsman }}</strong></span>
                @endif
                @if($cover->capture_year)
                <span>Year: {{ $cover->capture_year }}</span>
                @endif
                @if($cover->license)
                <span class="px-2 py-0.5 rounded bg-stone-800 text-stone-300 border border-stone-700">{{ $cover->license }}</span>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Visual Archive & Photographic Plates Section (All Media items) -->
    @if($site->media->isNotEmpty())
    <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs space-y-6" aria-labelledby="visual-archive-heading">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-stone-100 pb-4">
            <div>
                <h2 id="visual-archive-heading" class="text-xl font-bold text-stone-900 flex items-center gap-2">
                    <span aria-hidden="true">📸</span>
                    <span>{{ __('ui.visual_archive') }}</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-200">
                        {{ $site->media->count() }} Plates
                    </span>
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    {{ __('ui.visual_archive_sub') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($site->media as $idx => $item)
            <div class="rounded-2xl overflow-hidden border border-stone-200 bg-stone-50 flex flex-col group hover:shadow-md transition">
                <div class="relative aspect-[4/3] bg-stone-200 overflow-hidden">
                    <img src="{{ $item->url }}" 
                         alt="{{ $item->alt_text ?? ($item->caption ?? $site->name) }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                         loading="lazy" />
                    <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-stone-900/80 text-amber-300 font-mono font-bold text-[10px] backdrop-blur-xs">
                        PLATE {{ $idx + 1 }}
                    </div>
                </div>
                <div class="p-4 flex-grow flex flex-col justify-between space-y-2 text-xs">
                    <div>
                        <p class="font-semibold text-stone-900 leading-snug">
                            {{ $item->caption ?? 'Archival plate record' }}
                        </p>
                    </div>
                    <div class="pt-2 border-t border-stone-200/70 text-[11px] text-stone-500 flex flex-wrap items-center justify-between gap-1">
                        <span>{{ $item->photographer_or_draughtsman ?? $item->source_credit ?? 'Archival Survey' }}</span>
                        @if($item->capture_year)
                        <span class="font-mono">{{ $item->capture_year }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
    
    <!-- Location & In Situ Stratigraphy Bar -->
    @if($site->location)
    <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs" aria-labelledby="stratum-heading">
        <h2 id="stratum-heading" class="text-xs font-bold text-orange-950 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span aria-hidden="true">📍</span>
            <span>{{ __('ui.in_situ_stratum') }}</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 text-xs">
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.state') }}</span>
                <span class="font-bold text-stone-900 text-sm">{{ $site->location->state }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.district') }}</span>
                <span class="font-bold text-stone-900 text-sm">{{ $site->location->district ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.geographic_settlement') }}</span>
                <span class="font-bold text-stone-900 text-sm">{{ $site->location->name }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.coordinates') }}</span>
                <span class="font-mono font-bold text-stone-900 text-xs">{{ $site->location->latitude }}°N, {{ $site->location->longitude }}°E</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.spatial_accuracy') }}</span>
                <span class="font-mono font-bold text-stone-900 text-xs">±{{ $site->location->uncertainty_radius_meters ?? 0 }}m</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.elevation') }}</span>
                <span class="font-bold text-stone-900 text-sm">{{ $site->location->altitude_meters ? $site->location->altitude_meters . 'm ASL' : 'Unrecorded' }}</span>
            </div>
        </div>
    </section>
    @endif

    <!-- Detailed Architectural & Historical Narrative -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column: Summary & Architecture -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Executive Summary -->
            <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs space-y-3">
                <h2 class="text-lg font-bold text-stone-900 flex items-center gap-2">
                    <span aria-hidden="true">📜</span>
                    <span>{{ __('ui.executive_summary') }}</span>
                </h2>
                <p class="text-sm text-stone-700 leading-relaxed">{{ $site->summary }}</p>
            </section>

            <!-- Historical Context -->
            @if($site->historical_context)
            <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs space-y-3">
                <h2 class="text-lg font-bold text-stone-900 flex items-center gap-2">
                    <span aria-hidden="true">🏛️</span>
                    <span>{{ __('ui.historical_context') }}</span>
                </h2>
                <div class="text-sm text-stone-700 leading-relaxed space-y-3">
                    <p>{{ $site->historical_context }}</p>
                </div>
            </section>
            @endif

            <!-- Architectural Description -->
            @if($site->architectural_description)
            <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs space-y-3">
                <h2 class="text-lg font-bold text-stone-900 flex items-center gap-2">
                    <span aria-hidden="true">📐</span>
                    <span>{{ __('ui.architectural_description') }}</span>
                </h2>
                <div class="text-sm text-stone-700 leading-relaxed space-y-3">
                    <p>{{ $site->architectural_description }}</p>
                </div>
            </section>
            @endif
        </div>

        <!-- Sidebar Column: Metadata & Quick Facts -->
        <aside class="space-y-6">
            <div class="bg-stone-50 rounded-3xl border border-stone-200 p-6 space-y-4">
                <h3 class="text-xs font-bold text-stone-900 uppercase tracking-wider">{{ __('ui.classification') }}</h3>
                <dl class="space-y-3 text-xs">
                    <div>
                        <dt class="text-stone-600">{{ __('ui.monument_type') }}</dt>
                        <dd class="font-bold text-stone-900 mt-0.5">{{ $site->site_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-stone-600">{{ __('ui.ancient_period') }}</dt>
                        <dd class="font-bold text-stone-900 mt-0.5">{{ $site->primaryPeriod?->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-stone-600">{{ __('ui.dynasty') }}</dt>
                        <dd class="font-bold text-stone-900 mt-0.5">{{ $site->primaryDynasty?->name ?? 'Unspecified' }}</dd>
                    </div>
                    <div>
                        <dt class="text-stone-600">Canonical Slug</dt>
                        <dd class="font-mono text-stone-700 mt-0.5">{{ $site->slug }}</dd>
                    </div>
                </dl>
            </div>
        </aside>
    </div>

    <!-- Child Objects & Sculptures Section -->
    <section class="space-y-6" aria-labelledby="relics-heading">
        <div class="flex items-center justify-between border-b border-stone-200 pb-3">
            <h2 id="relics-heading" class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span aria-hidden="true">🏺</span>
                <span>{{ __('ui.nav_objects') }} ({{ $site->objects->count() }})</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($site->objects as $obj)
            @php
                $objCover = $obj->coverImage();
            @endphp
            <article class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between">
                @if($objCover)
                <div class="w-full h-40 bg-stone-100 overflow-hidden">
                    <img src="{{ $objCover->url }}" alt="{{ $obj->name }}" class="w-full h-full object-cover" loading="lazy">
                </div>
                @endif
                <div class="p-5 space-y-3 flex-grow">
                    <div class="flex items-center justify-between text-xs">
                        <span class="px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-900 text-[10px]">
                            {{ $obj->object_type }}
                        </span>
                        <span class="font-mono text-stone-500 text-[11px]">
                            {{ $obj->dating_statement }}
                        </span>
                    </div>
                    <h3 class="font-bold text-stone-900 text-base">
                        <a href="{{ route('objects.show', $obj->slug) }}" class="hover:text-orange-800 transition">
                            {{ $obj->name }}
                        </a>
                    </h3>
                    <p class="text-xs text-stone-600 line-clamp-2 leading-relaxed">
                        {{ $obj->description }}
                    </p>
                </div>
                <div class="p-5 pt-3 border-t border-stone-100 bg-stone-50/50 flex items-center justify-between text-xs text-stone-500">
                    <span class="truncate max-w-[60%]">{{ $obj->current_repository ?? __('ui.in_situ') }}</span>
                    <a href="{{ route('objects.show', $obj->slug) }}" class="min-h-[44px] inline-flex items-center font-bold text-orange-800 hover:text-orange-950">
                        {{ __('ui.view_object') }} &rarr;
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-xs text-stone-500 py-6 bg-white rounded-2xl border border-stone-200 text-center">
                No child objects registered for this monument.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Attached Epigraphical Corpus Section -->
    <section class="space-y-6" aria-labelledby="epigraphy-heading">
        <div class="flex items-center justify-between border-b border-stone-200 pb-3">
            <h2 id="epigraphy-heading" class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span aria-hidden="true">📜</span>
                <span>{{ __('ui.nav_inscriptions') }} ({{ $site->inscriptions->count() }})</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($site->inscriptions as $insc)
            @php
                $inscCover = $insc->coverImage();
            @endphp
            <article class="bg-stone-900 text-stone-100 rounded-3xl p-6 sm:p-7 border border-stone-800 shadow-md space-y-4">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-amber-300 text-sm">{{ $insc->title }}</span>
                    <span class="font-mono text-stone-400 text-[11px]">{{ $insc->script }} • {{ $insc->language }}</span>
                </div>

                @if($inscCover)
                <div class="w-full h-36 rounded-xl overflow-hidden bg-stone-950 border border-stone-800">
                    <img src="{{ $inscCover->url }}" alt="{{ $insc->title }}" class="w-full h-full object-cover" loading="lazy">
                </div>
                @endif

                @if($insc->raw_text || $insc->transliteration)
                <div class="bg-stone-950/90 p-3.5 rounded-xl border border-stone-800 font-mono text-xs text-amber-200 overflow-x-auto leading-relaxed">
                    {{ $insc->raw_text ?? $insc->transliteration }}
                </div>
                @endif

                <p class="text-xs text-stone-300 italic leading-relaxed">
                    "{{ $insc->translation }}"
                </p>

                <div class="pt-3 border-t border-stone-800 flex items-center justify-between text-[11px] text-stone-400">
                    <span>⏳ {{ $insc->dating_statement }}</span>
                    <a href="{{ route('inscriptions.show', $insc->slug) }}" class="min-h-[44px] inline-flex items-center font-bold text-amber-400 hover:text-amber-300">
                        {{ __('ui.epigraph_dossier') }} &rarr;
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-2 text-xs text-stone-500 py-6 bg-white rounded-2xl border border-stone-200 text-center">
                No direct inscriptions registered for this complex.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Linked Claims & Peer-Reviewed Consensus -->
    @if($site->claims->isNotEmpty())
    <section class="space-y-6" aria-labelledby="claims-heading">
        <div class="flex items-center justify-between border-b border-stone-200 pb-3">
            <h2 id="claims-heading" class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span aria-hidden="true">⚖️</span>
                <span>{{ __('ui.stat_claims') }} ({{ $site->claims->count() }})</span>
            </h2>
        </div>

        <div class="space-y-4">
            @foreach($site->claims as $claim)
            <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-xs space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="px-2.5 py-1 rounded-md font-mono font-bold text-[11px] {{ in_array($claim->consensus_status, ['SETTLED_CONSENSUS', 'STRONG_CONSENSUS']) ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-amber-100 text-amber-900 border border-amber-200' }}">
                        {{ str_replace('_', ' ', $claim->consensus_status) }}
                    </span>
                    <span class="text-stone-500 font-mono text-[11px]">Claim #{{ $claim->id }}</span>
                </div>
                <h3 class="font-bold text-stone-900 text-base">“{{ $claim->statement }}”</h3>
                @if($claim->summary_justification)
                <p class="text-xs text-stone-600 italic leading-relaxed">{{ $claim->summary_justification }}</p>
                @endif
                <div class="pt-3 border-t border-stone-100 text-right">
                    <a href="{{ route('verification.show', $claim->id) }}" class="min-h-[44px] inline-flex items-center text-xs font-bold text-orange-800 hover:text-orange-950 transition">
                        {{ __('ui.evidence_breakdown') }} &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection
