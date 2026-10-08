@extends('layouts.app')

@section('title', $object->name . ' — Bharat Heritage Atlas')
@section('meta_description', Str::limit($object->description, 160))

@section('content')
<div class="bg-[#faf8f5] py-8 sm:py-12 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span>/</span>
            <a href="{{ route('objects.index') }}" class="hover:text-orange-800 transition">Artifacts</a>
            <span>/</span>
            <span class="text-stone-800 font-semibold truncate">{{ $object->name }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            <div class="space-y-3 max-w-4xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                        {{ $object->object_type }}
                    </span>
                    @if($object->period)
                    <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-stone-100 text-stone-700">
                        {{ $object->period->name }}
                    </span>
                    @endif
                    @if($object->dynasty)
                    <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-stone-100 text-stone-700">
                        Dynasty: {{ $object->dynasty->name }}
                    </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-stone-900 leading-tight">
                    {{ $object->name }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-xs font-mono text-stone-700 pt-1">
                    <span class="inline-flex items-center gap-1.5 font-sans font-medium text-stone-900 bg-amber-50 px-2.5 py-1 rounded border border-amber-200">
                        <span>⏳</span>
                        <span>{{ $object->dating_statement }}</span>
                    </span>
                    <span class="text-stone-500">
                        Astronomical Range: <strong class="text-stone-900">{{ $object->start_year }}</strong> to <strong class="text-stone-900">{{ $object->end_year }}</strong>
                    </span>
                </div>
            </div>

            <!-- Parent Monument Link -->
            @if($object->heritageSite)
            <div class="shrink-0 bg-white p-4 rounded-xl border border-stone-200 shadow-2xs space-y-1">
                <span class="text-[11px] text-stone-500 block font-medium">In Situ Monument Context</span>
                <a href="{{ route('sites.show', $object->heritageSite->slug) }}" class="font-bold text-sm text-orange-800 hover:text-orange-950 flex items-center gap-1">
                    {{ $object->heritageSite->name }} &rarr;
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    
    <!-- Physical Attributes & Repository Card -->
    <div class="bg-white rounded-2xl border border-stone-200 p-6 shadow-2xs">
        <h3 class="text-xs font-bold text-stone-900 uppercase tracking-wider mb-4">
            Curatorial & Physical Specifications
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-stone-400 block text-[11px]">Material Composition</span>
                <span class="font-bold text-stone-900">{{ $object->material ?? 'Unspecified Stone' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Dimensions / Elevation</span>
                <span class="font-bold text-stone-900">{{ $object->dimensions ?? 'Unrecorded' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Current Repository</span>
                <span class="font-bold text-stone-900">{{ $object->current_repository ?? 'In Situ' }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[11px]">Accession / Registry ID</span>
                <span class="font-mono font-bold text-stone-900">{{ $object->accession_number ?? 'In Situ Archival' }}</span>
            </div>
        </div>
    </div>

    <!-- Description & Iconography -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-2xs space-y-3">
            <h2 class="text-lg font-bold text-stone-900">Archaeological Description</h2>
            <p class="text-sm text-stone-700 leading-relaxed">{{ $object->description }}</p>
        </div>

        @if($object->iconographic_notes)
        <div class="bg-white rounded-2xl border border-stone-200 p-6 sm:p-8 shadow-2xs space-y-3">
            <h2 class="text-lg font-bold text-stone-900">Iconographic & Epigraphical Notes</h2>
            <p class="text-sm text-stone-700 leading-relaxed">{{ $object->iconographic_notes }}</p>
        </div>
        @endif
    </div>

    <!-- Attached Inscriptions on Object -->
    @if($object->inscriptions->isNotEmpty())
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-stone-900 flex items-center gap-2">
            <span>📜 Engraved Epigraphs on Object</span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-700">{{ $object->inscriptions->count() }}</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($object->inscriptions as $insc)
            <article class="bg-stone-900 text-stone-100 rounded-2xl p-6 border border-stone-800 shadow-md space-y-4">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-amber-400">{{ $insc->title }}</span>
                    <span class="font-mono text-stone-400 text-[11px]">{{ $insc->script }} • {{ $insc->language }}</span>
                </div>

                @if($insc->raw_text || $insc->transliteration)
                <div class="bg-stone-950/80 p-3.5 rounded-xl border border-stone-800 font-mono text-xs text-amber-200 overflow-x-auto leading-relaxed">
                    {{ $insc->raw_text ?? $insc->transliteration }}
                </div>
                @endif

                <p class="text-xs text-stone-300 italic leading-relaxed">
                    "{{ $insc->translation }}"
                </p>

                <div class="pt-2 border-t border-stone-800 flex items-center justify-between text-[11px] text-stone-400">
                    <span>Dating: {{ $insc->dating_statement }}</span>
                    <a href="{{ route('inscriptions.show', $insc->slug) }}" class="font-bold text-amber-400 hover:text-amber-300">
                        View Facsimile &rarr;
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
