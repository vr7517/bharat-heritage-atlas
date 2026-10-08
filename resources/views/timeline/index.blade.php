@extends('layouts.app')

@section('title', 'Chronological Heritage Timeline — Bharat Heritage Atlas')
@section('meta_description', 'Interactive dual-era chronological timeline scrubber documenting ancient Indian monuments, monolithic pillars, and Brahmi epigraphs from 400 BCE to 1200 CE.')

@section('styles')
<style>
    /* Range Slider Styling */
    input[type=range]::-webkit-slider-thumb {
        height: 20px;
        width: 20px;
        border-radius: 50%;
        background: #9a3412;
        cursor: pointer;
        -webkit-appearance: none;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    input[type=range]::-moz-range-thumb {
        height: 20px;
        width: 20px;
        border-radius: 50%;
        background: #9a3412;
        cursor: pointer;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection

@section('content')
<!-- Header & Context Banner -->
<div class="bg-[#faf8f5] py-6 sm:py-8 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-stone-500 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
                    <span>/</span>
                    <span class="text-stone-800 font-semibold">Chronological Timeline</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 flex items-center gap-3">
                    <span>⏳ Chronological Heritage Timeline</span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-200" id="total-milestones-badge">
                        {{ $counts['total'] }} Historical Milestones
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1">
                    Dual-era continuous timeline synchronizing monumental architecture, sculpture, and primary epigraphy across Indian civilizational history.
                </p>
            </div>

            <!-- API Shortcut -->
            <div class="flex items-center gap-2">
                <a href="{{ url('/api/v1/timeline') }}" target="_blank" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-stone-900 hover:bg-stone-800 text-amber-200 transition flex items-center gap-1.5 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Timeline JSON API
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Sticky Scrubber & Controls Panel -->
<div class="sticky top-18 z-40 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-xs py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        
        <!-- Dual-Era Scrubber Slider Section -->
        <div class="bg-stone-50 rounded-2xl p-4 sm:p-5 border border-stone-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2 text-xs font-bold text-stone-800 uppercase tracking-wide">
                    <span>⏳ Dual Historical Dating Scrubber</span>
                    <span class="text-[10px] normal-case font-normal text-stone-500 hidden sm:inline">(Continuous Astronomical Bounds &harr; Human Eras)</span>
                </div>
                <!-- Dynamic Year Label Range -->
                <div class="flex items-center gap-2 text-xs font-mono">
                    <span class="px-2 py-1 rounded bg-amber-100 text-orange-950 font-bold border border-amber-200" id="label-start-year">
                        400 BCE (-400)
                    </span>
                    <span class="text-stone-400">&rarr;</span>
                    <span class="px-2 py-1 rounded bg-amber-100 text-orange-950 font-bold border border-amber-200" id="label-end-year">
                        1200 CE (+1200)
                    </span>
                </div>
            </div>

            <!-- Dual Range Sliders -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="flex justify-between text-[11px] text-stone-600 font-medium mb-1">
                        <span>From Year: <strong id="val-from-display" class="font-mono text-orange-800">400 BCE</strong></span>
                        <span class="font-mono text-stone-400">-400</span>
                    </div>
                    <input type="range" id="slider-from-year" min="-400" max="1200" step="25" value="-400" class="w-full h-2 bg-stone-200 rounded-lg appearance-none cursor-pointer accent-orange-800" />
                </div>
                <div>
                    <div class="flex justify-between text-[11px] text-stone-600 font-medium mb-1">
                        <span>To Year: <strong id="val-to-display" class="font-mono text-orange-800">1200 CE</strong></span>
                        <span class="font-mono text-stone-400">+1200</span>
                    </div>
                    <input type="range" id="slider-to-year" min="-400" max="1200" step="25" value="1200" class="w-full h-2 bg-stone-200 rounded-lg appearance-none cursor-pointer accent-orange-800" />
                </div>
            </div>

            <!-- Era Quick-Jump Presets -->
            <div class="mt-4 pt-3 border-t border-stone-200/80 flex flex-wrap items-center gap-2 text-xs">
                <span class="text-stone-500 font-medium text-[11px] mr-1">Historical Presets:</span>
                <button type="button" class="preset-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-stone-200/80 hover:bg-amber-100 text-stone-800 transition" data-from="-400" data-to="1200">
                    All Eras
                </button>
                <button type="button" class="preset-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-stone-200/80 hover:bg-amber-100 text-stone-800 transition" data-from="-350" data-to="-185">
                    Mauryan Imperial (c. 350–185 BCE)
                </button>
                <button type="button" class="preset-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-stone-200/80 hover:bg-amber-100 text-stone-800 transition" data-from="-190" data-to="-70">
                    Shunga & Indo-Greek (c. 190–70 BCE)
                </button>
                <button type="button" class="preset-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-stone-200/80 hover:bg-amber-100 text-stone-800 transition" data-from="-100" data-to="250">
                    Satavahana & Early Historic (c. 100 BCE – 250 CE)
                </button>
                <button type="button" class="preset-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-stone-200/80 hover:bg-amber-100 text-stone-800 transition" data-from="300" data-to="600">
                    Gupta Classical Era (c. 300–600 CE)
                </button>
                <button type="button" class="preset-btn px-2.5 py-1 rounded-lg text-[11px] font-medium bg-stone-200/80 hover:bg-amber-100 text-stone-800 transition" data-from="600" data-to="1200">
                    Early Medieval (c. 600–1200 CE)
                </button>
            </div>
        </div>

        <!-- Entity Filters & Stream Count -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs text-stone-500 font-semibold mr-1">Entities:</span>
                <button type="button" class="entity-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-stone-900 text-white shadow-2xs transition" data-entity="all">
                    All Entities (<span id="count-all">{{ $counts['total'] }}</span>)
                </button>
                <button type="button" class="entity-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium bg-stone-100 hover:bg-stone-200 text-stone-700 transition" data-entity="sites">
                    🏛️ Monuments (<span id="count-sites">{{ $counts['sites'] }}</span>)
                </button>
                <button type="button" class="entity-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium bg-stone-100 hover:bg-stone-200 text-stone-700 transition" data-entity="objects">
                    🏺 Artifacts & Relics (<span id="count-objects">{{ $counts['objects'] }}</span>)
                </button>
                <button type="button" class="entity-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium bg-stone-100 hover:bg-stone-200 text-stone-700 transition" data-entity="inscriptions">
                    📜 Epigraphs (<span id="count-inscriptions">{{ $counts['inscriptions'] }}</span>)
                </button>
            </div>

            <div class="text-xs font-mono text-stone-600">
                Displaying <span id="filtered-stream-count" class="font-bold text-orange-800">0</span> milestones
            </div>
        </div>

    </div>
</div>

<!-- Main Timeline Stream Content -->
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Loading Spinner -->
    <div id="timeline-loader" class="flex items-center justify-center py-16">
        <div class="flex items-center gap-3 px-5 py-3 rounded-2xl bg-white border border-stone-200 shadow-sm text-xs font-semibold text-stone-700">
            <svg class="animate-spin w-4 h-4 text-orange-800" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            <span>Querying verified chronological milestones...</span>
        </div>
    </div>

    <!-- Empty State -->
    <div id="timeline-empty" class="hidden text-center py-16 bg-white rounded-2xl border border-stone-200 p-8">
        <div class="text-3xl mb-2">⏳</div>
        <h3 class="text-base font-bold text-stone-800">No Historical Milestones Found</h3>
        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
            No monuments, objects, or inscriptions recorded in this specific temporal range. Expand the scrubber sliders or select 'All Eras'.
        </p>
    </div>

    <!-- Vertical Timeline Stream Container -->
    <div id="timeline-stream" class="relative border-l-2 border-stone-300 ml-4 sm:ml-32 space-y-8 pb-12">
        <!-- Event Cards dynamically inserted by JavaScript -->
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentFrom = -400;
        let currentTo = 1200;
        let currentEntity = 'all';

        const sliderFrom = document.getElementById('slider-from-year');
        const sliderTo = document.getElementById('slider-to-year');
        const valFromDisplay = document.getElementById('val-from-display');
        const valToDisplay = document.getElementById('val-to-display');
        const labelStartYear = document.getElementById('label-start-year');
        const labelEndYear = document.getElementById('label-end-year');
        const streamContainer = document.getElementById('timeline-stream');
        const loader = document.getElementById('timeline-loader');
        const emptyState = document.getElementById('timeline-empty');
        const streamCount = document.getElementById('filtered-stream-count');

        // Format astronomical year to human-readable label
        function formatEraYear(year) {
            const y = parseInt(year, 10);
            if (y < 0) {
                return `${Math.abs(y)} BCE`;
            } else if (y === 0) {
                return '1 BCE / 1 CE';
            } else {
                return `${y} CE`;
            }
        }

        function updateDisplays() {
            valFromDisplay.innerText = formatEraYear(currentFrom);
            valToDisplay.innerText = formatEraYear(currentTo);
            labelStartYear.innerText = `${formatEraYear(currentFrom)} (${currentFrom >= 0 ? '+' : ''}${currentFrom})`;
            labelEndYear.innerText = `${formatEraYear(currentTo)} (${currentTo >= 0 ? '+' : ''}${currentTo})`;
        }

        // Fetch chronological data from API
        function fetchTimelineData() {
            loader.classList.remove('hidden');
            emptyState.classList.add('hidden');
            streamContainer.innerHTML = '';

            const url = new URL('/api/v1/timeline', window.location.origin);
            url.searchParams.append('from_year', currentFrom);
            url.searchParams.append('to_year', currentTo);
            url.searchParams.append('entity_type', currentEntity);
            url.searchParams.append('limit', '100');

            fetch(url)
                .then(res => res.json())
                .then(res => {
                    loader.classList.add('hidden');
                    const events = res.data || [];
                    streamCount.innerText = events.length;

                    if (events.length === 0) {
                        emptyState.classList.remove('hidden');
                        return;
                    }

                    renderTimelineEvents(events);
                })
                .catch(err => {
                    console.error('Failed to load timeline:', err);
                    loader.innerHTML = '<span class="text-rose-600 text-xs font-bold">Failed to load timeline events.</span>';
                });
        }

        // Render timeline cards along the vertical spine
        function renderTimelineEvents(events) {
            events.forEach(evt => {
                const card = document.createElement('article');
                card.className = 'relative pl-6 sm:pl-8 group';

                // Determine entity badge configuration
                let typeBadge = '';
                let dotColor = 'bg-orange-800';

                if (evt.entity_type === 'HeritageSite') {
                    typeBadge = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-900 border border-orange-200">🏛️ Monument</span>';
                    dotColor = 'bg-orange-800 ring-orange-200';
                } else if (evt.entity_type === 'HeritageObject') {
                    typeBadge = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">🏺 Relic / Sculpture</span>';
                    dotColor = 'bg-amber-600 ring-amber-200';
                } else if (evt.entity_type === 'Inscription') {
                    typeBadge = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-900 border border-emerald-200">📜 Epigraph</span>';
                    dotColor = 'bg-emerald-600 ring-emerald-200';
                }

                // Date label positioned on the left gutter on desktop
                const astroYear = evt.chronology.start_year;
                const dateLabel = formatEraYear(astroYear);

                card.innerHTML = `
                    <!-- Timeline Node Marker -->
                    <div class="absolute -left-[9px] top-4 w-4 h-4 rounded-full ${dotColor} ring-4 border-2 border-white transition-transform group-hover:scale-125"></div>

                    <!-- Year Flag for Desktop (Left of Spine) -->
                    <div class="hidden sm:block absolute -left-32 top-3 w-24 text-right">
                        <span class="font-mono text-xs font-bold text-stone-800 block">${dateLabel}</span>
                        <span class="font-mono text-[10px] text-stone-400 block">${astroYear}</span>
                    </div>

                    <!-- Card Body -->
                    <div class="bg-white rounded-2xl border border-stone-200/90 p-5 shadow-xs hover:shadow-md transition-all duration-200 space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                ${typeBadge}
                                ${evt.period?.name ? `<span class="px-2 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-700">${evt.period.name}</span>` : ''}
                                ${evt.dynasty?.name ? `<span class="px-2 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-700">${evt.dynasty.name}</span>` : ''}
                            </div>
                            <!-- Mobile date label -->
                            <span class="sm:hidden font-mono text-xs font-bold text-orange-950 px-2 py-0.5 bg-amber-50 rounded border border-amber-200">
                                ${dateLabel} (${astroYear})
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-stone-900 group-hover:text-orange-800 transition">
                                ${evt.name}
                            </h3>
                            <div class="flex items-center gap-2 text-xs text-stone-500 font-medium mt-1">
                                <span>⏳ ${evt.chronology.dating_statement}</span>
                                ${evt.location?.name ? `<span>• 📍 ${evt.location.name}, ${evt.location.state}</span>` : ''}
                            </div>
                        </div>

                        ${evt.summary ? `<p class="text-xs text-stone-600 leading-relaxed line-clamp-3">${evt.summary}</p>` : ''}

                        <!-- Inscription Facsimile Block if applicable -->
                        ${evt.details?.raw_text || evt.details?.transliteration ? `
                            <div class="bg-stone-900 text-amber-200 p-3 rounded-xl border border-stone-800 space-y-1.5 font-mono text-xs">
                                <div class="flex items-center justify-between text-[10px] text-stone-400">
                                    <span>Script: ${evt.details.script || 'Brahmi'}</span>
                                    <span>Language: ${evt.details.language || 'Prakrit'}</span>
                                </div>
                                <div class="overflow-x-auto text-amber-100">${evt.details.raw_text || evt.details.transliteration}</div>
                                ${evt.details.translation ? `<div class="text-[11px] text-stone-300 italic pt-1 border-t border-stone-800">"${evt.details.translation}"</div>` : ''}
                            </div>
                        ` : ''}

                        <!-- Card Footer Links -->
                        <div class="pt-2 border-t border-stone-100 flex items-center justify-between text-xs">
                            <span class="text-stone-400 font-mono text-[11px]">
                                Type: ${evt.category || evt.entity_type}
                            </span>
                            <a href="${evt.api_url ? evt.api_url : '#'}" class="font-bold text-orange-800 hover:text-orange-950 flex items-center gap-1">
                                Inspect Details &rarr;
                            </a>
                        </div>
                    </div>
                `;

                streamContainer.appendChild(card);
            });
        }

        // Slider Event Listeners
        sliderFrom.addEventListener('input', function () {
            currentFrom = parseInt(this.value, 10);
            if (currentFrom > currentTo) {
                currentTo = currentFrom;
                sliderTo.value = currentTo;
            }
            updateDisplays();
            debounce(fetchTimelineData, 350)();
        });

        sliderTo.addEventListener('input', function () {
            currentTo = parseInt(this.value, 10);
            if (currentTo < currentFrom) {
                currentFrom = currentTo;
                sliderFrom.value = currentFrom;
            }
            updateDisplays();
            debounce(fetchTimelineData, 350)();
        });

        // Era Quick Preset Buttons
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                currentFrom = parseInt(this.dataset.from, 10);
                currentTo = parseInt(this.dataset.to, 10);
                sliderFrom.value = currentFrom;
                sliderTo.value = currentTo;
                updateDisplays();
                fetchTimelineData();
            });
        });

        // Entity Filter Buttons
        document.querySelectorAll('.entity-filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.entity-filter-btn').forEach(b => {
                    b.className = 'entity-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium bg-stone-100 hover:bg-stone-200 text-stone-700 transition';
                });
                this.className = 'entity-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-stone-900 text-white shadow-2xs transition';
                currentEntity = this.dataset.entity;
                fetchTimelineData();
            });
        });

        // Debounce Utility
        let timer = null;
        function debounce(func, delay) {
            return function (...args) {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), delay);
            };
        }

        // Initial Initialization
        updateDisplays();
        fetchTimelineData();
    });
</script>
@endsection
