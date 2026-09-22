<div>
    <h1 class="mb-6 text-xl font-semibold text-gray-900">
        {{ __('learner.dashboard.greeting') }}, {{ Auth::user()->name }}
    </h1>

    @if(session('message'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('message') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-3 gap-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-widest text-gray-400">{{ __('learner.dashboard.learners') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $learners->count() }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-widest text-gray-400">{{ __('learner.dashboard.points') }}</p>
            <p class="mt-2 text-3xl font-bold text-violet-600">{{ $totalPoints }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-widest text-gray-400">{{ __('learner.dashboard.upcoming_sessions') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $upcomingSessions->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-6">
        {{-- Upcoming sessions --}}
        <div class="col-span-3 rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">{{ __('learner.dashboard.upcoming_sessions') }}</h2>
                <a href="{{ route('learner.teachers') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700">
                    {{ __('learner.dashboard.find_teacher') }}
                </a>
            </div>

            @forelse($upcomingSessions as $session)
                <div class="flex items-center justify-between border-b border-gray-50 px-6 py-4 last:border-b-0">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $session->availabilitySlot->starts_at->translatedFormat('D j M Y, H:i') }}
                        </p>
                        <p class="mt-0.5 text-xs text-gray-400">
                            {{ __('learner.booking.with') }} {{ $session->teacherProfile->user->name }}
                            · {{ $session->learner->first_name }}
                        </p>
                    </div>

                    @if($cancelSessionId === $session->id)
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">{{ __('learner.booking.cancel_session_confirm') }}</span>
                            <button wire:click="cancelSession"
                                class="rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700 transition-colors">
                                {{ __('learner.booking.cancel_session') }}
                            </button>
                            <button wire:click="dismissCancel" class="text-xs text-gray-400 hover:text-gray-600">
                                {{ __('learner.booking.cancel') }}
                            </button>
                        </div>
                    @else
                        <button wire:click="confirmCancel({{ $session->id }})"
                            class="rounded-lg border border-gray-200 px-3 py-1 text-xs font-medium text-gray-500 hover:border-red-200 hover:text-red-600 transition-colors">
                            {{ __('learner.booking.cancel_session') }}
                        </button>
                    @endif
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-gray-400">
                    {{ __('learner.dashboard.no_sessions') }}
                </div>
            @endforelse
        </div>

        {{-- Learners --}}
        <div class="col-span-2 rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">{{ __('learner.dashboard.learners') }}</h2>
                <a href="{{ route('learner.learners') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700">
                    {{ __('learner.dashboard.manage_learners') }}
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($learners as $learner)
                    <div class="flex items-center gap-3 px-6 py-4">
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-violet-100 text-xs font-semibold text-violet-700">
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
