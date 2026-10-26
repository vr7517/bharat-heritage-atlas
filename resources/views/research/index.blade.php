@extends('layouts.app')

@section('title', 'Academic Research Tools & Evidence Workbench — Bharat Heritage Atlas')
@section('meta_description', 'Scholarly research suite for Indian archaeology: multi-format citation generator (APA, Chicago, BibTeX), chronological synchronizer, and evidence consensus matrix.')

@section('content')
<div class="space-y-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Hero Header -->
    <div class="bg-gradient-to-br from-stone-900 to-amber-950 text-white rounded-3xl p-6 sm:p-10 shadow-sm space-y-4">
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                Digital Humanities Suite
            </span>
            <span class="text-xs text-stone-400">&bull; Academic Evidence Standards</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-bold tracking-tight text-white leading-tight">
            Academic Research Tools & Evidence Workbench
        </h1>
        <p class="text-xs sm:text-sm text-stone-300 max-w-2xl leading-relaxed">
            Methodological utilities for archaeological researchers, historians, and epigraphists: automated citation formatting, stratigraphical synchronization, and evidence consensus assessment.
        </p>

        <!-- Quick Action Buttons -->
        <div class="pt-2 flex flex-wrap items-center gap-3 text-xs">
            <a href="{{ route('api.v1.export.geojson') }}" class="px-4 py-2 rounded-xl bg-amber-500 text-stone-950 font-bold hover:bg-amber-400 transition shadow-xs flex items-center gap-1.5">
                <span>🌐 Spatial GeoJSON Export</span>
                <span>&darr;</span>
            </a>
            <a href="{{ route('api.v1.export.bibtex') }}" class="px-4 py-2 rounded-xl bg-stone-800 text-stone-200 font-semibold hover:bg-stone-700 border border-stone-700 transition flex items-center gap-1.5">
                <span>📚 Complete BibTeX (.bib)</span>
                <span>&darr;</span>
            </a>
            <a href="{{ route('api.v1.export.sites.csv') }}" class="px-4 py-2 rounded-xl bg-stone-800 text-stone-200 font-semibold hover:bg-stone-700 border border-stone-700 transition flex items-center gap-1.5">
                <span>📊 Sites Tabular CSV</span>
                <span>&darr;</span>
            </a>
            <a href="{{ route('developers.index') }}" class="px-4 py-2 rounded-xl bg-stone-800 text-amber-400 font-semibold hover:bg-stone-700 border border-stone-700 transition flex items-center gap-1.5">
                <span>⚡ REST API Docs &rarr;</span>
            </a>
        </div>
    </div>

    <!-- Section 1: Multi-Format Academic Citation Generator -->
    <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-lg">✒️</span>
                <h2 class="text-lg font-bold text-stone-900">Academic Citation Generator</h2>
            </div>
            <p class="text-xs text-stone-500 mt-1">
                Generate formatted citations in APA 7th, Chicago 17th, Harvard, BibTeX, and Wikipedia markup for monuments, inscriptions, and peer-reviewed sources.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-4 space-y-1.5">
                <label for="citation-entity-type" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">Entity Typology</label>
                <select id="citation-entity-type" onchange="updateCitationEntities()"
                    class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600">
                    <option value="site">🏛️ Heritage Site / Monument</option>
                    <option value="inscription">📜 Epigraphical Inscription</option>
                    <option value="source">📚 Bibliographic Source</option>
                </select>
            </div>

            <div class="sm:col-span-8 space-y-1.5">
                <label for="citation-entity-select" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">Select Record</label>
                <select id="citation-entity-select" onchange="fetchCitation()"
                    class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600">
                    @foreach($sites as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->dating_statement }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Citation Formats Output Box -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center gap-2 border-b border-stone-100 pb-2">
                <button type="button" onclick="setCitationTab('apa')" id="tab-apa" class="citation-tab px-3 py-1.5 rounded-lg text-xs font-bold bg-stone-900 text-white">APA 7th</button>
                <button type="button" onclick="setCitationTab('chicago')" id="tab-chicago" class="citation-tab px-3 py-1.5 rounded-lg text-xs font-medium text-stone-600 hover:bg-stone-100">Chicago 17th</button>
                <button type="button" onclick="setCitationTab('harvard')" id="tab-harvard" class="citation-tab px-3 py-1.5 rounded-lg text-xs font-medium text-stone-600 hover:bg-stone-100">Harvard</button>
                <button type="button" onclick="setCitationTab('bibtex')" id="tab-bibtex" class="citation-tab px-3 py-1.5 rounded-lg text-xs font-medium text-stone-600 hover:bg-stone-100">BibTeX</button>
                <button type="button" onclick="setCitationTab('wikipedia')" id="tab-wikipedia" class="citation-tab px-3 py-1.5 rounded-lg text-xs font-medium text-stone-600 hover:bg-stone-100">Wikipedia</button>
            </div>

            <div class="relative">
                <pre id="citation-display" class="w-full rounded-2xl bg-stone-50 border border-stone-200 p-4 text-xs font-mono text-stone-800 whitespace-pre-wrap select-all leading-relaxed min-h-[5rem] overflow-x-auto">Loading citation data...</pre>
                <button type="button" onclick="copyCitation()" id="copy-btn"
                    class="absolute top-3 right-3 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-amber-800 hover:bg-amber-900 transition shadow-2xs">
                    Copy Citation
                </button>
            </div>
        </div>
    </div>

    <!-- Section 2: Stratigraphical & Chronological Synchronizer -->
    <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-lg">⏳</span>
                <h2 class="text-lg font-bold text-stone-900">Chronological & Stratigraphical Synchronizer</h2>
            </div>
            <p class="text-xs text-stone-500 mt-1">
                Comparative correlation of archaeological epochs, dynasties, and calendar calibrations across ancient and medieval India.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-stone-200 bg-stone-50/75 text-[11px] font-bold text-stone-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Period / Epoch Horizon</th>
                        <th class="py-3 px-4">Calibrated Year Span</th>
                        <th class="py-3 px-4">Associated Dynastic Houses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($periods as $period)
                    @php
                        $dynastiesForPeriod = $dynasties->where('period_id', $period->id);
                    @endphp
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="py-3 px-4 font-bold text-stone-900">
                            {{ $period->name }}
                        </td>
                        <td class="py-3 px-4 font-mono text-stone-700">
                            {{ $period->dating_statement }}
                            <span class="text-[10px] text-stone-400 block mt-0.5">({{ $period->start_year }} to {{ $period->end_year }})</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($dynastiesForPeriod->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($dynastiesForPeriod as $dyn)
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-stone-100 text-stone-800 border border-stone-200">
                                    {{ $dyn->name }} ({{ $dyn->dating_statement }})
                                </span>
                                @endforeach
                            </div>
                            @else
                            <span class="text-stone-400 italic text-[11px]">General Cultural Horizon</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 3: Evidence Consensus Matrix -->
    <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-lg">🛡️</span>
                <h2 class="text-lg font-bold text-stone-900">Academic Evidence & Consensus Matrix</h2>
            </div>
            <p class="text-xs text-stone-500 mt-1">
                Rigorous four-tier reliability assessment separating empirically verified primary inscriptions and stratigraphic strata from scholarly consensus and disputed hypotheses.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/50 space-y-1">
                <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider block">Strong Consensus</span>
                <p class="text-2xl font-bold text-emerald-950 font-mono">{{ $claimsByConsensus['STRONG_CONSENSUS'] }}</p>
                <p class="text-[10px] text-emerald-800">Direct primary epigraphical or stratigraphic proof.</p>
            </div>

            <div class="p-4 rounded-2xl border border-blue-200 bg-blue-50/50 space-y-1">
                <span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider block">Probable</span>
                <p class="text-2xl font-bold text-blue-950 font-mono">{{ $claimsByConsensus['PROBABLE'] }}</p>
                <p class="text-[10px] text-blue-800">Strong archaeological basis with high agreement.</p>
            </div>

            <div class="p-4 rounded-2xl border border-amber-200 bg-amber-50/50 space-y-1">
                <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider block">Scholarly Debate</span>
                <p class="text-2xl font-bold text-amber-950 font-mono">{{ $claimsByConsensus['SCHOLARLY_DEBATE'] }}</p>
                <p class="text-[10px] text-amber-800">Multiple competing peer-reviewed hypotheses.</p>
            </div>

            <div class="p-4 rounded-2xl border border-orange-200 bg-orange-50/50 space-y-1">
                <span class="text-[11px] font-bold text-orange-900 uppercase tracking-wider block">Contested</span>
                <p class="text-2xl font-bold text-orange-950 font-mono">{{ $claimsByConsensus['CONTESTED'] }}</p>
                <p class="text-[10px] text-orange-800">Major academic objections or conflicting data.</p>
            </div>

            <div class="p-4 rounded-2xl border border-stone-200 bg-stone-50 space-y-1 col-span-2 sm:col-span-1">
                <span class="text-[11px] font-bold text-stone-700 uppercase tracking-wider block">Speculative</span>
                <p class="text-2xl font-bold text-stone-900 font-mono">{{ $claimsByConsensus['SPECULATIVE'] }}</p>
                <p class="text-[10px] text-stone-600">Unverified tradition or speculative chronologies.</p>
            </div>
        </div>

        <div class="pt-2 text-right">
            <a href="{{ route('verification.index') }}" class="text-xs font-bold text-orange-800 hover:text-orange-900 inline-flex items-center gap-1">
                <span>Explore Full Evidence Verification Engine</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

</div>

<!-- Client Script for Interactive Citation Generator -->
<script>
    const entityData = {
        site: [
            @foreach($sites as $s)
            { id: {{ $s->id }}, name: "{{ addslashes($s->name) }} ({{ addslashes($s->dating_statement) }})" },
            @endforeach
        ],
        inscription: [
            @foreach($inscriptions as $i)
            { id: {{ $i->id }}, name: "{{ addslashes($i->title) }} ({{ addslashes($i->dating_statement) }})" },
            @endforeach
        ],
        source: [
            @foreach($sources as $src)
            { id: {{ $src->id }}, name: "{{ addslashes($src->title) }} ({{ $src->publication_year ?: 'n.d.' }})" },
            @endforeach
        ]
    };

    let currentCitations = {};
    let activeTab = 'apa';

    function updateCitationEntities() {
        const type = document.getElementById('citation-entity-type').value;
        const select = document.getElementById('citation-entity-select');
        select.innerHTML = '';

        const list = entityData[type] || [];
        list.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            select.appendChild(opt);
        });

        if (list.length > 0) {
            fetchCitation();
        } else {
            document.getElementById('citation-display').textContent = 'No records available for this typology.';
        }
    }

    async function fetchCitation() {
        const type = document.getElementById('citation-entity-type').value;
        const id = document.getElementById('citation-entity-select').value;
        if (!id) return;

        document.getElementById('citation-display').textContent = 'Formatting academic citation...';

        try {
            const res = await fetch(`/api/v1/research/citation?type=${type}&id=${id}`);
            const data = await res.json();
            if (data.success) {
                currentCitations = data.citations;
                renderCitation();
            } else {
                document.getElementById('citation-display').textContent = 'Failed to generate citation.';
            }
        } catch (e) {
            document.getElementById('citation-display').textContent = 'Error contacting citation engine.';
        }
    }

    function setCitationTab(tab) {
        activeTab = tab;
        document.querySelectorAll('.citation-tab').forEach(b => {
            b.classList.remove('bg-stone-900', 'text-white', 'font-bold');
            b.classList.add('text-stone-600', 'hover:bg-stone-100');
        });
        const activeBtn = document.getElementById('tab-' + tab);
        if (activeBtn) {
            activeBtn.classList.remove('text-stone-600', 'hover:bg-stone-100');
            activeBtn.classList.add('bg-stone-900', 'text-white', 'font-bold');
        }
        renderCitation();
    }

    function renderCitation() {
        const text = currentCitations[activeTab] || 'No citation available.';
        document.getElementById('citation-display').textContent = text;
    }

    function copyCitation() {
        const text = document.getElementById('citation-display').textContent;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById('copy-btn');
            btn.textContent = 'Copied!';
            btn.classList.add('bg-emerald-700');
            setTimeout(() => {
                btn.textContent = 'Copy Citation';
                btn.classList.remove('bg-emerald-700');
            }, 2000);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchCitation();
    });
</script>
@endsection
