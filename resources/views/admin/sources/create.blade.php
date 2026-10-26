@extends('layouts.admin')

@section('title', 'Register Bibliographic Source')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.sources.index') }}" class="hover:text-stone-900">Sources</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold">Register New</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Register Scholarly Source
            </h1>
        </div>

        <a href="{{ route('admin.sources.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
            &larr; Back to Sources
        </a>
    </div>

    <!-- Registration Form -->
    <form action="{{ route('admin.sources.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📚 1. Publication & Authorship</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Authoritative bibliographic metadata according to Chicago Manual of Style / ASI norms.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="title" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Full Work Title <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        placeholder="e.g. The Monuments of Sanchi: Volume I (Texts)"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label for="authors" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Author(s) / Editor(s) / Surveyors <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="authors" name="authors" required value="{{ old('authors') }}"
                        placeholder="e.g. Marshall, John; Foucher, Alfred; Majumdar, N. G."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="source_type" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Source Typology <span class="text-rose-600">*</span>
                    </label>
                    <select id="source_type" name="source_type" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($sourceTypes as $val => $label)
                        <option value="{{ $val }}" @selected(old('source_type') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="publication_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Publication Year (BCE negative, CE positive)
                    </label>
                    <input type="number" id="publication_year" name="publication_year" value="{{ old('publication_year') }}"
                        placeholder="e.g. 1940"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="publisher" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Publisher / Institution
                    </label>
                    <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}"
                        placeholder="e.g. Archaeological Survey of India / Manager of Publications, Delhi"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="journal_or_series" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Journal / Series Title
                    </label>
                    <input type="text" id="journal_or_series" name="journal_or_series" value="{{ old('journal_or_series') }}"
                        placeholder="e.g. Memoirs of the Archaeological Survey of India (MASI)"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="volume_issue" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Volume / Issue / Fascicle
                    </label>
                    <input type="text" id="volume_issue" name="volume_issue" value="{{ old('volume_issue') }}"
                        placeholder="e.g. Vol. 1, No. 3"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="pages" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Standard Pagination Span
                    </label>
                    <input type="text" id="pages" name="pages" value="{{ old('pages') }}"
                        placeholder="e.g. pp. 115–142"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🔍 2. Digital Identifiers & Rigor Tier</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Classification tier and persistent resolution handles.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div>
                    <label for="reliability_tier" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Reliability Tier <span class="text-rose-600">*</span>
                    </label>
                    <select id="reliability_tier" name="reliability_tier" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($reliabilityTiers as $tierKey => $tierLabel)
                        <option value="{{ $tierKey }}" @selected(old('reliability_tier') === $tierKey)>{{ $tierLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="isbn_issn" class="block font-bold text-stone-700 uppercase tracking-wider">
                        ISBN / ISSN Identifier
                    </label>
                    <input type="text" id="isbn_issn" name="isbn_issn" value="{{ old('isbn_issn') }}"
                        placeholder="e.g. 978-81-208-0138-7"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="doi" class="block font-bold text-stone-700 uppercase tracking-wider">
                        DOI (Digital Object Identifier)
                    </label>
                    <input type="text" id="doi" name="doi" value="{{ old('doi') }}"
                        placeholder="e.g. 10.1017/s0041977x00012345"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="url" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Archival / Repository URL
                    </label>
                    <input type="url" id="url" name="url" value="{{ old('url') }}"
                        placeholder="https://asi.nic.in/..."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label for="archival_location" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Physical Archival Location / Manuscript Repository
                    </label>
                    <input type="text" id="archival_location" name="archival_location" value="{{ old('archival_location') }}"
                        placeholder="e.g. National Archives of India, New Delhi; ASI Central Archaeological Library"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Curatorial Notes & Methodology Overview
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        placeholder="Contextual commentary on the source's reception, revisions, or critical apparatus."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.sources.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Register Bibliographic Source
            </button>
        </div>
    </form>

</div>
@endsection
