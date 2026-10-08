@extends('layouts.app')

@section('title', 'Epigraphical Corpus & Brahmi Facsimiles — Bharat Heritage Atlas')
@section('meta_description', 'Corpus of ancient Indian inscriptions in Brahmi and Kharosthi scripts with verbatim transcriptions, academic transliterations, and verified translations.')

@section('content')
<div class="bg-[#faf8f5] py-8 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold">Epigraphy Corpus</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">
                    📜 Epigraphical Corpus & Brahmi Facsimiles
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1">
                    Deciphered primary epigraphs in ancient Brahmi and Kharosthi scripts, with verbatim transcriptions and translations from Epigraphia Indica.
                </p>
            </div>
            <div class="font-mono text-xs text-stone-600 bg-white px-3 py-1.5 rounded-lg border border-stone-200 shadow-2xs">
                Total Inscriptions: <strong class="text-orange-950">{{ $inscriptions->total() }}</strong>
            </div>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('inscriptions.index') }}" method="GET" class="mt-6 bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search inscription, ruler, donor..." class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 focus:ring-amber-500 focus:border-amber-500" />
            </div>
            <div>
                <select name="script" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white">
                    <option value="">All Ancient Scripts</option>
                    @foreach($scripts as $sc)
                    <option value="{{ $sc }}" @selected(request('script') == $sc)>{{ $sc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-orange-800 hover:bg-orange-900 text-white rounded-lg text-xs font-semibold shadow-2xs transition">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'script', 'language']))
                <a href="{{ route('inscriptions.index') }}" class="px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-medium transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($inscriptions as $insc)
        <article class="bg-stone-900 text-stone-100 rounded-2xl p-6 border border-stone-800 shadow-md hover:border-amber-500/50 transition flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="px-2 py-0.5 rounded font-mono font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px]">
                        {{ $insc->script }} • {{ $insc->language }}
                    </span>
                    <span class="text-stone-400 font-mono text-[11px]">
                        {{ $insc->dating_statement }}
                    </span>
                </div>

                <h2 class="text-lg font-bold text-white hover:text-amber-300 transition">
                    <a href="{{ route('inscriptions.show', $insc->slug) }}">
                        {{ $insc->title }}
                    </a>
                </h2>

                @if($insc->raw_text || $insc->transliteration)
                <div class="bg-stone-950 p-3.5 rounded-xl border border-stone-800 font-mono text-xs text-amber-200 overflow-x-auto leading-relaxed">
                    {{ Str::limit($insc->raw_text ?? $insc->transliteration, 120) }}
                </div>
                @endif

                <p class="text-xs text-stone-300 italic leading-relaxed line-clamp-3">
                    "{{ $insc->translation }}"
                </p>
            </div>

            <div class="pt-3 border-t border-stone-800 flex items-center justify-between text-xs text-stone-400">
                <span class="truncate max-w-[200px]">📍 {{ $insc->heritageSite?->name ?? 'Archaeological Site' }}</span>
                <a href="{{ route('inscriptions.show', $insc->slug) }}" class="font-bold text-amber-400 hover:text-amber-300 shrink-0 ml-2">
                    Facsimile &rarr;
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-2 text-center py-16 bg-white rounded-2xl border border-stone-200">
            <div class="text-3xl mb-2">📜</div>
            <p class="text-stone-500 text-sm">No epigraphs matching your criteria.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $inscriptions->links() }}
    </div>
</div>
@endsection
