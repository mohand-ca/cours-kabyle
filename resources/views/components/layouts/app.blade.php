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
        input, textarea, select { transition: border-color .15s ease, box-shadow .15s ease; }
        input:focus, textarea:focus, select:focus, .focus-amber:focus { border-color: #F2B81D !important; box-shadow: 0 0 0 3px rgba(242,184,29,.22) !important; }
        .thz-logout:hover { background: #F1ECE6 !important; color: #1C1917 !important; }
        .thz-nav:hover { background: #F5F0E9; color: #1C1917; }
        .thz-card { transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
        .thz-card:hover { transform: translateY(-2px); box-shadow: 0 16px 34px -20px rgba(28,25,23,.26); border-color: #E4D8B8; }
        .thz-row { transition: border-color .15s ease, box-shadow .15s ease, background .15s ease; }
        .thz-row:hover { border-color: #E4D8B8; box-shadow: 0 8px 20px -14px rgba(28,25,23,.22); }
        [x-cloak] { display: none !important; }
        .nav-burger { display: none; }
        .nav-mobile-panel { display: none; }
        @media (max-width: 860px) {
            .nav-links { display: none !important; }
            .nav-name { display: none !important; }
            .nav-burger { display: flex !important; }
            .nav-mobile-panel { display: flex; }
        }
    </style>
</head>
<body>

    @php
        $navItems = auth()->user()->hasRole('learner')
            ? [
                ['route' => 'learner.dashboard', 'label' => __('nav.dashboard')],
                ['route' => 'learner.teachers', 'label' => __('nav.find_teacher')],
                ['route' => 'learner.learners', 'label' => __('nav.my_learners')],
                ['route' => 'learner.packages', 'label' => __('nav.buy_sessions')],
            ]
            : (auth()->user()->hasRole('teacher')
                ? [
                    ['route' => 'teacher.dashboard', 'label' => __('nav.dashboard')],
                    ['route' => 'teacher.profile', 'label' => __('nav.my_profile')],
                    ['route' => 'teacher.availability', 'label' => __('nav.availability')],
                ]
                : []);
    @endphp

    <header x-data="{ open: false }" style="position:sticky;top:0;z-index:40;background:rgba(250,248,245,.85);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid #EAE4DD">
        <div style="max-width:1160px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;gap:24px">

            <a href="{{ route('welcome') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:18px;font-weight:700;box-shadow:0 4px 12px -4px rgba(222,165,0,.6)">ⵣ</div>
                <span style="font-size:17px;font-weight:700;letter-spacing:-.02em;color:#1C1917">{{ config('app.name') }}</span>
            </a>

            <div class="nav-links" style="display:flex;gap:4px;flex:1">
                @foreach($navItems as $item)
                    <x-nav-link :href="route($item['route'])" :active="request()->routeIs($item['route'])">{{ $item['label'] }}</x-nav-link>
                @endforeach
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;margin-left:auto">
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
                <span class="nav-name" style="font-size:14px;font-weight:600;color:#1C1917">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="{{ __('nav.logout') }}" class="thz-logout" style="width:34px;height:34px;border-radius:10px;border:1px solid transparent;background:transparent;color:#78716C;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"></path></svg>
                    </button>
                </form>

                {{-- Mobile hamburger --}}
                @if(count($navItems) > 0)
                    <button type="button" class="nav-burger thz-logout" @click="open = !open" :aria-expanded="open" aria-label="{{ __('nav.menu') }}" style="width:34px;height:34px;border-radius:10px;border:1px solid transparent;background:transparent;color:#57534E;cursor:pointer;align-items:center;justify-content:center;transition:all .15s">
                        <svg x-show="!open" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                        <svg x-show="open" x-cloak width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                @endif
            </div>
        </div>

        {{-- Mobile dropdown panel --}}
        @if(count($navItems) > 0)
            <div x-show="open" x-cloak @click.away="open = false" x-transition.opacity class="nav-mobile-panel" style="border-top:1px solid #EAE4DD;background:rgba(250,248,245,.98);flex-direction:column">
                <nav style="max-width:1160px;margin:0 auto;width:100%;padding:8px 16px 14px;display:flex;flex-direction:column;gap:2px">
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" @click="open = false" @class(['thz-nav' => ! request()->routeIs($item['route'])])
                           style="padding:11px 14px;border-radius:10px;font-size:15px;font-weight:600;text-decoration:none;{{ request()->routeIs($item['route']) ? 'background:#F1ECE6;color:#1C1917' : 'background:transparent;color:#57534E' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        @endif
    </header>

    <main style="max-width:1160px;margin:0 auto;padding:40px 24px">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
