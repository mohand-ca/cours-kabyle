<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="flex w-60 flex-shrink-0 flex-col bg-white border-r border-gray-100">

            {{-- Logo --}}
            <div class="flex h-16 items-center px-6 border-b border-gray-100">
                <a href="/" class="text-lg font-bold text-violet-700 tracking-tight">
                    {{ config('app.name') }}
                </a>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 space-y-0.5 px-3 py-4">
                @if(auth()->user()->hasRole('learner'))
                    <x-nav-link :href="route('learner.dashboard')" :active="request()->routeIs('learner.dashboard')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        {{ __('nav.dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('learner.teachers')" :active="request()->routeIs('learner.teachers')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('nav.find_teacher') }}
                    </x-nav-link>
                    <x-nav-link :href="route('learner.learners')" :active="request()->routeIs('learner.learners')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ __('nav.my_learners') }}
                    </x-nav-link>
                    <x-nav-link :href="route('learner.packages')" :active="request()->routeIs('learner.packages')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        {{ __('nav.buy_sessions') }}
                    </x-nav-link>
                @elseif(auth()->user()->hasRole('teacher'))
                    <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        {{ __('nav.dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('teacher.profile')" :active="request()->routeIs('teacher.profile')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('nav.my_profile') }}
                    </x-nav-link>
                    <x-nav-link :href="route('teacher.availability')" :active="request()->routeIs('teacher.availability')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('nav.availability') }}
                    </x-nav-link>
                @endif
            </nav>

            {{-- User + logout --}}
            <div class="border-t border-gray-100 px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-violet-100 text-xs font-semibold text-violet-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-lg px-3 py-1.5 text-left text-xs font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                        {{ __('nav.logout') }}
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex flex-1 flex-col min-w-0">
            <main class="flex-1 px-8 py-8">
                {{ $slot }}
            </main>
        </div>

    </div>

    @livewireScripts
</body>
</html>
