@extends('layouts.admin')

@section('title', 'Manage Heritage Sites')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Row -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900 flex items-center gap-2">
                <span>🏛️ Heritage Sites & Monuments</span>
                <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-full bg-stone-200 text-stone-700">
                    {{ $counts['total'] }}
                </span>
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Authoritative archaeological complexes, temples, and monolithic pillars powering the digital atlas.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.sites.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-2xs transition">
                <span>➕ Add Heritage Site</span>
            </a>
        </div>
    </div>

    <!-- Status Filter Pills & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-2xs space-y-4">
        <!-- Status Pills -->
        <div class="flex items-center gap-2 border-b border-stone-100 pb-3 text-xs">
            <a href="{{ route('admin.sites.index') }}" class="px-3 py-1.5 rounded-lg font-medium transition {{ !request('status') ? 'bg-orange-800 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                All Sites ({{ $counts['total'] }})
            </a>
            <a href="{{ route('admin.sites.index', ['status' => 'published']) }}" class="px-3 py-1.5 rounded-lg font-medium transition {{ request('status') === 'published' ? 'bg-emerald-800 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                Published ({{ $counts['published'] }})
            </a>
            <a href="{{ route('admin.sites.index', ['status' => 'draft']) }}" class="px-3 py-1.5 rounded-lg font-medium transition {{ request('status') === 'draft' ? 'bg-stone-800 text-white font-bold' : 'text-stone-600 hover:bg-stone-100' }}">
                Drafts ({{ $counts['draft'] }})
            </a>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.sites.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search site, slug, or state..."
                    class="w-full text-xs px-3.5 py-2 rounded-xl border border-stone-300 focus:ring-amber-500 focus:border-amber-500 bg-stone-50/50" />
            </div>

            <div>
                <select name="site_type" class="w-full text-xs px-3.5 py-2 rounded-xl border border-stone-300 bg-stone-50/50">
                    <option value="">All Typologies</option>
                    @foreach($siteTypes as $type)
                    <option value="{{ $type }}" @selected(request('site_type') == $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-stone-900 hover:bg-black text-white rounded-xl text-xs font-semibold shadow-2xs transition">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'site_type']))
                <a href="{{ route('admin.sites.index', request()->only('status')) }}" class="px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-medium transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Sites Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-stone-100 bg-stone-50/70 text-stone-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-6">Monument & Slug</th>
                        <th class="py-3 px-6">Typology & Period</th>
                        <th class="py-3 px-6">Location</th>
                        <th class="py-3 px-6">Dating Statement</th>
                        <th class="py-3 px-6">Child Relics / Claims</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($sites as $site)
                    <tr class="hover:bg-stone-50/80 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-stone-900 text-sm">
                                <a href="{{ route('admin.sites.edit', $site->id) }}" class="hover:text-orange-800 transition">
                                    {{ $site->name }}
                                </a>
                            </div>
                            <div class="font-mono text-[11px] text-stone-400 mt-0.5">{{ $site->slug }}</div>
                        </td>
                        <td class="py-4 px-6 text-stone-600 space-y-1">
                            <div><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-900 border border-amber-200">{{ $site->site_type }}</span></div>
                            <div class="text-[11px] text-stone-500">{{ $site->primaryPeriod?->name ?? 'Unassigned Period' }}</div>
                        </td>
                        <td class="py-4 px-6 text-stone-600">
                            <div class="font-medium text-stone-900">{{ $site->location?->state ?? 'Unassigned' }}</div>
                            <div class="text-[11px] text-stone-400">
                                @if($site->location && $site->location->latitude)
                                {{ number_format($site->location->latitude, 4) }}°, {{ number_format($site->location->longitude, 4) }}°
                                @else
                                Coordinates unmapped
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-stone-600 font-mono text-[11px]">
                            <div>{{ $site->dating_statement }}</div>
                            <div class="text-[10px] text-stone-400">Bounds: {{ $site->start_year ?? '?' }} to {{ $site->end_year ?? '?' }}</div>
                        </td>
                        <td class="py-4 px-6 text-stone-500 font-mono text-[11px] space-y-1">
                            <div>🏺 {{ $site->objects_count }} Objects</div>
                            <div>📜 {{ $site->inscriptions_count }} Epigraphs &bull; ⚖️ {{ $site->claims_count }} Claims</div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <form action="{{ route('admin.sites.toggle-publish', $site->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="group focus:outline-hidden cursor-pointer" title="Click to toggle status">
                                    @if($site->is_published)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 group-hover:bg-amber-100 group-hover:text-amber-900 transition">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Published
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-stone-100 text-stone-600 border border-stone-200 group-hover:bg-emerald-100 group-hover:text-emerald-900 transition">
                                        Draft
                                    </span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="{{ route('sites.show', $site->slug) }}" target="_blank" class="text-stone-500 hover:text-stone-900 font-semibold" title="View live public dossier">
                                Public &nearr;
                            </a>
                            <span class="text-stone-300">•</span>
                            <a href="{{ route('admin.sites.edit', $site->id) }}" class="text-orange-800 hover:text-orange-950 font-bold">
                                Edit
                            </a>
                            <span class="text-stone-300">•</span>
                            <form action="{{ route('admin.sites.destroy', $site->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($site->name) }}\'? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-700 hover:text-rose-900 font-semibold cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-stone-500 text-xs space-y-2">
                            <div class="text-2xl">🏛️</div>
                            <div class="font-bold text-stone-700">No Heritage Sites Found</div>
                            <p class="text-stone-400">Try clearing your filters or create a new heritage site.</p>
                            <a href="{{ route('admin.sites.create') }}" class="inline-block mt-2 px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-orange-800">
                                Add Site Now
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sites->hasPages())
        <div class="p-4 border-t border-stone-100">
            {{ $sites->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
