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
<body class="min-h-screen bg-gray-50 font-sans antialiased" style="font-family: 'Inter', system-ui, sans-serif;">

    {{-- Top navbar --}}
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 px-6 py-0 flex items-center justify-between h-14">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-600 to-indigo-500 flex-shrink-0"></div>
            <span class="font-semibold text-gray-900 text-sm">{{ config('app.name') }}</span>
        </div>

        <div class="flex items-center gap-0.5">
            @if(auth()->user()->hasRole('learner'))
                <x-nav-link :href="route('learner.dashboard')" :active="request()->routeIs('learner.dashboard')">
                    {{ __('nav.dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('learner.teachers')" :active="request()->routeIs('learner.teachers')">
                    {{ __('nav.find_teacher') }}
                </x-nav-link>
                <x-nav-link :href="route('learner.learners')" :active="request()->routeIs('learner.learners')">
                    {{ __('nav.my_learners') }}
                </x-nav-link>
                <x-nav-link :href="route('learner.packages')" :active="request()->routeIs('learner.packages')">
                    {{ __('nav.buy_sessions') }}
                </x-nav-link>
            @elseif(auth()->user()->hasRole('teacher'))
                <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                    {{ __('nav.dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('teacher.profile')" :active="request()->routeIs('teacher.profile')">
                    {{ __('nav.my_profile') }}
                </x-nav-link>
                <x-nav-link :href="route('teacher.availability')" :active="request()->routeIs('teacher.availability')">
                    {{ __('nav.availability') }}
                </x-nav-link>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs font-medium text-gray-500 hover:text-gray-800 transition-colors">
                    {{ __('nav.logout') }}
                </button>
            </form>
        </div>
    </nav>

    {{-- Page content --}}
    <main class="max-w-4xl mx-auto px-6 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
