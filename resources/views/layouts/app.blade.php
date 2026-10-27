<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('ui.brand_title') . ' — ' . __('ui.brand_tagline'))</title>
    <meta name="description" content="@yield('meta_description', __('ui.brand_description'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Favicon / Brand Icon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23c2410c' stroke-width='2'%3E%3Ccircle cx='12' cy='12' r='9'/%3E%3Ccircle cx='12' cy='12' r='3'/%3E%3Cpath d='M12 3v6M12 15v6M3 12h6M15 12h6'/%3E%3C/svg%3E">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="min-h-full flex flex-col bg-[#faf8f5] text-stone-900 font-sans antialiased selection:bg-amber-200 selection:text-amber-950">

    @php
        $currentLocale = app()->getLocale();
        $isMap = request()->is('map*');
        $isSites = request()->is('sites*');
        $isObjects = request()->is('objects*');
        $isInscriptions = request()->is('inscriptions*');
        $isTimeline = request()->is('timeline*');
        $isDatasets = request()->is('datasets*');
        $isResearch = request()->is('research-tools*');
        $isVerification = request()->is('verification*');
        $isSources = request()->is('sources*');
        $isSearch = request()->is('search*');
        $isDevelopers = request()->is('developers*');
    @endphp

    <!-- Top Announcement Bar / Ethos Banner -->
    <aside class="bg-stone-900 text-stone-300 text-xs py-2 px-4 border-b border-stone-800 select-none">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2.5">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 tracking-wider">
                    {{ __('ui.ethos_standard') }}
                </span>
                <span class="hidden sm:inline text-stone-300 text-xs">
                    {{ __('ui.ethos_policy') }}
                </span>
            </div>
            <div class="flex items-center gap-4 text-stone-300 text-xs">
                <a href="{{ route('research.index') }}" class="hover:text-amber-400 transition {{ $isResearch ? 'text-amber-400 font-semibold' : '' }}">
                    {{ __('ui.research_tools') }}
                </a>
                <span class="text-stone-700" aria-hidden="true">•</span>
                <a href="{{ route('developers.index') }}" class="hover:text-amber-400 transition {{ $isDevelopers ? 'text-amber-400 font-semibold' : '' }}">
                    {{ __('ui.developer_api') }}
                </a>
                <span class="text-stone-700" aria-hidden="true">•</span>
                <a href="https://github.com/vr7517/bharat-heritage-atlas" target="_blank" rel="noopener noreferrer" class="hover:text-amber-400 transition flex items-center gap-1.5" aria-label="GitHub Repository">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    <span>{{ __('ui.github') }}</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 bg-[#faf8f5]/95 backdrop-blur-md border-b border-stone-200/90 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group focus:outline-hidden focus:ring-2 focus:ring-amber-500 rounded-xl" aria-label="{{ __('ui.brand_title') }} Home">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-700 via-orange-800 to-amber-950 text-amber-100 flex items-center justify-center shadow-md shadow-orange-950/20 group-hover:scale-105 group-hover:rotate-1 transition-all duration-200 border border-amber-500/30">
                            <!-- Ashoka Chakra / Dharmachakra Motif SVG -->
                            <svg class="w-6 h-6 stroke-current fill-none stroke-[1.8]" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M12 3v6M12 15v6M3 12h6M15 12h6M5.64 5.64l4.24 4.24M14.12 14.12l4.24 4.24M5.64 18.36l4.24-4.24M14.12 9.88l4.24-4.24"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-lg sm:text-xl tracking-tight text-stone-900 group-hover:text-orange-900 transition-colors">
                                    <span class="text-orange-700 font-serif">{{ __('ui.brand_hindi') }}</span> Heritage Atlas
                                </span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded-full bg-amber-100/80 text-amber-900 border border-amber-200">v0.4</span>
                            </div>
                            <p class="text-[11px] text-stone-500 font-medium tracking-wide">
                                {{ __('ui.brand_tagline') }}
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5 text-xs font-semibold text-stone-700" aria-label="Main Navigation">
                    <a href="{{ url('/map') }}" class="px-2.5 py-2 rounded-lg transition flex items-center gap-1.5 {{ $isMap ? 'bg-amber-100/90 text-orange-950 border border-amber-300/80 font-bold shadow-2xs' : 'hover:text-orange-900 hover:bg-stone-100/80' }}">
                        <svg class="w-4 h-4 {{ $isMap ? 'text-orange-800' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        {{ __('ui.nav_map') }}
                    </a>

                    <a href="{{ url('/sites') }}" class="px-2.5 py-2 rounded-lg transition flex items-center gap-1.5 {{ $isSites ? 'bg-amber-100/90 text-orange-950 border border-amber-300/80 font-bold shadow-2xs' : 'hover:text-orange-900 hover:bg-stone-100/80' }}">
                        <svg class="w-4 h-4 {{ $isSites ? 'text-orange-800' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        {{ __('ui.nav_sites') }}
                    </a>

                    <a href="{{ url('/objects') }}" class="px-2.5 py-2 rounded-lg transition flex items-center gap-1.5 {{ $isObjects ? 'bg-amber-100/90 text-orange-950 border border-amber-300/80 font-bold shadow-2xs' : 'hover:text-orange-900 hover:bg-stone-100/80' }}">
                        <svg class="w-4 h-4 {{ $isObjects ? 'text-orange-800' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        {{ __('ui.nav_objects') }}
                    </a>

                    <a href="{{ url('/inscriptions') }}" class="px-2.5 py-2 rounded-lg transition flex items-center gap-1.5 {{ $isInscriptions ? 'bg-amber-100/90 text-orange-950 border border-amber-300/80 font-bold shadow-2xs' : 'hover:text-orange-900 hover:bg-stone-100/80' }}">
                        <svg class="w-4 h-4 {{ $isInscriptions ? 'text-orange-800' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('ui.nav_inscriptions') }}
                    </a>

                    <a href="{{ url('/timeline') }}" class="px-2.5 py-2 rounded-lg transition flex items-center gap-1.5 {{ $isTimeline ? 'bg-amber-100/90 text-orange-950 border border-amber-300/80 font-bold shadow-2xs' : 'hover:text-orange-900 hover:bg-stone-100/80' }}">
                        <svg class="w-4 h-4 {{ $isTimeline ? 'text-orange-800' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('ui.nav_timeline') }}
                    </a>

                    <a href="{{ url('/datasets') }}" class="px-2.5 py-2 rounded-lg transition flex items-center gap-1.5 {{ $isDatasets ? 'bg-amber-100/90 text-orange-950 border border-amber-300/80 font-bold shadow-2xs' : 'hover:text-orange-900 hover:bg-stone-100/80' }}">
                        <svg class="w-4 h-4 {{ $isDatasets ? 'text-orange-800' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7c-2 0-3 1-3 3zm0 4h16M9 4v16"/></svg>
                        {{ __('ui.nav_datasets') }}
                    </a>

                    <a href="{{ url('/verification') }}" class="px-2.5 py-2 rounded-lg transition flex items-center gap-1.5 {{ $isVerification ? 'bg-amber-100/90 text-orange-950 border border-amber-300/80 font-bold shadow-2xs' : 'hover:text-orange-900 hover:bg-stone-100/80' }}">
                        <svg class="w-4 h-4 {{ $isVerification ? 'text-orange-800' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        {{ __('ui.nav_evidence') }}
                    </a>
                </nav>

                <!-- Actions / Language Switcher / Search / API -->
                <div class="hidden sm:flex items-center gap-2.5">
                    <!-- Real Language Switcher (EN | हिन्दी) -->
                    <div class="flex items-center bg-stone-200/70 p-1 rounded-xl border border-stone-300/80 text-xs font-bold" role="group" aria-label="Language Switcher">
                        <a href="{{ route('locale.switch', 'en') }}" 
                           class="px-2.5 py-1 rounded-lg transition-all duration-150 {{ $currentLocale === 'en' ? 'bg-white text-orange-950 shadow-xs' : 'text-stone-600 hover:text-stone-900' }}"
                           title="Switch to English"
                           aria-pressed="{{ $currentLocale === 'en' ? 'true' : 'false' }}">
                            EN
                        </a>
                        <a href="{{ route('locale.switch', 'hi') }}" 
                           class="px-2.5 py-1 rounded-lg transition-all duration-150 {{ $currentLocale === 'hi' ? 'bg-orange-800 text-white shadow-xs' : 'text-stone-600 hover:text-stone-900' }}"
                           title="हिन्दी में देखें"
                           aria-pressed="{{ $currentLocale === 'hi' ? 'true' : 'false' }}">
                            हिन्दी
                        </a>
                    </div>

                    <!-- Search Button -->
                    <a href="{{ route('search.index') }}" 
                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-xl border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 shadow-2xs transition {{ $isSearch ? 'border-orange-600 text-orange-900' : '' }}"
                       aria-label="{{ __('ui.nav_search') }}">
                        <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>{{ __('ui.nav_search') }}</span>
                    </a>

                    <!-- API Shortcut -->
                    <a href="{{ route('developers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-xl border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 shadow-2xs transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-500" aria-hidden="true"></span>
                        {{ __('ui.api_v1') }}
                    </a>
                </div>

                <!-- Mobile Menu Button (Accessible >=44px) -->
                <div class="flex items-center gap-2 lg:hidden">
                    <!-- Mobile Language Switcher -->
                    <div class="flex items-center bg-stone-200/80 p-0.5 rounded-lg border border-stone-300 text-xs font-bold sm:hidden">
                        <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 rounded {{ $currentLocale === 'en' ? 'bg-white text-orange-950 font-bold' : 'text-stone-600' }}">EN</a>
                        <a href="{{ route('locale.switch', 'hi') }}" class="px-2 py-1 rounded {{ $currentLocale === 'hi' ? 'bg-orange-800 text-white font-bold' : 'text-stone-600' }}">हिन्दी</a>
                    </div>

                    <button id="mobile-menu-toggle" type="button" 
                            class="min-w-[44px] min-h-[44px] p-2.5 rounded-xl text-stone-700 hover:text-stone-900 hover:bg-stone-100 border border-stone-200/80 focus:outline-hidden focus:ring-2 focus:ring-amber-500 flex items-center justify-center transition" 
                            aria-label="Toggle navigation menu"
                            aria-expanded="false"
                            aria-controls="mobile-menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="mobile-menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown (Accessible Drawer) -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-stone-200 bg-stone-50/95 backdrop-blur-md px-4 pt-4 pb-6 space-y-2 shadow-lg transition-all duration-200">
            <!-- Search Shortcut -->
            <div class="pb-2">
                <a href="{{ route('search.index') }}" class="flex items-center justify-between min-h-[44px] px-3.5 py-2.5 rounded-xl bg-white border border-stone-300 text-xs font-semibold text-stone-700 shadow-2xs">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('ui.search_placeholder') }}
                    </span>
                    <span class="text-orange-800 font-bold">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 pt-1">
                <a href="{{ url('/map') }}" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $isMap ? 'bg-amber-100 text-orange-950 font-bold' : 'text-stone-700 hover:bg-stone-100' }}">
                    <span>🗺️</span>
                    <span>{{ __('ui.nav_map') }}</span>
                </a>
                <a href="{{ url('/sites') }}" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $isSites ? 'bg-amber-100 text-orange-950 font-bold' : 'text-stone-700 hover:bg-stone-100' }}">
                    <span>🏛️</span>
                    <span>{{ __('ui.nav_sites') }}</span>
                </a>
                <a href="{{ url('/objects') }}" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $isObjects ? 'bg-amber-100 text-orange-950 font-bold' : 'text-stone-700 hover:bg-stone-100' }}">
                    <span>🏺</span>
                    <span>{{ __('ui.nav_objects') }}</span>
                </a>
                <a href="{{ url('/inscriptions') }}" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $isInscriptions ? 'bg-amber-100 text-orange-950 font-bold' : 'text-stone-700 hover:bg-stone-100' }}">
                    <span>📜</span>
                    <span>{{ __('ui.nav_inscriptions') }}</span>
                </a>
                <a href="{{ url('/timeline') }}" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $isTimeline ? 'bg-amber-100 text-orange-950 font-bold' : 'text-stone-700 hover:bg-stone-100' }}">
                    <span>⏳</span>
                    <span>{{ __('ui.nav_timeline') }}</span>
                </a>
                <a href="{{ url('/datasets') }}" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $isDatasets ? 'bg-amber-100 text-orange-950 font-bold' : 'text-stone-700 hover:bg-stone-100' }}">
                    <span>📊</span>
                    <span>{{ __('ui.nav_datasets') }}</span>
                </a>
                <a href="{{ url('/verification') }}" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $isVerification ? 'bg-amber-100 text-orange-950 font-bold' : 'text-stone-700 hover:bg-stone-100' }}">
                    <span>🔍</span>
                    <span>{{ __('ui.nav_evidence') }}</span>
                </a>
                <a href="{{ url('/research-tools') }}" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $isResearch ? 'bg-amber-100 text-orange-950 font-bold' : 'text-stone-700 hover:bg-stone-100' }}">
                    <span>✒️</span>
                    <span>{{ __('ui.research_tools') }}</span>
                </a>
            </div>

            <!-- Mobile Footer Links -->
            <div class="pt-3 border-t border-stone-200 flex items-center justify-between text-xs text-stone-600">
                <a href="{{ route('developers.index') }}" class="min-h-[44px] flex items-center font-bold text-stone-700 hover:text-orange-800">
                    {{ __('ui.developer_api') }} &rarr;
                </a>
                <div class="flex items-center gap-2">
                    <span class="text-stone-400">Language:</span>
                    <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 rounded {{ $currentLocale === 'en' ? 'bg-orange-800 text-white font-bold' : 'text-stone-700' }}">EN</a>
                    <a href="{{ route('locale.switch', 'hi') }}" class="px-2 py-1 rounded {{ $currentLocale === 'hi' ? 'bg-orange-800 text-white font-bold' : 'text-stone-700' }}">हिन्दी</a>
                </div>
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
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-600 to-orange-700 text-white flex items-center justify-center font-serif font-bold text-lg shadow-sm">
                            भ
                        </div>
                        <span class="font-bold text-lg text-stone-100 tracking-tight">
                            <span class="text-amber-500 font-serif">{{ __('ui.brand_hindi') }}</span> Heritage Atlas
                        </span>
                    </div>
                    <p class="text-xs text-stone-400 leading-relaxed max-w-sm">
                        {{ __('ui.footer_about') }}
                    </p>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-stone-400 pt-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-stone-800/80 text-stone-300 border border-stone-700 text-[11px]">
                            🛡️ {{ __('ui.zero_fabrication') }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-stone-800/80 text-stone-300 border border-stone-700 text-[11px]">
                            📜 {{ __('ui.primary_epigraphy') }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-stone-800/80 text-stone-300 border border-stone-700 text-[11px]">
                            🏛️ {{ __('ui.in_situ_strata') }}
                        </span>
                    </div>
                </div>

                <!-- Col 2: Digital Atlas Exploration -->
                <div>
                    <h3 class="text-xs font-bold text-stone-100 uppercase tracking-wider mb-3">{{ __('ui.exploration') }}</h3>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li><a href="{{ url('/search') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_search') }}</a></li>
                        <li><a href="{{ url('/map') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_map') }}</a></li>
                        <li><a href="{{ url('/timeline') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_timeline') }}</a></li>
                        <li><a href="{{ url('/sites') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_sites') }}</a></li>
                        <li><a href="{{ url('/objects') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_objects') }}</a></li>
                        <li><a href="{{ url('/inscriptions') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_inscriptions') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Evidence Methodology -->
                <div>
                    <h3 class="text-xs font-bold text-stone-100 uppercase tracking-wider mb-3">{{ __('ui.methodology') }}</h3>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li><a href="{{ url('/verification') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_evidence') }}</a></li>
                        <li><a href="{{ route('research.index') }}" class="hover:text-amber-400 transition">{{ __('ui.research_tools') }}</a></li>
                        <li><span class="text-stone-500">Dual Historical Dating</span></li>
                        <li><span class="text-stone-500">Reliability Tiers (1–4)</span></li>
                        <li><span class="text-stone-500">Peer-Reviewed Consensus</span></li>
                        <li><a href="{{ url('/sources') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_sources') }}</a></li>
                    </ul>
                </div>

                <!-- Col 4: Developers & Open Data -->
                <div>
                    <h3 class="text-xs font-bold text-stone-100 uppercase tracking-wider mb-3">{{ __('ui.open_science') }}</h3>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li><a href="{{ url('/datasets') }}" class="hover:text-amber-400 transition">{{ __('ui.nav_datasets') }}</a></li>
                        <li><a href="{{ route('developers.index') }}" class="hover:text-amber-400 transition">{{ __('ui.developer_api') }}</a></li>
                        <li><a href="{{ route('api.v1.export.geojson') }}" class="hover:text-amber-400 transition">Bulk GeoJSON Export</a></li>
                        <li><a href="{{ route('api.v1.export.bibtex') }}" class="hover:text-amber-400 transition">BibTeX Citation Stream</a></li>
                        <li><a href="{{ url('/api/v1/geo/sites') }}" class="hover:text-amber-400 transition">RFC 7946 GeoJSON Feed</a></li>
                        <li><a href="https://github.com/vr7517/bharat-heritage-atlas" target="_blank" rel="noopener noreferrer" class="hover:text-amber-400 transition">{{ __('ui.github') }} Open Source</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright & Licensing -->
            <div class="mt-12 pt-6 border-t border-stone-800 text-xs text-stone-400 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>© {{ date('Y') }} {{ __('ui.copyright') }}</p>
                <p class="text-center sm:text-right text-stone-500">{{ __('ui.built_with') }}</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Accessible Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('mobile-menu-icon');
            if (toggle && menu) {
                toggle.addEventListener('click', function () {
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', !isExpanded);
                    menu.classList.toggle('hidden');
                    
                    if (!isExpanded) {
                        icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                    } else {
                        icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                    }
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
