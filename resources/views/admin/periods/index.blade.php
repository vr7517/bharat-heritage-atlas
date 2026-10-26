@extends('layouts.admin')

@section('title', 'Historical Periods & Archaeological Horizons')

@section('content')
<div class="space-y-6">

    <!-- Top Heading & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Historical Periods & Archaeological Horizons
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Chronological timeline boundaries, cultural horizons, and stratigraphic epochs of Indian history.
            </p>
        </div>

        <a href="{{ route('admin.periods.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
            <span>+</span>
            <span>Register New Period</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Defined Horizons</span>
            <p class="text-2xl font-bold text-stone-900 mt-1">{{ $counts['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">BCE Horizons</span>
            <p class="text-2xl font-bold text-amber-800 mt-1">
                {{ $periods->where('start_era', 'BCE')->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">CE Horizons</span>
            <p class="text-2xl font-bold text-stone-700 mt-1">
                {{ $periods->where('start_era', 'CE')->count() }}
            </p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form action="{{ route('admin.periods.index') }}" method="GET"
        class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">

        <div class="sm:col-span-10">
            <label for="search" class="sr-only">Search</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                placeholder="Search by period name, dating statement, or cultural horizon description..."
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
        </div>

        <div class="sm:col-span-2 flex items-center gap-2">
            <button type="submit"
                class="w-full px-4 py-2 rounded-lg text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search']))
            <a href="{{ route('admin.periods.index') }}"
                class="px-3 py-2 rounded-lg text-xs font-medium text-stone-500 hover:bg-stone-100 transition">
                Reset
            </a>
            @endif
        </div>
    </form>

    <!-- Periods Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-stone-200 bg-stone-50/75 text-[11px] font-bold text-stone-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Period / Horizon</th>
                        <th class="py-3 px-4">Scholarly Dating</th>
                        <th class="py-3 px-4">Calibrated Year Span</th>
                        <th class="py-3 px-4 text-center">Sites Linked</th>
                        <th class="py-3 px-4 text-center">Artifacts Linked</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($periods as $period)
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-stone-900 text-sm">
                                <a href="{{ route('admin.periods.edit', $period->id) }}" class="hover:text-amber-800 hover:underline">
                                    {{ $period->name }}
                                </a>
                            </div>
                            <span class="text-[11px] font-mono text-stone-400">/{{ $period->slug }}</span>
                        </td>

                        <td class="py-3.5 px-4 font-semibold text-stone-800">
                            {{ $period->dating_statement }}
                        </td>

                        <td class="py-3.5 px-4 font-mono text-stone-600">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-stone-100 text-stone-800 text-[11px] font-semibold">
                                {{ $period->start_year }} {{ $period->start_era }} – {{ $period->end_year }} {{ $period->end_era }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700">
                                {{ $period->heritage_sites_count }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700">
                                {{ $period->objects_count }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.periods.edit', $period->id) }}"
                                    class="p-1.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition"
                                    title="Edit Period">
                                    <span class="font-semibold text-xs">Edit</span>
                                </a>

                                <form action="{{ route('admin.periods.destroy', $period->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to permanently delete historical period \'{{ $period->name }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition"
                                        title="Delete Period">
                                        <span class="font-semibold text-xs">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <p class="text-sm font-semibold text-stone-600">No historical periods found matching criteria.</p>
                            <p class="text-xs text-stone-400 mt-1">Register a period or clear search filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($periods->hasPages())
        <div class="p-4 border-t border-stone-100 bg-stone-50/50">
            {{ $periods->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
