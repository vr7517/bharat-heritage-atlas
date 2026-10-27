@extends('layouts.app')

@section('title', __('ui.nav_sites') . ' — ' . __('ui.brand_title'))
@section('meta_description', 'Explore verified ancient Indian monuments, Buddhist stupas, monolithic pillars, and rock-cut temple complexes with in situ stratigraphy.')

@section('content')
<div class="bg-[#faf8f5] py-8 sm:py-10 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span aria-hidden="true">/</span>
            <span class="text-stone-800 font-bold">{{ __('ui.nav_sites') }}</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-stone-900 flex items-center gap-2.5">
                    <span aria-hidden="true">🏛️</span>
                    <span>{{ __('ui.verified_sites') }}</span>
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1 max-w-2xl">
                    {{ __('ui.verified_sites_sub') }}
                </p>
            </div>
            <div class="font-mono text-xs text-stone-600 bg-white px-3.5 py-2 rounded-xl border border-stone-200 shadow-2xs shrink-0">
                Cataloged Complexes: <strong class="text-orange-950 font-bold text-sm">{{ $sites->total() }}</strong>
            </div>
        </div>

        <!-- Filter Bar Form -->
        <form action="{{ route('sites.index') }}" method="GET" class="mt-6 bg-white p-4 sm:p-5 rounded-2xl border border-stone-200/90 shadow-xs grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label for="search-input" class="sr-only">Search</label>
                <input id="search-input" type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ __('ui.search_placeholder') }}" 
                       class="w-full min-h-[44px] text-xs px-3.5 py-2 rounded-xl border border-stone-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500" />
            </div>
            <div>
                <label for="type-select" class="sr-only">Typology</label>
                <select id="type-select" name="site_type" class="w-full min-h-[44px] text-xs px-3 py-2 rounded-xl border border-stone-300 bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">{{ __('ui.all_typologies') }}</option>
                    @foreach($siteTypes as $type)
                    <option value="{{ $type }}" @selected(request('site_type') == $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="period-select" class="sr-only">Period</label>
                <select id="period-select" name="period" class="w-full min-h-[44px] text-xs px-3 py-2 rounded-xl border border-stone-300 bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">{{ __('ui.all_periods') }}</option>
                    @foreach($periods as $per)
                    <option value="{{ $per->slug }}" @selected(request('period') == $per->slug)>{{ $per->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full min-h-[44px] px-5 py-2 bg-orange-800 hover:bg-orange-900 text-white rounded-xl text-xs font-bold shadow-xs transition">
                    {{ __('ui.filter') }}
                </button>
                @if(request()->anyFilled(['search', 'site_type', 'period', 'state']))
                <a href="{{ route('sites.index') }}" class="min-h-[44px] px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-semibold transition flex items-center justify-center">
                    {{ __('ui.reset') }}
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($sites as $site)
        @php
            $cover = $site->coverImage();
        @endphp
        <article class="bg-white rounded-2xl border border-stone-200/90 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
            
            <!-- Cover Photo / Archival Pattern Placeholder -->
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

                <!-- Badges Overlay -->
                <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-stone-900/85 backdrop-blur-md text-amber-300 border border-amber-400/30 shadow-xs">
                        {{ $site->primaryPeriod?->name ?? __('ui.ancient_period') }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/95 backdrop-blur-md text-stone-800 shadow-xs border border-stone-200">
                        {{ $site->location?->state ?? 'India' }}
                    </span>
                </div>
            </div>

            <!-- Content Header -->
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

                <h2 class="text-xl font-bold text-stone-900 group-hover:text-orange-800 transition">
                    <a href="{{ route('sites.show', $site->slug) }}">
                        {{ $site->name }}
                    </a>
                </h2>

                <div class="mt-2.5 inline-flex items-center gap-1.5 text-xs text-stone-600 font-medium bg-amber-50/80 px-2.5 py-1 rounded-lg border border-amber-200/70">
                    <span class="text-amber-700" aria-hidden="true">⏳</span>
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
                    <span>🏺 {{ $site->objects_count }} {{ __('ui.artifacts_count') }}</span>
                    <span>•</span>
                    <span>📜 {{ $site->inscriptions_count }}</span>
                </div>
                <a href="{{ route('sites.show', $site->slug) }}" class="min-h-[44px] inline-flex items-center font-bold text-orange-800 hover:text-orange-950 transition gap-1">
                    <span>{{ __('ui.details') }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-20 bg-white rounded-3xl border border-stone-200 p-8 shadow-xs">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-800 mx-auto flex items-center justify-center text-3xl mb-4 border border-amber-200">
                🏛️
            </div>
            <h3 class="text-lg font-bold text-stone-900">{{ __('ui.no_records_found') }}</h3>
            <p class="text-xs sm:text-sm text-stone-600 mt-2 max-w-md mx-auto">
                {{ __('ui.no_records_suggestion') }}
            </p>
            <div class="mt-6">
                <a href="{{ route('sites.index') }}" class="min-h-[44px] inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                    {{ __('ui.clear_filters') }}
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $sites->links() }}
    </div>
</div>
@endsection
