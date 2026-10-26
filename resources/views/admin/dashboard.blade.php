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
                Manage the Bharat Heritage Atlas database directly. Adding or modifying records here dynamically propagates to the Public Map, Timeline, Monuments, and Evidence Graph.
            </p>
        </div>

        <div class="shrink-0 flex flex-wrap items-center gap-3">
            <a href="{{ url('/admin/sites/create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-2xs transition">
                <span>➕ Add Heritage Site</span>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-stone-700 bg-stone-100 hover:bg-stone-200 border border-stone-200 transition">
                <span>Public Atlas</span>
                <span>&nearr;</span>
            </a>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block font-semibold">Sites</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_sites'] }}</span>
                <span class="text-[11px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                    {{ $metrics['published_sites'] }} Live
                </span>
            </div>
            <div class="mt-2 text-[10px] text-stone-400">
                {{ $metrics['draft_sites'] }} Drafts
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block font-semibold">Artifacts</span>
            <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_objects'] }}</div>
            <div class="mt-2 text-[10px] text-stone-500">Physical Relics</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block font-semibold">Epigraphy</span>
            <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_inscriptions'] }}</div>
            <div class="mt-2 text-[10px] text-stone-500">Brahmi Facsimiles</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block font-semibold">Claims</span>
            <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_claims'] }}</div>
            <div class="mt-2 text-[10px] text-stone-500">Verified Theses</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block font-semibold">Evidence</span>
            <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_evidence'] }}</div>
            <div class="mt-2 text-[10px] text-stone-500">Methodology Nodes</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-2xs">
            <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 block font-semibold">Sources</span>
            <div class="mt-2 text-2xl font-bold text-stone-900 font-mono">{{ $metrics['total_sources'] }}</div>
            <div class="mt-2 text-[10px] text-stone-500">Peer-Reviewed</div>
        </div>
    </div>

    <!-- Recent Heritage Sites Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="p-6 border-b border-stone-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-stone-900">Recently Modified Monuments & Sites</h2>
                <p class="text-xs text-stone-500 mt-0.5">Database records controlling live public views.</p>
            </div>
            <a href="{{ url('/admin/sites') }}" class="text-xs font-bold text-orange-800 hover:text-orange-950 transition">
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
                            <a href="{{ url('/admin/sites/' . $site->id . '/edit') }}" class="text-orange-800 hover:text-orange-950 font-bold">
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
