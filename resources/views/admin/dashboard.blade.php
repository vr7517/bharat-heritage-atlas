@extends('layouts.admin')

@section('title', 'Curator Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Hero / Welcome Banner -->
    <div class="bg-white rounded-2xl border border-stone-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                    CMS Management Active
                </span>
                <span class="text-xs text-stone-500 font-mono">Laravel 12 / PHP 8.4</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">
                Welcome back, {{ auth()->user()->name }}
            </h1>
            <p class="text-xs sm:text-sm text-stone-600 mt-1 max-w-2xl">
                Manage the Bharat Heritage Atlas database directly. Adding or modifying records here dynamically propagates to the Public Map, Timeline, Monuments, Epigraphy, Open Datasets, and Evidence Graph.
            </p>
        </div>

        <div class="shrink-0 flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.sites.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-2xs transition">
                <span>➕ Add Heritage Site</span>
            </a>
            <a href="{{ route('admin.objects.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-stone-800 bg-stone-100 hover:bg-stone-200 border border-stone-300 transition">
                <span>🏺 Add Artifact</span>
            </a>
            <a href="{{ route('admin.inscriptions.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-stone-800 bg-stone-100 hover:bg-stone-200 border border-stone-300 transition">
                <span>📜 Add Epigraph</span>
            </a>
            <a href="{{ route('admin.datasets.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-stone-800 bg-stone-100 hover:bg-stone-200 border border-stone-300 transition">
                <span>📊 Add Dataset</span>
            </a>
            <a href="{{ route('admin.exports.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-stone-800 bg-stone-100 hover:bg-stone-200 border border-stone-300 transition">
                <span>📦 Export Center</span>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition">
                <span>Public Atlas</span>
                <span>&nearr;</span>
            </a>
        </div>
    </div>

    <!-- Core Content Modules (7 Primary Administrative Entities) -->
    <div>
        <h2 class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-3">
            Core Heritage Entities & Knowledge Base
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-4">
            <!-- Sites -->
            <a href="{{ route('admin.sites.index') }}" class="group bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs hover:border-amber-500 hover:shadow-xs transition block">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 group-hover:text-amber-800 block font-semibold">Sites & Monuments</span>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_sites'] }}</span>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                        {{ $metrics['published_sites'] }} Live
                    </span>
                </div>
                <div class="mt-2 text-[10px] text-stone-400 group-hover:text-amber-700 font-semibold">
                    Manage Sites &rarr;
                </div>
            </a>

            <!-- Artifacts -->
            <a href="{{ route('admin.objects.index') }}" class="group bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs hover:border-amber-500 hover:shadow-xs transition block">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 group-hover:text-amber-800 block font-semibold">Artifacts & Relics</span>
                <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_objects'] }}</div>
                <div class="mt-2 text-[10px] text-stone-400 group-hover:text-amber-700 font-semibold">
                    Manage Artifacts &rarr;
                </div>
            </a>

            <!-- Epigraphy -->
            <a href="{{ route('admin.inscriptions.index') }}" class="group bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs hover:border-amber-500 hover:shadow-xs transition block">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 group-hover:text-amber-800 block font-semibold">Epigraphy & Brahmi</span>
                <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_inscriptions'] }}</div>
                <div class="mt-2 text-[10px] text-stone-400 group-hover:text-amber-700 font-semibold">
                    Manage Epigraphy &rarr;
                </div>
            </a>

            <!-- Dynasties -->
            <a href="{{ route('admin.dynasties.index') }}" class="group bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs hover:border-amber-500 hover:shadow-xs transition block">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 group-hover:text-amber-800 block font-semibold">Dynasties & Realms</span>
                <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_dynasties'] }}</div>
                <div class="mt-2 text-[10px] text-stone-400 group-hover:text-amber-700 font-semibold">
                    Manage Dynasties &rarr;
                </div>
            </a>

            <!-- Timeline Horizons -->
            <a href="{{ route('admin.periods.index') }}" class="group bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs hover:border-amber-500 hover:shadow-xs transition block">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 group-hover:text-amber-800 block font-semibold">Timeline Horizons</span>
                <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_periods'] }}</div>
                <div class="mt-2 text-[10px] text-stone-400 group-hover:text-amber-700 font-semibold">
                    Manage Periods &rarr;
                </div>
            </a>

            <!-- Datasets -->
            <a href="{{ route('admin.datasets.index') }}" class="group bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs hover:border-amber-500 hover:shadow-xs transition block">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 group-hover:text-amber-800 block font-semibold">Open Datasets</span>
                <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_datasets'] }}</div>
                <div class="mt-2 text-[10px] text-stone-400 group-hover:text-amber-700 font-semibold">
                    Manage Datasets &rarr;
                </div>
            </a>

            <!-- Sources -->
            <a href="{{ route('admin.sources.index') }}" class="group bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs hover:border-amber-500 hover:shadow-xs transition block">
                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 group-hover:text-amber-800 block font-semibold">Bibliographic Sources</span>
                <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_sources'] }}</div>
                <div class="mt-2 text-[10px] text-stone-400 group-hover:text-amber-700 font-semibold">
                    Manage Sources &rarr;
                </div>
            </a>
        </div>
    </div>

    <!-- Secondary Scholarly Verification Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-stone-900 text-white p-5 rounded-2xl border border-stone-800 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider block">⚖️ Scholarly Claims Network</span>
                <p class="text-xs text-stone-400 mt-1">Formal hypotheses with consensus status attached across sites, objects, and epigraphs.</p>
            </div>
            <div class="text-3xl font-mono font-bold text-white px-4 border-l border-stone-800">
                {{ $metrics['total_claims'] }}
            </div>
        </div>

        <div class="bg-stone-900 text-white p-5 rounded-2xl border border-stone-800 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">🔬 Attached Evidence Nodes</span>
                <p class="text-xs text-stone-400 mt-1">Archaeological stratigraphy, carbon dating, and comparative palaeography nodes.</p>
            </div>
            <div class="text-3xl font-mono font-bold text-white px-4 border-l border-stone-800">
                {{ $metrics['total_evidence'] }}
            </div>
        </div>
    </div>

    <!-- Recent Heritage Sites Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="p-6 border-b border-stone-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-stone-900">Recently Modified Monuments & Sites</h2>
                <p class="text-xs text-stone-500 mt-0.5">Database records controlling live public views.</p>
            </div>
            <a href="{{ route('admin.sites.index') }}" class="text-xs font-bold text-orange-800 hover:text-orange-950 transition">
                View All Sites &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-stone-100 bg-stone-50/70 text-stone-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-6">Monument Name</th>
                        <th class="py-3 px-6">Typology</th>
                        <th class="py-3 px-6">Location</th>
                        <th class="py-3 px-6">Dating Statement</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($recentSites as $site)
                    <tr class="hover:bg-stone-50/80 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-stone-900">{{ $site->name }}</div>
                            <div class="font-mono text-[11px] text-stone-400">{{ $site->slug }}</div>
                        </td>
                        <td class="py-4 px-6 text-stone-600">
                            {{ $site->site_type }}
                        </td>
                        <td class="py-4 px-6 text-stone-600">
                            {{ $site->location?->state ?? 'Unassigned' }}
                        </td>
                        <td class="py-4 px-6 text-stone-600 font-mono text-[11px]">
                            {{ $site->dating_statement }}
                        </td>
                        <td class="py-4 px-6">
                            @if($site->is_published)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                Published
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 text-stone-700 border border-stone-200">
                                Draft
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="{{ route('sites.show', $site->slug) }}" target="_blank" class="text-stone-500 hover:text-stone-900 font-semibold">
                                View Public
                            </a>
                            <span class="text-stone-300">•</span>
                            <a href="{{ route('admin.sites.edit', $site->id) }}" class="text-orange-800 hover:text-orange-950 font-bold">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-stone-500 text-xs">
                            No heritage sites registered yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
