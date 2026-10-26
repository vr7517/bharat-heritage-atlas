@extends('layouts.app')

@section('title', 'Open Heritage Datasets & Spatial Archives — Bharat Heritage Atlas')
@section('meta_description', 'Open-access archaeological, epigraphical, and spatial GIS datasets of ancient and medieval India published under open licenses.')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Hero / Ethos Banner -->
    <div class="bg-gradient-to-br from-stone-900 via-stone-900 to-amber-950 text-white rounded-3xl p-8 sm:p-12 shadow-sm relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 w-80 h-80 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 max-w-3xl space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                <span>📊 OPEN ARCHAEOLOGICAL DATA</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white leading-tight">
                Open Access Indian Heritage Datasets & Spatial GIS Archives
            </h1>
            <p class="text-sm sm:text-base text-stone-300 leading-relaxed">
                Download verified archaeological coordinates, epigraphical rubbings corpora, and stratigraphical chronologies published under open licenses for research, academic computation, and digital humanities.
            </p>
        </div>

        <!-- Metric Badges -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-8 border-t border-stone-800/80">
            <div>
                <span class="text-xs text-stone-400 font-medium">Published Datasets</span>
                <p class="text-2xl font-bold text-amber-400 font-mono mt-0.5">{{ $stats['total_datasets'] }}</p>
            </div>
            <div>
                <span class="text-xs text-stone-400 font-medium">Documented Records</span>
                <p class="text-2xl font-bold text-white font-mono mt-0.5">{{ number_format($stats['total_records']) }}+</p>
            </div>
            <div>
                <span class="text-xs text-stone-400 font-medium">GeoJSON Spatial Files</span>
                <p class="text-2xl font-bold text-emerald-400 font-mono mt-0.5">{{ $stats['geojson_count'] }}</p>
            </div>
            <div>
                <span class="text-xs text-stone-400 font-medium">Tabular CSV Corpora</span>
                <p class="text-2xl font-bold text-amber-300 font-mono mt-0.5">{{ $stats['csv_count'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <form action="{{ route('datasets.index') }}" method="GET"
        class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-2xs grid grid-cols-1 sm:grid-cols-12 gap-4 text-xs">

        <div class="sm:col-span-6">
            <label for="search" class="sr-only">Search</label>
            <div class="relative">
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Search by dataset title, institution, or description..."
                    class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
            </div>
        </div>

        <div class="sm:col-span-3">
            <select name="type"
                class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                <option value="">All Formats & Typologies</option>
                @foreach($availableTypes as $t)
                <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-3 flex items-center gap-2">
            <button type="submit"
                class="flex-1 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                Search Datasets
            </button>
            @if(request()->anyFilled(['search', 'type', 'license']))
            <a href="{{ route('datasets.index') }}"
                class="px-3 py-2.5 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 transition">
                Reset
            </a>
            @endif
        </div>
    </form>

    <!-- Datasets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($datasets as $dataset)
        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-2xs hover:shadow-xs hover:border-amber-500/50 transition duration-200 flex flex-col justify-between overflow-hidden">
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold font-mono bg-stone-100 text-stone-800 border border-stone-200">
                        {{ $dataset->dataset_type }}
                    </span>
                    <div class="flex items-center gap-1.5">
                        @if($dataset->is_featured)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                            ⭐ Featured
                        </span>
                        @endif
                        <span class="text-xs font-mono text-stone-400">v{{ $dataset->version }}</span>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-stone-900 text-base leading-snug">
                        <a href="{{ route('datasets.show', $dataset->slug) }}" class="hover:text-amber-800 transition">
                            {{ $dataset->name }}
                        </a>
                    </h3>
                    <p class="text-xs text-stone-500 mt-1 line-clamp-1 font-medium">
                        {{ $dataset->source_organization }}
                    </p>
                </div>

                @if($dataset->description)
                <p class="text-xs text-stone-600 leading-relaxed line-clamp-3">
                    {{ $dataset->description }}
                </p>
                @endif

                <div class="pt-2 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-500 font-mono">
                    <span>
                        @if($dataset->record_count)
                        <strong>{{ number_format($dataset->record_count) }}</strong> records
                        @else
                        Structured dataset
                        @endif
                    </span>
                    <span>{{ $dataset->formatted_file_size }}</span>
                </div>
            </div>

            <div class="p-6 pt-0 flex items-center justify-between gap-2 border-t border-stone-100/80 mt-2 bg-stone-50/50 py-3.5">
                <a href="{{ route('datasets.show', $dataset->slug) }}" class="text-xs font-bold text-stone-700 hover:text-stone-900 hover:underline">
                    Metadata & Schema &rarr;
                </a>

                @if($dataset->file_path)
                <a href="{{ route('datasets.download', $dataset->slug) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-2xs transition">
                    <span>Download</span>
                    <span>&darr;</span>
                </a>
                @else
                <span class="text-xs text-stone-400 italic">Documentation Only</span>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center rounded-2xl bg-white border border-stone-200">
            <div class="w-12 h-12 rounded-full bg-stone-100 text-stone-400 mx-auto flex items-center justify-center text-xl mb-3">
                📊
            </div>
            <h3 class="text-base font-bold text-stone-900">No open datasets found matching your filters</h3>
            <p class="text-xs text-stone-500 mt-1 max-w-md mx-auto">
                Try clearing search terms or selecting a different data typology format.
            </p>
        </div>
        @endforelse
    </div>

    @if($datasets->hasPages())
    <div class="pt-4">
        {{ $datasets->links() }}
    </div>
    @endif

</div>
@endsection
