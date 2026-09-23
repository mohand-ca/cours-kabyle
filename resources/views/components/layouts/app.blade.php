<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Geist', system-ui, sans-serif; background: #FAF8F5; color: #1C1917; -webkit-font-smoothing: antialiased; margin: 0; }
        * { box-sizing: border-box; }
    </style>
</head>
<body>

    <header style="position:sticky;top:0;z-index:40;background:rgba(250,248,245,.85);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid #EAE4DD">
        <div style="max-width:1160px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;gap:24px">

            <a href="{{ route('welcome') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:18px;font-weight:700;box-shadow:0 4px 12px -4px rgba(222,165,0,.6)">ⵣ</div>
                <span style="font-size:17px;font-weight:700;letter-spacing:-.02em;color:#1C1917">{{ config('app.name') }}</span>
            </a>

            <div style="display:flex;gap:4px;flex:1">
                @if(auth()->user()->hasRole('learner'))
                    <x-nav-link :href="route('learner.dashboard')" :active="request()->routeIs('learner.dashboard')">{{ __('nav.dashboard') }}</x-nav-link>
                    <x-nav-link :href="route('learner.teachers')" :active="request()->routeIs('learner.teachers')">{{ __('nav.find_teacher') }}</x-nav-link>
                    <x-nav-link :href="route('learner.learners')" :active="request()->routeIs('learner.learners')">{{ __('nav.my_learners') }}</x-nav-link>
                    <x-nav-link :href="route('learner.packages')" :active="request()->routeIs('learner.packages')">{{ __('nav.buy_sessions') }}</x-nav-link>
                @elseif(auth()->user()->hasRole('teacher'))
                    <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">{{ __('nav.dashboard') }}</x-nav-link>
                    <x-nav-link :href="route('teacher.profile')" :active="request()->routeIs('teacher.profile')">{{ __('nav.my_profile') }}</x-nav-link>
                    <x-nav-link :href="route('teacher.availability')" :active="request()->routeIs('teacher.availability')">{{ __('nav.availability') }}</x-nav-link>
                @endif
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
                {{-- Language switcher --}}
                <div style="display:flex;align-items:center;gap:2px;background:#F1ECE6;border-radius:8px;padding:3px">
                    <a href="{{ route('locale.switch', 'fr') }}"
                       style="padding:4px 9px;border-radius:6px;font-size:12.5px;font-weight:600;text-decoration:none;{{ app()->getLocale() === 'fr' ? 'background:#fff;color:#1C1917;box-shadow:0 1px 2px rgba(28,25,23,.08)' : 'color:#78716C' }}">FR</a>
                    <a href="{{ route('locale.switch', 'en') }}"
                       style="padding:4px 9px;border-radius:6px;font-size:12.5px;font-weight:600;text-decoration:none;{{ app()->getLocale() === 'en' ? 'background:#fff;color:#1C1917;box-shadow:0 1px 2px rgba(28,25,23,.08)' : 'color:#78716C' }}">EN</a>
                </div>
                <div style="width:32px;height:32px;border-radius:999px;background:linear-gradient(135deg,#F2B81D,#F9D55C);color:#1C1917;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span style="font-size:14px;font-weight:600;color:#1C1917">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="{{ __('nav.logout') }}" style="width:34px;height:34px;border-radius:10px;border:1px solid transparent;background:transparent;color:#78716C;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main style="max-width:1160px;margin:0 auto;padding:40px 24px">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
