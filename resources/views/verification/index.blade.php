@extends('layouts.app')

@section('title', 'Evidence Verification Engine & Consensus Graph — Bharat Heritage Atlas')
@section('meta_description', 'Explore peer-reviewed historical claims, multi-disciplinary evidence graphs, and debate mapping across ancient Indian monuments and inscriptions.')

@section('content')
<div class="bg-[#faf8f5] py-8 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold">Evidence Verification Engine</span>
        </nav>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 flex items-center gap-2.5">
                    <span>🔍 Evidence Verification Engine</span>
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1 max-w-3xl">
                    Every historical claim in the Atlas is mapped to primary epigraphy, excavation stratigraphy, or peer-reviewed literature. No unverified speculation is admitted.
                </p>
            </div>
            
            <!-- Corpus Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 font-mono text-xs">
                <div class="bg-white px-3 py-2 rounded-xl border border-stone-200 shadow-2xs text-center">
                    <span class="text-[10px] text-stone-400 block font-sans uppercase tracking-wider">Total Claims</span>
                    <strong class="text-stone-900 text-sm">{{ $totalClaims }}</strong>
                </div>
                <div class="bg-white px-3 py-2 rounded-xl border border-emerald-200 shadow-2xs text-center">
                    <span class="text-[10px] text-emerald-700 block font-sans uppercase tracking-wider font-semibold">Settled</span>
                    <strong class="text-emerald-800 text-sm">{{ $settledCount }}</strong>
                </div>
                <div class="bg-white px-3 py-2 rounded-xl border border-amber-200 shadow-2xs text-center">
                    <span class="text-[10px] text-amber-700 block font-sans uppercase tracking-wider font-semibold">Debated</span>
                    <strong class="text-amber-800 text-sm">{{ $debatedCount }}</strong>
                </div>
                <div class="bg-white px-3 py-2 rounded-xl border border-stone-200 shadow-2xs text-center">
                    <span class="text-[10px] text-stone-400 block font-sans uppercase tracking-wider">Evidence Nodes</span>
                    <strong class="text-stone-900 text-sm">{{ $totalEvidence }}</strong>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('verification.index') }}" method="GET" class="mt-6 bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search claim statement or justification..." class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 focus:ring-amber-500 focus:border-amber-500" />
            </div>
            <div>
                <select name="consensus_status" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white">
                    <option value="">All Consensus Statuses</option>
                    @foreach($consensusStatuses as $status)
                    <option value="{{ $status }}" @selected(request('consensus_status') == $status)>
                        {{ str_replace('_', ' ', $status) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="claim_type" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white">
                    <option value="">All Claim Types</option>
                    @foreach($claimTypes as $type)
                    <option value="{{ $type }}" @selected(request('claim_type') == $type)>
                        {{ str_replace('_', ' ', $type) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-orange-800 hover:bg-orange-900 text-white rounded-lg text-xs font-semibold shadow-2xs transition">
                    Filter Claims
                </button>
                @if(request()->anyFilled(['search', 'consensus_status', 'claim_type']))
                <a href="{{ route('verification.index') }}" class="px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-medium transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="space-y-6">
        @forelse($claims as $claim)
        <article class="bg-white rounded-2xl border border-stone-200/90 shadow-xs hover:shadow-md transition-all duration-200 p-6 sm:p-7 flex flex-col space-y-4 group">
            <!-- Header Row: Badges & Subject Link -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Status Badge -->
                    @if(in_array($claim->consensus_status, ['SETTLED_CONSENSUS', 'STRONG_CONSENSUS']))
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        {{ str_replace('_', ' ', $claim->consensus_status) }}
                    </span>
                    @elseif($claim->consensus_status === 'ONGOING_DEBATE')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                        ⚖️ Ongoing Scholarly Debate
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-stone-100 text-stone-800 border border-stone-200">
                        {{ str_replace('_', ' ', $claim->consensus_status) }}
                    </span>
                    @endif

                    <!-- Claim Type -->
                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-stone-100 text-stone-700 border border-stone-200">
                        {{ str_replace('_', ' ', $claim->claim_type) }}
                    </span>

                    <span class="text-xs text-stone-400 font-mono">Claim #{{ $claim->id }}</span>
                </div>

                <!-- Polymorphic Subject Link -->
                @if($claim->claimable)
                <div>
                    @if($claim->claimable instanceof \App\Models\HeritageSite)
                    <a href="{{ route('sites.show', $claim->claimable->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg bg-orange-50 text-orange-900 hover:bg-orange-100 border border-orange-200 transition">
                        <span>🏛️ Monument:</span>
                        <span>{{ $claim->claimable->name }}</span>
                    </a>
                    @elseif($claim->claimable instanceof \App\Models\HeritageObject)
                    <a href="{{ route('objects.show', $claim->claimable->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-900 hover:bg-amber-100 border border-amber-200 transition">
                        <span>🏺 Artifact:</span>
                        <span>{{ $claim->claimable->name }}</span>
                    </a>
                    @elseif($claim->claimable instanceof \App\Models\Inscription)
                    <a href="{{ route('inscriptions.show', $claim->claimable->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg bg-stone-100 text-stone-900 hover:bg-stone-200 border border-stone-300 transition">
                        <span>📜 Epigraph:</span>
                        <span>{{ $claim->claimable->title }}</span>
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Claim Statement -->
            <div class="border-l-3 border-orange-800 pl-4 py-1">
                <h2 class="text-base sm:text-lg font-bold text-stone-900 group-hover:text-orange-900 transition leading-snug">
                    <a href="{{ route('verification.show', $claim->id) }}">
                        “{{ $claim->statement }}”
                    </a>
                </h2>
            </div>

            <!-- Justification Note -->
            @if($claim->summary_justification)
            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed bg-stone-50/80 p-3.5 rounded-xl border border-stone-100">
                <strong class="text-stone-800 font-semibold">Scholarly Justification:</strong> {{ $claim->summary_justification }}
            </p>
            @endif

            <!-- Evidence Graph Summary Footer -->
            <div class="pt-3 border-t border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-3 text-stone-600">
                    <span class="inline-flex items-center gap-1.5 font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <strong class="text-stone-900">{{ $claim->evidence->count() }}</strong> Linked Evidence Nodes
                    </span>
                    <span class="text-stone-300">•</span>
                    <span class="text-stone-500">
                        Methodologies: 
                        {{ $claim->evidence->pluck('evidence_type')->unique()->map(fn($t) => str_replace('_', ' ', $t))->implode(', ') }}
                    </span>
                </div>

                <a href="{{ route('verification.show', $claim->id) }}" class="inline-flex items-center gap-1 font-bold text-orange-800 hover:text-orange-950 transition text-xs">
                    <span>Inspect Evidence Graph & Breakdown</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </article>
        @empty
        <div class="bg-white rounded-2xl border border-stone-200 p-12 text-center space-y-3">
            <span class="text-3xl block">🔍</span>
            <h3 class="text-base font-bold text-stone-800">No Claims Match the Specified Filters</h3>
            <p class="text-xs text-stone-500 max-w-md mx-auto">
                Try clearing your search terms or resetting the consensus status and claim type filters.
            </p>
            <a href="{{ route('verification.index') }}" class="inline-block mt-2 px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-semibold">
                Reset All Filters
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $claims->links() }}
    </div>
</div>
@endsection
