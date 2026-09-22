<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('learner.dashboard.greeting') }}, {{ Auth::user()->name }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('learner.dashboard.subtitle') }}</p>
    </div>

    @if(session('message'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-100 px-4 py-3 text-sm font-medium text-green-700 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 ring-1 ring-gray-100/50">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ __('learner.dashboard.upcoming_sessions') }}</p>
                <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $upcomingSessions->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">séances planifiées</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 ring-1 ring-gray-100/50">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ __('learner.dashboard.sessions_remaining') }}</p>
                <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold {{ $sessionsRemaining > 0 ? 'text-violet-600' : 'text-gray-300' }}">{{ $sessionsRemaining }}</p>
            @if($sessionsRemaining === 0)
                <a href="{{ route('learner.packages') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-700 mt-1 block">
                    {{ __('learner.dashboard.buy_sessions') }} →
                </a>
            @else
                <p class="text-xs text-gray-400 mt-1">séances disponibles</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 ring-1 ring-gray-100/50">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ __('learner.dashboard.points') }}</p>
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalPoints }}</p>
            <p class="text-xs text-gray-400 mt-1">points accumulés</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 ring-1 ring-gray-100/50">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ __('learner.dashboard.learners') }}</p>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $learners->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">profils apprenants</p>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-6">
        {{-- Upcoming sessions --}}
        <div class="col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm ring-1 ring-gray-100/50">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">{{ __('learner.dashboard.upcoming_sessions') }}</h2>
                <a href="{{ route('learner.teachers') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700">
                    {{ __('learner.dashboard.find_teacher') }}
                </a>
            </div>

            @forelse($upcomingSessions as $session)
                <div class="px-6 py-4 flex items-center gap-4 border-b border-gray-50 last:border-b-0">
                    <div class="flex-shrink-0 text-center w-10">
                        <p class="text-base font-bold text-violet-600">{{ $session->availabilitySlot->starts_at->format('d') }}</p>
                        <p class="text-xs text-gray-400 -mt-0.5">{{ $session->availabilitySlot->starts_at->translatedFormat('M') }}</p>
                    </div>
                    <div class="w-px h-8 bg-gray-100 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $session->availabilitySlot->starts_at->format('H:i') }} – {{ $session->availabilitySlot->ends_at->format('H:i') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('learner.booking.with') }} {{ $session->teacherProfile->user->name }} · {{ $session->learner->first_name }}</p>
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Confirmé
                    </span>

                    @if($cancelSessionId === $session->id)
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button wire:click="cancelSession"
                                class="px-3 py-1 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                {{ __('learner.booking.cancel_session') }}
                            </button>
                            <button wire:click="dismissCancel" class="text-xs text-gray-400 hover:text-gray-600">
                                {{ __('learner.booking.cancel') }}
                            </button>
                        </div>
                    @else
                        <button wire:click="confirmCancel({{ $session->id }})"
                            class="flex-shrink-0 text-xs text-gray-300 hover:text-red-500 transition-colors">
                            ✕
                        </button>
                    @endif
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm text-gray-400">{{ __('learner.dashboard.no_sessions') }}</p>
                    <a href="{{ route('learner.teachers') }}"
                        class="mt-3 inline-block text-xs font-semibold text-violet-600 hover:text-violet-700">
                        {{ __('learner.dashboard.find_teacher') }}
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Learners --}}
        <div class="col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm ring-1 ring-gray-100/50">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">{{ __('learner.dashboard.learners') }}</h2>
                <a href="{{ route('learner.learners') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700">
                    + {{ __('learner.dashboard.add_learner') }}
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($learners as $learner)
                    <div class="px-6 py-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-100 to-indigo-100 flex items-center justify-center text-violet-700 text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($learner->first_name, 0, 1) . substr($learner->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $learner->first_name }}</p>
                            <p class="text-xs text-gray-400">{{ __('learner.learners.relationships.' . $learner->relationship) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400">{{ __('learner.learners.no_learners') }}</p>
                        <a href="{{ route('learner.learners') }}" class="mt-2 inline-block text-xs font-medium text-violet-600 hover:text-violet-700">
                            {{ __('learner.learners.add') }} →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
