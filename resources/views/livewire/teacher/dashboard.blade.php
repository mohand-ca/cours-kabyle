<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('teacher.dashboard.title') }}, {{ Auth::user()->name }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('teacher.dashboard.subtitle') }}</p>
    </div>

    {{-- Status banner --}}
    @if(!$profile?->isApproved())
        <div class="mb-6 rounded-xl border px-5 py-4 flex items-center gap-4
            {{ $profile?->submitted_at ? 'border-amber-200 bg-amber-50' : 'border-violet-200 bg-violet-50' }}">
            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                {{ $profile?->submitted_at ? 'bg-amber-100' : 'bg-violet-100' }}">
                @if($profile?->submitted_at)
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                    <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium {{ $profile?->submitted_at ? 'text-amber-800' : 'text-violet-800' }}">
                    @if($profile?->submitted_at)
                        {{ __('teacher.dashboard.profile_pending') }}
                    @else
                        {{ __('teacher.dashboard.profile_incomplete') }}
                    @endif
                </p>
            </div>
            @if(!$profile?->submitted_at)
                <a href="{{ route('teacher.profile') }}"
                    class="flex-shrink-0 px-4 py-1.5 text-xs font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition-colors">
                    {{ __('teacher.dashboard.complete_profile') }}
                </a>
            @endif
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm ring-1 ring-gray-100/50 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Séances à venir</p>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $upcomingSlots->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">réservées par des apprenants</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm ring-1 ring-gray-100/50 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Créneaux libres</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold {{ $availableSlotsCount > 0 ? 'text-green-600' : 'text-gray-300' }}">{{ $availableSlotsCount }}</p>
            <p class="text-xs text-gray-400 mt-1">disponibles à la réservation</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm ring-1 ring-gray-100/50 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Profil</p>
                <div class="w-8 h-8 rounded-lg {{ $profile?->isApproved() ? 'bg-green-50' : 'bg-gray-50' }} flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 {{ $profile?->isApproved() ? 'text-green-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            @if($profile?->isApproved())
                <p class="text-lg font-bold text-green-600">Approuvé</p>
                <p class="text-xs text-gray-400 mt-1">visible dans le catalogue</p>
            @elseif($profile?->submitted_at)
                <p class="text-lg font-bold text-amber-600">En attente</p>
                <p class="text-xs text-gray-400 mt-1">examen sous 48h</p>
            @else
                <p class="text-lg font-bold text-gray-400">Incomplet</p>
                <p class="text-xs text-gray-400 mt-1">à compléter pour publier</p>
            @endif
        </div>
    </div>

    {{-- Quick links --}}
    <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('teacher.profile') }}"
            class="group bg-white rounded-2xl border border-gray-100 shadow-sm ring-1 ring-gray-100/50 p-6 hover:border-violet-200 hover:shadow-md transition-all flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center group-hover:bg-violet-100 transition-colors flex-shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ __('teacher.dashboard.my_profile') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Bio, niveaux, langues, lien Meet</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-violet-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ route('teacher.availability') }}"
            class="group bg-white rounded-2xl border border-gray-100 shadow-sm ring-1 ring-gray-100/50 p-6 hover:border-violet-200 hover:shadow-md transition-all flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center group-hover:bg-violet-100 transition-colors flex-shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ __('teacher.dashboard.availability') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Ajouter et gérer vos créneaux</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-violet-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- Upcoming sessions list --}}
    @if($upcomingSlots->isNotEmpty())
        <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm ring-1 ring-gray-100/50">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Prochaines séances</h2>
            </div>
            @foreach($upcomingSlots as $slot)
                <div class="px-6 py-4 flex items-center gap-4 border-b border-gray-50 last:border-b-0">
                    <div class="flex-shrink-0 text-center w-10">
                        <p class="text-base font-bold text-violet-600">{{ $slot->starts_at->format('d') }}</p>
                        <p class="text-xs text-gray-400 -mt-0.5">{{ $slot->starts_at->translatedFormat('M') }}</p>
                    </div>
                    <div class="w-px h-8 bg-gray-100 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $slot->starts_at->format('H:i') }} – {{ $slot->ends_at->format('H:i') }}</p>
                        @if($slot->lessonSession?->learner)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $slot->lessonSession->learner->first_name }}</p>
                        @endif
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Réservée
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>
