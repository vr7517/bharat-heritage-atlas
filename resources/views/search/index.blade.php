@extends('layouts.app')

@section('title', 'Advanced Heritage Search & Cross-Corpus Filter — Bharat Heritage Atlas')
@section('meta_description', 'Multi-disciplinary search across ancient Indian monuments, epigraphical corpuses, artifacts, dynastic horizons, and academic sources.')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Top Search Header -->
    <div class="bg-gradient-to-br from-stone-900 via-stone-900 to-amber-950 text-white rounded-3xl p-6 sm:p-10 shadow-lg space-y-6">
        <div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                Cross-Corpus Knowledge Graph
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white mt-2">
                Advanced Heritage Search & Multi-Disciplinary Explorer
            </h1>
            <p class="text-xs sm:text-sm text-stone-300 mt-1 max-w-2xl">
                Search simultaneously across archaeological monuments, portable relics, Brahmi inscriptions, peer-reviewed sources, and open research datasets.
            </p>
        </div>

        <!-- Search Bar Form -->
        <form action="{{ route('search.index') }}" method="GET" class="space-y-4">
            <input type="hidden" name="type" value="{{ $entityType }}" />

            <div class="relative flex items-center">
                <input type="text" name="q" value="{{ $q }}"
                    placeholder="{{ __('ui.search_placeholder') }}"
                    class="w-full rounded-2xl bg-white text-stone-900 px-5 py-3.5 pr-28 text-sm focus:outline-hidden focus:ring-4 focus:ring-amber-500/30 shadow-lg placeholder:text-stone-400 font-medium min-h-[48px]" />

                <button type="submit"
                    class="absolute right-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition min-h-[40px]">
                    {{ __('ui.nav_search') }}
                </button>
            </div>

            <!-- Contextual Filter Chips -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs pt-1">
                <div>
                    <select name="period" onchange="this.form.submit()"
                        class="w-full rounded-xl bg-stone-800/90 text-stone-200 border border-stone-700 px-3 py-2 text-xs focus:ring-1 focus:ring-amber-500 focus:border-amber-500 min-h-[40px]">
                        <option value="">{{ __('ui.all_periods') }}</option>
                        @foreach($periods as $p)
                        <option value="{{ $p->slug }}" @selected($periodSlug === $p->slug)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="dynasty" onchange="this.form.submit()"
                        class="w-full rounded-xl bg-stone-800/90 text-stone-200 border border-stone-700 px-3 py-2 text-xs focus:ring-1 focus:ring-amber-500 focus:border-amber-500 min-h-[40px]">
                        <option value="">{{ __('ui.all_dynasties') }}</option>
                        @foreach($dynasties as $d)
                        <option value="{{ $d->slug }}" @selected($dynastySlug === $d->slug)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="state" onchange="this.form.submit()"
                        class="w-full rounded-xl bg-stone-800/90 text-stone-200 border border-stone-700 px-3 py-2 text-xs focus:ring-1 focus:ring-amber-500 focus:border-amber-500 min-h-[40px]">
                        <option value="">{{ __('ui.all_states') }}</option>
                        @foreach($states as $s)
                        <option value="{{ $s }}" @selected($state === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="consensus" onchange="this.form.submit()"
                        class="w-full rounded-xl bg-stone-800/90 text-stone-200 border border-stone-700 px-3 py-2 text-xs focus:ring-1 focus:ring-amber-500 focus:border-amber-500 min-h-[40px]">
                        <option value="">{{ __('ui.all_consensus') }}</option>
                        <option value="STRONG_CONSENSUS" @selected($consensus === 'STRONG_CONSENSUS')>{{ __('ui.strong_consensus') }}</option>
                        <option value="PROBABLE" @selected($consensus === 'PROBABLE')>Probable</option>
                        <option value="SCHOLARLY_DEBATE" @selected($consensus === 'SCHOLARLY_DEBATE')>{{ __('ui.scholarly_debate') }}</option>
                        <option value="CONTESTED" @selected($consensus === 'CONTESTED')>{{ __('ui.contested') }}</option>
                        <option value="SPECULATIVE" @selected($consensus === 'SPECULATIVE')>{{ __('ui.speculative') }}</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Entity Tabs -->
    <div class="border-b border-stone-200">
        <nav class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-semibold">
            @php
                $tabParams = array_filter(['q' => $q, 'period' => $periodSlug, 'dynasty' => $dynastySlug, 'state' => $state, 'consensus' => $consensus]);
            @endphp

            <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'all'])) }}"
                class="px-4 py-2 rounded-xl transition shrink-0 min-h-[40px] flex items-center {{ $entityType === 'all' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                All Results ({{ $counts['total'] }})
            </a>

            <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'sites'])) }}"
                class="px-4 py-2 rounded-xl transition shrink-0 min-h-[40px] flex items-center {{ $entityType === 'sites' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                🏛️ Sites ({{ $counts['sites'] }})
            </a>

            <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'objects'])) }}"
                class="px-4 py-2 rounded-xl transition shrink-0 min-h-[40px] flex items-center {{ $entityType === 'objects' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                🏺 Artifacts ({{ $counts['objects'] }})
            </a>

            <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'inscriptions'])) }}"
                class="px-4 py-2 rounded-xl transition shrink-0 min-h-[40px] flex items-center {{ $entityType === 'inscriptions' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                📜 Epigraphy ({{ $counts['inscriptions'] }})
            </a>

            <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'datasets'])) }}"
                class="px-4 py-2 rounded-xl transition shrink-0 min-h-[40px] flex items-center {{ $entityType === 'datasets' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                📊 Datasets ({{ $counts['datasets'] }})
            </a>

            <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'sources'])) }}"
                class="px-4 py-2 rounded-xl transition shrink-0 min-h-[40px] flex items-center {{ $entityType === 'sources' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                📚 Sources ({{ $counts['sources'] }})
            </a>

            <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'claims'])) }}"
                class="px-4 py-2 rounded-xl transition shrink-0 min-h-[40px] flex items-center {{ $entityType === 'claims' ? 'bg-stone-900 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                ⚖️ Claims ({{ $counts['claims'] }})
            </a>
        </nav>
    </div>

    <!-- Results Section -->
    <div class="space-y-10">

        <!-- 1. Heritage Sites -->
        @if($sites->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-stone-200/80 pb-2">
                <h2 class="text-sm font-bold uppercase tracking-wider text-stone-900 flex items-center gap-2">
                    <span>🏛️ Heritage Sites & Monuments</span>
                    <span class="text-stone-400 font-normal">({{ $counts['sites'] }})</span>
                </h2>
                @if($entityType === 'all' && $counts['sites'] > 6)
                <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'sites'])) }}" class="text-xs font-bold text-amber-800 hover:underline">
                    View all {{ $counts['sites'] }} sites &rarr;
                </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sites as $site)
                <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs overflow-hidden flex flex-col justify-between hover:border-amber-500/50 transition">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between text-[11px] font-mono text-stone-500">
                            <span>{{ $site->site_type }}</span>
                            <span>{{ $site->location?->state }}</span>
                        </div>
                        <h3 class="font-bold text-stone-900 text-base">
                            <a href="{{ route('sites.show', $site->slug) }}" class="hover:text-amber-800">
                                {{ $site->name }}
                            </a>
                        </h3>
                        <p class="text-xs text-stone-600 line-clamp-2">{{ $site->summary }}</p>
                    </div>

                    <div class="p-5 pt-0 border-t border-stone-100 mt-2 flex items-center justify-between text-xs py-3 bg-stone-50/50">
                        <span class="font-medium text-stone-600">{{ $site->dating_statement }}</span>
                        <a href="{{ route('sites.show', $site->slug) }}" class="font-bold text-amber-800 hover:underline">
                            Explore Site &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 2. Heritage Objects / Artifacts -->
        @if($objects->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-stone-200/80 pb-2">
                <h2 class="text-sm font-bold uppercase tracking-wider text-stone-900 flex items-center gap-2">
                    <span>🏺 Artifacts, Sculptures & Material Relics</span>
                    <span class="text-stone-400 font-normal">({{ $counts['objects'] }})</span>
                </h2>
                @if($entityType === 'all' && $counts['objects'] > 6)
                <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'objects'])) }}" class="text-xs font-bold text-amber-800 hover:underline">
                    View all {{ $counts['objects'] }} artifacts &rarr;
                </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($objects as $obj)
                <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs overflow-hidden flex flex-col justify-between hover:border-amber-500/50 transition">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between text-[11px] font-mono text-stone-500">
                            <span>{{ $obj->object_type }}</span>
                            <span class="truncate max-w-[120px]">{{ $obj->material }}</span>
                        </div>
                        <h3 class="font-bold text-stone-900 text-base">
                            <a href="{{ route('objects.show', $obj->slug) }}" class="hover:text-amber-800">
                                {{ $obj->name }}
                            </a>
                        </h3>
                        <p class="text-xs text-stone-500 line-clamp-1 font-medium">{{ $obj->current_repository }}</p>
                        <p class="text-xs text-stone-600 line-clamp-2">{{ $obj->description }}</p>
                    </div>

                    <div class="p-5 pt-0 border-t border-stone-100 mt-2 flex items-center justify-between text-xs py-3 bg-stone-50/50">
                        <span class="font-medium text-stone-600">{{ $obj->dating_statement }}</span>
                        <a href="{{ route('objects.show', $obj->slug) }}" class="font-bold text-amber-800 hover:underline">
                            View Artifact &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 3. Inscriptions / Epigraphy -->
        @if($inscriptions->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-stone-200/80 pb-2">
                <h2 class="text-sm font-bold uppercase tracking-wider text-stone-900 flex items-center gap-2">
                    <span>📜 Epigraphy & Brahmi Inscriptions</span>
                    <span class="text-stone-400 font-normal">({{ $counts['inscriptions'] }})</span>
                </h2>
                @if($entityType === 'all' && $counts['inscriptions'] > 6)
                <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'inscriptions'])) }}" class="text-xs font-bold text-amber-800 hover:underline">
                    View all {{ $counts['inscriptions'] }} epigraphs &rarr;
                </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($inscriptions as $insc)
                <div class="bg-white rounded-2xl border border-stone-200/90 p-5 shadow-2xs hover:border-amber-500/50 transition space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 font-mono">{{ $insc->language }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-stone-100 text-stone-700">{{ $insc->script }}</span>
                        <span class="text-xs text-stone-400 font-mono ml-auto">{{ $insc->dating_statement }}</span>
                    </div>

                    <h3 class="font-bold text-stone-900 text-sm">
                        <a href="{{ route('inscriptions.show', $insc->slug) }}" class="hover:text-amber-800">
                            {{ $insc->title }}
                        </a>
                    </h3>

                    <p class="text-xs text-stone-600 italic line-clamp-2">“{{ $insc->translation }}”</p>

                    <div class="pt-2 border-t border-stone-100 flex items-center justify-between text-xs">
                        <span class="text-[11px] font-mono text-stone-500 truncate max-w-xs">{{ $insc->epigraphic_reference }}</span>
                        <a href="{{ route('inscriptions.show', $insc->slug) }}" class="font-bold text-amber-800 hover:underline shrink-0">
                            Facsimile &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 4. Open Datasets -->
        @if($datasets->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-stone-200/80 pb-2">
                <h2 class="text-sm font-bold uppercase tracking-wider text-stone-900 flex items-center gap-2">
                    <span>📊 Open Datasets & GIS Spatial Files</span>
                    <span class="text-stone-400 font-normal">({{ $counts['datasets'] }})</span>
                </h2>
                @if($entityType === 'all' && $counts['datasets'] > 6)
                <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'datasets'])) }}" class="text-xs font-bold text-amber-800 hover:underline">
                    View all {{ $counts['datasets'] }} datasets &rarr;
                </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($datasets as $ds)
                <div class="bg-white rounded-2xl border border-stone-200/90 p-5 shadow-2xs hover:border-amber-500/50 transition flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-stone-100 text-stone-800 border border-stone-200">{{ $ds->dataset_type }}</span>
                            <span class="text-[11px] font-mono text-stone-500">{{ $ds->formatted_file_size }}</span>
                        </div>
                        <h3 class="font-bold text-stone-900 text-sm">
                            <a href="{{ route('datasets.show', $ds->slug) }}" class="hover:text-amber-800">
                                {{ $ds->name }}
                            </a>
                        </h3>
                        <p class="text-xs text-stone-500">{{ $ds->source_organization }}</p>
                    </div>

                    <div class="pt-4 border-t border-stone-100 mt-3 flex items-center justify-between text-xs">
                        <a href="{{ route('datasets.show', $ds->slug) }}" class="text-stone-700 font-semibold hover:underline">
                            Metadata
                        </a>
                        @if($ds->file_path)
                        <a href="{{ route('datasets.download', $ds->slug) }}" class="font-bold text-amber-800 hover:underline">
                            Download &darr;
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 5. Bibliographic Sources -->
        @if($sources->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-stone-200/80 pb-2">
                <h2 class="text-sm font-bold uppercase tracking-wider text-stone-900 flex items-center gap-2">
                    <span>📚 Bibliographic Sources & Primary Corpora</span>
                    <span class="text-stone-400 font-normal">({{ $counts['sources'] }})</span>
                </h2>
                @if($entityType === 'all' && $counts['sources'] > 6)
                <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'sources'])) }}" class="text-xs font-bold text-amber-800 hover:underline">
                    View all {{ $counts['sources'] }} sources &rarr;
                </a>
                @endif
            </div>

            <div class="space-y-3">
                @foreach($sources as $src)
                <div class="bg-white rounded-xl border border-stone-200 p-4 shadow-2xs text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-stone-100 text-stone-800 uppercase font-mono">{{ $src->source_type }}</span>
                            <span class="text-stone-400 font-mono">{{ $src->publication_year }}</span>
                        </div>
                        <h3 class="font-bold text-stone-900 text-sm">
                            <a href="{{ route('sources.show', $src->id) }}" class="hover:text-amber-800">
                                {{ $src->title }}
                            </a>
                        </h3>
                        <p class="text-stone-600 font-medium">{{ $src->authors }} @if($src->publisher) • {{ $src->publisher }} @endif</p>
                    </div>

                    <a href="{{ route('sources.show', $src->id) }}" class="font-bold text-amber-800 hover:underline shrink-0 text-xs">
                        View Citation &rarr;
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 6. Evidentiary Claims -->
        @if($claims->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-stone-200/80 pb-2">
                <h2 class="text-sm font-bold uppercase tracking-wider text-stone-900 flex items-center gap-2">
                    <span>⚖️ Peer-Reviewed Claims & Scholarly Consensus</span>
                    <span class="text-stone-400 font-normal">({{ $counts['claims'] }})</span>
                </h2>
                @if($entityType === 'all' && $counts['claims'] > 6)
                <a href="{{ route('search.index', array_merge($tabParams, ['type' => 'claims'])) }}" class="text-xs font-bold text-amber-800 hover:underline">
                    View all {{ $counts['claims'] }} claims &rarr;
                </a>
                @endif
            </div>

            <div class="space-y-3">
                @foreach($claims as $cl)
                <div class="bg-white rounded-xl border border-stone-200 p-4 shadow-2xs text-xs space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-stone-100 text-stone-800 uppercase font-mono">{{ $cl->claim_type }}</span>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ in_array($cl->consensus_status, ['SETTLED_CONSENSUS', 'STRONG_CONSENSUS']) ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">
                            {{ str_replace('_', ' ', $cl->consensus_status) }}
                        </span>
                    </div>

                    <h3 class="font-bold text-stone-900 text-sm">“{{ $cl->statement }}”</h3>
                    @if($cl->summary_justification)
                    <p class="text-stone-600 italic">{{ $cl->summary_justification }}</p>
                    @endif

                    <div class="pt-2 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-500">
                        <span>{{ $cl->evidence->count() }} attached evidence nodes</span>
                        <a href="{{ route('verification.show', $cl->id) }}" class="font-bold text-amber-800 hover:underline">
                            Verify Consensus Graph &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($counts['total'] === 0)
        <div class="py-20 text-center rounded-3xl bg-white border border-stone-200 p-8 shadow-xs">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-800 mx-auto flex items-center justify-center text-3xl mb-4 border border-amber-200">
                🔍
            </div>
            <h3 class="text-lg font-bold text-stone-900">{{ __('ui.no_records_found') }}</h3>
            <p class="text-xs sm:text-sm text-stone-600 mt-2 max-w-md mx-auto">
                {{ __('ui.no_records_suggestion') }}
            </p>
            <div class="mt-6">
                <a href="{{ route('search.index') }}" class="min-h-[44px] inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                    {{ __('ui.clear_filters') }}
                </a>
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
