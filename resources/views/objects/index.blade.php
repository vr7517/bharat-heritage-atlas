@extends('layouts.app')

@section('title', __('ui.nav_objects') . ' — ' . __('ui.brand_title'))
@section('meta_description', 'Explore cataloged ancient Indian sculptures, monolithic pillars, stupas, and reliquaries with verified archaeological stratigraphy.')

@section('content')
<div class="bg-[#faf8f5] py-8 sm:py-10 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
            <span aria-hidden="true">/</span>
            <span class="text-stone-800 font-bold">{{ __('ui.nav_objects') }}</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-stone-900 flex items-center gap-2.5">
                    <span aria-hidden="true">🏺</span>
                    <span>{{ __('ui.nav_objects') }}</span>
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1 max-w-2xl">
                    Cataloged ancient Indian sculptures, commemorative columns, reliquaries, and in situ architectural members.
                </p>
            </div>
            <div class="font-mono text-xs text-stone-600 bg-white px-3.5 py-2 rounded-xl border border-stone-200 shadow-2xs shrink-0">
                Cataloged Relics: <strong class="text-orange-950 font-bold text-sm">{{ $objects->total() }}</strong>
            </div>
        </div>

        <!-- Filter Bar Form -->
        <form action="{{ route('objects.index') }}" method="GET" class="mt-6 bg-white p-4 sm:p-5 rounded-2xl border border-stone-200/90 shadow-xs grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label for="object-search" class="sr-only">Search</label>
                <input id="object-search" type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search object, material, repository..." 
                       class="w-full min-h-[44px] text-xs px-3.5 py-2 rounded-xl border border-stone-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500" />
            </div>
            <div>
                <label for="object-type" class="sr-only">Object Type</label>
                <select id="object-type" name="object_type" class="w-full min-h-[44px] text-xs px-3 py-2 rounded-xl border border-stone-300 bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">All Object Types</option>
                    @foreach($objectTypes as $type)
                    <option value="{{ $type }}" @selected(request('object_type') == $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full min-h-[44px] px-5 py-2 bg-orange-800 hover:bg-orange-900 text-white rounded-xl text-xs font-bold shadow-xs transition">
                    {{ __('ui.filter') }}
                </button>
                @if(request()->anyFilled(['search', 'object_type']))
                <a href="{{ route('objects.index') }}" class="min-h-[44px] px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-semibold transition flex items-center justify-center">
                    {{ __('ui.reset') }}
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($objects as $obj)
        @php
            $cover = $obj->coverImage();
        @endphp
        <article class="bg-white rounded-2xl border border-stone-200/90 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
            
            <!-- Object Image / Fallback -->
            <div class="relative w-full aspect-[16/10] bg-stone-100 overflow-hidden border-b border-stone-100">
                @if($cover)
                    <img src="{{ $cover->url }}" 
                         alt="{{ $cover->alt_text ?? $obj->name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                         loading="lazy">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-amber-100/60 via-stone-100 to-amber-50 flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-12 h-12 rounded-full bg-amber-800/10 text-amber-800 flex items-center justify-center mb-2 shadow-2xs">
                            <span class="text-2xl" aria-hidden="true">🏺</span>
                        </div>
                        <span class="text-sm font-bold text-stone-800 font-serif">{{ $obj->name }}</span>
                        <span class="text-[11px] text-stone-600 mt-1">{{ $obj->material ?? 'Archival Antiquity' }}</span>
                    </div>
                @endif

                <!-- Badges Overlay -->
                <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-stone-900/85 backdrop-blur-md text-amber-300 border border-amber-400/30 shadow-xs">
                        {{ $obj->object_type }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/95 backdrop-blur-md text-stone-800 shadow-xs border border-stone-200">
                        {{ $obj->current_repository ?? __('ui.in_situ') }}
                    </span>
                </div>
            </div>

            <!-- Content Header -->
            <div class="p-6 pb-3">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    @if($obj->period)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-900 border border-amber-200">
                        {{ $obj->period->name }}
                    </span>
                    @endif
                    @if($obj->dynasty)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-stone-100 text-stone-700">
                        {{ $obj->dynasty->name }}
                    </span>
                    @endif
                </div>

                <h2 class="text-xl font-bold text-stone-900 group-hover:text-orange-800 transition">
                    <a href="{{ route('objects.show', $obj->slug) }}">
                        {{ $obj->name }}
                    </a>
                </h2>

                <div class="mt-2.5 inline-flex items-center gap-1.5 text-xs text-stone-600 font-medium bg-amber-50/80 px-2.5 py-1 rounded-lg border border-amber-200/70">
                    <span class="text-amber-700" aria-hidden="true">⏳</span>
                    <span>{{ $obj->dating_statement }}</span>
                </div>
            </div>

            <!-- Summary Body -->
            <div class="px-6 py-2 flex-grow">
                <p class="text-xs text-stone-600 leading-relaxed line-clamp-3">
                    {{ $obj->description }}
                </p>
            </div>

            <!-- Card Footer -->
            <div class="p-6 pt-4 border-t border-stone-100 bg-stone-50/60 flex items-center justify-between text-xs text-stone-500">
                <div class="flex items-center gap-1.5 truncate max-w-[60%]">
                    <span class="truncate">🏛️ {{ $obj->heritageSite?->name ?? 'Standalone' }}</span>
                </div>
                <a href="{{ route('objects.show', $obj->slug) }}" class="min-h-[44px] inline-flex items-center font-bold text-orange-800 hover:text-orange-950 transition gap-1 shrink-0">
                    <span>{{ __('ui.view_object') }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-20 bg-white rounded-3xl border border-stone-200 p-8 shadow-xs">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-800 mx-auto flex items-center justify-center text-3xl mb-4 border border-amber-200">
                🏺
            </div>
            <h3 class="text-lg font-bold text-stone-900">{{ __('ui.no_records_found') }}</h3>
            <p class="text-xs sm:text-sm text-stone-600 mt-2 max-w-md mx-auto">
                {{ __('ui.no_records_suggestion') }}
            </p>
            <div class="mt-6">
                <a href="{{ route('objects.index') }}" class="min-h-[44px] inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition">
                    {{ __('ui.clear_filters') }}
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $objects->links() }}
    </div>
</div>
@endsection
