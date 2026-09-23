<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Geist', system-ui, sans-serif; color: #1C1917; -webkit-font-smoothing: antialiased; margin: 0; min-height: 100vh; background: #FAF8F5; background-image: radial-gradient(circle at 50% 0%, #FBF0CF 0, rgba(250,248,245,0) 55%); display: flex; align-items: center; justify-content: center; padding: 48px 20px; }
    </style>
</head>
<body>
    <div style="width:100%;max-width:400px;text-align:center">
        <a href="{{ route('welcome') }}" style="display:inline-flex;flex-direction:column;align-items:center;text-decoration:none;margin-bottom:32px">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:24px;font-weight:700;box-shadow:0 8px 20px -6px rgba(222,165,0,.6)">ⵣ</div>
        </a>

        <div style="font-size:72px;font-weight:800;letter-spacing:-.04em;color:#F2B81D;line-height:1;margin-bottom:16px">404</div>
        <h1 style="font-size:22px;font-weight:700;margin:0 0 10px;color:#1C1917">Page introuvable</h1>
        <p style="font-size:15px;color:#78716C;margin:0 0 32px;line-height:1.55">Cette page n'existe pas ou a été déplacée.</p>

        <a href="{{ route('welcome') }}"
            style="display:inline-block;background:#F2B81D;color:#1C1917;font-family:inherit;font-size:15px;font-weight:700;padding:13px 28px;border-radius:12px;text-decoration:none;box-shadow:0 8px 20px -8px rgba(222,165,0,.6)">
            Retour à l'accueil
        </a>
    </div>
</body>
</html>
