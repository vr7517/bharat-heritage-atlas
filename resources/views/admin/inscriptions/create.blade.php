@extends('layouts.admin')

@section('title', 'Register New Inscription')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Navigation & Heading -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.inscriptions.index') }}" class="hover:text-stone-900">Epigraphy</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold">New Inscription</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">
                Register Epigraphical Record
            </h1>
        </div>

        <a href="{{ route('admin.inscriptions.index') }}" class="px-3.5 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
            &larr; Back to Epigraphy
        </a>
    </div>

    <!-- Registration Form -->
    <form action="{{ route('admin.inscriptions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- 1. Epigraph Nomenclature & Linguistic Attribution -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📜 1. Epigraph Nomenclature & Language</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Formal epigraph title, palaeographic script, and language medium.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="title" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Epigraph Title / Inscription Identifier <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        placeholder="e.g. Heliodorus Pillar Garuda Standard Brahmi Inscription"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="slug" class="block font-bold text-stone-700 uppercase tracking-wider">
                        URL Slug <span class="text-stone-400 font-normal font-sans">(leave blank to auto-generate)</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                        placeholder="e.g. heliodorus-brahmi-inscription"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50" />
                </div>

                <div>
                    <label for="epigraphic_reference" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Epigraphical Publication Reference <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="epigraphic_reference" name="epigraphic_reference" required value="{{ old('epigraphic_reference') }}"
                        placeholder="e.g. Epigraphia Indica Vol. II, p. 87; Corpus Inscriptionum Indicarum Vol. I"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="language" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Language Medium <span class="text-rose-600">*</span>
                    </label>
                    <select id="language" name="language" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($languages as $val => $label)
                        <option value="{{ $val }}" @selected(old('language') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="script" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Palaeographic Script <span class="text-rose-600">*</span>
                    </label>
                    <select id="script" name="script" required
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        @foreach($scripts as $val => $label)
                        <option value="{{ $val }}" @selected(old('script') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="donor" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Donor / Dedicator Mentioned
                    </label>
                    <input type="text" id="donor" name="donor" value="{{ old('donor') }}"
                        placeholder="e.g. Heliodorus, son of Dion, ambassador of King Antialcidas"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="ruler_mentioned" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Reigning Monarch / Sovereign Mentioned
                    </label>
                    <input type="text" id="ruler_mentioned" name="ruler_mentioned" value="{{ old('ruler_mentioned') }}"
                        placeholder="e.g. Kasiputra Bhagabhadra; Ashoka Maurya"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 2. Provenance & Chronology -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>⏳ 2. Dating & Physical Association</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Chronological dating statement and linkage to sites or objects.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="dating_statement" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Scholarly Dating Statement <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="dating_statement" name="dating_statement" required value="{{ old('dating_statement') }}"
                        placeholder="e.g. c. 113 BCE (14th regnal year of Maharaja Bhagabhadra)"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="start_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical Start Year
                    </label>
                    <input type="number" id="start_year" name="start_year" value="{{ old('start_year') }}"
                        placeholder="-115"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="end_year" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Astronomical End Year
                    </label>
                    <input type="number" id="end_year" name="end_year" value="{{ old('end_year') }}"
                        placeholder="-110"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="heritage_site_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Associated Heritage Monument / Site
                    </label>
                    <select id="heritage_site_id" name="heritage_site_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- No Direct Site Association (Independent Edict) --</option>
                        @foreach($sites as $site)
                        <option value="{{ $site->id }}" @selected(old('heritage_site_id') == $site->id)>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="object_id" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Associated Inscribed Artifact
                    </label>
                    <select id="object_id" name="object_id"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white">
                        <option value="">-- No Direct Object Association (Lithic Rock / Wall Inscription) --</option>
                        @foreach($objects as $obj)
                        <option value="{{ $obj->id }}" @selected(old('object_id') == $obj->id)>{{ $obj->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Diplomatic Text & Translation -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🔤 3. Epigraphical Text & Translation</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Diplomatic transliteration and English academic translation.</p>
            </div>

            <div class="space-y-5 text-xs">
                <div>
                    <label for="raw_text" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Diplomatic Transliteration / Original Brahmi Text
                    </label>
                    <textarea id="raw_text" name="raw_text" rows="4"
                        placeholder="e.g. Deva-devasa va-[sude]vasa garuda-dhvaje ayam karite ia heliodorena bhagavatena..."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs font-mono text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-900 text-amber-200 leading-relaxed">{{ old('raw_text') }}</textarea>
                </div>

                <div>
                    <label for="translation" class="block font-bold text-stone-700 uppercase tracking-wider">
                        English Academic Translation <span class="text-rose-600">*</span>
                    </label>
                    <textarea id="translation" name="translation" rows="4" required
                        placeholder="e.g. This Garuda-standard of Vasudeva, the god of gods, was erected here by the devotee Heliodorus..."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('translation') }}</textarea>
                </div>

                <div>
                    <label for="interpretation_notes" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Epigraphical Commentary & Palaeographical Notes
                    </label>
                    <textarea id="interpretation_notes" name="interpretation_notes" rows="3"
                        placeholder="Scholarly debate regarding reading of specific ligatures, grammar, or historical implications."
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white leading-relaxed">{{ old('interpretation_notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4. Rubbing / Estampage Photograph Upload -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-6">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                    <span>📸 4. Primary Rubbing / Estampage Facsimile</span>
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">High-resolution photographic plate or ink squeeze estampage.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label for="cover_image" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Estampage Image File <span class="text-stone-400 font-normal font-sans">(JPEG, PNG, WebP up to 10MB)</span>
                    </label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-stone-50/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 cursor-pointer" />
                </div>

                <div class="sm:col-span-2">
                    <label for="cover_caption" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Image Caption / Description
                    </label>
                    <input type="text" id="cover_caption" name="cover_caption" value="{{ old('cover_caption') }}"
                        placeholder="e.g. Ink squeeze estampage of the 8-line Brahmi dedication on pillar shaft"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="cover_credit" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Source / Institutional Credit
                    </label>
                    <input type="text" id="cover_credit" name="cover_credit" value="{{ old('cover_credit', 'Epigraphia Indica / ASI Archive') }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>

                <div>
                    <label for="cover_license" class="block font-bold text-stone-700 uppercase tracking-wider">
                        Copyright / License
                    </label>
                    <input type="text" id="cover_license" name="cover_license" value="{{ old('cover_license', 'Creative Commons (CC BY-SA 4.0)') }}"
                        class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
                </div>
            </div>
        </div>

        <!-- 5. Publishing Status -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-stone-200 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider flex items-center gap-2">
                <span>🛡️ 5. Publishing & Visibility Control</span>
            </h2>

            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 flex items-start gap-3">
                <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', true))
                    class="mt-0.5 w-5 h-5 rounded border-stone-300 text-orange-800 focus:ring-amber-500 cursor-pointer" />
                <label for="is_published" class="text-xs text-stone-700 cursor-pointer select-none">
                    <strong class="text-stone-900 block text-sm font-bold">Publish Immediately on Public Atlas</strong>
                    When checked, this inscription will immediately be visible on public epigraphy explorers and APIs. Uncheck to save as a draft.
                </label>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.inscriptions.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 shadow-sm transition">
                Register Epigraphical Record
            </button>
        </div>

    </form>
</div>
@endsection
