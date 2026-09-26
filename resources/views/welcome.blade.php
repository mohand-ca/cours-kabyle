<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — {{ __('welcome.meta_title') }}</title>
    <meta name="description" content="{{ __('welcome.meta_description') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Geist',system-ui,sans-serif;background:#FAF8F5;color:#1C1917;-webkit-font-smoothing:antialiased;overflow-x:hidden}
        a{color:#9A6A00;text-decoration:none}
        @keyframes thzFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
        @keyframes thzPing{0%{transform:scale(1);opacity:.7}80%,100%{transform:scale(2.6);opacity:0}}
        .thz-card{transition:transform .22s ease,box-shadow .22s ease,border-color .22s ease}
        .thz-card:hover{transform:translateY(-3px);box-shadow:0 20px 44px -22px rgba(28,25,23,.30);border-color:#E4D8B8}
        .thz-btn-primary{transition:transform .15s ease,box-shadow .2s ease}
        .thz-btn-primary:hover{transform:translateY(-2px);box-shadow:0 18px 32px -10px rgba(222,165,0,.72)}
        .thz-btn-ghost{transition:background .15s ease,border-color .15s ease}
        .thz-btn-ghost:hover{background:#F7F2EB;border-color:#D8CDBF}
        .thz-nav-link{transition:background .15s ease,color .15s ease}
        .thz-nav-link:hover{background:#F1ECE6;color:#1C1917}
        .thz-price{transition:transform .22s ease,box-shadow .22s ease,border-color .22s ease}
        .thz-price:hover{transform:translateY(-4px);box-shadow:0 26px 50px -24px rgba(28,25,23,.32)}
        .thz-faq{transition:border-color .15s ease,box-shadow .15s ease}
        .thz-faq:hover{border-color:#E4D8B8}
        .thz-faq summary{cursor:pointer;list-style:none;outline:none}
        .thz-faq summary::-webkit-details-marker{display:none}
        .thz-faq-icon{transition:transform .2s ease;flex-shrink:0}
        .thz-faq[open] .thz-faq-icon{transform:rotate(45deg)}
        .thz-faq[open]{box-shadow:0 12px 30px -18px rgba(28,25,23,.22)}
        .nav-toggle-cb{position:absolute;opacity:0;pointer-events:none}
        .nav-burger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2DBD3;background:#fff;color:#44403C;cursor:pointer;flex-shrink:0}
        .nav-burger .ic-close{display:none}
        .nav-dropdown{display:none}
        @media(max-width:860px){
            .thz-nav-center{display:none!important}
            .nav-desktop-actions{display:none!important}
            .nav-burger{display:flex!important}
            #navtoggle:checked~.thz-bar .nav-burger .ic-open{display:none}
            #navtoggle:checked~.thz-bar .nav-burger .ic-close{display:block}
            #navtoggle:checked~.nav-dropdown{display:flex}
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<header style="position:sticky;top:0;z-index:40;background:rgba(250,248,245,.85);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid #EAE4DD">
    <input type="checkbox" id="navtoggle" class="nav-toggle-cb" aria-hidden="true" tabindex="-1">
    <div class="thz-bar" style="max-width:1160px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;gap:24px">
        <a href="{{ route('welcome') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0">
            <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:18px;font-weight:700;box-shadow:0 4px 12px -4px rgba(222,165,0,.6)">ⵣ</div>
            <span style="font-size:17px;font-weight:700;letter-spacing:-.02em;color:#1C1917">{{ config('app.name') }}</span>
        </a>
        <nav class="thz-nav-center" style="display:flex;gap:4px;flex:1">
            <a href="#features" class="thz-nav-link" style="padding:7px 12px;border-radius:999px;font-size:14px;font-weight:500;color:#57534E">{{ __('welcome.nav.features') }}</a>
            <a href="#how" class="thz-nav-link" style="padding:7px 12px;border-radius:999px;font-size:14px;font-weight:500;color:#57534E">{{ __('welcome.nav.how_it_works') }}</a>
            <a href="#pricing" class="thz-nav-link" style="padding:7px 12px;border-radius:999px;font-size:14px;font-weight:500;color:#57534E">{{ __('welcome.nav.pricing') }}</a>
            <a href="{{ route('teacher.register') }}" class="thz-nav-link" style="padding:7px 12px;border-radius:999px;font-size:14px;font-weight:500;color:#57534E">{{ __('welcome.nav.teach') }}</a>
        </nav>
        <div style="display:flex;gap:8px;align-items:center;margin-left:auto">
            {{-- Language switcher --}}
            <div style="display:flex;align-items:center;gap:2px;background:#F1ECE6;border-radius:8px;padding:3px">
                <a href="{{ route('locale.switch', 'fr') }}"
                   style="padding:4px 9px;border-radius:6px;font-size:12.5px;font-weight:600;text-decoration:none;{{ app()->getLocale() === 'fr' ? 'background:#fff;color:#1C1917;box-shadow:0 1px 2px rgba(28,25,23,.08)' : 'color:#78716C' }}">FR</a>
                <a href="{{ route('locale.switch', 'en') }}"
                   style="padding:4px 9px;border-radius:6px;font-size:12.5px;font-weight:600;text-decoration:none;{{ app()->getLocale() === 'en' ? 'background:#fff;color:#1C1917;box-shadow:0 1px 2px rgba(28,25,23,.08)' : 'color:#78716C' }}">EN</a>
            </div>
            <div class="nav-desktop-actions" style="display:flex;gap:8px;align-items:center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="thz-btn-primary" style="background:#F2B81D;color:#1C1917;font-size:14px;font-weight:700;padding:9px 15px;border-radius:12px;box-shadow:0 6px 16px -6px rgba(222,165,0,.6)">{{ __('welcome.nav.dashboard') }} →</a>
                @else
                    <a href="{{ route('login') }}" class="thz-btn-ghost" style="background:transparent;border:1px solid #E2DBD3;color:#44403C;font-size:14px;font-weight:600;padding:8px 14px;border-radius:12px">{{ __('welcome.nav.login') }}</a>
                    <a href="{{ route('register') }}" class="thz-btn-primary" style="background:#F2B81D;color:#1C1917;font-size:14px;font-weight:700;padding:9px 15px;border-radius:12px;box-shadow:0 6px 16px -6px rgba(222,165,0,.6)">{{ __('welcome.nav.get_started') }}</a>
                @endauth
            </div>
            <label for="navtoggle" class="nav-burger" role="button" aria-label="{{ __('welcome.nav.menu') }}" aria-controls="navtoggle">
                <svg class="ic-open" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                <svg class="ic-close" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </label>
        </div>
    </div>
    {{-- Mobile dropdown --}}
    <div class="nav-dropdown" style="flex-direction:column;border-top:1px solid #EAE4DD;background:rgba(250,248,245,.98)">
        <nav style="max-width:1160px;margin:0 auto;width:100%;padding:8px 16px 16px;display:flex;flex-direction:column;gap:2px">
            <a href="#features" style="padding:11px 14px;border-radius:10px;font-size:15px;font-weight:600;color:#57534E">{{ __('welcome.nav.features') }}</a>
            <a href="#how" style="padding:11px 14px;border-radius:10px;font-size:15px;font-weight:600;color:#57534E">{{ __('welcome.nav.how_it_works') }}</a>
            <a href="#pricing" style="padding:11px 14px;border-radius:10px;font-size:15px;font-weight:600;color:#57534E">{{ __('welcome.nav.pricing') }}</a>
            <a href="{{ route('teacher.register') }}" style="padding:11px 14px;border-radius:10px;font-size:15px;font-weight:600;color:#57534E">{{ __('welcome.nav.teach') }}</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="thz-btn-primary" style="margin-top:8px;text-align:center;background:#F2B81D;color:#1C1917;font-size:15px;font-weight:700;padding:13px 15px;border-radius:12px">{{ __('welcome.nav.dashboard') }} →</a>
            @else
                <a href="{{ route('login') }}" style="margin-top:8px;text-align:center;background:transparent;border:1px solid #E2DBD3;color:#44403C;font-size:15px;font-weight:600;padding:12px 15px;border-radius:12px">{{ __('welcome.nav.login') }}</a>
                <a href="{{ route('register') }}" class="thz-btn-primary" style="text-align:center;background:#F2B81D;color:#1C1917;font-size:15px;font-weight:700;padding:13px 15px;border-radius:12px">{{ __('welcome.nav.get_started') }}</a>
            @endauth
        </nav>
    </div>
</header>

{{-- Hero --}}
<section style="position:relative;overflow:hidden">
    <div style="position:absolute;right:-60px;top:-40px;font-size:520px;line-height:1;color:#9A6A00;opacity:.035;font-weight:700;pointer-events:none;user-select:none">ⵣ</div>
    <div style="max-width:1160px;margin:0 auto;padding:88px 24px 76px;position:relative;text-align:center">

        {{-- Centered hero message --}}
        <div style="max-width:940px;margin:0 auto">
            <div style="display:inline-flex;align-items:center;gap:10px;padding:6px 12px 6px 10px;border-radius:999px;background:#fff;border:1px solid #EAE4DD;font-size:13px;font-weight:500;color:#44403C;box-shadow:0 1px 2px rgba(28,25,23,.04)">
                <span style="position:relative;width:10px;height:10px;flex-shrink:0">
                    <span style="position:absolute;inset:0;border-radius:999px;background:#F2B81D;opacity:.7;animation:thzPing 1.5s ease-out infinite"></span>
                    <span style="position:relative;width:10px;height:10px;border-radius:999px;background:#E0A800;display:block"></span>
                </span>
                <span>{{ __('welcome.hero.badge') }}</span>
            </div>

            <h1 style="font-size:clamp(44px,7vw,80px);line-height:1.03;letter-spacing:-.045em;font-weight:800;margin:26px auto 22px;text-wrap:balance;max-width:920px">
                {{ __('welcome.hero.h1_plain') }}<span style="background:linear-gradient(120deg,#A87200,#D39B12);-webkit-background-clip:text;background-clip:text;color:transparent">{{ __('welcome.hero.h1_highlight') }}</span>{{ __('welcome.hero.h1_end') }}
            </h1>

            <p style="font-size:18.5px;line-height:1.55;color:#78716C;font-weight:400;margin:0 auto 34px;max-width:600px;text-wrap:pretty">
                {{ __('welcome.hero.subtitle') }}
            </p>

            <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center">
                <a href="{{ route('register') }}" class="thz-btn-primary" style="background:#F2B81D;color:#1C1917;font-size:15px;font-weight:700;padding:14px 22px;border-radius:12px;box-shadow:0 10px 24px -8px rgba(222,165,0,.6);display:flex;align-items:center;gap:8px;text-decoration:none">
                    {{ __('welcome.hero.cta_primary') }}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"></path></svg>
                </a>
                <a href="{{ route('teacher.register') }}" class="thz-btn-ghost" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font-size:15px;font-weight:600;padding:14px 22px;border-radius:12px;text-decoration:none">{{ __('welcome.hero.cta_teacher') }}</a>
            </div>

            <div style="display:flex;align-items:center;gap:14px;margin-top:34px;justify-content:center;flex-wrap:wrap">
                <div style="display:flex">
                    <div style="width:34px;height:34px;border-radius:999px;border:2px solid #FAF8F5;background:linear-gradient(135deg,#F2B81D,#F9D55C);color:#1C1917;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center">SM</div>
                    <div style="width:34px;height:34px;border-radius:999px;border:2px solid #FAF8F5;margin-left:-10px;background:linear-gradient(135deg,#3F6E5E,#8BAE7C);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center">AK</div>
                    <div style="width:34px;height:34px;border-radius:999px;border:2px solid #FAF8F5;margin-left:-10px;background:linear-gradient(135deg,#5B4B8A,#A77BB5);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center">NB</div>
                    <div style="width:34px;height:34px;border-radius:999px;border:2px solid #FAF8F5;margin-left:-10px;background:linear-gradient(135deg,#7A5C3E,#C49A6C);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center">YT</div>
                </div>
                <div style="font-size:13px;color:#78716C;line-height:1.4;max-width:260px">{{ __('welcome.hero.social_proof') }}</div>
            </div>
        </div>
    </div>
</section>

{{-- Trust bar --}}
<section style="border-top:1px solid #EAE4DD;border-bottom:1px solid #EAE4DD;background:#fff">
    <div style="max-width:1160px;margin:0 auto;padding:0 24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
        @foreach(__('welcome.trust.items') as $item)
        <div style="padding:26px 20px;border-left:1px solid #F1ECE6;{{ $loop->last ? 'border-right:1px solid #F1ECE6' : '' }}">
            <div style="font-size:clamp(24px,3vw,32px);font-weight:800;letter-spacing:-.035em;line-height:1;color:#1C1917">{{ $item['value'] }}</div>
            <div style="font-size:13.5px;color:#78716C;margin-top:8px;line-height:1.4">{{ $item['label'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- Features --}}
<section id="features" style="max-width:1160px;margin:0 auto;padding:96px 24px 40px">
    <div style="max-width:560px;margin-bottom:48px">
        <div style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#9A6A00">{{ __('welcome.features.label') }}</div>
        <h2 style="font-size:clamp(30px,4vw,42px);letter-spacing:-.035em;line-height:1.08;font-weight:800;margin:12px 0 14px">{{ __('welcome.features.h2') }}</h2>
        <p style="font-size:16.5px;color:#78716C;line-height:1.55;margin:0">{{ __('welcome.features.subtitle') }}</p>
    </div>
    @php
        $featureIcons = [
            '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path>',
            '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>',
            '<rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>',
            '<path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2"></rect>',
            '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>',
            '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"></path><path d="M3 3v5h5"></path>',
        ];
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
        @foreach(__('welcome.features.items') as $index => $feature)
        <div class="thz-card" style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:24px">
            <div style="width:40px;height:40px;border-radius:11px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;margin-bottom:18px">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $featureIcons[$index] !!}</svg>
            </div>
            <div style="font-size:16px;font-weight:700;margin-bottom:6px">{{ $feature['title'] }}</div>
            <div style="font-size:14.5px;color:#78716C;line-height:1.55">{{ $feature['desc'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- How it works --}}
<section id="how" style="max-width:1160px;margin:0 auto;padding:72px 24px">
    <div style="text-align:center;max-width:560px;margin:0 auto 56px">
        <div style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#9A6A00">{{ __('welcome.how.label') }}</div>
        <h2 style="font-size:clamp(30px,4vw,42px);letter-spacing:-.035em;line-height:1.08;font-weight:800;margin:12px 0 0">{{ __('welcome.how.h2') }}</h2>
    </div>
    <div style="position:relative;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:32px">
        <div style="position:absolute;top:28px;left:16.66%;right:16.66%;height:2px;background:repeating-linear-gradient(90deg,#EBD69A 0 8px,transparent 8px 14px)"></div>
        @foreach(__('welcome.how.steps') as $index => $step)
        <div style="text-align:center;position:relative">
            <div style="width:56px;height:56px;margin:0 auto 20px;border-radius:16px;background:linear-gradient(135deg,#F2B81D,#F9D55C);color:#1C1917;font-size:20px;font-weight:800;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px -8px rgba(222,165,0,.55),0 0 0 6px #FAF8F5">{{ $index + 1 }}</div>
            <div style="font-size:17px;font-weight:700;margin-bottom:8px">{{ $step['title'] }}</div>
            <div style="font-size:14.5px;color:#78716C;line-height:1.55;max-width:280px;margin:0 auto">{{ $step['desc'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- Pricing --}}
@php
    $pricingCtaUrl = auth()->check() ? url('/dashboard') : route('register');
@endphp
<section id="pricing" style="background:#fff;border-top:1px solid #EAE4DD;border-bottom:1px solid #EAE4DD">
    <div style="max-width:1160px;margin:0 auto;padding:88px 24px">
        <div style="text-align:center;max-width:600px;margin:0 auto 52px">
            <div style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#9A6A00">{{ __('welcome.pricing.label') }}</div>
            <h2 style="font-size:clamp(30px,4vw,42px);letter-spacing:-.035em;line-height:1.08;font-weight:800;margin:12px 0 14px">{{ __('welcome.pricing.h2') }}</h2>
            <p style="font-size:16.5px;color:#78716C;line-height:1.55;margin:0">{{ __('welcome.pricing.subtitle') }}</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;align-items:stretch;max-width:960px;margin:0 auto">
            @foreach(config('packages') as $key => $pack)
                @php
                    $featured = $key === 'standard';
                    $currency = strtoupper(config('cashier.currency'));
                    $priceValue = rtrim(rtrim(number_format($pack['price_cents'] / 100, 2, '.', ''), '0'), '.');
                    $priceLabel = __('welcome.pricing.amount', ['value' => $priceValue]);
                    $perSessionValue = number_format($pack['price_cents'] / 100 / $pack['sessions_count'], 2, '.', '');
                    $perSessionLabel = __('welcome.pricing.amount', ['value' => $perSessionValue]);
                @endphp
                <div class="thz-price" style="position:relative;background:{{ $featured ? '#FFFDF8' : '#fff' }};border:{{ $featured ? '2px solid #F2B81D' : '1px solid #EAE4DD' }};border-radius:20px;padding:{{ $featured ? '30px 26px' : '28px 26px' }};display:flex;flex-direction:column;box-shadow:{{ $featured ? '0 24px 48px -22px rgba(222,165,0,.45)' : '0 1px 2px rgba(28,25,23,.04)' }}">
                    @if($featured)
                        <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:#F2B81D;color:#1C1917;font-size:11px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;padding:5px 14px;border-radius:999px;box-shadow:0 8px 18px -6px rgba(222,165,0,.7);white-space:nowrap">{{ __('welcome.pricing.popular_badge') }}</div>
                    @endif
                    <div style="font-size:18px;font-weight:800;letter-spacing:-.02em">{{ __('welcome.pricing.packs.'.$key.'.name') }}</div>
                    <div style="font-size:13.5px;color:#78716C;line-height:1.45;margin-top:6px;min-height:38px">{{ __('welcome.pricing.packs.'.$key.'.tagline') }}</div>

                    <div style="display:flex;align-items:baseline;gap:6px;margin:20px 0 4px">
                        <span style="font-size:44px;font-weight:800;letter-spacing:-.045em;line-height:1">{{ $priceLabel }}</span>
                        <span style="font-size:15px;font-weight:700;color:#9A6A00">{{ $currency }}</span>
                    </div>
                    <div style="font-size:13px;color:#9A6A00;font-weight:600">{{ __('welcome.pricing.sessions_count', ['count' => $pack['sessions_count']]) }} · {{ __('welcome.pricing.per_session', ['price' => $perSessionLabel]) }}</div>

                    <a href="{{ $pricingCtaUrl }}" class="{{ $featured ? 'thz-btn-primary' : 'thz-btn-ghost' }}" style="margin-top:24px;text-align:center;{{ $featured ? 'background:#F2B81D;color:#1C1917;box-shadow:0 10px 24px -8px rgba(222,165,0,.6)' : 'background:#fff;border:1px solid #E2DBD3;color:#1C1917' }};font-size:15px;font-weight:700;padding:12px 18px;border-radius:12px;text-decoration:none">{{ __('welcome.pricing.cta') }}</a>
                </div>
            @endforeach
        </div>

        {{-- Included in every pack --}}
        <div style="max-width:960px;margin:36px auto 0;background:#FCFAF8;border:1px solid #F1ECE6;border-radius:16px;padding:24px 26px">
            <div style="font-size:12px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#9A6A00;margin-bottom:16px">{{ __('welcome.pricing.included_title') }}</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px">
                @foreach(__('welcome.pricing.included') as $item)
                <div style="display:flex;align-items:flex-start;gap:10px">
                    <div style="width:20px;height:20px;border-radius:99px;background:#E8F3EC;color:#2F7D5B;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
                    </div>
                    <span style="font-size:14px;color:#44403C;line-height:1.45">{{ $item }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section id="faq" style="max-width:760px;margin:0 auto;padding:88px 24px 40px">
    <div style="text-align:center;margin-bottom:44px">
        <div style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#9A6A00">{{ __('welcome.faq.label') }}</div>
        <h2 style="font-size:clamp(28px,4vw,40px);letter-spacing:-.035em;line-height:1.08;font-weight:800;margin:12px 0 0">{{ __('welcome.faq.h2') }}</h2>
    </div>
    <div style="display:flex;flex-direction:column;gap:12px">
        @foreach(__('welcome.faq.items') as $item)
        <details class="thz-faq" style="background:#fff;border:1px solid #EAE4DD;border-radius:14px;padding:0">
            <summary style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 20px;font-size:16px;font-weight:600;color:#1C1917">
                <span>{{ $item['q'] }}</span>
                <svg class="thz-faq-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9A6A00" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"></path></svg>
            </summary>
            <div style="padding:0 20px 20px;font-size:15px;color:#78716C;line-height:1.6">{{ $item['a'] }}</div>
        </details>
        @endforeach
    </div>
</section>

{{-- Teacher CTA --}}
<section style="max-width:1160px;margin:0 auto;padding:24px 24px 88px">
    <div style="position:relative;overflow:hidden;border-radius:24px;background:linear-gradient(135deg,#1C1917 0%,#26211C 60%,#3A2E14 100%);padding:clamp(36px,6vw,64px);color:#fff">
        <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.07) 1px,transparent 1px);background-size:36px 36px;mask-image:linear-gradient(90deg,transparent,#000 60%);-webkit-mask-image:linear-gradient(90deg,transparent,#000 60%)"></div>
        <div style="position:absolute;right:40px;bottom:-70px;font-size:300px;line-height:1;color:#fff;opacity:.08;font-weight:700">ⵣ</div>
        <div style="position:relative;max-width:560px">
            <div style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#F2B81D">{{ __('welcome.teacher_cta.label') }}</div>
            <h2 style="font-size:clamp(28px,4vw,40px);letter-spacing:-.035em;line-height:1.08;font-weight:800;margin:12px 0 14px">{{ __('welcome.teacher_cta.h2') }}</h2>
            <p style="font-size:16.5px;line-height:1.55;color:#D6D0C7;margin:0 0 28px">{{ __('welcome.teacher_cta.subtitle') }}</p>
            <a href="{{ route('teacher.register') }}" class="thz-btn-primary" style="display:inline-flex;background:#F2B81D;color:#1C1917;font-size:15px;font-weight:700;padding:14px 22px;border-radius:12px;box-shadow:0 10px 24px -8px rgba(0,0,0,.35);text-decoration:none">{{ __('welcome.teacher_cta.cta', ['app' => config('app.name')]) }}</a>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer style="border-top:1px solid #EAE4DD">
    <div style="max-width:1160px;margin:0 auto;padding:36px 24px;display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between">
        <div style="display:flex;align-items:center;gap:12px">
            <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:15px;font-weight:700">ⵣ</div>
            <div>
                <div style="font-size:15px;font-weight:700;line-height:1.2">{{ config('app.name') }}</div>
                <div style="font-size:12.5px;color:#A8A29E;line-height:1.3">{{ __('welcome.footer.tagline') }}</div>
            </div>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:22px;font-size:14px">
            <a href="#features" style="color:#78716C">{{ __('welcome.nav.features') }}</a>
            <a href="#how" style="color:#78716C">{{ __('welcome.nav.how_it_works') }}</a>
            <a href="#pricing" style="color:#78716C">{{ __('welcome.nav.pricing') }}</a>
            <a href="{{ route('teacher.register') }}" style="color:#78716C">{{ __('welcome.nav.teach') }}</a>
            <a href="{{ route('login') }}" style="color:#78716C">{{ __('welcome.nav.login') }}</a>
        </div>
        <div style="font-size:13px;color:#A8A29E">{{ __('welcome.footer.copyright', ['year' => date('Y'), 'app' => config('app.name')]) }}</div>
    </div>
</footer>

</body>
</html>
