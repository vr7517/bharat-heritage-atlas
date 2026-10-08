@extends('layouts.app')

@section('title', 'Geospatial Heritage Atlas — Bharat Heritage Atlas')
@section('meta_description', 'Interactive archaeological map atlas of verified ancient Indian monuments, monolithic pillars, stupas, and temples backed by in situ GeoJSON spatial coordinates.')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    /* Custom Leaflet Marker & Popup Styling */
    .heritage-marker-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c2410c, #9a3412);
        border: 2.5px solid #ffffff;
        box-shadow: 0 4px 12px rgba(154, 52, 18, 0.45);
        color: #ffffff;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .heritage-marker-pin:hover {
        transform: scale(1.15);
        box-shadow: 0 6px 18px rgba(154, 52, 18, 0.65);
    }
    .leaflet-popup-content-wrapper {
        border-radius: 14px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e7e5e4;
    }
    .leaflet-popup-content {
        margin: 0;
        width: 310px !important;
        font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    }
    .leaflet-popup-tip {
        background: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="bg-[#faf8f5] py-6 sm:py-8 border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-stone-500 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-orange-800 transition">Atlas Home</a>
                    <span>/</span>
                    <span class="text-stone-800 font-semibold">Geospatial Web Map</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 flex items-center gap-3">
                    <span>🗺️ Geospatial Heritage Atlas</span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-orange-100 text-orange-800 border border-orange-200" id="total-sites-badge">
                        {{ $totalSitesWithCoords }} Verified Sites
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 mt-1">
                    Spatial distribution of in situ ancient Indian archaeological monuments, Buddhist complexes, and early monolithic pillars.
                </p>
            </div>

            <!-- Quick Map Controls -->
            <div class="flex items-center gap-2">
                <button id="btn-reset-map" class="px-3 py-1.5 text-xs font-medium rounded-lg border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 shadow-2xs transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    Fit All
                </button>
                <a href="{{ url('/api/v1/geo/sites') }}" target="_blank" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-stone-900 hover:bg-stone-800 text-amber-200 transition flex items-center gap-1.5 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    GeoJSON Feed
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Interactive Map Area -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Spatial Filters Panel (Left Sidebar) -->
        <aside class="lg:col-span-4 bg-white rounded-2xl border border-stone-200 p-5 shadow-xs space-y-5">
            <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                <div class="flex items-center gap-2 text-stone-800 font-bold text-sm">
                    <svg class="w-4 h-4 text-orange-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Spatial & Historical Filters</span>
                </div>
                <button id="btn-clear-filters" class="text-[11px] text-orange-800 hover:text-orange-950 font-semibold underline">
                    Clear All
                </button>
            </div>

            <!-- Keyword Search -->
            <div class="space-y-1.5">
                <label for="filter-search" class="block text-xs font-semibold text-stone-700">Search Monument</label>
                <div class="relative">
                    <input type="text" id="filter-search" placeholder="e.g. Sanchi, Heliodorus, Linga..." class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 focus:outline-hidden focus:ring-2 focus:ring-amber-500 focus:border-amber-500 pl-8" />
                    <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- State Filter -->
            <div class="space-y-1.5">
                <label for="filter-state" class="block text-xs font-semibold text-stone-700">Modern State</label>
                <select id="filter-state" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white focus:outline-hidden focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">All States</option>
                    @foreach($states as $st)
                    <option value="{{ $st }}">{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Site Typology Filter -->
            <div class="space-y-1.5">
                <label for="filter-site-type" class="block text-xs font-semibold text-stone-700">Architectural Typology</label>
                <select id="filter-site-type" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white focus:outline-hidden focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">All Typologies</option>
                    @foreach($siteTypes as $stype)
                    <option value="{{ $stype }}">{{ $stype }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Historical Period Filter -->
            <div class="space-y-1.5">
                <label for="filter-period" class="block text-xs font-semibold text-stone-700">Historical Era / Period</label>
                <select id="filter-period" class="w-full text-xs px-3 py-2 rounded-lg border border-stone-300 bg-white focus:outline-hidden focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">All Periods</option>
                    @foreach($periods as $per)
                    <option value="{{ $per->slug }}">{{ $per->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Uncertainty Radius Toggle -->
            <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                <div>
                    <label for="toggle-uncertainty" class="text-xs font-semibold text-stone-700 block">Spatial Uncertainty</label>
                    <span class="text-[11px] text-stone-500">Display excavation accuracy circles</span>
                </div>
                <input type="checkbox" id="toggle-uncertainty" checked class="w-4 h-4 rounded text-orange-800 focus:ring-amber-500 border-stone-300">
            </div>

            <!-- Matching Sites Counter & List Preview -->
            <div class="pt-3 border-t border-stone-100">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="font-bold text-stone-800">Filtered Monuments</span>
                    <span id="filtered-count" class="font-mono text-orange-800 font-bold">0 matching</span>
                </div>
                <div id="sites-preview-list" class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
        </aside>

        <!-- Leaflet Map Container (Right Area) -->
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs relative">
                <!-- Map loading overlay -->
                <div id="map-loader" class="absolute inset-0 z-1000 bg-white/70 backdrop-blur-xs flex items-center justify-center transition-opacity">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-stone-100 text-xs shadow-md">
                        <svg class="animate-spin w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>Loading Archaeological GeoJSON...</span>
                    </div>
                </div>

                <!-- Map Element -->
                <div id="heritage-map" class="w-full h-[600px] sm:h-[680px] lg:h-[720px] bg-stone-100"></div>
            </div>

            <!-- Cartography Legend & Methodology Notice -->
            <div class="bg-stone-50 rounded-xl p-4 border border-stone-200/80 flex flex-wrap items-center justify-between gap-4 text-xs text-stone-600">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 font-medium">
                        <span class="w-3.5 h-3.5 rounded-full bg-orange-800 border-2 border-white shadow-xs"></span>
                        <span>In Situ Archaeological Complex</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-medium">
                        <span class="w-3.5 h-3.5 rounded-full bg-amber-400/30 border border-amber-600 border-dashed"></span>
                        <span>Spatial Uncertainty Radius (±m)</span>
                    </div>
                </div>
                <div class="text-[11px] text-stone-500 font-mono">
                    CRS: EPSG:4326 (WGS 84) • Standard RFC 7946 GeoJSON
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Initialize Map centered on Indian Subcontinent
        const map = L.map('heritage-map', {
            center: [22.5, 78.9],
            zoom: 5,
            minZoom: 4,
            maxZoom: 18,
            zoomControl: true,
            scrollWheelZoom: true
        });

        // 2. Add Base Cartographic Tiles (CartoDB Positron for antique clean aesthetic)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        let geojsonLayer = null;
        let uncertaintyCircles = [];
        let allFeatures = [];

        const loader = document.getElementById('map-loader');
        const countDisplay = document.getElementById('filtered-count');
        const previewList = document.getElementById('sites-preview-list');
        const showUncertaintyCheckbox = document.getElementById('toggle-uncertainty');

        // Custom SVG Pin Icon
        function createCustomPin() {
            return L.divIcon({
                className: 'custom-heritage-div-icon',
                html: `
                    <div class="heritage-marker-pin">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                `,
                iconSize: [38, 38],
                iconAnchor: [19, 19],
                popupAnchor: [0, -20]
            });
        }

        // 3. Fetch GeoJSON from Bharat Heritage Atlas REST API
        function loadGeoJsonData(params = {}) {
            loader.classList.remove('opacity-0', 'pointer-events-none');

            const url = new URL('/api/v1/geo/sites', window.location.origin);
            Object.keys(params).forEach(key => {
                if (params[key]) url.searchParams.append(key, params[key]);
            });

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    renderGeoJson(data);
                    loader.classList.add('opacity-0', 'pointer-events-none');
                })
                .catch(err => {
                    console.error('Failed to load GeoJSON:', err);
                    loader.innerHTML = '<span class="text-rose-600 font-bold text-xs p-3">Failed to load spatial data.</span>';
                });
        }

        // 4. Render GeoJSON features on Map
        function renderGeoJson(data) {
            // Clear existing layers
            if (geojsonLayer) map.removeLayer(geojsonLayer);
            uncertaintyCircles.forEach(c => map.removeLayer(c));
            uncertaintyCircles = [];

            allFeatures = data.features || [];
            countDisplay.innerText = `${allFeatures.length} matching`;

            // Update preview sidebar list
            previewList.innerHTML = '';
            if (allFeatures.length === 0) {
                previewList.innerHTML = '<div class="text-xs text-stone-500 italic py-2">No monuments match the current filters.</div>';
            }

            geojsonLayer = L.geoJSON(data, {
                pointToLayer: function (feature, latlng) {
                    const marker = L.marker(latlng, { icon: createCustomPin() });

                    // Render spatial uncertainty radius circle if available
                    const radius = feature.properties?.location?.uncertainty_radius_meters;
                    if (radius && radius > 0) {
                        const circle = L.circle(latlng, {
                            radius: radius,
                            color: '#c2410c',
                            dashArray: '4, 4',
                            fillColor: '#f97316',
                            fillOpacity: 0.12,
                            weight: 1.5
                        });
                        uncertaintyCircles.push(circle);
                        if (showUncertaintyCheckbox.checked) {
                            circle.addTo(map);
                        }
                    }

                    return marker;
                },
                onEachFeature: function (feature, layer) {
                    const p = feature.properties;
                    const coords = feature.geometry.coordinates;

                    // Popup Template
                    const popupHtml = `
                        <div class="bg-white">
                            <div class="p-4 border-b border-stone-100 bg-stone-50/70">
                                <div class="flex items-center gap-1.5 mb-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                        ${p.period?.name || 'Ancient Era'}
                                    </span>
                                    <span class="text-[10px] font-mono text-stone-500 ml-auto">
                                        ${p.location?.state || ''}
                                    </span>
                                </div>
                                <h4 class="text-sm font-bold text-stone-900 leading-snug">
                                    <a href="/sites/${p.slug}" class="hover:text-orange-800 transition">
                                        ${p.name}
                                    </a>
                                </h4>
                                <div class="text-[11px] text-stone-600 font-medium mt-1">
                                    ⏳ ${p.dating_statement || ''}
                                </div>
                            </div>
                            <div class="p-4 space-y-2.5 text-xs text-stone-600">
                                <p class="line-clamp-2 leading-relaxed text-[11px]">
                                    ${p.summary || ''}
                                </p>
                                <div class="flex items-center justify-between text-[10px] text-stone-500 font-mono pt-1 border-t border-stone-100">
                                    <span>📍 ${coords[1].toFixed(4)}°N, ${coords[0].toFixed(4)}°E</span>
                                    <span>±${p.location?.uncertainty_radius_meters || 0}m</span>
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex gap-2 text-[11px] font-semibold text-stone-700">
                                        <span>🏺 ${p.counts?.objects || 0}</span>
                                        <span>📜 ${p.counts?.inscriptions || 0}</span>
                                    </div>
                                    <a href="/sites/${p.slug}" class="inline-flex items-center gap-1 text-[11px] font-bold text-orange-800 hover:text-orange-950">
                                        Site Monograph &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    layer.bindPopup(popupHtml);

                    // Add to sidebar preview list
                    const item = document.createElement('div');
                    item.className = 'p-2.5 rounded-lg border border-stone-200 bg-stone-50/50 hover:bg-amber-50/60 cursor-pointer transition text-xs';
                    item.innerHTML = `
                        <div class="font-bold text-stone-900 line-clamp-1">${p.name}</div>
                        <div class="flex items-center justify-between text-[10px] text-stone-500 mt-0.5">
                            <span>${p.site_type || ''}</span>
                            <span class="font-mono">${p.location?.state || ''}</span>
                        </div>
                    `;
                    item.addEventListener('click', () => {
                        map.flyTo([coords[1], coords[0]], 12, { duration: 1.2 });
                        layer.openPopup();
                    });
                    previewList.appendChild(item);
                }
            }).addTo(map);

            // Fit map bounds if features exist
            if (allFeatures.length > 0) {
                const bounds = geojsonLayer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [40, 40], maxZoom: 10 });
                }
            }
        }

        // 5. Filter Controls Listener
        function applyFilters() {
            const params = {};
            const search = document.getElementById('filter-search').value.trim();
            const state = document.getElementById('filter-state').value;
            const siteType = document.getElementById('filter-site-type').value;
            const period = document.getElementById('filter-period').value;

            if (search) params.search = search;
            if (state) params.state = state;
            if (siteType) params.site_type = siteType;
            if (period) params.period = period;

            loadGeoJsonData(params);
        }

        document.getElementById('filter-search').addEventListener('input', debounce(applyFilters, 350));
        document.getElementById('filter-state').addEventListener('change', applyFilters);
        document.getElementById('filter-site-type').addEventListener('change', applyFilters);
        document.getElementById('filter-period').addEventListener('change', applyFilters);

        // Clear Filters Button
        document.getElementById('btn-clear-filters').addEventListener('click', function () {
            document.getElementById('filter-search').value = '';
            document.getElementById('filter-state').value = '';
            document.getElementById('filter-site-type').value = '';
            document.getElementById('filter-period').value = '';
            loadGeoJsonData();
        });

        // Reset Bounds Button
        document.getElementById('btn-reset-map').addEventListener('click', function () {
            if (geojsonLayer && allFeatures.length > 0) {
                const bounds = geojsonLayer.getBounds();
                if (bounds.isValid()) map.fitBounds(bounds, { padding: [40, 40] });
            } else {
                map.setView([22.5, 78.9], 5);
            }
        });

        // Uncertainty toggle
        showUncertaintyCheckbox.addEventListener('change', function () {
            uncertaintyCircles.forEach(circle => {
                if (showUncertaintyCheckbox.checked) {
                    circle.addTo(map);
                } else {
                    map.removeLayer(circle);
                }
            });
        });

        // Debounce helper
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Initial Data Load
        loadGeoJsonData();
    });
</script>
@endsection
