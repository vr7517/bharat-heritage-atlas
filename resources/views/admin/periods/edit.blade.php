@extends('layouts.admin')

@section('title', 'Edit ' . $period->name)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.periods.index') }}" class="hover:text-stone-900">Timeline Periods</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold truncate">{{ Str::limit($period->name, 30) }}</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Edit Historical Period / Horizon
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.periods.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                &larr; Back to Periods
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.periods.update', $period->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- 1. Nomenclature & Chronological Bounds -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                        <span>⏳ 1. Period Horizon & Chronological Coordinates</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Formal period designation, dating formula, and astronomical years.</p>
                </div>
                <span class="text-xs font-mono text-stone-400">ID: #{{ $period->id }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Period / Horizon Name <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $period->name) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" required value="{{ old('slug', $period->slug) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="dating_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Dating Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement', $period->dating_statement) }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <!-- Start Year & Era -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        Start Year & Era <span class="text-rose-600">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" id="start_year" name="start_year" required value="{{ old('start_year', $period->start_year) }}"
                            class="w-full rounded-xl border border-stone-300 px-3 py-2 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                        <select name="start_era" required
                            class="w-full rounded-xl border border-stone-300 px-3 py-2 text-xs font-bold text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                            <option value="BCE" @selected(old('start_era', $period->start_era) === 'BCE')>BCE</option>
                            <option value="CE" @selected(old('start_era', $period->start_era) === 'CE')>CE</option>
                        </select>
                    </div>
                </div>

                <!-- End Year & Era -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-stone-700 uppercase tracking-wider">
                        End Year & Era <span class="text-rose-600">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" id="end_year" name="end_year" required value="{{ old('end_year', $period->end_year) }}"
                            class="w-full rounded-xl border border-stone-300 px-3 py-2 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                        <select name="end_era" required
                            class="w-full rounded-xl border border-stone-300 px-3 py-2 text-xs font-bold text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                            <option value="BCE" @selected(old('end_era', $period->end_era) === 'BCE')>BCE</option>
                            <option value="CE" @selected(old('end_era', $period->end_era) === 'CE')>CE</option>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Stratigraphical & Cultural Description
                    </label>
                    <textarea id="description" name="description" rows="5"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('description', $period->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 2. Linked Entities Summary -->
        <div class="bg-stone-50 p-6 rounded-2xl border border-stone-200 grid grid-cols-2 gap-4 text-center">
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Associated Heritage Sites</span>
                <p class="text-2xl font-bold text-stone-900 mt-1">{{ $period->heritage_sites_count }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Associated Artifacts & Relics</span>
                <p class="text-2xl font-bold text-amber-800 mt-1">{{ $period->objects_count }}</p>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-between gap-3 pt-4">
            <a href="{{ route('admin.periods.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>

            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Update Historical Period
            </button>
        </div>

    </form>
</div>
@endsection
