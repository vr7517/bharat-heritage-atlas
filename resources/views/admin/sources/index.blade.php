@extends('layouts.admin')

@section('title', 'Bibliographic Sources & Citations')

@section('content')
<div class="space-y-6">

    <!-- Top Heading & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Bibliographic Sources & Epigraphical Archives
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Peer-reviewed monographs, ASI reports, and primary epigraphical corpora underpinning heritage claims.
            </p>
        </div>

        <a href="{{ route('admin.sources.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
            <span>+</span>
            <span>Register New Source</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Cataloged Sources</span>
            <p class="text-2xl font-bold text-stone-900 mt-1">{{ $counts['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Tier 1 Primary Excavation</span>
            <p class="text-2xl font-bold text-emerald-800 mt-1">{{ $counts['tier1'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Peer-Reviewed Academic</span>
            <p class="text-2xl font-bold text-amber-800 mt-1">{{ $counts['peerReviewed'] }}</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form action="{{ route('admin.sources.index') }}" method="GET"
        class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">

        <div class="sm:col-span-6">
            <label for="search" class="sr-only">Search</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                placeholder="Search by title, authors, publisher, ISBN, DOI..."
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
        </div>

        <div class="sm:col-span-3">
            <select name="source_type"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                <option value="">All Source Typologies</option>
                @foreach($sourceTypes as $val => $label)
                <option value="{{ $val }}" @selected(request('source_type') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-3 flex items-center gap-2">
            <button type="submit"
                class="flex-1 px-4 py-2 rounded-lg bg-stone-900 text-white font-semibold hover:bg-stone-800 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'source_type', 'reliability_tier']))
            <a href="{{ route('admin.sources.index') }}"
                class="px-3 py-2 rounded-lg border border-stone-300 text-stone-600 hover:bg-stone-50 transition">
                Reset
            </a>
            @endif
        </div>
    </form>

    <!-- Sources Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-600 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-3 px-4">Source Title & Publication</th>
                        <th class="py-3 px-4">Author(s)</th>
                        <th class="py-3 px-4">Year</th>
                        <th class="py-3 px-4">Typology</th>
                        <th class="py-3 px-4">Reliability Tier</th>
                        <th class="py-3 px-4">Linked Evidence</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($sources as $source)
                    <tr class="hover:bg-stone-50/60 transition">
                        <td class="py-3.5 px-4 max-w-sm">
                            <span class="font-bold text-stone-900 line-clamp-1">{{ $source->title }}</span>
                            @if($source->publisher || $source->journal_or_series)
                            <span class="text-[11px] text-stone-500 block truncate">
                                {{ $source->publisher ?? $source->journal_or_series }}
                                @if($source->volume_issue) • {{ $source->volume_issue }} @endif
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 font-medium text-stone-800 max-w-xs truncate">
                            {{ $source->authors }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-stone-600">
                            {{ $source->publication_year ?? '—' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-stone-100 text-stone-800 border border-stone-200">
                                {{ $sourceTypes[$source->source_type] ?? $source->source_type }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($source->reliability_tier === 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-900 border border-emerald-200">
                                <span>Tier 1</span>
                            </span>
                            @elseif($source->reliability_tier === 'TIER_2_PEER_REVIEWED_ACADEMIC')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                <span>Tier 2</span>
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-stone-100 text-stone-700 border border-stone-200">
                                <span>Tier 3/4</span>
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-stone-800">
                            {{ $source->evidence_count }} {{ Str::plural('node', $source->evidence_count) }}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.sources.edit', $source->id) }}"
                                    class="text-xs font-semibold text-amber-800 hover:text-amber-950 hover:underline">
                                    Edit
                                </a>
                                <span class="text-stone-300">|</span>
                                <form action="{{ route('admin.sources.destroy', $source->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this bibliographic source?');"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-stone-500">
                            <p class="font-medium">No bibliographic sources match your criteria.</p>
                            <a href="{{ route('admin.sources.create') }}" class="mt-2 inline-block text-xs font-bold text-orange-800 hover:underline">
                                + Register New Source
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sources->hasPages())
        <div class="p-4 border-t border-stone-100 bg-stone-50/50">
            {{ $sources->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
