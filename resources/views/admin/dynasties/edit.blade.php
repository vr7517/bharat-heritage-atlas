@extends('layouts.admin')

@section('title', 'Edit ' . $dynasty->name)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.dynasties.index') }}" class="hover:text-stone-900">Dynasties</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold truncate">{{ Str::limit($dynasty->name, 30) }}</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Edit Imperial Realm / Dynasty
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dynasties.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                &larr; Back to Dynasties
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.dynasties.update', $dynasty->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- 1. Nomenclature & Chronology -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                        <span>👑 1. Nomenclature & Chronological Bounds</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Dynasty name, regnal duration, and astronomical chronology.</p>
                </div>
                <span class="text-xs font-mono text-stone-400">ID: #{{ $dynasty->id }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Dynasty / Imperial House Name <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $dynasty->name) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" required value="{{ old('slug', $dynasty->slug) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="dating_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Dating Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement', $dynasty->dating_statement) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="start_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical Start Year <span class="text-stone-400 font-normal font-sans">(Negative for BCE)</span>
                    </label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year', $dynasty->start_year) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year <span class="text-stone-400 font-normal font-sans">(Negative for BCE)</span>
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year', $dynasty->end_year) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 2. Geographic Realm & Seat of Power -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🏛️ 2. Core Geography & Imperial Seat</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Primary imperial capital and territorial extent.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div>
                    <label for="capital" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Imperial Capital / Seat of Sovereign
                    </label>
                    <input type="text" id="capital" name="capital" value="{{ old('capital', $dynasty->capital) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="region" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Core Geographic Territory
                    </label>
                    <input type="text" id="region" name="region" value="{{ old('region', $dynasty->region) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Historical & Architectural Monograph
                    </label>
                    <textarea id="description" name="description" rows="5"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('description', $dynasty->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 3. Linked Entities Summary -->
        <div class="bg-stone-50 p-6 rounded-2xl border border-stone-200 grid grid-cols-2 gap-4 text-center">
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Associated Heritage Sites</span>
                <p class="text-2xl font-bold text-stone-900 mt-1">{{ $dynasty->heritage_sites_count }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Associated Artifacts & Relics</span>
                <p class="text-2xl font-bold text-amber-800 mt-1">{{ $dynasty->objects_count }}</p>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-between gap-3 pt-4">
            <a href="{{ route('admin.dynasties.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Update Dynasty Record
            </button>
        </div>

    </form>
</div>
@endsection
