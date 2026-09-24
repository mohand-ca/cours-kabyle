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
        body { font-family: 'Geist', system-ui, sans-serif; color: #1C1917; -webkit-font-smoothing: antialiased; margin: 0; min-height: 100vh; background: #FAF8F5; background-image: radial-gradient(circle at 50% 0%, #FBF0CF 0, rgba(250,248,245,0) 55%); }
        * { box-sizing: border-box; }
        input, textarea, select { transition: border-color .15s ease, box-shadow .15s ease; }
        input:focus, textarea:focus, select:focus, .focus-amber:focus { border-color: #F2B81D !important; box-shadow: 0 0 0 3px rgba(242,184,29,.22) !important; }
        button[type=submit] { transition: transform .15s ease, box-shadow .2s ease, filter .15s ease; }
        button[type=submit]:hover { transform: translateY(-1px); filter: brightness(1.02); box-shadow: 0 14px 26px -8px rgba(222,165,0,.7) !important; }
        a { transition: color .15s ease; }
    </style>
</head>
<body>

    {{-- Language switcher --}}
    <div style="position:fixed;top:16px;right:20px;z-index:10;display:flex;align-items:center;gap:2px;background:#F1ECE6;border-radius:8px;padding:3px">
        <a href="{{ route('locale.switch', 'fr') }}"
           style="padding:4px 9px;border-radius:6px;font-size:12.5px;font-weight:600;text-decoration:none;{{ app()->getLocale() === 'fr' ? 'background:#fff;color:#1C1917;box-shadow:0 1px 2px rgba(28,25,23,.08)' : 'color:#78716C' }}">FR</a>
        <a href="{{ route('locale.switch', 'en') }}"
           style="padding:4px 9px;border-radius:6px;font-size:12.5px;font-weight:600;text-decoration:none;{{ app()->getLocale() === 'en' ? 'background:#fff;color:#1C1917;box-shadow:0 1px 2px rgba(28,25,23,.08)' : 'color:#78716C' }}">EN</a>
    </div>

    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:48px 20px">
        <div style="width:100%;max-width:384px">

            <a href="{{ route('welcome') }}" style="display:flex;flex-direction:column;align-items:center;text-decoration:none;margin-bottom:28px">
                <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:26px;font-weight:700;box-shadow:0 8px 20px -6px rgba(222,165,0,.6)">ⵣ</div>
                <span style="margin-top:12px;font-size:19px;font-weight:800;letter-spacing:-.02em;color:#1C1917">{{ config('app.name') }}</span>
                <span style="margin-top:4px;font-size:13px;color:#78716C;text-align:center;max-width:280px;line-height:1.4">{{ __('auth.tagline') }}</span>
            </a>

            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
