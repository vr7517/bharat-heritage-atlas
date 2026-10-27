@extends('layouts.app')

@section('title', $inscription->title . ' — ' . __('ui.brand_title'))
@section('meta_description', Str::limit($inscription->translation, 160))

@section('content')
@php
    $cover = $inscription->coverImage();
@endphp

<!-- Header Hero & Identity Section -->
<div class="relative bg-gradient-to-b from-[#f5ede0] to-[#faf8f5] py-8 sm:py-12 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('inscriptions.index') }}" class="hover:text-orange-800 transition">{{ __('ui.nav_inscriptions') }}</a>
            <span aria-hidden="true">/</span>
            <span class="text-stone-800 font-bold truncate">{{ $inscription->title }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-8">
            <div class="space-y-4 max-w-4xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200 shadow-2xs">
                        {{ __('ui.script') }}: {{ $inscription->script }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 border border-stone-200">
                        {{ __('ui.language') }}: {{ $inscription->language }}
                    </span>
                    @if($inscription->ruler_mentioned)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-950 border border-orange-200">
                        {{ __('ui.ruler') }}: {{ $inscription->ruler_mentioned }}
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-stone-900 leading-tight">
                    {{ $inscription->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-3 text-xs font-mono text-stone-700 pt-1">
                    <span class="inline-flex items-center gap-1.5 font-sans font-semibold text-stone-900 bg-amber-50 px-3 py-1 rounded-lg border border-amber-200 shadow-2xs">
                        <span aria-hidden="true">⏳</span>
                        <span>{{ $inscription->dating_statement }}</span>
                    </span>
                    <span class="text-stone-500 bg-white px-3 py-1 rounded-lg border border-stone-200 font-sans">
                        {{ __('ui.astronomical_range') }}: <strong class="text-stone-900 font-mono">{{ $inscription->start_year }}</strong> to <strong class="text-stone-900 font-mono">{{ $inscription->end_year }}</strong>
                    </span>
                </div>
            </div>

            <!-- Context Link Cards -->
            <div class="shrink-0 space-y-2.5">
                @if($inscription->heritageSite)
                <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-xs text-xs space-y-1">
                    <span class="text-stone-500 block text-[10px] uppercase font-bold tracking-wider">In Situ Complex</span>
                    <a href="{{ route('sites.show', $inscription->heritageSite->slug) }}" class="font-bold text-sm text-orange-800 hover:text-orange-950 flex items-center gap-1">
                        <span>{{ $inscription->heritageSite->name }}</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                @endif
                @if($inscription->object)
                <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-xs text-xs space-y-1">
                    <span class="text-stone-500 block text-[10px] uppercase font-bold tracking-wider">Inscribed Object</span>
                    <a href="{{ route('objects.show', $inscription->object->slug) }}" class="font-bold text-sm text-orange-800 hover:text-orange-950 flex items-center gap-1">
                        <span>{{ $inscription->object->name }}</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Estampage Rubbing / Plate Banner (if cover exists) -->
    @if($cover)
    <div class="rounded-3xl overflow-hidden bg-stone-950 border border-stone-800 shadow-xl relative group">
        <div class="w-full h-72 sm:h-96 lg:h-[440px] overflow-hidden flex items-center justify-center bg-black">
            <img src="{{ $cover->url }}" 
                 alt="{{ $cover->alt_text ?? $inscription->title }}" 
                 class="max-h-full w-auto object-contain group-hover:scale-[1.02] transition-transform duration-500" />
        </div>
        <div class="bg-stone-900/95 backdrop-blur-md text-stone-300 px-6 py-3.5 border-t border-stone-800 flex flex-wrap items-center justify-between text-xs gap-3">
            <div class="flex items-center gap-2">
                <span class="font-bold text-amber-300 font-mono">ESTAMPAGE FACSIMILE</span>
                <span class="text-stone-200 font-medium">{{ $cover->caption ?? $inscription->title }}</span>
            </div>
            <div class="flex items-center gap-4 text-stone-400 text-[11px]">
                @if($cover->photographer_or_draughtsman)
                <span>Impression: <strong class="text-stone-200">{{ $cover->photographer_or_draughtsman }}</strong></span>
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

    <!-- Epigraphical Corpus & Facsimile Viewer Box -->
    <section class="bg-stone-900 text-stone-100 rounded-3xl p-6 sm:p-10 border border-stone-800 shadow-xl space-y-8" aria-labelledby="epigraph-corpus-heading">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-stone-800 pb-4 gap-2">
            <h2 id="epigraph-corpus-heading" class="text-base font-bold text-amber-400 uppercase tracking-wider flex items-center gap-2">
                <span aria-hidden="true">📜</span>
                <span>{{ __('ui.verbatim_script') }}</span>
            </h2>
            <span class="text-xs font-mono text-stone-400">
                Corpus Epigraphicum Indicarum
            </span>
        </div>

        @if($inscription->raw_text)
        <div class="space-y-2">
            <span class="text-[11px] font-mono text-amber-400/90 uppercase tracking-wider block font-bold">Verbatim Glyph Text:</span>
            <div class="bg-stone-950 p-6 rounded-2xl border border-stone-800/80 font-mono text-lg text-amber-100 overflow-x-auto leading-relaxed whitespace-pre-wrap select-all shadow-inner">
                {{ $inscription->raw_text }}
            </div>
        </div>
        @endif

        @if($inscription->transliteration)
        <div class="space-y-2">
            <span class="text-[11px] font-mono text-amber-400/90 uppercase tracking-wider block font-bold">Scholarly Roman Transliteration (IAST):</span>
            <div class="bg-stone-950/70 p-5 rounded-2xl border border-stone-800/60 font-mono text-sm text-stone-200 overflow-x-auto leading-relaxed select-all shadow-inner">
                {{ $inscription->transliteration }}
            </div>
        </div>
        @endif

        <div class="space-y-2 pt-2 border-t border-stone-800">
            <span class="text-[11px] font-mono text-amber-400/90 uppercase tracking-wider block font-bold">{{ __('ui.translation') }}:</span>
            <blockquote class="bg-stone-950/50 p-6 rounded-2xl border border-stone-800/40 text-sm text-stone-200 italic leading-relaxed">
                "{{ $inscription->translation }}"
            </blockquote>
        </div>
    </section>

    <!-- Epigraphical Metadata & Scholarly Reference Card -->
    <section class="bg-white rounded-3xl border border-stone-200/90 p-6 sm:p-8 shadow-xs space-y-4">
        <h2 class="text-xs font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
            <span aria-hidden="true">📚</span>
            <span>Epigraphical Citation & Epigraphia Indica Records</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-xs">
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">Epigraphic Publication</span>
                <span class="font-bold text-stone-900 text-sm">{{ $inscription->epigraphic_reference ?? 'Archaeological Survey Record' }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">Donor / Patron</span>
                <span class="font-bold text-stone-900 text-sm">{{ $inscription->donor ?? 'Not mentioned' }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.ruler') }}</span>
                <span class="font-bold text-stone-900 text-sm">{{ $inscription->ruler_mentioned ?? 'Unspecified' }}</span>
            </div>
            <div>
                <span class="text-stone-600 block text-[11px] mb-0.5">{{ __('ui.script') }}</span>
                <span class="font-mono font-bold text-stone-900 text-sm">{{ $inscription->script }}</span>
            </div>
        </div>

        @if($inscription->interpretation_notes)
        <div class="pt-4 border-t border-stone-100">
            <h3 class="text-xs font-bold text-stone-800 mb-1">Philological & Historical Significance</h3>
            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">{{ $inscription->interpretation_notes }}</p>
        </div>
        @endif
    </section>

    <!-- Evidentiary Claims Linked to Inscription -->
    @if($inscription->claims->isNotEmpty())
    <section class="space-y-6" aria-labelledby="claims-heading">
        <div class="flex items-center justify-between border-b border-stone-200 pb-3">
            <h2 id="claims-heading" class="text-xl font-bold text-stone-900 flex items-center gap-2">
                <span aria-hidden="true">⚖️</span>
                <span>Linked Historical Claims ({{ $inscription->claims->count() }})</span>
            </h2>
        </div>

        <div class="space-y-4">
            @foreach($inscription->claims as $claim)
            <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-xs space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="px-2.5 py-1 rounded-md font-mono font-bold text-[11px] {{ in_array($claim->consensus_status, ['SETTLED_CONSENSUS', 'STRONG_CONSENSUS']) ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-amber-100 text-amber-900 border border-amber-200' }}">
                        {{ str_replace('_', ' ', $claim->consensus_status) }}
                    </span>
                    <span class="text-stone-500 font-mono text-[11px]">Claim #{{ $claim->id }}</span>
                </div>
                <h3 class="font-bold text-stone-900 text-base">“{{ $claim->statement }}”</h3>
                @if($claim->summary_justification)
                <p class="text-xs text-stone-600 italic leading-relaxed">{{ $claim->summary_justification }}</p>
                @endif
                <div class="pt-3 border-t border-stone-100 text-right">
                    <a href="{{ route('verification.show', $claim->id) }}" class="min-h-[44px] inline-flex items-center text-xs font-bold text-orange-800 hover:text-orange-950 transition">
                        {{ __('ui.evidence_breakdown') }} &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection
