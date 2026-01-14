<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            .font-display { font-family: 'Oswald', sans-serif; }
        </style>
    </head>
    <body class="font-sans text-slate-200 antialiased bg-slate-950 selection:bg-orange-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            
            {{-- Background Effects --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-7xl pointer-events-none z-0">
                <div class="absolute top-10 left-10 w-72 h-72 bg-orange-600/20 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px]"></div>
            </div>

            {{-- Logo --}}
            <div class="mb-6 z-10 relative">
                <a href="/" class="text-4xl font-bold font-display uppercase tracking-tighter text-white hover:text-orange-500 transition">
                    Fajri<span class="text-orange-500">Garage.</span>
                </a>
            </div>

            {{-- Card Container --}}
            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-slate-900/80 backdrop-blur-xl border border-slate-800 shadow-2xl overflow-hidden sm:rounded-lg z-10 relative">
                {{ $slot }}
            </div>

            {{-- Footer Copyright --}}
            <div class="mt-8 text-slate-600 text-sm z-10">
                &copy; {{ date('Y') }} FajriGarage. All rights reserved.
            </div>
        </div>
    </body>
</html>