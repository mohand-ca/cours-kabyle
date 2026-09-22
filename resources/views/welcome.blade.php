<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Apprenez le kabyle en ligne</title>
    <meta name="description" content="Plateforme d'apprentissage du kabyle avec des enseignants natifs basés en Algérie. Cours en ligne, réservation simple, paiement sécurisé.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-white antialiased" style="font-family: 'Inter', system-ui, sans-serif;">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 h-14 flex items-center px-6 justify-between">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-600 to-indigo-500 flex-shrink-0"></div>
            <span class="font-semibold text-gray-900 text-sm">{{ config('app.name') }}</span>
        </div>
        <div class="flex items-center gap-2">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="px-4 py-1.5 text-sm font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition-colors">
                    Mon espace →
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-3 py-1.5 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                    Se connecter
                </a>
                <a href="{{ route('register') }}"
                    class="px-4 py-1.5 text-sm font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition-colors">
                    Commencer
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-4xl mx-auto px-6 pt-20 pb-16 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium text-violet-700 bg-violet-50 border border-violet-100 rounded-full mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span>
            Cours en ligne avec des locuteurs natifs
        </div>
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
            Apprenez le <span class="text-violet-600">kabyle</span><br>avec des enseignants natifs
        </h1>
        <p class="mt-6 text-lg text-gray-500 max-w-xl mx-auto leading-relaxed">
            Des enseignants basés en Algérie, disponibles pour des cours particuliers en ligne.
            Réservation simple, paiement sécurisé, annulation libre.
        </p>
        <div class="mt-8 flex items-center justify-center gap-3">
            <a href="{{ route('register') }}"
                class="px-6 py-3 text-sm font-semibold text-white bg-violet-600 rounded-xl hover:bg-violet-700 transition-colors shadow-sm">
                Commencer gratuitement
            </a>
            <a href="{{ route('teacher.register') }}"
                class="px-6 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:border-violet-300 hover:text-violet-600 transition-colors">
                Je suis enseignant →
            </a>
        </div>
    </section>

    {{-- How it works --}}
    <section class="max-w-4xl mx-auto px-6 py-16 border-t border-gray-100">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-widest text-center mb-10">Comment ça marche</h2>
        <div class="grid grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center mx-auto mb-4">
                    <span class="text-lg font-bold text-violet-600">1</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Créez votre compte</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Inscrivez-vous en 2 minutes. Ajoutez les profils des apprenants de votre famille.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center mx-auto mb-4">
                    <span class="text-lg font-bold text-violet-600">2</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Choisissez un enseignant</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Parcourez les profils, consultez les disponibilités et réservez votre créneau.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center mx-auto mb-4">
                    <span class="text-lg font-bold text-violet-600">3</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Commencez à apprendre</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Le cours se tient via Google Meet. Annulation libre jusqu'à 24h avant la séance.</p>
            </div>
        </div>
    </section>

    {{-- Why kabyle --}}
    <section class="max-w-4xl mx-auto px-6 py-16 border-t border-gray-100">
        <div class="grid grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-snug">
                    Une langue vivante,<br>un héritage à transmettre
                </h2>
                <p class="mt-4 text-sm text-gray-500 leading-relaxed">
                    Le kabyle (taqbaylit) est une langue berbère parlée par des millions de personnes
                    en Algérie et dans la diaspora. Apprendre avec un locuteur natif est la voie
                    la plus directe vers une maîtrise authentique.
                </p>
                <a href="{{ route('register') }}" class="inline-block mt-6 text-sm font-semibold text-violet-600 hover:text-violet-700">
                    Trouver un enseignant →
                </a>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-violet-50 rounded-2xl p-5">
                    <p class="text-2xl font-bold text-violet-600">100%</p>
                    <p class="text-xs text-gray-600 mt-1">cours particuliers<br>avec un natif</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-2xl font-bold text-gray-900">60 min</p>
                    <p class="text-xs text-gray-600 mt-1">par séance,<br>à votre rythme</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-2xl font-bold text-gray-900">En ligne</p>
                    <p class="text-xs text-gray-600 mt-1">depuis n'importe<br>où dans le monde</p>
                </div>
                <div class="bg-indigo-50 rounded-2xl p-5">
                    <p class="text-2xl font-bold text-indigo-600">Libre</p>
                    <p class="text-xs text-gray-600 mt-1">annulation<br>sans frais</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA enseignant --}}
    <section class="max-w-4xl mx-auto px-6 py-16 border-t border-gray-100">
        <div class="bg-gradient-to-br from-violet-600 to-indigo-600 rounded-3xl px-10 py-12 text-center text-white">
            <h2 class="text-2xl font-bold">Vous parlez kabyle ?</h2>
            <p class="mt-3 text-sm text-violet-100 max-w-md mx-auto">
                Rejoignez notre équipe d'enseignants. Définissez vos propres disponibilités et aidez la diaspora à se reconnecter à ses racines.
            </p>
            <a href="{{ route('teacher.register') }}"
                class="inline-block mt-6 px-6 py-3 text-sm font-semibold text-violet-700 bg-white rounded-xl hover:bg-violet-50 transition-colors">
                Devenir enseignant →
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-8 px-6">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded-md bg-gradient-to-br from-violet-600 to-indigo-500 flex-shrink-0"></div>
                <span class="text-xs font-semibold text-gray-500">{{ config('app.name') }}</span>
            </div>
            <p class="text-xs text-gray-400">
                © {{ date('Y') }} · Tous droits réservés
            </p>
        </div>
    </footer>

</body>
</html>
