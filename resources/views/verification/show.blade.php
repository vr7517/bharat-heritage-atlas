@extends('layouts.app')

@section('title', 'Claim #' . $claim->id . ' Verification Dossier — Bharat Heritage Atlas')
@section('meta_description', Str::limit($claim->statement, 160))

@section('content')
<div class="bg-[#faf8f5] py-8 sm:py-12 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <a href="{{ route('verification.index') }}" class="hover:text-orange-800 transition">Evidence Verification</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold">Claim #{{ $claim->id }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            <div class="space-y-4 max-w-4xl">
                <!-- Status & Type Pills -->
                <div class="flex flex-wrap items-center gap-2">
                    @if(in_array($claim->consensus_status, ['SETTLED_CONSENSUS', 'STRONG_CONSENSUS']))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        🛡️ Settled Archaeological Consensus
                    </span>
                    @elseif($claim->consensus_status === 'ONGOING_DEBATE')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                        <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                        ⚖️ Active Historiographical Debate
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-stone-100 text-stone-800 border border-stone-300">
                        {{ str_replace('_', ' ', $claim->consensus_status) }}
                    </span>
                    @endif

                    <span class="px-3 py-1 rounded text-xs font-medium bg-stone-100 text-stone-700 border border-stone-200">
                        {{ str_replace('_', ' ', $claim->claim_type) }}
                    </span>

                    <span class="text-xs text-stone-400 font-mono">Claim ID: #{{ $claim->id }}</span>
                </div>

                <!-- Verified Statement -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-3">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block">Formal Historical Claim</span>
                    <h1 class="text-xl sm:text-2xl font-bold text-stone-900 leading-snug">
                        “{{ $claim->statement }}”
                    </h1>
                    @if($claim->summary_justification)
                    <div class="pt-3 border-t border-stone-100 text-xs sm:text-sm text-stone-600 leading-relaxed">
                        <strong class="text-stone-800 font-semibold">Executive Justification:</strong> {{ $claim->summary_justification }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Subject Entity Context Card -->
            @if($claim->claimable)
            <div class="shrink-0 bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs space-y-2 lg:max-w-xs w-full">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block">Subject Heritage Entity</span>
                <div>
                    @if($claim->claimable instanceof \App\Models\HeritageSite)
                    <div class="text-xs font-bold text-orange-800 mb-1">🏛️ Monument Complex</div>
                    <a href="{{ route('sites.show', $claim->claimable->slug) }}" class="font-bold text-sm text-stone-900 hover:text-orange-800 transition block">
                        {{ $claim->claimable->name }} &rarr;
                    </a>
                    <p class="text-[11px] text-stone-500 mt-1">{{ $claim->claimable->location?->state ?? 'India' }} • {{ $claim->claimable->dating_statement }}</p>
                    @elseif($claim->claimable instanceof \App\Models\HeritageObject)
                    <div class="text-xs font-bold text-amber-800 mb-1">🏺 Physical Artifact</div>
                    <a href="{{ route('objects.show', $claim->claimable->slug) }}" class="font-bold text-sm text-stone-900 hover:text-amber-800 transition block">
                        {{ $claim->claimable->name }} &rarr;
                    </a>
                    <p class="text-[11px] text-stone-500 mt-1">{{ $claim->claimable->object_type }} • {{ $claim->claimable->dating_statement }}</p>
                    @elseif($claim->claimable instanceof \App\Models\Inscription)
                    <div class="text-xs font-bold text-stone-800 mb-1">📜 Epigraphical Inscription</div>
                    <a href="{{ route('inscriptions.show', $claim->claimable->slug) }}" class="font-bold text-sm text-stone-900 hover:text-orange-800 transition block">
                        {{ $claim->claimable->title }} &rarr;
                    </a>
                    <p class="text-[11px] text-stone-500 mt-1">{{ $claim->claimable->script }} • {{ $claim->claimable->language }}</p>
                    @endif
                </div>
                <div class="pt-3 border-t border-stone-100 text-[11px] text-stone-500">
                    Linked to <strong class="text-stone-800">{{ $claim->evidence->count() }}</strong> corroborating evidence nodes.
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Section 1: Supporting Evidence Graph -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span>🛡️ Supporting Archaeological & Epigraphical Evidence</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-900 border border-emerald-200">
                    {{ $supportingEvidence->count() }} Nodes
                </span>
            </h2>
        </div>

        <div class="space-y-6">
            @forelse($supportingEvidence as $ev)
            <article class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
                <!-- Card Header -->
                <div class="p-6 border-b border-stone-100 bg-stone-50/40 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            {{ $ev->pivot->relationship_type }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-stone-200 text-stone-800">
                            {{ str_replace('_', ' ', $ev->evidence_type) }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono text-stone-600 bg-stone-100">
                            Weight: {{ $ev->pivot->scholarly_weight }}
                        </span>
                    </div>
                    <span class="text-xs text-stone-400 font-mono">Node #{{ $ev->id }}</span>
                </div>

                <div class="p-6 sm:p-7 space-y-5">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-stone-900">
                            {{ $ev->title }}
                        </h3>
                        <p class="text-xs sm:text-sm text-stone-600 leading-relaxed mt-2">
                            {{ $ev->description }}
                        </p>
                    </div>

                    <!-- Archaeological Metadata Specs -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-stone-50 border border-stone-100 text-xs">
                        @if($ev->stratigraphic_context)
                        <div>
                            <span class="text-stone-400 block text-[10px] uppercase tracking-wider font-semibold">Stratigraphic Context</span>
                            <span class="font-medium text-stone-900">{{ $ev->stratigraphic_context }}</span>
                        </div>
                        @endif
                        @if($ev->methodology_applied)
                        <div>
                            <span class="text-stone-400 block text-[10px] uppercase tracking-wider font-semibold">Methodology Applied</span>
                            <span class="font-medium text-stone-900">{{ $ev->methodology_applied }}</span>
                        </div>
                        @endif
                        @if($ev->pivot->analysis_notes)
                        <div class="sm:col-span-1">
                            <span class="text-stone-400 block text-[10px] uppercase tracking-wider font-semibold">Scholarly Analysis</span>
                            <span class="font-medium text-stone-900">{{ $ev->pivot->analysis_notes }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Cited Primary & Secondary Sources -->
                    @if($ev->sources->isNotEmpty())
                    <div class="space-y-3 pt-2">
                        <h4 class="text-xs font-bold text-stone-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span>📚 Archival Sources Cited</span>
                            <span class="text-[10px] px-1.5 py-0.2 rounded bg-stone-200 text-stone-700 font-mono">{{ $ev->sources->count() }}</span>
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($ev->sources as $src)
                            <div class="p-4 rounded-xl border border-stone-200 bg-white hover:border-amber-400 transition space-y-2">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-stone-800">{{ $src->authors }} ({{ $src->publication_year }})</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-mono {{ str_starts_with($src->reliability_tier, 'TIER_1') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                        {{ str_starts_with($src->reliability_tier, 'TIER_1') ? 'Tier 1 Primary' : 'Tier 2 Peer-Reviewed' }}
                                    </span>
                                </div>

                                <h5 class="text-xs font-bold text-stone-900 line-clamp-2">
                                    <a href="{{ route('sources.show', $src->id) }}" class="hover:text-orange-800 transition">
                                        {{ $src->title }}
                                    </a>
                                </h5>

                                @if($src->pivot->specific_pages)
                                <div class="text-[11px] font-mono text-stone-600">
                                    Specific Pages: <strong>{{ $src->pivot->specific_pages }}</strong>
                                </div>
                                @endif

                                @if($src->pivot->direct_quotation_or_data)
                                <div class="text-xs font-mono bg-stone-50 p-2 rounded border border-stone-100 text-stone-700 italic">
                                    “{{ $src->pivot->direct_quotation_or_data }}”
                                </div>
                                @endif

                                @if($src->pivot->citation_context)
                                <p class="text-[11px] text-stone-500 italic">
                                    Context: {{ $src->pivot->citation_context }}
                                </p>
                                @endif

                                <div class="pt-1 text-right">
                                    <a href="{{ route('sources.show', $src->id) }}" class="text-[11px] font-bold text-orange-800 hover:text-orange-950">
                                        View Archival Monograph &rarr;
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </article>
            @empty
            <div class="p-6 bg-white rounded-xl border border-stone-200 text-xs text-stone-500">
                No primary supporting nodes recorded for this claim.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Section 2: Divergent / Complicating Evidence (If Any) -->
    @if($divergentEvidence->isNotEmpty())
    <div class="space-y-6 pt-4 border-t border-stone-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span>⚖️ Divergent, Complicating, or Alternative Evidence</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                    {{ $divergentEvidence->count() }} Nodes
                </span>
            </h2>
        </div>

        <p class="text-xs text-stone-600">
            The Atlas documents scholarly dissent and competing historiographical hypotheses with equal scientific rigor.
        </p>

        <div class="space-y-6">
            @foreach($divergentEvidence as $ev)
            <article class="bg-amber-50/40 rounded-2xl border border-amber-200 shadow-2xs overflow-hidden">
                <div class="p-6 border-b border-amber-100 bg-amber-50/70 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-amber-200 text-amber-900 border border-amber-300">
                            {{ $ev->pivot->relationship_type }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-stone-200 text-stone-800">
                            {{ str_replace('_', ' ', $ev->evidence_type) }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono text-stone-700 bg-white">
                            Weight: {{ $ev->pivot->scholarly_weight }}
                        </span>
                    </div>
                    <span class="text-xs text-amber-900/60 font-mono">Divergent Node #{{ $ev->id }}</span>
                </div>

                <div class="p-6 sm:p-7 space-y-4">
                    <h3 class="text-base sm:text-lg font-bold text-stone-900">
                        {{ $ev->title }}
                    </h3>
                    <p class="text-xs sm:text-sm text-stone-700 leading-relaxed">
                        {{ $ev->description }}
                    </p>

                    @if($ev->pivot->analysis_notes)
                    <div class="p-3.5 rounded-xl bg-white border border-amber-200 text-xs text-stone-800">
                        <strong class="font-semibold text-amber-950">Historiographical Counterpoint:</strong> {{ $ev->pivot->analysis_notes }}
                    </div>
                    @endif

                    <!-- Sources for Divergent Node -->
                    @if($ev->sources->isNotEmpty())
                    <div class="space-y-3 pt-2">
                        <h4 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Alternative Literature Cited</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($ev->sources as $src)
                            <div class="p-4 rounded-xl border border-amber-200 bg-white space-y-2">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-stone-800">{{ $src->authors }} ({{ $src->publication_year }})</span>
                                </div>
                                <h5 class="text-xs font-bold text-stone-900">
                                    <a href="{{ route('sources.show', $src->id) }}" class="hover:text-orange-800 transition">
                                        {{ $src->title }}
                                    </a>
                                </h5>
                                @if($src->pivot->specific_pages)
                                <div class="text-[11px] font-mono text-stone-600">Pages: {{ $src->pivot->specific_pages }}</div>
                                @endif
                                <div class="pt-1 text-right">
                                    <a href="{{ route('sources.show', $src->id) }}" class="text-[11px] font-bold text-orange-800 hover:text-orange-950">
                                        View Source &rarr;
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Open Science & API Reference Banner -->
    <div class="bg-stone-900 text-stone-200 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 border border-stone-800">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold tracking-widest text-amber-400 uppercase">Open Archaeological Science</span>
            <h3 class="text-base sm:text-lg font-bold text-white">Machine-Readable Verification Graph</h3>
            <p class="text-xs text-stone-400 max-w-xl">
                Fetch this claim's complete evidence hierarchy, source citations, and uncertainty specifications via our OpenAPI 3.1 REST API.
            </p>
        </div>
        <a href="{{ url('/api/v1/verification/claims/' . $claim->id) }}" target="_blank" class="shrink-0 px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-stone-950 rounded-xl text-xs font-bold shadow-sm transition">
            GET /api/v1/verification/claims/{{ $claim->id }}
        </a>
    </div>

</div>
@endsection
