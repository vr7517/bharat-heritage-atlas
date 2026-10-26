@extends('layouts.admin')

@section('title', 'Epigraphical Corpus & Inscriptions')

@section('content')
<div class="space-y-6">

    <!-- Top Heading & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Epigraphical Corpus & Brahmi Facsimiles
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Manage lithic rock edicts, copper-plate grants, pillar inscriptions, and rubbings.
            </p>
        </div>

        <a href="{{ route('admin.inscriptions.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
            <span>+</span>
            <span>Register New Inscription</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Cataloged Epigraphs</span>
            <p class="text-2xl font-bold text-stone-900 mt-1">{{ $counts['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Published on Public Atlas</span>
            <p class="text-2xl font-bold text-emerald-800 mt-1">{{ $counts['published'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Draft / Epigraphical Review</span>
            <p class="text-2xl font-bold text-amber-800 mt-1">{{ $counts['draft'] }}</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form action="{{ route('admin.inscriptions.index') }}" method="GET"
        class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">

        <div class="sm:col-span-4">
            <label for="search" class="sr-only">Search</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                placeholder="Search by epigraph title, reference, donor, ruler, text..."
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
        </div>

        <div class="sm:col-span-3">
            <select name="language"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                <option value="">All Linguistic Media</option>
                @foreach($languages as $val => $label)
                <option value="{{ $val }}" @selected(request('language') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-3">
            <select name="script"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                <option value="">All Palaeographic Scripts</option>
                @foreach($scripts as $val => $label)
                <option value="{{ $val }}" @selected(request('script') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit"
                class="flex-1 px-4 py-2 rounded-lg bg-stone-900 text-white font-semibold hover:bg-stone-800 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'language', 'script', 'site_id', 'status']))
            <a href="{{ route('admin.inscriptions.index') }}"
                class="px-3 py-2 rounded-lg border border-stone-300 text-stone-600 hover:bg-stone-50 transition">
                Reset
            </a>
            @endif
        </div>
    </form>

    <!-- Inscriptions Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-600 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="py-3 px-4">Epigraph Nomenclature</th>
                        <th class="py-3 px-4">Language & Palaeography</th>
                        <th class="py-3 px-4">Epigraphical Citation</th>
                        <th class="py-3 px-4">Dating Statement</th>
                        <th class="py-3 px-4">Associated Site / Object</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($inscriptions as $insc)
                    <tr class="hover:bg-stone-50/60 transition">
                        <td class="py-3.5 px-4 max-w-xs">
                            <span class="font-bold text-stone-900 line-clamp-1">{{ $insc->title }}</span>
                            <span class="text-[11px] text-stone-400 font-mono block truncate">{{ $insc->slug }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                {{ $insc->language }}
                            </span>
                            <span class="text-[11px] text-stone-500 font-mono block mt-0.5 truncate">{{ $insc->script }}</span>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-stone-800 max-w-xs truncate">
                            {{ $insc->epigraphic_reference }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-medium text-stone-800">{{ $insc->dating_statement }}</span>
                        </td>
                        <td class="py-3.5 px-4 max-w-xs">
                            @if($insc->heritageSite)
                            <span class="font-medium text-stone-800 block truncate">Site: {{ $insc->heritageSite->name }}</span>
                            @endif
                            @if($insc->object)
                            <span class="text-[11px] text-stone-500 block truncate">Object: {{ $insc->object->name }}</span>
                            @endif
                            @if(!$insc->heritageSite && !$insc->object)
                            <span class="text-stone-400">Independent Epigraph</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <form action="{{ route('admin.inscriptions.toggle-publish', $insc->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $insc->is_published ? 'bg-emerald-100 text-emerald-900 hover:bg-emerald-200' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $insc->is_published ? 'bg-emerald-600' : 'bg-stone-400' }}"></span>
                                    <span>{{ $insc->is_published ? 'Published' : 'Draft' }}</span>
                                </button>
                            </form>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.inscriptions.edit', $insc->id) }}"
                                    class="text-xs font-semibold text-amber-800 hover:text-amber-950 hover:underline">
                                    Edit
                                </a>
                                <span class="text-stone-300">|</span>
                                <form action="{{ route('admin.inscriptions.destroy', $insc->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this epigraphical record?');"
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
                            <p class="font-medium">No epigraphical records match your criteria.</p>
                            <a href="{{ route('admin.inscriptions.create') }}" class="mt-2 inline-block text-xs font-bold text-orange-800 hover:underline">
                                + Register New Inscription
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inscriptions->hasPages())
        <div class="p-4 border-t border-stone-100 bg-stone-50/50">
            {{ $inscriptions->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
