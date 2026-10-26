@extends('layouts.admin')

@section('title', 'Dynasties & Imperial Realms')

@section('content')
<div class="space-y-6">

    <!-- Top Heading & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Dynasties & Imperial Realms
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Historical royal dynasties, imperial houses, and regional ruling polities of the Indian subcontinent.
            </p>
        </div>

        <a href="{{ route('admin.dynasties.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
            <span>+</span>
            <span>Register New Dynasty</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Cataloged Dynasties</span>
            <p class="text-2xl font-bold text-stone-900 mt-1">{{ $counts['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Ancient & Classical Dynasties</span>
            <p class="text-2xl font-bold text-amber-800 mt-1">
                {{ $dynasties->where('start_year', '<', 600)->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Medieval & Later Polities</span>
            <p class="text-2xl font-bold text-stone-700 mt-1">
                {{ $dynasties->where('start_year', '>=', 600)->count() }}
            </p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form action="{{ route('admin.dynasties.index') }}" method="GET"
        class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">

        <div class="sm:col-span-10">
            <label for="search" class="sr-only">Search</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                placeholder="Search by dynasty name, capital, region, or historical period..."
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
        </div>

        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit"
                class="w-full px-4 py-2 rounded-lg text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search']))
            <a href="{{ route('admin.dynasties.index') }}"
                class="px-3 py-2 rounded-lg text-xs font-medium text-stone-500 hover:bg-stone-100 transition">
                Reset
            </a>
            @endif
        </div>
    </form>

    <!-- Dynasties Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-stone-200 bg-stone-50/75 text-[11px] font-bold text-stone-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Dynasty / House</th>
                        <th class="py-3 px-4">Chronology & Dating</th>
                        <th class="py-3 px-4">Core Region & Capital</th>
                        <th class="py-3 px-4 text-center">Sites Linked</th>
                        <th class="py-3 px-4 text-center">Artifacts Linked</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($dynasties as $dynasty)
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-stone-900 text-sm">
                                <a href="{{ route('admin.dynasties.edit', $dynasty->id) }}" class="hover:text-amber-800 hover:underline">
                                    {{ $dynasty->name }}
                                </a>
                            </div>
                            <span class="text-[11px] font-mono text-stone-400">/{{ $dynasty->slug }}</span>
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="font-semibold text-stone-800 block">{{ $dynasty->dating_statement }}</span>
                            @if($dynasty->start_year !== null && $dynasty->end_year !== null)
                            <span class="text-[11px] text-stone-500 font-mono">
                                {{ $dynasty->start_year < 0 ? abs($dynasty->start_year) . ' BCE' : $dynasty->start_year . ' CE' }} &ndash;
                                {{ $dynasty->end_year < 0 ? abs($dynasty->end_year) . ' BCE' : $dynasty->end_year . ' CE' }}
                            </span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-stone-600">
                            @if($dynasty->capital)
                            <div class="font-medium text-stone-800 flex items-center gap-1">
                                <span>🏛️ Capital:</span>
                                <span>{{ $dynasty->capital }}</span>
                            </div>
                            @endif
                            @if($dynasty->region)
                            <span class="text-[11px] text-stone-500 block">Territory: {{ $dynasty->region }}</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700">
                                {{ $dynasty->heritage_sites_count }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700">
                                {{ $dynasty->objects_count }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.dynasties.edit', $dynasty->id) }}"
                                    class="p-1.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition"
                                    title="Edit Dynasty">
                                    <span class="font-semibold text-xs">Edit</span>
                                </a>

                                <form action="{{ route('admin.dynasties.destroy', $dynasty->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to permanently delete dynasty \'{{ $dynasty->name }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition"
                                        title="Delete Dynasty">
                                        <span class="font-semibold text-xs">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <p class="text-sm font-semibold text-stone-600">No dynasties found matching criteria.</p>
                            <p class="text-xs text-stone-400 mt-1">Register a dynasty or clear search filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dynasties->hasPages())
        <div class="p-4 border-t border-stone-100 bg-stone-50/50">
            {{ $dynasties->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
