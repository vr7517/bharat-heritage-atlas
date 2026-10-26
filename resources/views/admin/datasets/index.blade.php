@extends('layouts.admin')

@section('title', 'Open Research Datasets & Spatial Archives')

@section('content')
<div class="space-y-6">

    <!-- Top Heading & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Open Research Datasets & Spatial Archives
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Open datasets, GeoJSON coordinates, GIS shapefiles, and epigraphical corpora for public download and academic research.
            </p>
        </div>

        <a href="{{ route('admin.datasets.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
            <span>+</span>
            <span>Register New Dataset</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Datasets</span>
            <p class="text-2xl font-bold text-stone-900 mt-1">{{ $counts['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Published on Atlas</span>
            <p class="text-2xl font-bold text-emerald-800 mt-1">{{ $counts['published'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Featured Open Data</span>
            <p class="text-2xl font-bold text-amber-800 mt-1">{{ $counts['featured'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Draft / Review</span>
            <p class="text-2xl font-bold text-stone-500 mt-1">{{ $counts['draft'] }}</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form action="{{ route('admin.datasets.index') }}" method="GET"
        class="bg-white p-4 rounded-xl border border-stone-200 shadow-2xs grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">

        <div class="sm:col-span-6">
            <label for="search" class="sr-only">Search</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                placeholder="Search by dataset name, organization, description, notes..."
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
        </div>

        <div class="sm:col-span-3">
            <select name="dataset_type"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                <option value="">All Dataset Typologies</option>
                @foreach($datasetTypes as $val => $label)
                <option value="{{ $val }}" @selected(request('dataset_type') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2">
            <select name="status"
                class="w-full rounded-lg border border-stone-300 px-3 py-2 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                <option value="">All Statuses</option>
                <option value="published" @selected(request('status') === 'published')>Published Only</option>
                <option value="draft" @selected(request('status') === 'draft')>Drafts Only</option>
                <option value="featured" @selected(request('status') === 'featured')>Featured Only</option>
            </select>
        </div>

        <div class="sm:col-span-1 flex items-center gap-1">
            <button type="submit"
                class="w-full px-3 py-2 rounded-lg text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                Filter
            </button>
        </div>
    </form>

    <!-- Datasets Table -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-stone-200 bg-stone-50/75 text-[11px] font-bold text-stone-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Dataset Name & Identifier</th>
                        <th class="py-3 px-4">Format & Version</th>
                        <th class="py-3 px-4">Source Organization</th>
                        <th class="py-3 px-4">File Details</th>
                        <th class="py-3 px-4 text-center">Featured</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($datasets as $dataset)
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="py-3.5 px-4 max-w-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-stone-900 text-sm line-clamp-1">
                                    <a href="{{ route('admin.datasets.edit', $dataset->id) }}" class="hover:text-amber-800 hover:underline">
                                        {{ $dataset->name }}
                                    </a>
                                </span>
                            </div>
                            <span class="text-[11px] font-mono text-stone-400 block truncate">/{{ $dataset->slug }}</span>
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-stone-100 text-stone-800 border border-stone-200 font-mono">
                                {{ $dataset->dataset_type }}
                            </span>
                            <span class="text-[11px] text-stone-500 font-mono block mt-0.5">v{{ $dataset->version }}</span>
                        </td>

                        <td class="py-3.5 px-4 text-stone-600 max-w-xs truncate">
                            <span class="font-medium text-stone-800 block truncate">{{ $dataset->source_organization }}</span>
                            @if($dataset->source_url)
                            <a href="{{ $dataset->source_url }}" target="_blank" class="text-[11px] text-amber-800 hover:underline truncate block">
                                Institutional Link &nearr;
                            </a>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-stone-600 font-mono text-[11px]">
                            @if($dataset->file_path)
                            <span class="font-semibold text-emerald-800 block truncate">{{ $dataset->file_name ?: 'Uploaded File' }}</span>
                            <span class="text-stone-400">{{ $dataset->formatted_file_size }}</span>
                            @if($dataset->record_count)
                            <span class="text-stone-300">•</span>
                            <span class="text-stone-500">{{ number_format($dataset->record_count) }} records</span>
                            @endif
                            @else
                            <span class="text-stone-400 italic">No File Uploaded</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <form action="{{ route('admin.datasets.toggle-featured', $dataset->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $dataset->is_featured ? 'bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200' : 'bg-stone-100 text-stone-500 hover:bg-stone-200' }}"
                                    title="Click to toggle featured status">
                                    <span>{{ $dataset->is_featured ? '⭐ Featured' : 'Normal' }}</span>
                                </button>
                            </form>
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <form action="{{ route('admin.datasets.toggle-publish', $dataset->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $dataset->is_published ? 'bg-emerald-100 text-emerald-900 hover:bg-emerald-200' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}"
                                    title="Click to toggle publication">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dataset->is_published ? 'bg-emerald-600' : 'bg-stone-400' }}"></span>
                                    <span>{{ $dataset->is_published ? 'Published' : 'Draft' }}</span>
                                </button>
                            </form>
                        </td>

                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($dataset->is_published)
                                <a href="{{ route('datasets.show', $dataset->slug) }}" target="_blank"
                                    class="p-1.5 rounded-lg text-emerald-700 hover:text-emerald-900 hover:bg-emerald-50 transition"
                                    title="View Public Page">
                                    <span class="font-semibold text-xs">View</span>
                                </a>
                                @endif

                                @if($dataset->file_path)
                                <a href="{{ route('datasets.download', $dataset->slug) }}"
                                    class="p-1.5 rounded-lg text-amber-800 hover:text-amber-950 hover:bg-amber-50 transition"
                                    title="Download File">
                                    <span class="font-semibold text-xs">Download</span>
                                </a>
                                @endif

                                <a href="{{ route('admin.datasets.edit', $dataset->id) }}"
                                    class="p-1.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition"
                                    title="Edit Dataset">
                                    <span class="font-semibold text-xs">Edit</span>
                                </a>

                                <form action="{{ route('admin.datasets.destroy', $dataset->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to permanently delete dataset \'{{ $dataset->name }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition"
                                        title="Delete Dataset">
                                        <span class="font-semibold text-xs">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <p class="text-sm font-semibold text-stone-600">No datasets found matching your query.</p>
                            <p class="text-xs text-stone-400 mt-1">Register a new open dataset or clear search filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($datasets->hasPages())
        <div class="p-4 border-t border-stone-100 bg-stone-50/50">
            {{ $datasets->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
