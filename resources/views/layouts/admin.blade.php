<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin CMS Dashboard') — Bharat Heritage Atlas</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col text-stone-900 font-sans antialiased selection:bg-amber-200 selection:text-amber-950">

    <!-- Admin Top Navbar -->
    <header class="bg-stone-900 text-stone-200 sticky top-0 z-40 border-b border-stone-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-600 to-orange-700 text-stone-950 flex items-center justify-center font-bold text-sm shadow-xs group-hover:scale-105 transition">
                            भ
                        </div>
                        <div>
                            <span class="font-bold text-base text-stone-100 tracking-tight flex items-center gap-2">
                                <span><span class="text-amber-500 font-serif">भारत</span> CMS</span>
                                <span class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">Curator</span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Nav Links -->
                <nav class="hidden lg:flex items-center gap-1 text-xs font-medium text-stone-300">
                    <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1.5 rounded-lg hover:bg-stone-800 hover:text-white transition {{ request()->routeIs('admin.dashboard') ? 'bg-stone-800 text-amber-400 font-bold' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.sites.index') }}" class="px-2.5 py-1.5 rounded-lg hover:bg-stone-800 hover:text-white transition {{ request()->is('admin/sites*') ? 'bg-stone-800 text-amber-400 font-bold' : '' }}">
                        🏛️ Sites
                    </a>
                    <a href="{{ route('admin.objects.index') }}" class="px-2.5 py-1.5 rounded-lg hover:bg-stone-800 hover:text-white transition {{ request()->is('admin/objects*') ? 'bg-stone-800 text-amber-400 font-bold' : '' }}">
                        🏺 Artifacts
                    </a>
                    <a href="{{ route('admin.inscriptions.index') }}" class="px-2.5 py-1.5 rounded-lg hover:bg-stone-800 hover:text-white transition {{ request()->is('admin/inscriptions*') ? 'bg-stone-800 text-amber-400 font-bold' : '' }}">
                        📜 Epigraphy
                    </a>
                    <a href="{{ route('admin.dynasties.index') }}" class="px-2.5 py-1.5 rounded-lg hover:bg-stone-800 hover:text-white transition {{ request()->is('admin/dynasties*') ? 'bg-stone-800 text-amber-400 font-bold' : '' }}">
                        👑 Dynasties
                    </a>
                    <a href="{{ route('admin.periods.index') }}" class="px-2.5 py-1.5 rounded-lg hover:bg-stone-800 hover:text-white transition {{ request()->is('admin/periods*') ? 'bg-stone-800 text-amber-400 font-bold' : '' }}">
                        ⏳ Timeline
                    </a>
                    <a href="{{ route('admin.sources.index') }}" class="px-2.5 py-1.5 rounded-lg hover:bg-stone-800 hover:text-white transition {{ request()->is('admin/sources*') ? 'bg-stone-800 text-amber-400 font-bold' : '' }}">
                        📚 Sources
                    </a>
                    <a href="{{ route('admin.datasets.index') }}" class="px-2.5 py-1.5 rounded-lg hover:bg-stone-800 hover:text-white transition {{ request()->is('admin/datasets*') ? 'bg-stone-800 text-amber-400 font-bold' : '' }}">
                        📊 Datasets
                    </a>
                </nav>

                <!-- User & Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs text-stone-400 hover:text-amber-400 transition font-medium px-2.5 py-1 rounded-md border border-stone-800 bg-stone-950/40">
                        <span>View Public Atlas</span>
                        <span>&nearr;</span>
                    </a>

                    <div class="flex items-center gap-2 pl-2 border-l border-stone-800">
                        <span class="hidden sm:inline-block text-xs font-medium text-stone-300">
                            {{ auth()->user()->name ?? 'Curator' }}
                        </span>

                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs px-2.5 py-1 rounded-md bg-stone-800 hover:bg-rose-900/60 hover:text-rose-200 text-stone-400 border border-stone-700 transition">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-center justify-between gap-2 shadow-2xs">
            <span class="flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </span>
        </div>
        @endif

        @if(session('warning'))
        <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-center justify-between gap-2 shadow-2xs">
            <span class="flex items-center gap-2">
                <span>⚠️</span>
                <span>{{ session('warning') }}</span>
            </span>
        </div>
        @endif

        @if(session('error') || $errors->any())
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1 shadow-2xs">
            @if(session('error'))
            <div>{{ session('error') }}</div>
            @endif
            @foreach($errors->all() as $error)
            <div>• {{ $error }}</div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Minimal Admin Footer -->
    <footer class="bg-stone-900 text-stone-500 border-t border-stone-800 text-xs py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                Bharat Heritage Atlas CMS &bull; Strict Zero-Fabrication Academic Engine
            </div>
            <div class="font-mono text-[11px] text-stone-400">
                Session Active &bull; {{ auth()->user()->email ?? '' }}
            </div>
        </div>
    </footer>

</body>
</html>
