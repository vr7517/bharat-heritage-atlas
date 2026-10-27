@extends('layouts.app')

@section('title', __('ui.nav_inscriptions') . ' — ' . __('ui.brand_title'))
@section('meta_description', 'Corpus of ancient Indian inscriptions in Brahmi and Kharosthi scripts with verbatim transcriptions, academic transliterations, and verified translations.')

@section('content')
<div class="bg-[#faf8f5] py-8 sm:py-10 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span aria-hidden="true">/</span>
            <span class="text-stone-800 font-bold">{{ __('ui.nav_inscriptions') }}</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-stone-900 flex items-center gap-2.5">
                    <span aria-hidden="true">📜</span>
                    <span>{{ __('ui.epigraphy_title') }}</span>
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1 max-w-2xl">
                    Deciphered primary epigraphs in ancient Brahmi and Kharosthi scripts, with verbatim transcriptions and translations from Epigraphia Indica.
                </p>
            </div>
            <div class="font-mono text-xs text-stone-600 bg-white px-3.5 py-2 rounded-xl border border-stone-200 shadow-2xs shrink-0">
                Inscriptions Cataloged: <strong class="text-orange-950 font-bold text-sm">{{ $inscriptions->total() }}</strong>
            </div>
        </div>

        <!-- Filter Bar Form -->
        <form action="{{ route('inscriptions.index') }}" method="GET" class="mt-6 bg-white p-4 sm:p-5 rounded-2xl border border-stone-200/90 shadow-xs grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label for="insc-search" class="sr-only">Search</label>
                <input id="insc-search" type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search inscription, ruler, donor..." 
                       class="w-full min-h-[44px] text-xs px-3.5 py-2 rounded-xl border border-stone-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500" />
            </div>
            <div>
                <label for="script-select" class="sr-only">Script</label>
                <select id="script-select" name="script" class="w-full min-h-[44px] text-xs px-3 py-2 rounded-xl border border-stone-300 bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">All Ancient Scripts</option>
                    @foreach($scripts as $sc)
                    <option value="{{ $sc }}" @selected(request('script') == $sc)>{{ $sc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full min-h-[44px] px-5 py-2 bg-orange-800 hover:bg-orange-900 text-white rounded-xl text-xs font-bold shadow-xs transition">
                    {{ __('ui.filter') }}
                </button>
                @if(request()->anyFilled(['search', 'script', 'language']))
                <a href="{{ route('inscriptions.index') }}" class="min-h-[44px] px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-semibold transition flex items-center justify-center">
                    {{ __('ui.reset') }}
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($inscriptions as $insc)
        @php
            $cover = $insc->coverImage();
        @endphp
        <article class="bg-stone-900 text-stone-100 rounded-3xl p-6 sm:p-7 border border-stone-800 shadow-lg hover:border-amber-500/60 transition-all flex flex-col justify-between space-y-5 group">
            <div class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                    <span class="px-2.5 py-1 rounded-md font-mono font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 text-[11px]">
                        {{ $insc->script }} • {{ $insc->language }}
                    </span>
                    <span class="text-stone-400 font-mono text-xs">
                        ⏳ {{ $insc->dating_statement }}
                    </span>
                </div>

                @if($cover)
                <div class="w-full h-40 rounded-2xl overflow-hidden bg-stone-950 border border-stone-800">
                    <img src="{{ $cover->url }}" 
                         alt="{{ $cover->alt_text ?? $insc->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                         loading="lazy">
                </div>
                @endif

                <h2 class="text-xl font-bold text-white group-hover:text-amber-300 transition leading-snug">
                    <a href="{{ route('inscriptions.show', $insc->slug) }}">
                        {{ $insc->title }}
                    </a>
                </h2>

                @if($insc->raw_text || $insc->transliteration)
                <div class="bg-stone-950/90 p-4 rounded-2xl border border-stone-800/90 font-mono text-xs text-amber-200 overflow-x-auto leading-relaxed shadow-inner">
                    <div class="text-[10px] text-amber-500/80 uppercase tracking-wider mb-1 font-sans font-bold">Verbatim Glyphs / Transliteration:</div>
                    {{ Str::limit($insc->raw_text ?? $insc->transliteration, 150) }}
                </div>
                @endif

                <p class="text-xs sm:text-sm text-stone-300 italic leading-relaxed line-clamp-3">
                    "{{ $insc->translation }}"
                </p>
            </div>

            <div class="pt-4 border-t border-stone-800 flex items-center justify-between text-xs text-stone-400">
                <span class="truncate max-w-[60%]">📍 {{ $insc->heritageSite?->name ?? 'Archaeological Site' }}</span>
                <a href="{{ route('inscriptions.show', $insc->slug) }}" class="min-h-[44px] inline-flex items-center font-bold text-amber-400 hover:text-amber-300 shrink-0 gap-1">
                    <span>{{ __('ui.epigraph_dossier') }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-2 text-center py-20 bg-white rounded-3xl border border-stone-200 p-8 shadow-xs">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-800 mx-auto flex items-center justify-center text-3xl mb-4 border border-amber-200">
                📜
            </div>
            <h3 class="text-lg font-bold text-stone-900">{{ __('ui.no_records_found') }}</h3>
            <p class="text-xs sm:text-sm text-stone-600 mt-2 max-w-md mx-auto">
                {{ __('ui.no_records_suggestion') }}
            </p>
            <div class="mt-6">
                <a href="{{ route('inscriptions.index') }}" class="min-h-[44px] inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                    {{ __('ui.clear_filters') }}
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $inscriptions->links() }}
    </div>
</div>
@endsection
