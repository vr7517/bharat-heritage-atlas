@extends('layouts.app')

@section('title', 'Archival Bibliographic Sources — Bharat Heritage Atlas')
@section('meta_description', 'Searchable library of verified excavation reports, epigraphical corpuses (Epigraphia Indica), and peer-reviewed scholarly monographs backing the Atlas.')

@section('content')
<div class="bg-[#faf8f5] py-8 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold">Archival Bibliographic Sources</span>
        </nav>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 flex items-center gap-2.5">
                    <span>📚 Archival Bibliographic Sources</span>
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1 max-w-3xl">
                    Every assertion in the Atlas is linked to peer-reviewed archaeological monographs, Archaeological Survey of India (ASI) excavation reports, and authoritative epigraphical corpuses.
                </p>
            </div>
            
            <!-- Sources Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 font-mono text-xs">
                <div class="bg-white px-3 py-2 rounded-xl border border-stone-200 shadow-2xs text-center">
                    <span class="text-[10px] text-stone-400 block font-sans uppercase tracking-wider">Total Sources</span>
                    <strong class="text-stone-900 text-sm">{{ $totalSources }}</strong>
                </div>
                <div class="bg-white px-3 py-2 rounded-xl border border-emerald-200 shadow-2xs text-center">
                    <span class="text-[10px] text-emerald-700 block font-sans uppercase tracking-wider font-semibold">Tier 1 Primary</span>
                    <strong class="text-emerald-800 text-sm">{{ $tier1Count }}</strong>
                </div>
                <div class="bg-white px-3 py-2 rounded-xl border border-blue-200 shadow-2xs text-center">
                    <span class="text-[10px] text-blue-700 block font-sans uppercase tracking-wider font-semibold">Tier 2 Scholarly</span>
                    <strong class="text-blue-800 text-sm">{{ $tier2Count }}</strong>
                </div>
                <div class="bg-white px-3 py-2 rounded-xl border border-stone-200 shadow-2xs text-center">
                    <span class="text-[10px] text-stone-400 block font-sans uppercase tracking-wider">Evidence Citations</span>
                    <strong class="text-orange-950 text-sm">{{ $totalEvidentiaryCitations }}</strong>
                </div>
            </div>
        </div>

        <!-- Reliability Methodology Callout Banner -->
        <div class="mt-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-xs text-stone-700 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-base">🛡️</span>
                <span><strong>Reliability Framework:</strong> <em>Tier 1</em> comprises primary in situ excavation reports and published epigraphical corpuses. <em>Tier 2</em> comprises peer-reviewed university monographs and critical journal articles.</span>
            </div>
            <a href="{{ route('verification.index') }}" class="text-orange-800 font-bold hover:underline shrink-0 text-xs">
                View Evidence Engine &rarr;
            </a>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('sources.index') }}" method="GET" class="mt-6 bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search author, title, publisher..." class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 focus:ring-amber-500 focus:border-amber-500" />
            </div>
            <div>
                <select name="reliability_tier" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white">
                    <option value="">All Reliability Tiers</option>
                    @foreach($reliabilityTiers as $tier)
                    <option value="{{ $tier }}" @selected(request('reliability_tier') == $tier)>
                        {{ str_replace('_', ' ', $tier) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="source_type" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white">
                    <option value="">All Source Types</option>
                    @foreach($sourceTypes as $type)
                    <option value="{{ $type }}" @selected(request('source_type') == $type)>
                        {{ str_replace('_', ' ', $type) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-orange-800 hover:bg-orange-900 text-white rounded-lg text-xs font-semibold shadow-2xs transition">
                    Filter Sources
                </button>
                @if(request()->anyFilled(['search', 'reliability_tier', 'source_type']))
                <a href="{{ route('sources.index') }}" class="px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-medium transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sources as $source)
        <article class="bg-white rounded-2xl border border-stone-200/90 shadow-xs hover:shadow-md transition-all duration-200 p-6 flex flex-col justify-between group">
            <div class="space-y-3">
                <!-- Badges -->
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono {{ str_starts_with($source->reliability_tier, 'TIER_1') ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-blue-100 text-blue-900 border border-blue-200' }}">
                        {{ str_starts_with($source->reliability_tier, 'TIER_1') ? 'Tier 1 Primary' : 'Tier 2 Critical' }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-700">
                        {{ str_replace('_', ' ', $source->source_type) }}
                    </span>
                    <span class="text-xs font-mono font-bold text-stone-500 ml-auto">
                        {{ $source->publication_year }}
                    </span>
                </div>

                <!-- Title & Authors -->
                <div>
                    <h2 class="text-base font-bold text-stone-900 group-hover:text-orange-900 transition leading-snug">
                        <a href="{{ route('sources.show', $source->id) }}">
                            {{ $source->title }}
                        </a>
                    </h2>
                    <p class="text-xs text-stone-600 font-medium mt-1">
                        ✍️ {{ $source->authors }}
                    </p>
                </div>

                <!-- Publication Metadata -->
                <div class="text-[11px] text-stone-500 space-y-1 bg-stone-50 p-3 rounded-xl border border-stone-100">
                    @if($source->publisher)
                    <div><span class="text-stone-400">Publisher:</span> {{ $source->publisher }}</div>
                    @endif
                    @if($source->journal_or_series)
                    <div><span class="text-stone-400">Series:</span> {{ $source->journal_or_series }}</div>
                    @endif
                    @if($source->volume_issue || $source->pages)
                    <div><span class="text-stone-400">Pages:</span> {{ $source->volume_issue ? $source->volume_issue . ', ' : '' }}{{ $source->pages }}</div>
                    @endif
                </div>

                @if($source->notes)
                <p class="text-xs text-stone-600 line-clamp-2 italic">
                    {{ $source->notes }}
                </p>
                @endif
            </div>

            <!-- Footer: Evidentiary Impact -->
            <div class="pt-4 mt-4 border-t border-stone-100 flex items-center justify-between text-xs">
                <span class="inline-flex items-center gap-1.5 font-medium text-stone-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Powers <strong class="text-stone-900">{{ $source->evidence_count }}</strong> Nodes</span>
                </span>

                <a href="{{ route('sources.show', $source->id) }}" class="font-bold text-orange-800 hover:text-orange-950 transition">
                    Inspect Monograph &rarr;
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 bg-white rounded-2xl border border-stone-200 p-12 text-center space-y-3">
            <span class="text-3xl block">📚</span>
            <h3 class="text-base font-bold text-stone-800">No Bibliographic Sources Found</h3>
            <p class="text-xs text-stone-500 max-w-md mx-auto">
                No sources matched the active filter criteria. Clear your search or change the tier selection.
            </p>
            <a href="{{ route('sources.index') }}" class="inline-block mt-2 px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-semibold">
                Reset Filters
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $sources->links() }}
    </div>
</div>
@endsection
