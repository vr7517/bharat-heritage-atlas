@extends('layouts.app')

@section('title', 'REST API v1 & Developer Documentation — Bharat Heritage Atlas')
@section('meta_description', 'Official developer documentation and REST API v1 reference for Indian archaeological monuments, GeoJSON spatial archives, Brahmi epigraphical corpora, and bibliographic metadata.')

@section('content')
<div class="space-y-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Hero Header -->
    <div class="bg-gradient-to-br from-stone-900 to-amber-950 text-white rounded-3xl p-6 sm:p-10 shadow-sm space-y-4">
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                Open Access API v1
            </span>
            <span class="text-xs text-stone-400">&bull; RFC 7946 GeoJSON &bull; GODL &amp; CC BY 4.0</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-bold tracking-tight text-white leading-tight">
            REST API v1 & Developer Documentation
        </h1>
        <p class="text-xs sm:text-sm text-stone-300 max-w-2xl leading-relaxed">
            Free and open programmatic access to Indian archaeological stratigraphy, Brahmi epigraphy facsimiles, geospatial coordinates, and peer-reviewed bibliographic citations.
        </p>

        <!-- Base URL Pill -->
        <div class="pt-2 flex flex-wrap items-center gap-3 text-xs">
            <div class="px-3.5 py-2 rounded-xl bg-stone-800 text-stone-200 font-mono text-[11px] border border-stone-700 flex items-center gap-2">
                <span class="text-stone-400">Base URL:</span>
                <span class="text-amber-400 font-bold">{{ url('/api/v1') }}</span>
            </div>
            <a href="{{ route('api.v1.export.geojson') }}" class="px-3.5 py-2 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-200 border border-stone-700 font-medium transition">
                Bulk GeoJSON &darr;
            </a>
            <a href="{{ route('api.v1.export.bibtex') }}" class="px-3.5 py-2 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-200 border border-stone-700 font-medium transition">
                Bulk BibTeX &darr;
            </a>
        </div>
    </div>

    <!-- Quick Start Curl Snippets -->
    <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                <span>💻 Quick Start Examples</span>
            </h2>
            <span class="text-[11px] font-mono text-stone-400">cURL &bull; No Auth Required</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-mono">
            <div class="bg-stone-900 text-amber-200 p-4 rounded-2xl space-y-2 overflow-x-auto">
                <span class="text-stone-400 text-[10px] uppercase font-sans font-bold">1. Fetch Monuments GeoJSON</span>
                <pre class="text-xs">curl -X GET "{{ url('/api/v1/geo/sites') }}" \
  -H "Accept: application/geo+json"</pre>
            </div>

            <div class="bg-stone-900 text-amber-200 p-4 rounded-2xl space-y-2 overflow-x-auto">
                <span class="text-stone-400 text-[10px] uppercase font-sans font-bold">2. Programmatic Cross-Corpus Search</span>
                <pre class="text-xs">curl -X GET "{{ url('/api/v1/search?q=Badami&consensus=STRONG_CONSENSUS') }}" \
  -H "Accept: application/json"</pre>
            </div>
        </div>
    </div>

    <!-- API Endpoints Directory -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-stone-900">API v1 Endpoints Directory</h2>
                <p class="text-xs text-stone-500 mt-0.5">All responses return UTF-8 JSON or RFC 7946 GeoJSON.</p>
            </div>
            <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-full bg-stone-100 text-stone-700">
                {{ count($endpoints) }} Endpoints Available
            </span>
        </div>

        <div class="bg-white rounded-3xl border border-stone-200 shadow-xs overflow-hidden divide-y divide-stone-100 text-xs">
            @foreach($endpoints as $ep)
            <div class="p-5 hover:bg-stone-50/60 transition space-y-2">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <span class="px-2 py-0.5 rounded font-mono font-bold text-[10px] bg-emerald-100 text-emerald-900 border border-emerald-300">
                            {{ $ep['method'] }}
                        </span>
                        <code class="font-bold text-stone-900 text-sm font-mono">{{ $ep['path'] }}</code>
                    </div>
                    <span class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider">
                        {{ $ep['group'] }}
                    </span>
                </div>

                <p class="text-stone-600 text-xs leading-relaxed">
                    {{ $ep['description'] }}
                </p>

                @if(!empty($ep['params']))
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider">Query Params:</span>
                    @foreach($ep['params'] as $p)
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-stone-100 text-stone-700 border border-stone-200">
                        {{ $p }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Licensing & Attribution Terms -->
    <div class="bg-stone-50 rounded-3xl border border-stone-200 p-6 sm:p-8 space-y-3 text-xs text-stone-600">
        <h3 class="font-bold text-stone-900 uppercase tracking-wider text-[11px]">
            Academic Licensing & Attribution Guidelines
        </h3>
        <p class="leading-relaxed">
            Data provided by Bharat Heritage Atlas is open for academic, scientific, and educational reuse under the <strong>Government Open Data License - India (GODL)</strong> and <strong>Creative Commons Attribution 4.0 International (CC BY 4.0)</strong>. When republishing datasets or cartographic outputs, please attribute both the primary archaeological repository and the Bharat Heritage Atlas stable accession identifier.
        </p>
    </div>

</div>
@endsection
