@extends('layouts.app')

@section('title', $site->name . ' — Bharat Heritage Atlas')
@section('meta_description', Str::limit($site->summary, 160))

@section('content')
<!-- Header Hero -->
<div class="bg-[#faf8f5] py-8 sm:py-12 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <a href="{{ route('sites.index') }}" class="hover:text-orange-800 transition">Monuments</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold truncate">{{ $site->name }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            <div class="space-y-3 max-w-4xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                        {{ $site->primaryPeriod?->name ?? 'Ancient Period' }}
                    </span>
                    @if($site->primaryDynasty)
                    <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-stone-100 text-stone-700">
                        Dynasty: {{ $site->primaryDynasty->name }}
                    </span>
                    @endif
                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-900 border border-emerald-200">
                        {{ $site->site_type }}
                    </span>
                    @if($site->protection_status)
                    <span class="text-[11px] font-medium text-stone-500 bg-stone-100 px-2 py-0.5 rounded">
                        🛡️ {{ $site->protection_status }}
                    </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-stone-900 leading-tight">
                    {{ $site->name }}
                </h1>

                <!-- Dating Statement & Astronomical Bounds -->
                <div class="flex flex-wrap items-center gap-4 text-xs font-mono text-stone-700 pt-1">
                    <span class="inline-flex items-center gap-1.5 font-sans font-medium text-stone-900 bg-amber-50 px-2.5 py-1 rounded border border-amber-200">
                        <span>⏳</span>
                        <span>{{ $site->dating_statement }}</span>
                    </span>
                    <span class="text-stone-500">
                        Astronomical Range: <strong class="text-stone-900">{{ $site->start_year }}</strong> to <strong class="text-stone-900">{{ $site->end_year }}</strong>
                    </span>
                    @if($site->is_dating_uncertain)
                    <span class="text-amber-800 bg-amber-100 px-2 py-0.5 rounded text-[10px]">
                        Dating Uncertainty Documented
                    </span>
                    @endif
                </div>
            </div>

            <!-- API Endpoint Link -->
            <div class="shrink-0 flex items-center gap-2">
                <a href="{{ url('/api/v1/sites/' . $site->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg border border-stone-300 bg-white hover:bg-stone-50 text-xs font-semibold text-stone-700 shadow-2xs transition flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    API Monograph
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Monographic Body -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
    
    <!-- Location & In Situ Stratigraphy Bar -->
    @if($site->location)
    <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-2xs">
        <h3 class="text-xs font-bold text-orange-950 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span>📍 In Situ Archaeological Stratum & Coordinates</span>
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 text-xs">
            <div>
                <span class="text-stone-400 block text-[11px]">Modern State</span>
                <span class="font-bold text-stone-900">{{ $site->location->state }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">District</span>
                <span class="font-bold text-stone-900">{{ $site->location->district ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Geographic Settlement</span>
                <span class="font-bold text-stone-900">{{ $site->location->name }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Latitude / Longitude</span>
                <span class="font-mono font-bold text-stone-900">{{ $site->location->latitude }}°N, {{ $site->location->longitude }}°E</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Spatial Accuracy</span>
                <span class="font-mono font-bold text-stone-900">±{{ $site->location->uncertainty_radius_meters ?? 0 }}m</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Elevation / Altitude</span>
                <span class="font-bold text-stone-900">{{ $site->location->altitude_meters ? $site->location->altitude_meters . 'm ASL' : 'Unrecorded' }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Detailed Architectural & Historical Narrative -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column: Summary & Architecture -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Executive Summary -->
            <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-2xs space-y-3">
                <h2 class="text-lg font-bold text-stone-900">Executive Archaeological Summary</h2>
                <p class="text-sm text-stone-700 leading-relaxed">{{ $site->summary }}</p>
            </div>

            <!-- Historical Context -->
            @if($site->historical_context)
            <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-2xs space-y-3">
                <h2 class="text-lg font-bold text-stone-900">Historical & Epigraphical Context</h2>
                <div class="text-sm text-stone-700 leading-relaxed space-y-3">
                    <p>{{ $site->historical_context }}</p>
                </div>
            </div>
            @endif

            <!-- Architectural Description -->
            @if($site->architectural_description)
            <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-2xs space-y-3">
                <h2 class="text-lg font-bold text-stone-900">Architectural & Structural Breakdown</h2>
                <div class="text-sm text-stone-700 leading-relaxed space-y-3">
                    <p>{{ $site->architectural_description }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Column: Metadata & Quick Facts -->
        <div class="space-y-6">
            <div class="bg-stone-50 rounded-2xl border border-stone-200 p-6 space-y-4">
                <h3 class="text-xs font-bold text-stone-900 uppercase tracking-wide">Archival Classification</h3>
                <dl class="space-y-3 text-xs">
                    <div>
                        <dt class="text-stone-500">Site Typology</dt>
                        <dd class="font-semibold text-stone-900">{{ $site->site_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-stone-500">Primary Period</dt>
                        <dd class="font-semibold text-stone-900">{{ $site->primaryPeriod?->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-stone-500">Dynastic Realm</dt>
                        <dd class="font-semibold text-stone-900">{{ $site->primaryDynasty?->name ?? 'Unspecified' }}</dd>
                    </div>
                    <div>
                        <dt class="text-stone-500">Canonical Slug</dt>
                        <dd class="font-mono text-stone-700">{{ $site->slug }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Child Objects & Sculptures Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span>🏺 Cataloged Physical Objects & Relics</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-700">{{ $site->objects->count() }}</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($site->objects as $obj)
            <article class="bg-white rounded-xl border border-stone-200 p-5 shadow-2xs hover:shadow-sm transition space-y-3">
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
                <p class="text-xs text-stone-600 line-clamp-3 leading-relaxed">
                    {{ $obj->description }}
                </p>
                <div class="pt-2 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-500">
                    <span>Repository: {{ $obj->current_repository ?? 'In Situ' }}</span>
                    <a href="{{ route('objects.show', $obj->slug) }}" class="font-bold text-orange-800 hover:text-orange-950">
                        View Object &rarr;
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-xs text-stone-500 py-6">No child objects registered for this monument.</div>
            @endforelse
        </div>
    </div>

    <!-- Attached Epigraphical Corpus Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span>📜 Epigraphical Corpus & Brahmi Facsimiles</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-700">{{ $site->inscriptions->count() }}</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($site->inscriptions as $insc)
            <article class="bg-stone-900 text-stone-100 rounded-2xl p-6 border border-stone-800 shadow-md space-y-4">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-amber-400">{{ $insc->title }}</span>
                    <span class="font-mono text-stone-400 text-[11px]">{{ $insc->script }} • {{ $insc->language }}</span>
                </div>

                @if($insc->raw_text || $insc->transliteration)
                <div class="bg-stone-950/80 p-3.5 rounded-xl border border-stone-800 font-mono text-xs text-amber-200 overflow-x-auto leading-relaxed">
                    {{ $insc->raw_text ?? $insc->transliteration }}
                </div>
                @endif

                <p class="text-xs text-stone-300 italic leading-relaxed">
                    "{{ $insc->translation }}"
                </p>

                <div class="pt-2 border-t border-stone-800 flex items-center justify-between text-[11px] text-stone-400">
                    <span>Dating: {{ $insc->dating_statement }}</span>
                    <a href="{{ route('inscriptions.show', $insc->slug) }}" class="font-bold text-amber-400 hover:text-amber-300">
                        Epigraph Dossier &rarr;
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-2 text-xs text-stone-500 py-6">No direct inscriptions registered for this complex.</div>
            @endforelse
        </div>
    </div>

    <!-- Linked Claims & Peer-Reviewed Consensus -->
    @if($site->claims->isNotEmpty())
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-stone-900 flex items-center gap-2">
            <span>⚖️ Verified Evidentiary Claims & Academic Consensus</span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-700">{{ $site->claims->count() }}</span>
        </h2>

        <div class="space-y-4">
            @foreach($site->claims as $claim)
            <div class="bg-white rounded-xl border border-stone-200 p-5 shadow-2xs space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="px-2 py-0.5 rounded font-mono font-bold text-[10px] {{ $claim->consensus_status === 'SETTLED_CONSENSUS' ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-amber-100 text-amber-900 border border-amber-200' }}">
                        {{ $claim->consensus_status }}
                    </span>
                    <span class="text-stone-500 font-mono text-[11px]">Claim #{{ $claim->id }}</span>
                </div>
                <h4 class="font-bold text-stone-900 text-sm">{{ $claim->claim_text }}</h4>
                @if($claim->consensus_notes)
                <p class="text-xs text-stone-600 italic">{{ $claim->consensus_notes }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
