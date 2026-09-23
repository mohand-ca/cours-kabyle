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
    </style>
</head>
<body>

    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:48px 20px">
        <div style="width:100%;max-width:384px">

            <a href="{{ route('welcome') }}" style="display:flex;flex-direction:column;align-items:center;text-decoration:none;margin-bottom:24px">
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:24px;font-weight:700;box-shadow:0 8px 20px -6px rgba(222,165,0,.6)">ⵣ</div>
            </a>

            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
