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

    {{-- Quick access cards --}}
    <div class="grid grid-cols-2 gap-4 mb-8">
        <a href="{{ route('teacher.profile') }}"
            class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-violet-200 hover:shadow-md transition-all">
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center mb-4 group-hover:bg-violet-100 transition-colors">
                <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <p class="text-xs font-medium text-gray-400 uppercase tracking-widest">{{ __('teacher.dashboard.my_profile') }}</p>
            <p class="mt-1 text-sm font-semibold text-gray-900">
                @if($profile?->isApproved())
                    <span class="text-green-600">{{ __('teacher.dashboard.profile_approved') }}</span>
                @else
                    {{ __('teacher.dashboard.profile_action') }}
                @endif
            </p>
        </a>

        <a href="{{ route('teacher.availability') }}"
            class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-violet-200 hover:shadow-md transition-all">
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center mb-4 group-hover:bg-violet-100 transition-colors">
                <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-xs font-medium text-gray-400 uppercase tracking-widest">{{ __('teacher.dashboard.availability') }}</p>
            <p class="mt-1 text-sm font-semibold text-gray-900">{{ __('teacher.dashboard.manage') }}</p>
        </a>
    </div>
</div>
