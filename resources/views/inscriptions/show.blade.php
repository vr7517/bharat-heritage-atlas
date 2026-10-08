@extends('layouts.app')

@section('title', $inscription->title . ' — Bharat Heritage Atlas')
@section('meta_description', Str::limit($inscription->translation, 160))

@section('content')
<div class="bg-[#faf8f5] py-8 sm:py-12 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <a href="{{ route('inscriptions.index') }}" class="hover:text-orange-800 transition">Epigraphy</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold truncate">{{ $inscription->title }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            <div class="space-y-3 max-w-4xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                        Script: {{ $inscription->script }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-stone-100 text-stone-700">
                        Language: {{ $inscription->language }}
                    </span>
                    @if($inscription->ruler_mentioned)
                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-orange-100 text-orange-950 border border-orange-200">
                        Ruler: {{ $inscription->ruler_mentioned }}
                    </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-stone-900 leading-tight">
                    {{ $inscription->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-xs font-mono text-stone-700 pt-1">
                    <span class="inline-flex items-center gap-1.5 font-sans font-medium text-stone-900 bg-amber-50 px-2.5 py-1 rounded border border-amber-200">
                        <span>⏳</span>
                        <span>{{ $inscription->dating_statement }}</span>
                    </span>
                    <span class="text-stone-500">
                        Astronomical Range: <strong class="text-stone-900">{{ $inscription->start_year }}</strong> to <strong class="text-stone-900">{{ $inscription->end_year }}</strong>
                    </span>
                </div>
            </div>

            <!-- Context Link -->
            <div class="shrink-0 space-y-2">
                @if($inscription->heritageSite)
                <div class="bg-white p-3.5 rounded-xl border border-stone-200 shadow-2xs text-xs space-y-1">
                    <span class="text-stone-400 block text-[10px]">In Situ Monument</span>
                    <a href="{{ route('sites.show', $inscription->heritageSite->slug) }}" class="font-bold text-orange-800 hover:text-orange-950 block">
                        {{ $inscription->heritageSite->name }} &rarr;
                    </a>
                </div>
                @endif
                @if($inscription->object)
                <div class="bg-white p-3.5 rounded-xl border border-stone-200 shadow-2xs text-xs space-y-1">
                    <span class="text-stone-400 block text-[10px]">Inscribed Object</span>
                    <a href="{{ route('objects.show', $inscription->object->slug) }}" class="font-bold text-orange-800 hover:text-orange-950 block">
                        {{ $inscription->object->name }} &rarr;
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    
    <!-- Epigraphical Corpus & Facsimile Viewer Box -->
    <div class="bg-stone-900 text-stone-100 rounded-3xl p-6 sm:p-10 border border-stone-800 shadow-xl space-y-8">
        <div class="flex items-center justify-between border-b border-stone-800 pb-4">
            <h2 class="text-base font-bold text-amber-400 uppercase tracking-wider flex items-center gap-2">
                <span>📜 Verbatim Ancient Script Facsimile & Transcription</span>
            </h2>
            <span class="text-xs font-mono text-stone-400">
                Corpus Epigraphicum
            </span>
        </div>

        @if($inscription->raw_text)
        <div class="space-y-2">
            <span class="text-[11px] font-mono text-amber-400/80 uppercase tracking-wider block">Verbatim Glyph Text:</span>
            <div class="bg-stone-950 p-6 rounded-2xl border border-stone-800/80 font-mono text-lg text-amber-100 overflow-x-auto leading-relaxed whitespace-pre-wrap select-all">
                {{ $inscription->raw_text }}
            </div>
        </div>
        @endif

        @if($inscription->transliteration)
        <div class="space-y-2">
            <span class="text-[11px] font-mono text-amber-400/80 uppercase tracking-wider block">Scholarly Roman Transliteration (IAST):</span>
            <div class="bg-stone-950/70 p-5 rounded-2xl border border-stone-800/60 font-mono text-sm text-stone-200 overflow-x-auto leading-relaxed select-all">
                {{ $inscription->transliteration }}
            </div>
        </div>
        @endif

        <div class="space-y-2 pt-2 border-t border-stone-800">
            <span class="text-[11px] font-mono text-amber-400/80 uppercase tracking-wider block">Verified English Translation:</span>
            <blockquote class="bg-stone-950/50 p-6 rounded-2xl border border-stone-800/40 text-sm text-stone-200 italic leading-relaxed">
                "{{ $inscription->translation }}"
            </blockquote>
        </div>
    </div>

    <!-- Epigraphical Metadata & Scholarly Reference Card -->
    <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-2xs space-y-4">
        <h3 class="text-xs font-bold text-stone-900 uppercase tracking-wider">
            Epigraphical Citation & Epigraphia Indica Records
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-stone-400 block text-[11px]">Epigraphic Publication Citation</span>
                <span class="font-bold text-stone-900">{{ $inscription->epigraphic_reference ?? 'Archaeological Survey Record' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Donor / Patron</span>
                <span class="font-bold text-stone-900">{{ $inscription->donor ?? 'Not mentioned' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Ruler / Sovereign</span>
                <span class="font-bold text-stone-900">{{ $inscription->ruler_mentioned ?? 'Unspecified' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Paleographical Script</span>
                <span class="font-mono font-bold text-stone-900">{{ $inscription->script }}</span>
            </div>
        </div>

        @if($inscription->interpretation_notes)
        <div class="pt-4 border-t border-stone-100">
            <h4 class="text-xs font-bold text-stone-800 mb-1">Philological & Historical Significance</h4>
            <p class="text-xs text-stone-600 leading-relaxed">{{ $inscription->interpretation_notes }}</p>
        </div>
        @endif
    </div>

    <!-- Evidentiary Claims Linked to Inscription -->
    @if($inscription->claims->isNotEmpty())
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-stone-900 flex items-center gap-2">
            <span>⚖️ Linked Historical Evidentiary Claims</span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-700">{{ $inscription->claims->count() }}</span>
        </h2>

        <div class="space-y-4">
            @foreach($inscription->claims as $claim)
            <div class="bg-white rounded-xl border border-stone-200 p-5 shadow-2xs space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="px-2 py-0.5 rounded font-mono font-bold text-[10px] {{ $claim->consensus_status === 'SETTLED_CONSENSUS' ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-amber-100 text-amber-900 border border-amber-200' }}">
                        {{ $claim->consensus_status }}
                    </span>
                    <span class="text-stone-500 font-mono text-[11px]">Claim #{{ $claim->id }}</span>
                </div>
                <h4 class="font-bold text-stone-900 text-sm">{{ $claim->claim_text }}</h4>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
