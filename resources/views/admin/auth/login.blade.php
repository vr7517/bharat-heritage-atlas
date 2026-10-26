<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Curator Sign In — Bharat Heritage Atlas CMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 text-stone-900 antialiased font-sans selection:bg-amber-500 selection:text-stone-950">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <!-- Logo / Icon -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-600 to-orange-700 text-stone-950 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                <svg class="w-7 h-7 stroke-current fill-none stroke-[2]" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 3v6M12 15v6M3 12h6M15 12h6M5.64 5.64l4.24 4.24M14.12 14.12l4.24 4.24M5.64 18.36l4.24-4.24M14.12 9.88l4.24-4.24"/>
                </svg>
            </div>
        </a>

        <h2 class="mt-4 text-2xl font-bold tracking-tight text-stone-100">
            <span class="text-amber-500 font-serif">भारत</span> Heritage Atlas
        </h2>
        <p class="mt-1 text-xs text-stone-400 font-mono tracking-wider uppercase">
            Curator & Editorial CMS Portal
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-[#faf8f5] py-8 px-6 sm:px-10 shadow-2xl rounded-2xl border border-stone-200">

            @if(session('warning'))
            <div class="mb-5 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-center gap-2">
                <span>⚠️</span>
                <span>{{ session('warning') }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <span>⚠️</span>
                    <span>{{ $error }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                        Curator Email
                    </label>
                    <div class="mt-1.5">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            value="{{ old('email') }}"
                            placeholder="admin@bharatheritage.in"
                            class="block w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:ring-2 focus:ring-amber-500/20 focus:outline-hidden transition bg-white" />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                        Master Password
                    </label>
                    <div class="mt-1.5">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            placeholder="••••••••••••"
                            class="block w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:ring-2 focus:ring-amber-500/20 focus:outline-hidden transition bg-white" />
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-stone-300 text-orange-800 focus:ring-amber-500" />
                        <span class="text-stone-600">Remember this terminal</span>
                    </label>
                    <span class="text-stone-400 font-mono text-[11px]">Strict Access Control</span>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gradient-to-r from-orange-800 to-amber-700 hover:from-orange-900 hover:to-amber-800 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-orange-800 transition">
                        Authenticate as Curator
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-stone-200 text-center">
                <a href="{{ route('home') }}" class="text-xs font-semibold text-stone-500 hover:text-orange-900 transition flex items-center justify-center gap-1.5">
                    <span>&larr;</span>
                    <span>Return to Public Heritage Atlas</span>
                </a>
            </div>
        </div>

        <p class="mt-4 text-center text-xs text-stone-500">
            Authorized academic curators and archaeological editors only.
        </p>
    </div>

</body>
</html>
