@extends('layouts.admin')

@section('title', 'Register New Dynasty')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.dynasties.index') }}" class="hover:text-stone-900">Dynasties</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold">New Dynasty</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Register Imperial Realm / Dynasty
            </h1>
        </div>

        <a href="{{ route('admin.dynasties.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
            &larr; Back to Dynasties
        </a>
    </div>

    <!-- Registration Form -->
    <form action="{{ route('admin.dynasties.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- 1. Nomenclature & Chronology -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>👑 1. Nomenclature & Chronological Bounds</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Dynasty name, regnal duration, and astronomical chronology.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="name" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Dynasty / Imperial House Name <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        placeholder="e.g. Maurya Dynasty; Satavahana Empire; Imperial Guptas"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-stone-400 font-normal font-sans">(leave blank to auto-generate)</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                        placeholder="e.g. maurya-empire"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="dating_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Dating Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement') }}"
                        placeholder="e.g. c. 322 – 185 BCE; c. 1st c. BCE – 2nd c. CE"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="start_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical Start Year <span class="text-stone-400 font-normal font-sans">(Negative for BCE)</span>
                    </label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year') }}"
                        placeholder="-322"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year <span class="text-stone-400 font-normal font-sans">(Negative for BCE)</span>
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year') }}"
                        placeholder="-185"
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
                    <input type="text" id="capital" name="capital" value="{{ old('capital') }}"
                        placeholder="e.g. Pataliputra; Pratishthana (Paithan); Tanjavur"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="region" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Core Geographic Territory
                    </label>
                    <input type="text" id="region" name="region" value="{{ old('region') }}"
                        placeholder="e.g. Magadha, Gangetic Plains, Deccan Plateau"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Historical & Architectural Monograph
                    </label>
                    <textarea id="description" name="description" rows="4"
                        placeholder="Monographic overview of the dynasty, architectural stylistic patronages, numismatic output, and historical synchronisms."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.dynasties.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Register Dynasty
            </button>
        </div>

    </form>
</div>
@endsection
