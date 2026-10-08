@extends('layouts.app')

@section('title', 'Bharat Heritage Atlas — Evidence-Linked Indian Heritage Platform')
@section('meta_description', 'A serious, evidence-linked digital platform documenting ancient Indian monuments, Brahmi epigraphical corpuses, and relics backed by primary archaeological excavation strata.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-[#f5f0e6] via-[#faf8f5] to-[#faf8f5] border-b border-stone-200/80 pt-12 pb-20 lg:pt-20 lg:pb-28 overflow-hidden">
    <!-- Subtle Heritage Background Accents -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none flex items-center justify-center">
        <svg class="w-[800px] h-[800px] text-stone-900 fill-current" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="0.5" fill="none"/>
            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="0.5" fill="none"/>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <!-- Academic Trust Pill -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100/80 border border-amber-300/60 text-amber-900 text-xs font-semibold uppercase tracking-wider mb-6 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                Evidence-Linked Cultural Heritage Platform
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-stone-900 leading-[1.12]">
                Unveiling Ancient India Through <span class="text-orange-800 font-serif underline decoration-amber-400 decoration-wavy decoration-2">Verified Epigraphy</span> & Archaeological Science
            </h1>

            <p class="mt-6 text-base sm:text-lg text-stone-600 leading-relaxed font-normal">
                Every monument, sculpture, and epigraphical record in the <strong>Bharat Heritage Atlas</strong> is strictly anchored in primary archaeological excavation reports, in situ stratum verifications, and peer-reviewed academic consensus — with zero AI-generated fabrication.
            </p>

            <!-- Hero Action Buttons -->
            <div class="mt-8 sm:mt-10 flex flex-wrap items-center gap-4">
                <a href="{{ url('/map') }}" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-orange-800 text-white font-medium text-sm hover:bg-orange-900 shadow-sm hover:shadow transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Explore Map Atlas
                </a>
                <a href="{{ url('/timeline') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-stone-100 text-stone-800 font-medium text-sm hover:bg-stone-200 border border-stone-300 shadow-2xs transition-all duration-200">
                    <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Chronological Timeline
                </a>
                <a href="{{ url('/verification') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl text-stone-700 font-medium text-sm hover:text-orange-900 transition">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Evidence Engine
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Live Corpus Statistics Counter -->
<section class="relative -mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-20">
    <div class="bg-white rounded-2xl shadow-lg border border-stone-200/90 p-6 sm:p-8">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 sm:gap-8 divide-y sm:divide-y-0 sm:divide-x divide-stone-100">
            <!-- Stat 1: Sites -->
            <div class="pt-4 sm:pt-0 sm:px-4 first:pt-0 first:px-0">
                <div class="flex items-center gap-2 text-stone-500 text-xs font-semibold uppercase tracking-wider mb-1">
                    <span>🏛️</span>
                    <span>Monuments</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-sites-count">
                    {{ $stats['sites_count'] }}
                </div>
                <div class="text-[11px] text-stone-500 mt-1">Verified In Situ Complexes</div>
            </div>

            <!-- Stat 2: Objects -->
            <div class="pt-4 sm:pt-0 sm:px-4">
                <div class="flex items-center gap-2 text-stone-500 text-xs font-semibold uppercase tracking-wider mb-1">
                    <span>🏺</span>
                    <span>Relics & Pillars</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-objects-count">
                    {{ $stats['objects_count'] }}
                </div>
                <div class="text-[11px] text-stone-500 mt-1">Sculptures & Architectural Relics</div>
            </div>

            <!-- Stat 3: Epigraphs -->
            <div class="pt-4 sm:pt-0 sm:px-4">
                <div class="flex items-center gap-2 text-stone-500 text-xs font-semibold uppercase tracking-wider mb-1">
                    <span>📜</span>
                    <span>Epigraphs</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-inscriptions-count">
                    {{ $stats['inscriptions_count'] }}
                </div>
                <div class="text-[11px] text-stone-500 mt-1">Brahmi Facsimiles & Translations</div>
            </div>

            <!-- Stat 4: Sources -->
            <div class="pt-4 sm:pt-0 sm:px-4">
                <div class="flex items-center gap-2 text-stone-500 text-xs font-semibold uppercase tracking-wider mb-1">
                    <span>📚</span>
                    <span>Scholarly Sources</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-sources-count">
                    {{ $stats['sources_count'] }}
                </div>
                <div class="text-[11px] text-stone-500 mt-1">ASI, Marshall, Cunningham, Sircar</div>
            </div>

            <!-- Stat 5: Claims -->
            <div class="pt-4 sm:pt-0 sm:px-4">
                <div class="flex items-center gap-2 text-stone-500 text-xs font-semibold uppercase tracking-wider mb-1">
                    <span>⚖️</span>
                    <span>Evidence Claims</span>
                </div>
                <div class="text-3xl font-extrabold text-stone-900 tracking-tight" id="stat-claims-count">
                    {{ $stats['claims_count'] }}
                </div>
                <div class="text-[11px] text-stone-500 mt-1">Peer-Reviewed Consensus Claims</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Monuments & In Situ Complexes -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-orange-800 uppercase tracking-wider mb-2">
                <span>Featured Monographic Dossiers</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-stone-900 tracking-tight">
                Verified Archaeological Heritage Sites
            </h2>
            <p class="text-sm text-stone-600 mt-1">
                Monuments documented with verified stratigraphy, dual dating, and epigraphical facsimiles.
            </p>
        </div>
        <a href="{{ url('/sites') }}" class="text-sm font-semibold text-orange-800 hover:text-orange-950 flex items-center gap-1 group">
            Browse All Monuments
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($featuredSites as $site)
        <article class="bg-white rounded-2xl border border-stone-200/90 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
            <!-- Card Header & Badge -->
            <div class="p-6 pb-4">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        {{ $site->primaryPeriod?->name ?? 'Ancient Period' }}
                    </span>
                    @if($site->primaryDynasty)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-stone-100 text-stone-700">
                        {{ $site->primaryDynasty->name }}
                    </span>
                    @endif
                    <span class="text-[11px] text-stone-500 font-mono ml-auto">
                        {{ $site->location?->state ?? 'India' }}
                    </span>
                </div>

                <h3 class="text-xl font-bold text-stone-900 group-hover:text-orange-800 transition">
                    <a href="{{ url('/sites/' . $site->slug) }}">
                        {{ $site->name }}
                    </a>
                </h3>

                <!-- Dating Statement Badge -->
                <div class="mt-2.5 inline-flex items-center gap-1.5 text-xs text-stone-600 font-medium">
                    <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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
            <div class="p-6 pt-4 border-t border-stone-100 bg-stone-50/50 flex items-center justify-between text-xs text-stone-500">
                <div class="flex items-center gap-3">
                    <span>🏺 {{ $site->objects->count() }} Artifacts</span>
                    <span>•</span>
                    <span>📍 {{ $site->site_type }}</span>
                </div>
                <a href="{{ url('/sites/' . $site->slug) }}" class="font-semibold text-orange-800 hover:text-orange-950 flex items-center gap-1">
                    Details &rarr;
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-12 text-stone-500 text-sm">
            No heritage monuments currently published. Run seeders to populate verified records.
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
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-semibold uppercase tracking-wider">
                    📜 Epigraphical Corpus
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white leading-tight">
                    Verbatim Brahmi Facsimiles & Archaeological Epigraphs
                </h2>
                <p class="text-sm text-stone-300 leading-relaxed">
                    Epigraphy is the bedrock of Indian civilizational history. We record verbatim Brahmi glyphs alongside scholarly transliteration, paleographical dating, and verified translations from <em>Epigraphia Indica</em>.
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ url('/inscriptions') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-stone-950 font-semibold text-xs tracking-wide transition">
                        Explore Full Epigraphy Corpus &rarr;
                    </a>
                </div>
            </div>

            <!-- Right Sample Inscription Cards -->
            <div class="lg:col-span-7 space-y-4">
                @foreach($sampleInscriptions as $insc)
                <div class="bg-stone-800/90 rounded-xl p-5 border border-stone-700/80 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-amber-400">{{ $insc->title }}</span>
                        <span class="text-stone-400 font-mono text-[11px]">{{ $insc->script }} • {{ $insc->language }}</span>
                    </div>

                    <!-- Facsimile / Transliteration Snippet -->
                    <div class="bg-stone-950/70 p-3 rounded-lg border border-stone-800 font-mono text-xs text-amber-200/90 overflow-x-auto leading-relaxed">
                        {{ \Illuminate\Support\Str::limit($insc->raw_text ?? $insc->transliteration, 120) }}
                    </div>

                    <!-- Translation Preview -->
                    <p class="text-xs text-stone-400 italic leading-relaxed">
                        "{{ \Illuminate\Support\Str::limit($insc->translation, 140) }}"
                    </p>

                    <div class="flex items-center justify-between text-[11px] text-stone-500 pt-1 border-t border-stone-800">
                        <span>Dated: {{ $insc->dating_statement }}</span>
                        <span>Site: {{ $insc->heritageSite?->name ?? 'Archaeological Site' }}</span>
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
            <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-800 flex items-center justify-center font-bold text-lg">
                ⏳
            </div>
            <h3 class="font-bold text-stone-900 text-base">Dual Historical Dating</h3>
            <p class="text-xs text-stone-600 leading-relaxed">
                Reconciles continuous astronomical integer bounds (e.g. <code class="font-mono text-stone-800">-300</code> to <code class="font-mono text-stone-800">100</code>) with paleographical human statements (<em>c. 3rd Cent. BCE</em>), allowing seamless timeline math without distorting nuanced dates.
            </p>
        </div>

        <!-- Pillar 2 -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/90 shadow-2xs space-y-3">
            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg">
                🔍
            </div>
            <h3 class="font-bold text-stone-900 text-base">Multi-Tiered Evidence</h3>
            <p class="text-xs text-stone-600 leading-relaxed">
                Archaeological strata, inscriptions, and radiocarbon data exist as distinct evidence nodes linked to atomized claims. Disputed claims are explicitly flagged as <span class="font-mono text-amber-800 font-medium">ONGOING_DEBATE</span>.
            </p>
        </div>

        <!-- Pillar 3 -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/90 shadow-2xs space-y-3">
            <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-800 flex items-center justify-center font-bold text-lg">
                🛡️
            </div>
            <h3 class="font-bold text-stone-900 text-base">Zero Fabrication Policy</h3>
            <p class="text-xs text-stone-600 leading-relaxed">
                Absolute zero tolerance for fictional citations or speculative attributions. Every data record links directly to published volumes from the Archaeological Survey of India (ASI) or equivalent peer-reviewed journals.
            </p>
        </div>

        <!-- Pillar 4 -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/90 shadow-2xs space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg">
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
    <div class="bg-gradient-to-r from-stone-900 via-stone-850 to-stone-900 rounded-3xl p-8 sm:p-12 border border-stone-800 text-white flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-xl space-y-3 text-center md:text-left">
            <h3 class="text-2xl sm:text-3xl font-bold tracking-tight">
                Empowering Researchers, Students & Historians
            </h3>
            <p class="text-stone-300 text-sm leading-relaxed">
                Explore the interactive web map, filter monuments by astronomical centuries, and inspect primary epigraphical facsimiles with full evidentiary confidence.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-4 shrink-0">
            <a href="{{ url('/map') }}" class="px-5 py-3 rounded-xl bg-orange-700 hover:bg-orange-800 text-white font-medium text-sm transition">
                Launch Map Atlas
            </a>
            <a href="{{ url('/api/v1/sites') }}" class="px-5 py-3 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-200 border border-stone-700 font-medium text-sm transition">
                Explore REST API v1
            </a>
        </div>
    </div>
</section>
@endsection
