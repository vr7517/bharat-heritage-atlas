@extends('layouts.admin')

@section('title', 'Admin Data Export Center — Bharat Heritage Atlas')

@section('content')
<div class="space-y-6">

    <!-- Top Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Administrative Data Export Center
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Direct tabular CSV and structured JSON archive extraction across all Indian archaeological and epigraphical entities.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('api.v1.export.geojson') }}" target="_blank"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-stone-700 bg-white border border-stone-200 hover:bg-stone-50 transition shadow-2xs">
                <span>🌐 Stream GeoJSON</span>
                <span>&darr;</span>
            </a>
            <a href="{{ route('api.v1.export.bibtex') }}" target="_blank"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-stone-700 bg-white border border-stone-200 hover:bg-stone-50 transition shadow-2xs">
                <span>📚 Stream BibTeX</span>
                <span>&darr;</span>
            </a>
        </div>
    </div>

    <!-- Entity Export Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($entities as $key => $entity)
        <div class="bg-white rounded-2xl border border-stone-200 p-5 shadow-2xs flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-2xl">{{ $entity['icon'] }}</span>
                    <span class="text-[11px] font-mono font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-700">
                        {{ number_format($entity['count']) }} records
                    </span>
                </div>
                <h3 class="font-bold text-stone-900 text-sm mt-3">{{ $entity['name'] }}</h3>
                <p class="text-xs text-stone-500 mt-1 leading-relaxed line-clamp-2">{{ $entity['description'] }}</p>
            </div>

            <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-2">
                <a href="{{ route('admin.exports.download', ['entity' => $key, 'format' => 'csv']) }}"
                    class="flex-1 text-center px-3 py-1.5 rounded-lg text-xs font-bold text-stone-700 bg-stone-100 hover:bg-amber-100 hover:text-amber-900 transition">
                    Export CSV &darr;
                </a>
                <a href="{{ route('admin.exports.download', ['entity' => $key, 'format' => 'json']) }}"
                    class="flex-1 text-center px-3 py-1.5 rounded-lg text-xs font-bold text-stone-700 bg-stone-100 hover:bg-orange-100 hover:text-orange-900 transition">
                    Export JSON &darr;
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Public API & Researcher Links Banner -->
    <div class="bg-stone-900 text-stone-200 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="space-y-1 text-center sm:text-left">
            <h3 class="text-sm font-bold text-white flex items-center gap-2 justify-center sm:justify-start">
                <span>⚡ Programmatic Academic Exports & APIs</span>
            </h3>
            <p class="text-xs text-stone-400 max-w-xl">
                Researchers and GIS analysts can also utilize open endpoints for direct RFC 7946 GeoJSON, BibTeX citations, and live JSON feeds.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('developers.index') }}" target="_blank"
                class="px-4 py-2 rounded-xl text-xs font-bold text-amber-950 bg-amber-400 hover:bg-amber-300 transition">
                Developer API Docs &rarr;
            </a>
            <a href="{{ route('research.index') }}" target="_blank"
                class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-stone-800 hover:bg-stone-700 border border-stone-700 transition">
                Research Workbench &rarr;
            </a>
        </div>
    </div>

</div>
@endsection
