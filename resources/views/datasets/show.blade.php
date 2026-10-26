@extends('layouts.app')

@section('title', $dataset->name . ' — Open Heritage Dataset — Bharat Heritage Atlas')
@section('meta_description', Str::limit($dataset->description ?: 'Open archaeological dataset published by ' . $dataset->source_organization, 160))

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-stone-900 transition">Atlas Home</a>
        <span>/</span>
        <a href="{{ route('datasets.index') }}" class="hover:text-stone-900 transition">Open Datasets</a>
        <span>/</span>
        <span class="text-stone-800 font-semibold truncate">{{ Str::limit($dataset->name, 40) }}</span>
    </nav>

    <!-- Header & Hero Block -->
    <div class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-10 shadow-xs space-y-6">
        <div class="flex flex-wrap items-center gap-2">
            <span class="px-3 py-1 rounded-lg text-xs font-mono font-bold bg-amber-100 text-amber-950 border border-amber-300">
                {{ $dataset->dataset_type }}
            </span>
            <span class="px-3 py-1 rounded-lg text-xs font-mono font-bold bg-stone-100 text-stone-800 border border-stone-200">
                v{{ $dataset->version }}
            </span>
            @if($dataset->is_featured)
            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                ⭐ Featured Dataset
            </span>
            @endif
            @if($dataset->release_date)
            <span class="text-xs text-stone-500 font-medium">
                Released {{ $dataset->release_date->format('F d, Y') }}
            </span>
            @endif
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl sm:text-4xl font-bold tracking-tight text-stone-900 leading-tight">
                {{ $dataset->name }}
            </h1>
            <p class="text-sm sm:text-base text-stone-600 font-medium">
                Originating Institution: <span class="text-stone-900 font-semibold">{{ $dataset->source_organization }}</span>
            </p>
        </div>

        @if($dataset->file_path)
        <div class="pt-4 border-t border-stone-100 flex flex-wrap items-center gap-4">
            <a href="{{ route('datasets.download', $dataset->slug) }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                <span>Download Dataset File</span>
                <span>({{ $dataset->formatted_file_size }}) &darr;</span>
            </a>
            @if($dataset->source_url)
            <a href="{{ $dataset->source_url }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-4 py-3 rounded-xl text-xs font-semibold text-stone-700 hover:text-stone-950 bg-stone-100 hover:bg-stone-200 transition">
                <span>Institutional Portal</span>
                <span>&nearr;</span>
            </a>
            @endif
        </div>
        @endif
    </div>

    <!-- Main Grid: Documentation & Metadata Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Documentation & Methodology (8 cols) -->
        <div class="lg:col-span-8 space-y-8">

            <!-- Abstract -->
            <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-2xs space-y-4">
                <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                    <span>📜 Executive Abstract & Scope</span>
                </h2>
                <div class="text-stone-700 text-sm leading-relaxed space-y-3">
                    <p>{{ $dataset->description ?: 'No detailed abstract recorded for this dataset.' }}</p>
                </div>
            </div>

            <!-- Technical Schema & CRS Notes -->
            @if($dataset->notes)
            <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-2xs space-y-4">
                <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                    <span>📐 Technical Specification & Schema Notes</span>
                </h2>
                <div class="bg-stone-900 text-amber-200 p-4 rounded-xl text-xs font-mono whitespace-pre-wrap leading-relaxed overflow-x-auto">
{{ $dataset->notes }}
                </div>
            </div>
            @endif

            <!-- Academic Citation Format -->
            <div class="bg-stone-50 rounded-2xl border border-stone-200 p-6 sm:p-8 space-y-3">
                <h3 class="text-xs font-bold text-stone-600 uppercase tracking-wider">
                    Academic Citation Format
                </h3>
                <div class="p-4 rounded-xl bg-white border border-stone-200 text-xs text-stone-800 font-mono leading-relaxed select-all">
{{ $dataset->source_organization }}. ({{ $dataset->release_date?->format('Y') ?: date('Y') }}). <em>{{ $dataset->name }}</em> (Version {{ $dataset->version }}) [Data set]. Bharat Heritage Atlas. {{ url('/datasets/' . $dataset->slug) }}
                </div>
                <p class="text-[11px] text-stone-500">
                    When using this dataset in academic publications or cartographic products, cite both the originating repository and the Bharat Heritage Atlas stable accession identifier.
                </p>
            </div>

        </div>

        <!-- Metadata Sidebar (4 cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Specification Card -->
            <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-2xs space-y-4 text-xs">
                <h3 class="font-bold text-stone-900 uppercase tracking-wider text-[11px] border-b border-stone-100 pb-2">
                    Dataset Specifications
                </h3>

                <dl class="divide-y divide-stone-100 space-y-2.5">
                    <div class="pt-2 flex items-center justify-between">
                        <dt class="text-stone-500">Format</dt>
                        <dd class="font-mono font-bold text-stone-900">{{ $dataset->dataset_type }}</dd>
                    </div>

                    <div class="pt-2 flex items-center justify-between">
                        <dt class="text-stone-500">File Size</dt>
                        <dd class="font-mono font-bold text-stone-900">{{ $dataset->formatted_file_size }}</dd>
                    </div>

                    <div class="pt-2 flex items-center justify-between">
                        <dt class="text-stone-500">Record Count</dt>
                        <dd class="font-mono font-bold text-stone-900">
                            {{ $dataset->record_count ? number_format($dataset->record_count) . ' items' : 'N/A' }}
                        </dd>
                    </div>

                    <div class="pt-2 flex items-center justify-between">
                        <dt class="text-stone-500">Release Version</dt>
                        <dd class="font-mono font-bold text-stone-900">v{{ $dataset->version }}</dd>
                    </div>

                    <div class="pt-2 space-y-1">
                        <dt class="text-stone-500">Open License</dt>
                        <dd class="font-semibold text-emerald-800 bg-emerald-50 p-2 rounded-lg border border-emerald-200 text-[11px]">
                            {{ $dataset->license }}
                        </dd>
                    </div>

                    <div class="pt-2 space-y-1">
                        <dt class="text-stone-500">Original Asset</dt>
                        <dd class="font-mono text-[11px] text-stone-700 truncate">
                            {{ $dataset->file_name ?: 'Hosted via BHA Storage' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Related Datasets -->
            @if($relatedDatasets->isNotEmpty())
            <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-2xs space-y-3">
                <h3 class="font-bold text-stone-900 uppercase tracking-wider text-[11px]">
                    Similar {{ $dataset->dataset_type }} Datasets
                </h3>
                <div class="space-y-3 text-xs">
                    @foreach($relatedDatasets as $rel)
                    <div class="p-3 rounded-xl bg-stone-50 border border-stone-200/80 hover:bg-stone-100 transition space-y-1">
                        <a href="{{ route('datasets.show', $rel->slug) }}" class="font-bold text-stone-900 hover:text-amber-800 line-clamp-1">
                            {{ $rel->name }}
                        </a>
                        <p class="text-[11px] text-stone-500 line-clamp-1 font-mono">
                            {{ $rel->formatted_file_size }} • {{ $rel->source_organization }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

    </div>

</div>
@endsection
