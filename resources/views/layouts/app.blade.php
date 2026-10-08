<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Bharat Heritage Atlas — Evidence-Linked Indian Heritage Platform')</title>
    <meta name="description" content="@yield('meta_description', 'A rigorous, evidence-linked digital heritage platform documenting ancient Indian monuments, epigraphical corpuses, and artifacts backed by peer-reviewed archaeological consensus.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body class="min-h-full flex flex-col bg-[#faf8f5] text-stone-900 font-sans antialiased selection:bg-amber-200 selection:text-amber-950">

    <!-- Top Announcement Bar / Ethos Banner -->
    <aside class="bg-stone-900 text-stone-300 text-xs py-1.5 px-4 border-b border-stone-800">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30">
                    ACADEMIC EVIDENCE STANDARD
                </span>
                <span class="hidden sm:inline text-stone-400">Strict Zero-Fabrication Policy • Every claim linked to primary epigraphy & excavation reports</span>
            </div>
            <div class="flex items-center gap-4 text-stone-400 text-[11px]">
                <a href="{{ url('/api/v1/sites') }}" class="hover:text-amber-400 transition">REST API v1</a>
                <span class="text-stone-700">•</span>
                <a href="https://github.com/vr7517/bharat-heritage-atlas" target="_blank" rel="noopener noreferrer" class="hover:text-amber-400 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    GitHub
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 bg-[#faf8f5]/95 backdrop-blur-md border-b border-stone-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-700 to-orange-800 text-amber-100 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-200">
                            <!-- Ashoka Chakra / Dharmachakra Motif SVG -->
                            <svg class="w-6 h-6 stroke-current fill-none stroke-[1.75]" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M12 3v6M12 15v6M3 12h6M15 12h6M5.64 5.64l4.24 4.24M14.12 14.12l4.24 4.24M5.64 18.36l4.24-4.24M14.12 9.88l4.24-4.24"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-lg tracking-tight text-stone-900 group-hover:text-amber-800 transition">
                                    <span class="text-orange-700 font-serif">भारत</span> Heritage Atlas
                                </span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-stone-200 text-stone-700">v0.3</span>
                            </div>
                            <p class="text-[11px] text-stone-500 font-medium tracking-wide">Evidence-Linked Digital Heritage Platform</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-2 text-sm font-medium text-stone-700">
                    <a href="{{ url('/map') }}" class="px-3 py-2 rounded-md hover:text-orange-800 hover:bg-stone-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Map Atlas
                    </a>
                    <a href="{{ url('/sites') }}" class="px-3 py-2 rounded-md hover:text-orange-800 hover:bg-stone-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Monuments
                    </a>
                    <a href="{{ url('/objects') }}" class="px-3 py-2 rounded-md hover:text-orange-800 hover:bg-stone-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Artifacts
                    </a>
                    <a href="{{ url('/inscriptions') }}" class="px-3 py-2 rounded-md hover:text-orange-800 hover:bg-stone-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Epigraphy
                    </a>
                    <a href="{{ url('/timeline') }}" class="px-3 py-2 rounded-md hover:text-orange-800 hover:bg-stone-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Timeline
                    </a>
                    <a href="{{ url('/verification') }}" class="px-3 py-2 rounded-md hover:text-orange-800 hover:bg-stone-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Evidence Engine
                    </a>
                    <a href="{{ url('/sources') }}" class="px-3 py-2 rounded-md hover:text-orange-800 hover:bg-stone-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Sources
                    </a>
                </nav>

                <!-- Actions / Search / API Shortcut -->
                <div class="hidden sm:flex items-center gap-3">
                    <a href="{{ url('/api/v1/sites') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 shadow-2xs transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        API v1
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center lg:hidden">
                    <button id="mobile-menu-toggle" type="button" class="p-2 rounded-md text-stone-600 hover:text-stone-900 hover:bg-stone-100 focus:outline-hidden" aria-label="Toggle navigation menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="mobile-menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-stone-200 bg-stone-50 px-4 pt-3 pb-5 space-y-1">
            <a href="{{ url('/map') }}" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:bg-amber-100 hover:text-orange-900">🗺️ Map Atlas</a>
            <a href="{{ url('/sites') }}" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:bg-amber-100 hover:text-orange-900">🏛️ Monuments & Sites</a>
            <a href="{{ url('/objects') }}" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:bg-amber-100 hover:text-orange-900">🏺 Artifacts & Relics</a>
            <a href="{{ url('/inscriptions') }}" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:bg-amber-100 hover:text-orange-900">📜 Epigraphy Corpus</a>
            <a href="{{ url('/timeline') }}" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:bg-amber-100 hover:text-orange-900">⏳ Chronological Timeline</a>
            <a href="{{ url('/verification') }}" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:bg-amber-100 hover:text-orange-900">🔍 Evidence Engine</a>
            <a href="{{ url('/sources') }}" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:bg-amber-100 hover:text-orange-900">📚 Primary Sources</a>
            <div class="pt-3 border-t border-stone-200 flex items-center justify-between">
                <a href="{{ url('/api/v1/sites') }}" class="text-xs font-semibold text-stone-600 hover:text-orange-800">REST API v1 (/api/v1/sites)</a>
                <a href="https://github.com/vr7517/bharat-heritage-atlas" class="text-xs font-semibold text-stone-600 hover:text-orange-800">GitHub Repository</a>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Academic & Methodology Footer -->
    <footer class="bg-stone-900 text-stone-300 border-t border-stone-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
                <!-- Col 1: About Platform -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-600 text-stone-900 flex items-center justify-center font-bold">
                            भ
                        </div>
                        <span class="font-bold text-lg text-stone-100 tracking-tight">
                            <span class="text-amber-500 font-serif">भारत</span> Heritage Atlas
                        </span>
                    </div>
                    <p class="text-xs text-stone-400 leading-relaxed max-w-sm">
                        An evidence-linked digital heritage platform dedicated to Indian civilizational history. Built with strict academic rigor, separating empirical epigraphy and excavation strata from historical speculation.
                    </p>
                    <div class="flex items-center gap-3 text-xs text-stone-400 pt-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-stone-800 text-stone-300 border border-stone-700">
                            🛡️ Zero Fabrication
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-stone-800 text-stone-300 border border-stone-700">
                            📜 Primary Epigraphy
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-stone-800 text-stone-300 border border-stone-700">
                            🏛️ In Situ Strata
                        </span>
                    </div>
                </div>

                <!-- Col 2: Digital Atlas Exploration -->
                <div>
                    <h3 class="text-xs font-bold text-stone-100 uppercase tracking-wider mb-3">Exploration</h3>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li><a href="{{ url('/map') }}" class="hover:text-amber-400 transition">Interactive Map Atlas</a></li>
                        <li><a href="{{ url('/timeline') }}" class="hover:text-amber-400 transition">Chronological Timeline</a></li>
                        <li><a href="{{ url('/sites') }}" class="hover:text-amber-400 transition">Heritage Monuments</a></li>
                        <li><a href="{{ url('/objects') }}" class="hover:text-amber-400 transition">Artifacts & Sculptures</a></li>
                        <li><a href="{{ url('/inscriptions') }}" class="hover:text-amber-400 transition">Epigraphy & Brahmi Facsimiles</a></li>
                    </ul>
                </div>

                <!-- Col 3: Evidence Methodology -->
                <div>
                    <h3 class="text-xs font-bold text-stone-100 uppercase tracking-wider mb-3">Methodology</h3>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li><a href="{{ url('/verification') }}" class="hover:text-amber-400 transition">Evidence Verification Engine</a></li>
                        <li><span class="text-stone-500">Dual Historical Dating</span></li>
                        <li><span class="text-stone-500">Reliability Tiers (1–4)</span></li>
                        <li><span class="text-stone-500">Peer-Reviewed Consensus</span></li>
                        <li><a href="{{ url('/sources') }}" class="hover:text-amber-400 transition">Archival Bibliographic Sources</a></li>
                    </ul>
                </div>

                <!-- Col 4: Developers & Open Data -->
                <div>
                    <h3 class="text-xs font-bold text-stone-100 uppercase tracking-wider mb-3">Open Science</h3>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li><a href="{{ url('/api/v1/sites') }}" class="hover:text-amber-400 transition">REST API v1 Endpoints</a></li>
                        <li><a href="{{ url('/api/v1/geo/sites') }}" class="hover:text-amber-400 transition">RFC 7946 GeoJSON</a></li>
                        <li><a href="{{ url('/api/v1/timeline') }}" class="hover:text-amber-400 transition">Timeline JSON Stream</a></li>
                        <li><a href="https://github.com/vr7517/bharat-heritage-atlas" target="_blank" rel="noopener noreferrer" class="hover:text-amber-400 transition">GitHub Open Source</a></li>
                        <li><span class="text-stone-500">OpenAPI 3.1 Contract</span></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright & Licensing -->
            <div class="mt-12 pt-6 border-t border-stone-800 text-xs text-stone-500 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>© {{ date('Y') }} Bharat Heritage Atlas. Open Cultural Heritage & Academic Epigraphy.</p>
                <p class="text-center sm:text-right">Built with Laravel 12 & Tailwind CSS • Dual Historical Dating Framework</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');
            if (toggle && menu) {
                toggle.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
