@extends('layouts.app')

@section('title', 'Heritage Monuments & In Situ Complexes — Bharat Heritage Atlas')
@section('meta_description', 'Explore verified ancient Indian monuments, Buddhist stupas, monolithic pillars, and rock-cut temple complexes with in situ stratigraphy.')

@section('content')
<div class="bg-[#faf8f5] py-8 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold">Heritage Monuments</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">
                    🏛️ Ancient Monuments & In Situ Complexes
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1">
                    Verified archaeological sites documented with in situ stratigraphy, astronomical year bounds, and epigraphical facsimiles.
                </p>
            </div>
            <div class="font-mono text-xs text-stone-600 bg-white px-3 py-1.5 rounded-lg border border-stone-200 shadow-2xs">
                Total Cataloged: <strong class="text-orange-950">{{ $sites->total() }}</strong>
            </div>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('sites.index') }}" method="GET" class="mt-6 bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search site or location..." class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 focus:ring-amber-500 focus:border-amber-500" />
            </div>
            <div>
                <select name="site_type" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white">
                    <option value="">All Typologies</option>
                    @foreach($siteTypes as $type)
                    <option value="{{ $type }}" @selected(request('site_type') == $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="period" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white">
                    <option value="">All Periods</option>
                    @foreach($periods as $per)
                    <option value="{{ $per->slug }}" @selected(request('period') == $per->slug)>{{ $per->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-orange-800 hover:bg-orange-900 text-white rounded-lg text-xs font-semibold shadow-2xs transition">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'site_type', 'period', 'state']))
                <a href="{{ route('sites.index') }}" class="px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-medium transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sites as $site)
        <article class="bg-white rounded-2xl border border-stone-200/90 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
            <div class="p-6 pb-4">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                        {{ $site->primaryPeriod?->name ?? 'Ancient Era' }}
                    </span>
                    @if($site->primaryDynasty)
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-700">
                        {{ $site->primaryDynasty->name }}
                    </span>
                    @endif
                    <span class="text-[11px] text-stone-500 font-mono ml-auto">
                        {{ $site->location?->state ?? 'India' }}
                    </span>
                </div>

                <h2 class="text-lg font-bold text-stone-900 group-hover:text-orange-800 transition">
                    <a href="{{ route('sites.show', $site->slug) }}">
                        {{ $site->name }}
                    </a>
                </h2>

                <div class="mt-2 text-xs text-stone-600 flex items-center gap-1.5 font-medium">
                    <span class="text-amber-700">⏳</span>
                    <span>{{ $site->dating_statement }}</span>
                </div>
            </div>

            <div class="px-6 py-2 flex-grow">
                <p class="text-xs text-stone-600 leading-relaxed line-clamp-3">
                    {{ $site->summary }}
                </p>
            </div>

            <div class="p-6 pt-4 border-t border-stone-100 bg-stone-50/50 flex items-center justify-between text-xs text-stone-500">
                <div class="flex items-center gap-3">
                    <span>🏺 {{ $site->objects_count }} Relics</span>
                    <span>•</span>
                    <span>📜 {{ $site->inscriptions_count }} Inscriptions</span>
                </div>
                <a href="{{ route('sites.show', $site->slug) }}" class="font-bold text-orange-800 hover:text-orange-950 flex items-center gap-1">
                    Dossier &rarr;
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-stone-200">
            <div class="text-3xl mb-2">🏛️</div>
            <p class="text-stone-500 text-sm">No monuments matching your criteria.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $sites->links() }}
    </div>
</div>
@endsection
