@extends('layouts.app')

@section('title', $source->title . ' — Archival Source Dossier — Bharat Heritage Atlas')
@section('meta_description', Str::limit($source->notes ?? $source->title, 160))

@section('content')
<div class="bg-[#faf8f5] py-8 sm:py-12 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <a href="{{ route('sources.index') }}" class="hover:text-orange-800 transition">Archival Sources</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold truncate">{{ Str::limit($source->title, 40) }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            <div class="space-y-4 max-w-4xl">
                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold font-mono {{ str_starts_with($source->reliability_tier, 'TIER_1') ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-blue-100 text-blue-900 border border-blue-300' }}">
                        {{ str_replace('_', ' ', $source->reliability_tier) }}
                    </span>
                    <span class="px-3 py-1 rounded text-xs font-medium bg-stone-100 text-stone-700 border border-stone-200">
                        {{ str_replace('_', ' ', $source->source_type) }}
                    </span>
                    <span class="text-xs text-stone-400 font-mono">Source ID: #{{ $source->id }}</span>
                </div>

                <!-- Title & Authors -->
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-stone-900 leading-tight">
                        {{ $source->title }}
                    </h1>
                    <p class="text-sm sm:text-base text-stone-700 font-medium mt-2 flex items-center gap-2">
                        <span>✍️ {{ $source->authors }}</span>
                        <span class="text-stone-400">•</span>
                        <span class="font-mono text-stone-600 font-bold">{{ $source->publication_year }}</span>
                    </p>
                </div>
            </div>

            <!-- Evidentiary Footprint Box -->
            <div class="shrink-0 bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs space-y-2 lg:max-w-xs w-full">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block">Atlas Evidentiary Footprint</span>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-orange-950 font-mono">{{ $source->evidence->count() }}</span>
                    <span class="text-xs text-stone-600">Corroborated Evidentiary Nodes</span>
                </div>
                <p class="text-[11px] text-stone-500 pt-2 border-t border-stone-100">
                    Directly provides verifiable citations, excavation trench strata, or epigraphical readings to the Atlas evidence graph.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Bibliographical Specifications Card -->
    <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-2xs space-y-6">
        <h2 class="text-xs font-bold text-stone-900 uppercase tracking-wider">
            Bibliographical Record & Publication Data
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
            <div>
                <span class="text-stone-400 block text-[11px]">Primary Authors / Decipherers</span>
                <span class="font-bold text-stone-900">{{ $source->authors }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Publication Year</span>
                <span class="font-mono font-bold text-stone-900">{{ $source->publication_year }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Publisher / Printing Office</span>
                <span class="font-bold text-stone-900">{{ $source->publisher ?? 'Not Recorded' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Journal or Series</span>
                <span class="font-bold text-stone-900">{{ $source->journal_or_series ?? 'Monograph / Standalone' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Volume & Issue</span>
                <span class="font-mono font-bold text-stone-900">{{ $source->volume_issue ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Page Ranges</span>
                <span class="font-mono font-bold text-stone-900">{{ $source->pages ?? 'Complete Work' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Archival Repository / Shelfmark</span>
                <span class="font-bold text-stone-900">{{ $source->archival_location ?? 'Public Academic Domain' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Digital Repository Link</span>
                @if($source->url)
                <a href="{{ $source->url }}" target="_blank" rel="noopener noreferrer" class="font-bold text-orange-800 hover:text-orange-950 underline truncate block">
                    Access Digital Scan &rarr;
                </a>
                @else
                <span class="text-stone-500 font-mono">Print Archive Only</span>
                @endif
            </div>
        </div>

        @if($source->notes)
        <div class="pt-4 border-t border-stone-100">
            <h3 class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-2">Historiographical Context</h3>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed bg-stone-50 p-4 rounded-xl border border-stone-100">
                {{ $source->notes }}
            </p>
        </div>
        @endif
    </div>

    <!-- Evidentiary Nodes Corroborated by this Source -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span>🛡️ Evidentiary Contributions Powered by this Source</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-700">
                    {{ $source->evidence->count() }}
                </span>
            </h2>
        </div>

        <div class="space-y-6">
            @forelse($source->evidence as $ev)
            <article class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-stone-100 bg-stone-50/50 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-stone-200 text-stone-800">
                            {{ str_replace('_', ' ', $ev->evidence_type) }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded text-[11px] font-medium bg-amber-100 text-amber-900 border border-amber-200">
                            {{ str_replace('_', ' ', $ev->classification) }}
                        </span>
                    </div>
                    <span class="text-xs text-stone-400 font-mono">Evidence Node #{{ $ev->id }}</span>
                </div>

                <div class="p-6 sm:p-7 space-y-4">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-stone-900">
                            {{ $ev->title }}
                        </h3>
                        <p class="text-xs sm:text-sm text-stone-600 leading-relaxed mt-2">
                            {{ $ev->description }}
                        </p>
                    </div>

                    <!-- Page Citations & Quotation Data -->
                    <div class="bg-amber-500/5 p-4 rounded-xl border border-amber-500/20 space-y-2 text-xs">
                        @if($ev->pivot->specific_pages)
                        <div class="font-mono text-stone-800">
                            <strong>Specific Pages:</strong> {{ $ev->pivot->specific_pages }}
                        </div>
                        @endif

                        @if($ev->pivot->direct_quotation_or_data)
                        <div class="font-mono text-stone-900 bg-white p-3 rounded border border-stone-200 italic">
                            “{{ $ev->pivot->direct_quotation_or_data }}”
                        </div>
                        @endif

                        @if($ev->pivot->citation_context)
                        <p class="text-stone-600 italic text-[11px]">
                            <strong>Citation Context:</strong> {{ $ev->pivot->citation_context }}
                        </p>
                        @endif
                    </div>

                    <!-- Claims Supported by this Evidence -->
                    @if($ev->claims->isNotEmpty())
                    <div class="pt-3 border-t border-stone-100 space-y-2">
                        <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block">Proves / Corroborates Claims</span>
                        <div class="space-y-2">
                            @foreach($ev->claims as $claim)
                            <div class="p-3 rounded-lg bg-stone-50 border border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $claim->consensus_status === 'SETTLED_CONSENSUS' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ str_replace('_', ' ', $claim->consensus_status) }}
                                    </span>
                                    <span class="text-stone-800 font-medium line-clamp-1">“{{ $claim->statement }}”</span>
                                </div>
                                <a href="{{ route('verification.show', $claim->id) }}" class="shrink-0 text-orange-800 font-bold hover:text-orange-950 text-xs">
                                    Claim #{{ $claim->id }} Dossier &rarr;
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </article>
            @empty
            <div class="p-6 bg-white rounded-xl border border-stone-200 text-xs text-stone-500">
                This source has not yet been linked to an active evidence node.
            </div>
            @endforelse
        </div>
    </div>

    <!-- API Link Banner -->
    <div class="bg-stone-900 text-stone-200 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 border border-stone-800">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold tracking-widest text-amber-400 uppercase">Bibliographic Data API</span>
            <h3 class="text-base sm:text-lg font-bold text-white">REST API v1 Source Resource</h3>
            <p class="text-xs text-stone-400 max-w-xl">
                Fetch this bibliographic record and its complete evidentiary citations via the public REST API.
            </p>
        </div>
        <a href="{{ url('/api/v1/sources/' . $source->id) }}" target="_blank" class="shrink-0 px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-stone-950 rounded-xl text-xs font-bold shadow-sm transition">
            GET /api/v1/sources/{{ $source->id }}
        </a>
    </div>

</div>
@endsection
