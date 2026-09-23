<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Apprenez le kabyle avec des natifs</title>
    <meta name="description" content="Cours particuliers de kabyle en ligne avec des enseignants natifs basés en Algérie.">
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
    </style>
</head>
<body>

{{-- Navbar --}}
<header style="position:sticky;top:0;z-index:40;background:rgba(250,248,245,.85);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid #EAE4DD">
    <div style="max-width:1160px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;gap:24px">
        <a href="{{ route('welcome') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0">
            <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:18px;font-weight:700;box-shadow:0 4px 12px -4px rgba(222,165,0,.6)">ⵣ</div>
            <span style="font-size:17px;font-weight:700;letter-spacing:-.02em;color:#1C1917">{{ config('app.name') }}</span>
        </a>
        <nav style="display:flex;gap:4px;flex:1">
            <a href="#features" style="padding:7px 12px;border-radius:999px;font-size:14px;font-weight:500;color:#57534E;transition:background .15s">Fonctionnalités</a>
            <a href="#how" style="padding:7px 12px;border-radius:999px;font-size:14px;font-weight:500;color:#57534E;transition:background .15s">Comment ça marche</a>
            <a href="{{ route('teacher.register') }}" style="padding:7px 12px;border-radius:999px;font-size:14px;font-weight:500;color:#57534E;transition:background .15s">Enseigner</a>
        </nav>
        <div style="display:flex;gap:8px;align-items:center">
            @auth
                <a href="{{ url('/dashboard') }}" style="background:#F2B81D;color:#1C1917;font-size:14px;font-weight:700;padding:9px 15px;border-radius:12px;box-shadow:0 6px 16px -6px rgba(222,165,0,.6)">Mon espace →</a>
            @else
                <a href="{{ route('login') }}" style="background:transparent;border:1px solid #E2DBD3;color:#44403C;font-size:14px;font-weight:600;padding:8px 14px;border-radius:12px">Se connecter</a>
                <a href="{{ route('register') }}" style="background:#F2B81D;color:#1C1917;font-size:14px;font-weight:700;padding:9px 15px;border-radius:12px;box-shadow:0 6px 16px -6px rgba(222,165,0,.6)">Commencer</a>
            @endauth
        </div>
    </div>
</header>

{{-- Hero --}}
<section style="position:relative;overflow:hidden">
    <div style="position:absolute;right:-60px;top:-40px;font-size:520px;line-height:1;color:#9A6A00;opacity:.035;font-weight:700;pointer-events:none;user-select:none">ⵣ</div>
    <div style="max-width:1160px;margin:0 auto;padding:72px 24px 64px;display:flex;flex-wrap:wrap;gap:56px;align-items:center;position:relative">

        {{-- Left col --}}
        <div style="flex:1 1 440px;min-width:0">
            <div style="display:inline-flex;align-items:center;gap:10px;padding:6px 12px 6px 10px;border-radius:999px;background:#fff;border:1px solid #EAE4DD;font-size:13px;font-weight:500;color:#44403C;box-shadow:0 1px 2px rgba(28,25,23,.04)">
                <span style="position:relative;width:10px;height:10px;flex-shrink:0">
                    <span style="position:absolute;inset:0;border-radius:999px;background:#F2B81D;opacity:.7;animation:thzPing 1.5s ease-out infinite"></span>
                    <span style="position:relative;width:10px;height:10px;border-radius:999px;background:#E0A800;display:block"></span>
                </span>
                <span><b style="font-weight:700;color:#1C1917">23 enseignants</b> ont des créneaux disponibles cette semaine</span>
            </div>

            <h1 style="font-size:clamp(40px,6vw,64px);line-height:1.02;letter-spacing:-.045em;font-weight:800;margin:24px 0 20px;text-wrap:balance">
                Parlez <span style="background:linear-gradient(120deg,#A87200,#D39B12);-webkit-background-clip:text;background-clip:text;color:transparent">kabyle</span><br>avec ceux qui ont grandi avec.
            </h1>

            <p style="font-size:18px;line-height:1.55;color:#78716C;font-weight:400;margin:0 0 32px;max-width:520px;text-wrap:pretty">
                Cours particuliers en ligne avec des enseignants natifs en Algérie. Réservez un créneau en quelques secondes, apprenez sur Google Meet.
            </p>

            <div style="display:flex;flex-wrap:wrap;gap:12px">
                <a href="{{ route('register') }}" style="background:#F2B81D;color:#1C1917;font-size:15px;font-weight:700;padding:14px 22px;border-radius:12px;box-shadow:0 10px 24px -8px rgba(222,165,0,.6);display:flex;align-items:center;gap:8px;text-decoration:none">
                    Commencer gratuitement
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"></path></svg>
                </a>
                <a href="{{ route('teacher.register') }}" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font-size:15px;font-weight:600;padding:14px 22px;border-radius:12px;text-decoration:none">Je suis enseignant</a>
            </div>

            <div style="display:flex;align-items:center;gap:14px;margin-top:36px">
                <div style="display:flex">
                    <div style="width:34px;height:34px;border-radius:999px;border:2px solid #FAF8F5;background:linear-gradient(135deg,#F2B81D,#F9D55C);color:#1C1917;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center">SM</div>
                    <div style="width:34px;height:34px;border-radius:999px;border:2px solid #FAF8F5;margin-left:-10px;background:linear-gradient(135deg,#3F6E5E,#8BAE7C);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center">AK</div>
                    <div style="width:34px;height:34px;border-radius:999px;border:2px solid #FAF8F5;margin-left:-10px;background:linear-gradient(135deg,#5B4B8A,#A77BB5);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center">NB</div>
                    <div style="width:34px;height:34px;border-radius:999px;border:2px solid #FAF8F5;margin-left:-10px;background:linear-gradient(135deg,#7A5C3E,#C49A6C);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center">YT</div>
                </div>
                <div style="font-size:13px;color:#78716C;line-height:1.4"><b style="color:#1C1917;font-weight:600">2 400+ apprenants</b> dans 31 pays<br>qui se reconnectent à leur langue</div>
            </div>
        </div>

        {{-- Right col — UI mockup --}}
        <div style="flex:1 1 400px;min-width:0;position:relative;padding:20px 8px">
            <div style="background:#fff;border:1px solid #EAE4DD;border-radius:20px;box-shadow:0 30px 60px -24px rgba(28,25,23,.22),0 0 0 6px rgba(255,255,255,.6);overflow:hidden">
                <div style="display:flex;align-items:center;gap:6px;padding:12px 16px;border-bottom:1px solid #F1ECE6">
                    <div style="width:9px;height:9px;border-radius:99px;background:#E7E0D8"></div>
                    <div style="width:9px;height:9px;border-radius:99px;background:#E7E0D8"></div>
                    <div style="width:9px;height:9px;border-radius:99px;background:#E7E0D8"></div>
                    <div style="margin-left:12px;height:8px;width:120px;border-radius:99px;background:#F1ECE6"></div>
                </div>
                <div style="padding:18px;display:flex;flex-direction:column;gap:12px">
                    <div style="display:flex;gap:8px">
                        <div style="height:30px;flex:1;border-radius:10px;border:1px solid #EAE4DD"></div>
                        <div style="height:30px;width:90px;border-radius:10px;border:1px solid #EAE4DD"></div>
                    </div>
                    <div style="border:1px solid #EAE4DD;border-radius:14px;padding:14px;display:flex;gap:12px;align-items:flex-start">
                        <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#F2B81D,#F9D55C);color:#1C1917;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">KA</div>
                        <div style="flex:1;display:flex;flex-direction:column;gap:7px">
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <div style="font-size:13px;font-weight:700">Kahina Aït Ali</div>
                                <div style="font-size:10px;font-weight:600;color:#2F7D5B;background:#E8F3EC;padding:2px 8px;border-radius:99px">4 créneaux</div>
                            </div>
                            <div style="height:6px;width:90%;border-radius:99px;background:#F1ECE6"></div>
                            <div style="height:6px;width:65%;border-radius:99px;background:#F1ECE6"></div>
                        </div>
                    </div>
                    <div style="border-radius:12px;background:#FCFAF8;border:1px solid #F1ECE6;overflow:hidden">
                        <div style="display:flex;align-items:center;gap:12px;padding:10px 12px">
                            <div style="text-align:center;width:28px">
                                <div style="font-size:14px;font-weight:700;color:#9A6A00;line-height:1">24</div>
                                <div style="font-size:9px;color:#A8A29E;text-transform:uppercase">Sep</div>
                            </div>
                            <div style="width:1px;height:22px;background:#EAE4DD"></div>
                            <div style="flex:1;font-size:12px;font-weight:600">18:00 – 19:00</div>
                            <div style="font-size:11px;font-weight:700;color:#1C1917;background:#F2B81D;padding:5px 10px;border-radius:8px">Réserver</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-top:1px solid #F1ECE6">
                            <div style="text-align:center;width:28px">
                                <div style="font-size:14px;font-weight:700;color:#9A6A00;line-height:1">26</div>
                                <div style="font-size:9px;color:#A8A29E;text-transform:uppercase">Sep</div>
                            </div>
                            <div style="width:1px;height:22px;background:#EAE4DD"></div>
                            <div style="flex:1;font-size:12px;font-weight:600">10:00 – 11:00</div>
                            <div style="font-size:11px;font-weight:600;color:#57534E;border:1px solid #E2DBD3;padding:4px 10px;border-radius:8px">Réserver</div>
                        </div>
                    </div>
                    <div style="border:1px solid #EAE4DD;border-radius:14px;padding:14px;display:flex;gap:12px;align-items:center;opacity:.6">
                        <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#3F6E5E,#8BAE7C);flex-shrink:0"></div>
                        <div style="flex:1;display:flex;flex-direction:column;gap:7px">
                            <div style="height:7px;width:40%;border-radius:99px;background:#E7E0D8"></div>
                            <div style="height:6px;width:80%;border-radius:99px;background:#F1ECE6"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Floating badge --}}
            <div style="position:absolute;left:-12px;bottom:-4px;background:#fff;border:1px solid #EAE4DD;border-radius:14px;padding:10px 14px;display:flex;align-items:center;gap:10px;box-shadow:0 16px 32px -12px rgba(28,25,23,.25);animation:thzFloat 5s ease-in-out infinite">
                <div style="width:30px;height:30px;border-radius:9px;background:#E8F3EC;color:#2F7D5B;display:flex;align-items:center;justify-content:center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
                </div>
                <div>
                    <div style="font-size:12.5px;font-weight:700">Cours confirmé</div>
                    <div style="font-size:11.5px;color:#78716C">Jeu 24 Sep · 18:00 · Google Meet</div>
                </div>
            </div>

            {{-- Azul badge --}}
            <div style="position:absolute;right:-4px;top:0;background:#1C1917;color:#FAF8F5;border-radius:12px;padding:8px 12px;font-size:12px;font-weight:600;box-shadow:0 12px 24px -10px rgba(28,25,23,.4)">Azul! <span style="color:#A8A29E;font-weight:400">· Bonjour</span></div>
        </div>
    </div>
</section>

{{-- Stats bar --}}
<section style="border-top:1px solid #EAE4DD;border-bottom:1px solid #EAE4DD;background:#fff">
    <div style="max-width:1160px;margin:0 auto;padding:0 24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
        <div style="padding:28px 20px;border-left:1px solid #F1ECE6">
            <div style="font-size:34px;font-weight:800;letter-spacing:-.04em">40+</div>
            <div style="font-size:13.5px;color:#78716C;margin-top:4px">Enseignants kabyle natifs</div>
        </div>
        <div style="padding:28px 20px;border-left:1px solid #F1ECE6">
            <div style="font-size:34px;font-weight:800;letter-spacing:-.04em">60 min</div>
            <div style="font-size:13.5px;color:#78716C;margin-top:4px">Cours individuels ciblés</div>
        </div>
        <div style="padding:28px 20px;border-left:1px solid #F1ECE6">
            <div style="font-size:34px;font-weight:800;letter-spacing:-.04em">Kabylie</div>
            <div style="font-size:13.5px;color:#78716C;margin-top:4px">Enseignants basés en Algérie</div>
        </div>
        <div style="padding:28px 20px;border-left:1px solid #F1ECE6;border-right:1px solid #F1ECE6">
            <div style="font-size:34px;font-weight:800;letter-spacing:-.04em">24h</div>
            <div style="font-size:13.5px;color:#78716C;margin-top:4px">Annulation gratuite</div>
        </div>
    </div>
</section>

{{-- Features --}}
<section id="features" style="max-width:1160px;margin:0 auto;padding:96px 24px 40px">
    <div style="max-width:560px;margin-bottom:48px">
        <div style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#9A6A00">Pourquoi Thamazight</div>
        <h2 style="font-size:clamp(30px,4vw,42px);letter-spacing:-.035em;line-height:1.08;font-weight:800;margin:12px 0 14px">Tout ce qu'il faut pour apprendre, rien de superflu.</h2>
        <p style="font-size:16.5px;color:#78716C;line-height:1.55;margin:0">Conçu pour les héritiers de la langue, les curieux et les familles qui veulent transmettre.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
        @foreach([
            ['icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path>', 'title' => 'Enseignants vérifiés', 'desc' => 'Chaque profil est examiné par notre équipe avant publication. Natifs uniquement.'],
            ['icon' => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>', 'title' => 'Réservation instantanée', 'desc' => 'Consultez les créneaux réels et réservez en deux clics. Sans aller-retour email.'],
            ['icon' => '<rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>', 'title' => 'Paiement sécurisé', 'desc' => 'Achetez des forfaits de séances par carte. Les enseignants sont payés après chaque cours.'],
            ['icon' => '<path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2"></rect>', 'title' => 'Cours sur Google Meet', 'desc' => 'Rejoignez depuis n\'importe quel appareil avec un lien. Rien à installer, rien à configurer.'],
            ['icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>', 'title' => 'Profils famille', 'desc' => 'Un compte, un solde, un profil pour chaque enfant, conjoint ou parent.'],
            ['icon' => '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"></path><path d="M3 3v5h5"></path>', 'title' => 'Annulation libre', 'desc' => 'Les plans changent. Annulez jusqu\'à 24h avant et la séance revient à votre solde.'],
        ] as $feature)
        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:24px;transition:all .2s">
            <div style="width:40px;height:40px;border-radius:11px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;margin-bottom:18px">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $feature['icon'] !!}</svg>
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
        <div style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#9A6A00">Comment ça marche</div>
        <h2 style="font-size:clamp(30px,4vw,42px);letter-spacing:-.035em;line-height:1.08;font-weight:800;margin:12px 0 0">De l'inscription à votre premier « Azul » en quelques minutes.</h2>
    </div>
    <div style="position:relative;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:32px">
        <div style="position:absolute;top:28px;left:16.66%;right:16.66%;height:2px;background:repeating-linear-gradient(90deg,#EBD69A 0 8px,transparent 8px 14px)"></div>
        @foreach([
            ['n' => '1', 'title' => 'Créez votre compte', 'desc' => 'Ajoutez un profil pour vous et tous les membres de votre famille qui souhaitent apprendre.'],
            ['n' => '2', 'title' => 'Choisissez un enseignant', 'desc' => 'Filtrez par niveau, lisez les biographies et réservez un créneau qui correspond à votre semaine.'],
            ['n' => '3', 'title' => 'Apprenez sur Google Meet', 'desc' => 'Recevez le lien par email, rejoignez à l\'heure et parlez dès la première minute.'],
        ] as $step)
        <div style="text-align:center;position:relative">
            <div style="width:56px;height:56px;margin:0 auto 20px;border-radius:16px;background:linear-gradient(135deg,#F2B81D,#F9D55C);color:#1C1917;font-size:20px;font-weight:800;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px -8px rgba(222,165,0,.55),0 0 0 6px #FAF8F5">{{ $step['n'] }}</div>
            <div style="font-size:17px;font-weight:700;margin-bottom:8px">{{ $step['title'] }}</div>
            <div style="font-size:14.5px;color:#78716C;line-height:1.55;max-width:280px;margin:0 auto">{{ $step['desc'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- Teacher CTA --}}
<section style="max-width:1160px;margin:0 auto;padding:24px 24px 88px">
    <div style="position:relative;overflow:hidden;border-radius:24px;background:linear-gradient(135deg,#1C1917 0%,#26211C 60%,#3A2E14 100%);padding:clamp(36px,6vw,64px);color:#fff">
        <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.07) 1px,transparent 1px);background-size:36px 36px;mask-image:linear-gradient(90deg,transparent,#000 60%);-webkit-mask-image:linear-gradient(90deg,transparent,#000 60%)"></div>
        <div style="position:absolute;right:40px;bottom:-70px;font-size:300px;line-height:1;color:#fff;opacity:.08;font-weight:700">ⵣ</div>
        <div style="position:relative;max-width:560px">
            <div style="font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#F2B81D">Pour les enseignants</div>
            <h2 style="font-size:clamp(28px,4vw,40px);letter-spacing:-.035em;line-height:1.08;font-weight:800;margin:12px 0 14px">Enseignez le kabyle à des apprenants du monde entier — depuis chez vous.</h2>
            <p style="font-size:16.5px;line-height:1.55;color:#D6D0C7;margin:0 0 28px">Définissez vos horaires, soyez réservé automatiquement et soyez payé après chaque cours. Nous gérons les paiements, rappels et planification.</p>
            <a href="{{ route('teacher.register') }}" style="display:inline-flex;background:#F2B81D;color:#1C1917;font-size:15px;font-weight:700;padding:14px 22px;border-radius:12px;box-shadow:0 10px 24px -8px rgba(0,0,0,.35);text-decoration:none">Enseigner avec Thamazight →</a>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer style="border-top:1px solid #EAE4DD">
    <div style="max-width:1160px;margin:0 auto;padding:32px 24px;display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-size:15px;font-weight:700">ⵣ</div>
            <span style="font-size:15px;font-weight:700">{{ config('app.name') }}</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:22px;font-size:14px">
            <a href="#features" style="color:#78716C">Fonctionnalités</a>
            <a href="#how" style="color:#78716C">Comment ça marche</a>
            <a href="{{ route('teacher.register') }}" style="color:#78716C">Enseigner</a>
            <a href="{{ route('login') }}" style="color:#78716C">Se connecter</a>
        </div>
        <div style="font-size:13px;color:#A8A29E">© {{ date('Y') }} {{ config('app.name') }}. Tanemmirt.</div>
    </div>
</footer>

</body>
</html>
