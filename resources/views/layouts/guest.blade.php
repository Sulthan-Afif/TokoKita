<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <title>TokoKita — Belanja Online Terpercaya</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50 selection:bg-emerald-500 selection:text-white min-h-screen flex flex-col">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-8 sm:py-12 px-4">
            <div>
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 sm:px-8 py-8 bg-white border border-slate-200/80 shadow-xl shadow-slate-900/5 overflow-hidden rounded-3xl">
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs text-slate-400 text-center">
                &copy; {{ date('Y') }} TokoKita. Platform Belanja Online Terpercaya.
            </p>
        </div>
        @stack('scripts')
    </body> 
</html>
