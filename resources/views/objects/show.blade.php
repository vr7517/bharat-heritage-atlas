@extends('layouts.app')

@section('title', $object->name . ' — ' . __('ui.brand_title'))
@section('meta_description', Str::limit($object->description, 160))

@section('content')
@php
    $cover = $object->coverImage();
@endphp

<!-- Header Hero & Identity Section -->
<div class="relative bg-gradient-to-b from-[#f5ede0] to-[#faf8f5] py-8 sm:py-12 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('objects.index') }}" class="hover:text-orange-800 transition">{{ __('ui.nav_objects') }}</a>
            <span aria-hidden="true">/</span>
            <span class="text-stone-800 font-bold truncate">{{ $object->name }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-8">
            <div class="space-y-4 max-w-4xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200 shadow-2xs">
                        {{ $object->object_type }}
                    </span>
                    @if($object->period)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 border border-stone-200">
                        {{ $object->period->name }}
                    </span>
                    @endif
                    @if($object->dynasty)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 border border-stone-200">
                        {{ __('ui.dynasty') }}: {{ $object->dynasty->name }}
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-stone-900 leading-tight">
                    {{ $object->name }}
                </h1>

                <div class="flex flex-wrap items-center gap-3 text-xs font-mono text-stone-700 pt-1">
                    <span class="inline-flex items-center gap-1.5 font-sans font-semibold text-stone-900 bg-amber-50 px-3 py-1 rounded-lg border border-amber-200 shadow-2xs">
                        <span aria-hidden="true">⏳</span>
                        <span>{{ $object->dating_statement }}</span>
                    </span>
                    <span class="text-stone-500 bg-white px-3 py-1 rounded-lg border border-stone-200 font-sans">
                        {{ __('ui.astronomical_range') }}: <strong class="text-stone-900 font-mono">{{ $object->start_year }}</strong> to <strong class="text-stone-900 font-mono">{{ $object->end_year }}</strong>
                    </span>
                </div>
            </div>

            <!-- Parent Monument Context Card -->
            @if($object->heritageSite)
            <div class="shrink-0 bg-white p-5 rounded-2xl border border-stone-200 shadow-xs space-y-1.5 max-w-xs">
                <span class="text-[11px] text-stone-500 block font-semibold uppercase tracking-wider">{{ __('ui.parent_monument') }}</span>
                <a href="{{ route('sites.show', $object->heritageSite->slug) }}" class="font-bold text-sm sm:text-base text-orange-800 hover:text-orange-950 flex items-center gap-1.5">
                    <span>{{ $object->heritageSite->name }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <span class="text-[11px] text-stone-500 block">📍 {{ $object->heritageSite->location?->state ?? 'India' }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Hero Image Banner & Visual Plate (if cover exists) -->
    @if($cover)
    <div class="rounded-3xl overflow-hidden bg-stone-900 border border-stone-200 shadow-lg relative group">
        <div class="w-full h-72 sm:h-96 lg:h-[460px] overflow-hidden">
            <img src="{{ $cover->url }}" 
                 alt="{{ $cover->alt_text ?? $object->name }}" 
                 class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500" />
        </div>
        <div class="bg-stone-900/90 backdrop-blur-md text-stone-300 px-6 py-3 border-t border-stone-800 flex flex-wrap items-center justify-between text-xs gap-3">
            <div class="flex items-center gap-2">
                <span class="font-bold text-amber-300 font-mono">ARCHIVAL PLATE</span>
                <span class="text-stone-200 font-medium">{{ $cover->caption ?? $object->name }}</span>
            </div>
            <div class="flex items-center gap-4 text-stone-400 text-[11px]">
                @if($cover->photographer_or_draughtsman)
                <span>Photo: <strong class="text-stone-200">{{ $cover->photographer_or_draughtsman }}</strong></span>
                @endif
                @if($cover->capture_year)
                <span>Year: {{ $cover->capture_year }}</span>
                @endif
                @if($cover->license)
                <span class="px-2 py-0.5 rounded bg-stone-800 text-stone-300 border border-stone-700">{{ $cover->license }}</span>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Visual Archive Gallery (if multiple media) -->
    @if($object->media->count() > 1)
    <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs space-y-6">
        <h2 class="text-lg font-bold text-stone-900 flex items-center gap-2">
            <span aria-hidden="true">📸</span>
            <span>Archival Photographic Plates & Drawings ({{ $object->media->count() }})</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($object->media as $idx => $item)
            <div class="rounded-2xl overflow-hidden border border-stone-200 bg-stone-50 flex flex-col group hover:shadow-md transition">
                <div class="relative aspect-[4/3] bg-stone-200 overflow-hidden">
                    <img src="{{ $item->url }}" alt="{{ $item->alt_text ?? $object->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy" />
                    <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-stone-900/80 text-amber-300 font-mono font-bold text-[10px]">
                        PLATE {{ $idx + 1 }}
                    </div>
                </div>
                <div class="p-4 text-xs space-y-1">
                    <p class="font-semibold text-stone-900">{{ $item->caption ?? 'Archival plate record' }}</p>
                    <p class="text-[11px] text-stone-500">{{ $item->photographer_or_draughtsman ?? 'Archival survey' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
    
    <!-- Physical Attributes & Repository Card -->
    <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs">
        <h2 class="text-xs font-bold text-orange-950 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span aria-hidden="true">📐</span>
            <span>Curatorial & Physical Specifications</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-xs">
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.material') }}</span>
                <span class="font-bold text-stone-900 text-sm">{{ $object->material ?? 'Unspecified Stone' }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.dimensions') }}</span>
                <span class="font-bold text-stone-900 text-sm">{{ $object->dimensions ?? 'Unrecorded' }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.repository') }}</span>
                <span class="font-bold text-stone-900 text-sm">{{ $object->current_repository ?? __('ui.in_situ') }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">Accession / Registry ID</span>
                <span class="font-mono font-bold text-stone-900 text-sm">{{ $object->accession_number ?? 'In Situ Archival' }}</span>
            </div>
        </div>
    </section>

    <!-- Description & Iconography -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs space-y-3">
            <h2 class="text-lg font-bold text-stone-900 flex items-center gap-2">
                <span aria-hidden="true">📜</span>
                <span>Archaeological Description</span>
            </h2>
            <p class="text-sm text-stone-700 leading-relaxed">{{ $object->description }}</p>
        </section>

        @if($object->iconographic_notes)
        <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs space-y-3">
            <h2 class="text-lg font-bold text-stone-900 flex items-center gap-2">
                <span aria-hidden="true">🔍</span>
                <span>Iconographic & Stylistic Analysis</span>
            </h2>
            <p class="text-sm text-stone-700 leading-relaxed">{{ $object->iconographic_notes }}</p>
        </section>
        @endif
    </div>

    <!-- Attached Inscriptions on Object -->
    @if($object->inscriptions->isNotEmpty())
    <section class="space-y-6" aria-labelledby="inscriptions-heading">
        <div class="flex items-center justify-between border-b border-stone-200 pb-3">
            <h2 id="inscriptions-heading" class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span aria-hidden="true">📜</span>
                <span>Engraved Epigraphs on Object ({{ $object->inscriptions->count() }})</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($object->inscriptions as $insc)
            @php
                $inscCover = $insc->coverImage();
            @endphp
            <article class="bg-stone-900 text-stone-100 rounded-3xl p-6 sm:p-7 border border-stone-800 shadow-md space-y-4">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-amber-300 text-sm">{{ $insc->title }}</span>
                    <span class="font-mono text-stone-400 text-[11px]">{{ $insc->script }} • {{ $insc->language }}</span>
                </div>

                @if($inscCover)
                <div class="w-full h-36 rounded-xl overflow-hidden bg-stone-950 border border-stone-800">
                    <img src="{{ $inscCover->url }}" alt="{{ $insc->title }}" class="w-full h-full object-cover" loading="lazy">
                </div>
                @endif

                @if($insc->raw_text || $insc->transliteration)
                <div class="bg-stone-950/80 p-3.5 rounded-xl border border-stone-800 font-mono text-xs text-amber-200 overflow-x-auto leading-relaxed">
                    {{ $insc->raw_text ?? $insc->transliteration }}
                </div>
                @endif

                <p class="text-xs text-stone-300 italic leading-relaxed">
                    "{{ $insc->translation }}"
                </p>

                <div class="pt-3 border-t border-stone-800 flex items-center justify-between text-[11px] text-stone-400">
                    <span>⏳ {{ $insc->dating_statement }}</span>
                    <a href="{{ route('inscriptions.show', $insc->slug) }}" class="min-h-[44px] inline-flex items-center font-bold text-amber-400 hover:text-amber-300">
                        {{ __('ui.epigraph_dossier') }} &rarr;
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection
