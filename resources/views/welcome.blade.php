<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Apprenez le kabyle avec des natifs</title>
    <meta name="description" content="Plateforme d'apprentissage du kabyle. Cours particuliers en ligne avec des enseignants natifs basés en Algérie.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .hero-glow {
            position: absolute;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 900px;
            height: 600px;
            background: radial-gradient(ellipse at center, rgba(139, 92, 246, 0.12) 0%, rgba(99, 102, 241, 0.06) 40%, transparent 70%);
            pointer-events: none;
        }
        .grid-pattern {
            background-image: linear-gradient(rgba(139, 92, 246, 0.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(139, 92, 246, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-white antialiased overflow-x-hidden">

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100/80 h-14 flex items-center px-6 justify-between">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-600 to-indigo-500 flex-shrink-0 shadow-sm shadow-violet-200"></div>
            <span class="font-bold text-gray-900 text-sm tracking-tight">{{ config('app.name') }}</span>
        </div>
        <div class="hidden md:flex items-center gap-6">
            <a href="#features" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Fonctionnalités</a>
            <a href="#how" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Comment ça marche</a>
            <a href="{{ route('teacher.register') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Enseigner</a>
        </div>
        <div class="flex items-center gap-2">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="px-4 py-1.5 text-sm font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition-colors shadow-sm shadow-violet-200">
                    Mon espace →
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-3 py-1.5 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                    Se connecter
                </a>
                <a href="{{ route('register') }}"
                    class="px-4 py-1.5 text-sm font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition-colors shadow-sm shadow-violet-200">
                    Commencer gratuitement
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative pt-32 pb-24 overflow-hidden grid-pattern">
        <div class="hero-glow"></div>
        <div class="relative max-w-4xl mx-auto px-6 text-center">

            <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold text-violet-700 bg-violet-50 border border-violet-200/60 rounded-full mb-8 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-violet-500"></span>
                </span>
                Enseignants disponibles maintenant
            </div>

            <h1 class="text-5xl sm:text-6xl font-black text-gray-900 tracking-tighter leading-none mb-6">
                Apprenez le<br>
                <span class="bg-gradient-to-r from-violet-600 to-indigo-500 bg-clip-text text-transparent">kabyle</span><br>
                avec un natif
            </h1>

            <p class="text-lg text-gray-500 max-w-lg mx-auto leading-relaxed mb-10">
                Des cours particuliers en ligne avec des enseignants basés en Algérie.
                Réservation en 2 clics, paiement sécurisé, annulation libre.
            </p>

            <div class="flex items-center justify-center gap-3 mb-16">
                <a href="{{ route('register') }}"
                    class="group px-6 py-3 text-sm font-bold text-white bg-violet-600 rounded-xl hover:bg-violet-700 transition-all shadow-lg shadow-violet-200 hover:shadow-violet-300 hover:scale-105">
                    Commencer gratuitement
                    <span class="ml-1 group-hover:translate-x-0.5 inline-block transition-transform">→</span>
                </a>
                <a href="{{ route('teacher.register') }}"
                    class="px-6 py-3 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:border-violet-300 hover:text-violet-700 transition-all shadow-sm">
                    Je suis enseignant
                </a>
            </div>

            {{-- Social proof --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-400">
                <div class="flex -space-x-2">
                    @foreach(['A','B','C','D'] as $i => $letter)
                        @php $colors = ['from-violet-400 to-indigo-500', 'from-indigo-400 to-blue-500', 'from-blue-400 to-cyan-500', 'from-cyan-400 to-teal-500']; @endphp
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br {{ $colors[$i] }} border-2 border-white flex items-center justify-center text-white text-xs font-bold">{{ $letter }}</div>
                    @endforeach
                </div>
                <span>Rejoignez des dizaines d'apprenants de la diaspora</span>
            </div>
        </div>

        {{-- UI mockup --}}
        <div class="relative max-w-3xl mx-auto mt-16 px-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl shadow-gray-200/80 overflow-hidden">
                {{-- Fake browser chrome --}}
                <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center gap-3">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-300"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-300"></div>
                        <div class="w-3 h-3 rounded-full bg-green-300"></div>
                    </div>
                    <div class="flex-1 bg-white border border-gray-200 rounded-md px-3 py-1 text-xs text-gray-400 max-w-xs mx-auto text-center">
                        app.thamazight.com/dashboard
                    </div>
                </div>
                {{-- Fake dashboard content --}}
                <div class="p-5 bg-gray-50">
                    <div class="mb-4">
                        <div class="h-6 w-48 bg-gray-200 rounded-lg mb-2"></div>
                        <div class="h-3 w-64 bg-gray-100 rounded"></div>
                    </div>
                    <div class="grid grid-cols-4 gap-3 mb-4">
                        @foreach(['violet', 'violet', 'amber', 'blue'] as $color)
                            <div class="bg-white rounded-xl border border-gray-100 p-4">
                                <div class="flex justify-between mb-3">
                                    <div class="h-2.5 w-16 bg-gray-100 rounded"></div>
                                    <div class="w-6 h-6 rounded-lg bg-{{ $color }}-50"></div>
                                </div>
                                <div class="h-7 w-10 bg-gray-200 rounded mb-1"></div>
                                <div class="h-2 w-20 bg-gray-100 rounded"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-5 gap-3">
                        <div class="col-span-3 bg-white rounded-xl border border-gray-100 p-4">
                            <div class="h-3 w-32 bg-gray-200 rounded mb-4"></div>
                            @foreach([1,2,3] as $_)
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-violet-50"></div>
                                    <div class="flex-1">
                                        <div class="h-2.5 w-24 bg-gray-100 rounded mb-1.5"></div>
                                        <div class="h-2 w-32 bg-gray-50 rounded"></div>
                                    </div>
                                    <div class="h-5 w-16 bg-blue-50 rounded-full"></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-span-2 bg-white rounded-xl border border-gray-100 p-4">
                            <div class="h-3 w-24 bg-gray-200 rounded mb-4"></div>
                            @foreach([1,2] as $_)
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-100 to-indigo-100"></div>
                                    <div>
                                        <div class="h-2.5 w-16 bg-gray-100 rounded mb-1.5"></div>
                                        <div class="h-2 w-12 bg-gray-50 rounded"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats bar --}}
    <section class="border-y border-gray-100 bg-gray-50/50 py-8">
        <div class="max-w-4xl mx-auto px-6">
            <div class="grid grid-cols-4 gap-8 text-center">
                <div>
                    <p class="text-2xl font-black text-gray-900 tracking-tight">100%</p>
                    <p class="text-xs text-gray-500 mt-1">natifs kabyle</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900 tracking-tight">60 min</p>
                    <p class="text-xs text-gray-500 mt-1">par séance</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900 tracking-tight">En ligne</p>
                    <p class="text-xs text-gray-500 mt-1">depuis partout</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900 tracking-tight">Libre</p>
                    <p class="text-xs text-gray-500 mt-1">annulation sans frais</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="max-w-4xl mx-auto px-6 py-20">
        <div class="text-center mb-14">
            <p class="text-xs font-semibold text-violet-600 uppercase tracking-widest mb-3">Fonctionnalités</p>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Tout ce qu'il vous faut<br>pour apprendre</h2>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-violet-200 hover:shadow-md transition-all group">
                <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center mb-5 group-hover:bg-violet-100 transition-colors">
                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Enseignants vérifiés</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Chaque enseignant est examiné par notre équipe avant d'être publié sur la plateforme.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-violet-200 hover:shadow-md transition-all group">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center mb-5 group-hover:bg-indigo-100 transition-colors">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Réservation en 2 clics</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Consultez les disponibilités en temps réel et réservez un créneau sans aller-retour email.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-violet-200 hover:shadow-md transition-all group">
                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center mb-5 group-hover:bg-green-100 transition-colors">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Paiement sécurisé</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Achetez des forfaits de séances via Stripe. Vos séances sont créditées immédiatement.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-violet-200 hover:shadow-md transition-all group">
                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center mb-5 group-hover:bg-blue-100 transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Cours via Google Meet</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Chaque enseignant partage son lien Meet. Aucune installation requise.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-violet-200 hover:shadow-md transition-all group">
                <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center mb-5 group-hover:bg-amber-100 transition-colors">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Profils famille</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Gérez les profils de toute votre famille (enfants, conjoint) depuis un seul compte.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-violet-200 hover:shadow-md transition-all group">
                <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center mb-5 group-hover:bg-rose-100 transition-colors">
                    <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Annulation libre</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Annulez une séance et récupérez votre crédit instantanément. Sans question.</p>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how" class="bg-gray-50/60 border-y border-gray-100 py-20">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-xs font-semibold text-violet-600 uppercase tracking-widest mb-3">Processus</p>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">De zéro à votre premier cours<br>en moins de 5 minutes</h2>
            </div>
            <div class="grid grid-cols-3 gap-10 relative">
                <div class="absolute top-6 left-[22%] right-[22%] h-px bg-gradient-to-r from-violet-200 via-indigo-200 to-violet-200 hidden md:block"></div>
                <div class="text-center relative">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center mx-auto mb-5 shadow-lg shadow-violet-200">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-2">1. Créez votre compte</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Inscrivez-vous et ajoutez les profils des apprenants de votre famille.</p>
                </div>
                <div class="text-center relative">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center mx-auto mb-5 shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-2">2. Choisissez un enseignant</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Parcourez les profils, filtrez par niveau, et réservez un créneau disponible.</p>
                </div>
                <div class="text-center relative">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center mx-auto mb-5 shadow-lg shadow-blue-200">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-2">3. Apprenez</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Rejoignez le cours via Google Meet à l'heure convenue. C'est tout.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA enseignant --}}
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="relative overflow-hidden bg-gradient-to-br from-violet-600 via-violet-600 to-indigo-600 rounded-3xl px-10 py-14 text-center">
            <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(255,255,255,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.3) 1px, transparent 1px); background-size: 30px 30px;"></div>
            <div class="relative">
                <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold text-violet-200 bg-white/10 border border-white/20 rounded-full mb-6">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Pour les enseignants
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight mb-4">Vous parlez kabyle couramment ?</h2>
                <p class="text-violet-200 text-sm max-w-md mx-auto leading-relaxed mb-8">
                    Rejoignez notre équipe d'enseignants. Définissez vos propres disponibilités,
                    enseignez depuis chez vous, et aidez la diaspora à se reconnecter à ses racines.
                </p>
                <a href="{{ route('teacher.register') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-violet-700 bg-white rounded-xl hover:bg-violet-50 transition-colors shadow-lg">
                    Devenir enseignant
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-10 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-gradient-to-br from-violet-600 to-indigo-500 flex-shrink-0"></div>
                    <span class="text-sm font-bold text-gray-700">{{ config('app.name') }}</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-gray-700 transition-colors">Se connecter</a>
                    <a href="{{ route('register') }}" class="text-xs text-gray-400 hover:text-gray-700 transition-colors">S'inscrire</a>
                    <a href="{{ route('teacher.register') }}" class="text-xs text-gray-400 hover:text-gray-700 transition-colors">Devenir enseignant</a>
                </div>
                <p class="text-xs text-gray-300">© {{ date('Y') }} {{ config('app.name') }}</p>
            </div>
        </div>
    </footer>

</body>
</html>
