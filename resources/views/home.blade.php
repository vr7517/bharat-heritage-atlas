@extends('layouts.app')

@section('title', __('ui.brand_title') . ' — ' . __('ui.brand_tagline'))
@section('meta_description', __('ui.brand_description'))

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-[#f5ede0] via-[#faf8f5] to-[#faf8f5] border-b border-stone-200/80 pt-12 pb-20 lg:pt-20 lg:pb-28 overflow-hidden">
    <!-- Subtle Heritage Dharmachakra Background Watermark -->
    <div class="absolute inset-0 opacity-[0.035] pointer-events-none flex items-center justify-center" aria-hidden="true">
        <svg class="w-[850px] h-[850px] text-stone-900 fill-current" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="0.5" fill="none"/>
            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="0.5" fill="none"/>
            <path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" stroke="currentColor" stroke-width="0.5"/>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <!-- Academic Trust Pill -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-100/90 border border-amber-300 text-amber-950 text-xs font-bold tracking-wide mb-6 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-orange-700 animate-pulse" aria-hidden="true"></span>
                <span>{{ __('ui.hero_pill') }}</span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-stone-900 leading-[1.14]">
                {{ __('ui.hero_title_prefix') }} <span class="text-orange-800 font-serif underline decoration-amber-400 decoration-wavy decoration-2">{{ __('ui.hero_title_highlight') }}</span> {{ __('ui.hero_title_suffix') }}
            </h1>

            <p class="mt-6 text-base sm:text-lg text-stone-700 leading-relaxed font-normal">
                {{ __('ui.hero_subtitle') }}
            </p>

            <!-- Hero Action Buttons (Touch Friendly >=44px) -->
            <div class="mt-8 sm:mt-10 flex flex-wrap items-center gap-3.5">
                <a href="{{ url('/map') }}" class="min-h-[46px] inline-flex items-center gap-2.5 px-6 py-3 rounded-xl bg-orange-800 text-white font-semibold text-sm hover:bg-orange-900 shadow-md shadow-orange-950/15 hover:shadow-lg transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <span>{{ __('ui.explore_map') }}</span>
                </a>
                <a href="{{ url('/timeline') }}" class="min-h-[46px] inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-stone-800 font-semibold text-sm hover:bg-stone-50 border border-stone-300 shadow-2xs hover:shadow-xs transition-all duration-200">
                    <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('ui.chronological_timeline') }}</span>
                </a>
                <a href="{{ url('/verification') }}" class="min-h-[46px] inline-flex items-center gap-2 px-5 py-3 rounded-xl text-stone-700 font-semibold text-sm hover:text-orange-900 transition hover:bg-amber-100/60 rounded-xl">
                    <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>{{ __('ui.evidence_engine') }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Live Corpus Statistics Counter -->
<section class="relative -mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-20" aria-label="Atlas Corpus Statistics">
    <div class="bg-white rounded-2xl shadow-xl shadow-stone-200/60 border border-stone-200 p-6 sm:p-8">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 sm:gap-8 divide-y sm:divide-y-0 sm:divide-x divide-stone-100">
            <!-- Stat 1: Sites -->
            <div class="pt-4 sm:pt-0 sm:px-4 first:pt-0 first:px-0">
                <div class="flex items-center gap-2 text-stone-600 text-xs font-bold uppercase tracking-wider mb-1">
                    <span aria-hidden="true">🏛️</span>
                    <span>{{ __('ui.stat_monuments') }}</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-sites-count">
                    {{ $stats['sites_count'] }}
                </div>
                <div class="text-xs text-stone-600 mt-1">{{ __('ui.stat_monuments_sub') }}</div>
            </div>

            <!-- Stat 2: Objects -->
            <div class="pt-4 sm:pt-0 sm:px-4">
                <div class="flex items-center gap-2 text-stone-600 text-xs font-bold uppercase tracking-wider mb-1">
                    <span aria-hidden="true">🏺</span>
                    <span>{{ __('ui.stat_objects') }}</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-objects-count">
                    {{ $stats['objects_count'] }}
                </div>
                <div class="text-xs text-stone-600 mt-1">{{ __('ui.stat_objects_sub') }}</div>
            </div>

            <!-- Stat 3: Epigraphs -->
            <div class="pt-4 sm:pt-0 sm:px-4">
                <div class="flex items-center gap-2 text-stone-600 text-xs font-bold uppercase tracking-wider mb-1">
                    <span aria-hidden="true">📜</span>
                    <span>{{ __('ui.stat_epigraphs') }}</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-inscriptions-count">
                    {{ $stats['inscriptions_count'] }}
                </div>
                <div class="text-xs text-stone-600 mt-1">{{ __('ui.stat_epigraphs_sub') }}</div>
            </div>

            <!-- Stat 4: Sources -->
            <div class="pt-4 sm:pt-0 sm:px-4">
                <div class="flex items-center gap-2 text-stone-600 text-xs font-bold uppercase tracking-wider mb-1">
                    <span aria-hidden="true">📚</span>
                    <span>{{ __('ui.stat_sources') }}</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-sources-count">
                    {{ $stats['sources_count'] }}
                </div>
                <div class="text-xs text-stone-600 mt-1">{{ __('ui.stat_sources_sub') }}</div>
            </div>

            <!-- Stat 5: Claims -->
            <div class="pt-4 sm:pt-0 sm:px-4">
                <div class="flex items-center gap-2 text-stone-600 text-xs font-bold uppercase tracking-wider mb-1">
                    <span aria-hidden="true">⚖️</span>
                    <span>{{ __('ui.stat_claims') }}</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-claims-count">
                    {{ $stats['claims_count'] }}
                </div>
                <div class="text-xs text-stone-600 mt-1">{{ __('ui.stat_claims_sub') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Monuments & In Situ Complexes -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-orange-800 uppercase tracking-wider mb-2">
                <span>{{ __('ui.featured_dossiers') }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-stone-900 tracking-tight">
                {{ __('ui.verified_sites') }}
            </h2>
            <p class="text-sm text-stone-600 mt-1">
                {{ __('ui.verified_sites_sub') }}
            </p>
        </div>
        <a href="{{ url('/sites') }}" class="min-h-[44px] inline-flex items-center text-sm font-bold text-orange-800 hover:text-orange-950 gap-1.5 group">
            <span>{{ __('ui.browse_all_sites') }}</span>
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($featuredSites as $site)
        @php
            $cover = $site->coverImage();
        @endphp
        <article class="bg-white rounded-2xl border border-stone-200/90 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
            
            <!-- Monument Image / Archival Fallback -->
            <div class="relative w-full aspect-[16/10] bg-stone-100 overflow-hidden border-b border-stone-100">
                @if($cover)
                    <img src="{{ $cover->url }}" 
                         alt="{{ $cover->alt_text ?? $site->name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                         loading="lazy">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-amber-100/60 via-stone-100 to-amber-50 flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-12 h-12 rounded-full bg-amber-800/10 text-amber-800 flex items-center justify-center mb-2 shadow-2xs">
                            <svg class="w-6 h-6 stroke-current fill-none stroke-[1.6]" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M12 3v6M12 15v6M3 12h6M15 12h6"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-stone-800 font-serif">{{ $site->name }}</span>
                        <span class="text-[11px] text-stone-600 mt-1">In Situ Archaeological Complex</span>
                    </div>
                @endif

                <!-- Overlay Badges -->
                <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-stone-900/85 backdrop-blur-md text-amber-300 border border-amber-400/30 shadow-xs">
                        {{ $site->primaryPeriod?->name ?? __('ui.ancient_period') }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/95 backdrop-blur-md text-stone-800 shadow-xs border border-stone-200">
                        {{ $site->location?->state ?? 'India' }}
                    </span>
                </div>
            </div>

            <!-- Card Header & Badge -->
            <div class="p-6 pb-3">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    @if($site->primaryDynasty)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-stone-100 text-stone-700">
                        {{ __('ui.dynasty') }}: {{ $site->primaryDynasty->name }}
                    </span>
                    @endif
                    <span class="text-[11px] text-stone-600 font-mono ml-auto">
                        📍 {{ $site->site_type }}
                    </span>
                </div>

                <h3 class="text-xl font-bold text-stone-900 group-hover:text-orange-800 transition">
                    <a href="{{ url('/sites/' . $site->slug) }}">
                        {{ $site->name }}
                    </a>
                </h3>

                <!-- Dating Statement Badge -->
                <div class="mt-2.5 inline-flex items-center gap-1.5 text-xs text-stone-600 font-medium bg-amber-50/80 px-2.5 py-1 rounded-lg border border-amber-200/70">
                    <svg class="w-3.5 h-3.5 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $site->dating_statement }}</span>
                </div>
            </div>

            <!-- Summary Body -->
            <div class="px-6 py-2 flex-grow">
                <p class="text-xs text-stone-600 leading-relaxed line-clamp-3">
                    {{ $site->summary }}
                </p>
            </div>

            <!-- Card Footer -->
            <div class="p-6 pt-4 border-t border-stone-100 bg-stone-50/60 flex items-center justify-between text-xs text-stone-500">
                <div class="flex items-center gap-2 font-medium">
                    <span>🏺 {{ $site->objects->count() }} {{ __('ui.artifacts_count') }}</span>
                    <span>•</span>
                    <span>📜 {{ $site->inscriptions->count() }}</span>
                </div>
                <a href="{{ url('/sites/' . $site->slug) }}" class="min-h-[44px] inline-flex items-center font-bold text-orange-800 hover:text-orange-950 transition gap-1">
                    <span>{{ __('ui.details') }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 text-stone-500 text-sm bg-white rounded-2xl border border-stone-200">
            {{ __('ui.no_records_found') }}.
        </div>
        @endforelse
    </div>
</section>

<!-- Epigraphy Facsimile Corpus Feature Section -->
<section class="bg-stone-900 text-stone-100 py-20 border-y border-stone-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Info -->
            <div class="lg:col-span-5 space-y-5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold uppercase tracking-wider">
                    📜 {{ __('ui.epigraphical_corpus') }}
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white leading-tight">
                    {{ __('ui.epigraphy_title') }}
                </h2>
                <p class="text-sm text-stone-300 leading-relaxed">
                    {{ __('ui.epigraphy_sub') }}
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ url('/inscriptions') }}" class="min-h-[44px] inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 font-bold text-xs tracking-wide transition shadow-md">
                        {{ __('ui.explore_epigraphy') }}
                    </a>
                </div>
            </div>

            <!-- Right Sample Inscription Cards -->
            <div class="lg:col-span-7 space-y-4">
                @foreach($sampleInscriptions as $insc)
                <div class="bg-stone-800/90 rounded-2xl p-5 border border-stone-700/80 space-y-3 shadow-md">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-amber-300 text-sm">{{ $insc->title }}</span>
                        <span class="text-stone-400 font-mono text-[11px] px-2 py-0.5 rounded bg-stone-900/60 border border-stone-700">{{ $insc->script }} • {{ $insc->language }}</span>
                    </div>

                    <!-- Facsimile / Transliteration Snippet -->
                    <div class="bg-stone-950/80 p-3.5 rounded-xl border border-stone-800 font-mono text-xs text-amber-200/90 overflow-x-auto leading-relaxed">
                        {{ \Illuminate\Support\Str::limit($insc->raw_text ?? $insc->transliteration, 140) }}
                    </div>

                    <!-- Translation Preview -->
                    <p class="text-xs text-stone-300 italic leading-relaxed">
                        "{{ \Illuminate\Support\Str::limit($insc->translation, 150) }}"
                    </p>

                    <div class="flex items-center justify-between text-[11px] text-stone-400 pt-2 border-t border-stone-800">
                        <span>⏳ {{ $insc->dating_statement }}</span>
                        <a href="{{ route('inscriptions.show', $insc->slug) }}" class="min-h-[44px] inline-flex items-center font-bold text-amber-400 hover:text-amber-300">
                            {{ __('ui.epigraph_dossier') }} &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Methodological Foundations -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <div class="text-xs font-bold text-orange-800 uppercase tracking-wider mb-2">Core Methodology</div>
        <h2 class="text-2xl sm:text-3xl font-bold text-stone-900 tracking-tight">
            How Bharat Heritage Atlas Guarantees Integrity
        </h2>
        <p class="text-sm text-stone-600 mt-2">
            Eliminating historical mythologization and AI hallucination through structured scientific principles.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Pillar 1 -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/90 shadow-2xs space-y-3">
            <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-800 flex items-center justify-center font-bold text-lg">
                ⏳
            </div>
            <h3 class="font-bold text-stone-900 text-base">Dual Historical Dating</h3>
            <p class="text-xs text-stone-600 leading-relaxed">
                Reconciles continuous astronomical integer bounds (e.g. <code class="font-mono text-stone-800">-300</code> to <code class="font-mono text-stone-800">100</code>) with paleographical human statements (<em>c. 3rd Cent. BCE</em>), allowing seamless timeline math without distorting nuanced dates.
            </p>
        </div>

        <!-- Pillar 2 -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/90 shadow-2xs space-y-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg">
                🔍
            </div>
            <h3 class="font-bold text-stone-900 text-base">Multi-Tiered Evidence</h3>
            <p class="text-xs text-stone-600 leading-relaxed">
                Archaeological strata, inscriptions, and radiocarbon data exist as distinct evidence nodes linked to atomized claims. Disputed claims are explicitly flagged as <span class="font-mono text-amber-800 font-medium">ONGOING_DEBATE</span>.
            </p>
        </div>

        <!-- Pillar 3 -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/90 shadow-2xs space-y-3">
            <div class="w-10 h-10 rounded-xl bg-stone-100 text-stone-800 flex items-center justify-center font-bold text-lg">
                🛡️
            </div>
            <h3 class="font-bold text-stone-900 text-base">Zero Fabrication Policy</h3>
            <p class="text-xs text-stone-600 leading-relaxed">
                Absolute zero tolerance for fictional citations or speculative attributions. Every data record links directly to published volumes from the Archaeological Survey of India (ASI) or equivalent peer-reviewed journals.
            </p>
        </div>

        <!-- Pillar 4 -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/90 shadow-2xs space-y-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg">
                ⚡
            </div>
            <h3 class="font-bold text-stone-900 text-base">Open Science API</h3>
            <p class="text-xs text-stone-600 leading-relaxed">
                Fully documented with an OpenAPI 3.1 specification, delivering RFC 7946 GeoJSON geospatial layers, astronomical timeline streams, and JSON REST envelopes for public academic reuse.
            </p>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="bg-gradient-to-r from-stone-900 via-stone-850 to-stone-900 rounded-3xl p-8 sm:p-12 border border-stone-800 text-white flex flex-col md:flex-row items-center justify-between gap-8 shadow-xl">
        <div class="max-w-xl space-y-3 text-center md:text-left">
            <h3 class="text-2xl sm:text-3xl font-bold tracking-tight">
                Empowering Researchers, Students & Historians
            </h3>
            <p class="text-stone-300 text-sm leading-relaxed">
                Explore the interactive web map, filter monuments by astronomical centuries, and inspect primary epigraphical facsimiles with full evidentiary confidence.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-4 shrink-0">
            <a href="{{ url('/map') }}" class="min-h-[46px] inline-flex items-center px-6 py-3 rounded-xl bg-orange-700 hover:bg-orange-800 text-white font-semibold text-sm transition shadow-md">
                Launch Map Atlas
            </a>
            <a href="{{ url('/api/v1/sites') }}" class="min-h-[46px] inline-flex items-center px-6 py-3 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-200 border border-stone-700 font-semibold text-sm transition">
                Explore REST API v1
            </a>
        </div>
    </div>
</section>
@endsection
