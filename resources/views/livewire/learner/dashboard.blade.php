<div>
    <h1 class="mb-6 text-xl font-semibold text-gray-900">
        {{ __('learner.dashboard.greeting') }}, {{ Auth::user()->name }} 👋
    </h1>

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
            <p class="mt-2 text-3xl font-bold text-gray-900">0</p>
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
            <div class="px-6 py-8 text-center text-sm text-gray-400">
                {{ __('learner.dashboard.no_sessions') }}
            </div>
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
