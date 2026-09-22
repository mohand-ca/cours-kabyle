<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('learner.dashboard.greeting') }}, {{ Auth::user()->name }} 👋</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('learner.dashboard.subtitle') }}</p>
    </div>

    @if(session('message'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-100 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('message') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-widest">{{ __('learner.dashboard.upcoming_sessions') }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingSessions->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-widest">{{ __('learner.dashboard.sessions_remaining') }}</p>
            <p class="text-3xl font-bold text-violet-600 mt-2">{{ $sessionsRemaining }}</p>
            @if($sessionsRemaining === 0)
                <a href="{{ route('learner.packages') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700 mt-1 block">
                    {{ __('learner.dashboard.buy_sessions') }} →
                </a>
            @endif
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-widest">{{ __('learner.dashboard.points') }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPoints }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-widest">{{ __('learner.dashboard.learners') }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $learners->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-6">
        {{-- Upcoming sessions --}}
        <div class="col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">{{ __('learner.dashboard.upcoming_sessions') }}</h2>
                <a href="{{ route('learner.teachers') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700">
                    {{ __('learner.dashboard.find_teacher') }}
                </a>
            </div>

            @forelse($upcomingSessions as $session)
                <div class="px-6 py-4 flex items-center gap-4 border-b border-gray-50 last:border-b-0">
                    {{-- Date column --}}
                    <div class="flex-shrink-0 text-center w-10">
                        <p class="text-base font-bold text-violet-600">{{ $session->availabilitySlot->starts_at->format('d') }}</p>
                        <p class="text-xs text-gray-400 -mt-0.5">{{ $session->availabilitySlot->starts_at->translatedFormat('M') }}</p>
                    </div>
                    <div class="w-px h-8 bg-gray-100 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $session->availabilitySlot->starts_at->format('H:i') }} – {{ $session->availabilitySlot->ends_at->format('H:i') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('learner.booking.with') }} {{ $session->teacherProfile->user->name }} · {{ $session->learner->first_name }}</p>
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full">
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
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-gray-400">{{ __('learner.dashboard.no_sessions') }}</p>
                    <a href="{{ route('learner.teachers') }}"
                        class="mt-3 inline-block text-xs font-medium text-violet-600 hover:text-violet-700">
                        {{ __('learner.dashboard.find_teacher') }}
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Learners --}}
        <div class="col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">{{ __('learner.dashboard.learners') }}</h2>
                <a href="{{ route('learner.learners') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700">
                    + {{ __('learner.dashboard.add_learner') }}
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($learners as $learner)
                    <div class="px-6 py-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 text-xs font-semibold flex-shrink-0">
                            {{ strtoupper(substr($learner->first_name, 0, 1) . substr($learner->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $learner->first_name }}</p>
                            <p class="text-xs text-gray-400">{{ __('learner.learners.relationships.' . $learner->relationship) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-4 text-sm text-gray-400">{{ __('learner.learners.no_learners') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
