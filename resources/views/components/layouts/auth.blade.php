<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 antialiased" style="font-family: 'Inter', system-ui, sans-serif;">

    {{-- Top bar --}}
    <div class="flex items-center justify-between h-14 px-6 border-b border-gray-100 bg-white">
        <a href="{{ route('welcome') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-600 to-indigo-500 flex-shrink-0"></div>
            <span class="font-semibold text-gray-900 text-sm">{{ config('app.name') }}</span>
        </a>
        <a href="{{ route('welcome') }}" class="text-xs font-medium text-gray-400 hover:text-gray-600 transition-colors">← Accueil</a>
    </div>

    <div class="flex min-h-[calc(100vh-3.5rem)] flex-col items-center justify-center px-4 py-12">
        <div class="w-full max-w-sm">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
