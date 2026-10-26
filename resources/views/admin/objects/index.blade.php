@extends('layouts.admin')

@section('title', 'Artifacts & Physical Relics')

@section('content')
<div class="space-y-6">

    <!-- Top Heading & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Artifacts, Sculptures & Material Relics
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Manage portable antiquities, iconographic statuary, architectural elements, and sacred reliquaries.
            </p>
        </div>

        <a href="{{ route('admin.objects.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
            <span>+</span>
            <span>Register New Artifact</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Cataloged Relics</span>
            <p class="text-2xl font-bold text-stone-900 mt-1">{{ $counts['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Published on Atlas</span>
            <p class="text-2xl font-bold text-emerald-800 mt-1">{{ $counts['published'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Draft / Curatorial Review</span>
            <p class="text-2xl font-bold text-amber-800 mt-1">{{ $counts['draft'] }}</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form action="{{ route('admin.objects.index') }}" method="GET"
        class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">

        <div class="sm:col-span-4">
            <label for="search" class="sr-only">Search</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                placeholder="Search by artifact name, accession number, material, repository..."
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
        </div>

        <div class="sm:col-span-3">
            <select name="object_type"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                <option value="">All Object Typologies</option>
                @foreach($objectTypes as $val => $label)
                <option value="{{ $val }}" @selected(request('object_type') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-3">
            <select name="site_id"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                <option value="">All Associated Sites</option>
                @foreach($sites as $site)
                <option value="{{ $site->id }}" @selected(request('site_id') == $site->id)>{{ $site->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit"
                class="flex-1 px-4 py-2 rounded-lg bg-stone-900 text-white font-semibold hover:bg-stone-800 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'object_type', 'site_id', 'status']))
            <a href="{{ route('admin.objects.index') }}"
                class="px-3 py-2 rounded-lg border border-stone-300 text-stone-600 hover:bg-stone-50 transition">
                Reset
            </a>
            @endif
        </div>
    </form>

    <!-- Artifacts Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-600 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-3 px-4">Artifact Nomenclature</th>
                        <th class="py-3 px-4">Typology & Material</th>
                        <th class="py-3 px-4">Chronology</th>
                        <th class="py-3 px-4">Repository / Findspot</th>
                        <th class="py-3 px-4">Evidentiary Graph</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($objects as $obj)
                    <tr class="hover:bg-stone-50/60 transition">
                        <td class="py-3.5 px-4 max-w-xs">
                            <div class="flex items-center gap-3">
                                @if($cover = $obj->coverImage())
                                <img src="{{ $cover->url }}" alt="{{ $obj->name }}" class="w-10 h-10 rounded-lg object-cover border border-stone-200 shrink-0" />
                                @else
                                <div class="w-10 h-10 rounded-lg bg-stone-100 border border-stone-200 flex items-center justify-center text-base shrink-0">🏺</div>
                                @endif
                                <div class="min-w-0">
                                    <span class="font-bold text-stone-900 line-clamp-1">{{ $obj->name }}</span>
                                    <span class="text-[11px] text-stone-400 font-mono block truncate">{{ $obj->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-stone-100 text-stone-800 border border-stone-200">
                                {{ $objectTypes[$obj->object_type] ?? $obj->object_type }}
                            </span>
                            <span class="text-[11px] text-stone-500 block mt-0.5 truncate">{{ $obj->material }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-medium text-stone-800">{{ $obj->dating_statement }}</span>
                            @if($obj->period)
                            <span class="text-[11px] text-stone-500 block">{{ $obj->period->name }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 max-w-xs">
                            <span class="font-medium text-stone-800 line-clamp-1">{{ $obj->current_repository }}</span>
                            @if($obj->heritageSite)
                            <span class="text-[11px] text-stone-500 block truncate">Site: {{ $obj->heritageSite->name }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center gap-1.5 font-mono text-[11px]">
                                <span class="font-bold text-stone-800">{{ $obj->claims_count }}</span>
                                <span class="text-stone-400">claims</span>
                            </span>
                            @if($obj->inscriptions_count > 0)
                            <span class="text-[10px] text-amber-800 block font-semibold">+ {{ $obj->inscriptions_count }} epigraphs</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <form action="{{ route('admin.objects.toggle-publish', $obj->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $obj->is_published ? 'bg-emerald-100 text-emerald-900 hover:bg-emerald-200' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $obj->is_published ? 'bg-emerald-600' : 'bg-stone-400' }}"></span>
                                    <span>{{ $obj->is_published ? 'Published' : 'Draft' }}</span>
                                </button>
                            </form>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.objects.edit', $obj->id) }}"
                                    class="text-xs font-semibold text-amber-800 hover:text-amber-950 hover:underline">
                                    Edit
                                </a>
                                <span class="text-stone-300">|</span>
                                <form action="{{ route('admin.objects.destroy', $obj->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this artifact record?');"
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
                            <p class="font-medium">No artifacts or sculptures match your criteria.</p>
                            <a href="{{ route('admin.objects.create') }}" class="mt-2 inline-block text-xs font-bold text-orange-800 hover:underline">
                                + Register New Artifact
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($objects->hasPages())
        <div class="p-4 border-t border-stone-100 bg-stone-50/50">
            {{ $objects->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
