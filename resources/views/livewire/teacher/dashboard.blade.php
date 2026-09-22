<div>
    <h1 class="mb-6 text-xl font-semibold text-gray-900">{{ __('teacher.dashboard.title') }}</h1>

    @if(!$profile?->isApproved())
        <div class="mb-6 rounded-lg {{ $profile?->submitted_at ? 'bg-yellow-50' : 'bg-violet-50' }} p-4 text-sm {{ $profile?->submitted_at ? 'text-yellow-800' : 'text-violet-800' }}">
            @if($profile?->submitted_at)
                {{ __('teacher.dashboard.profile_pending') }}
            @else
                {{ __('teacher.dashboard.profile_incomplete') }}
                <a href="{{ route('teacher.profile') }}" class="font-medium underline">{{ __('teacher.dashboard.complete_profile') }}</a>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
        <a href="{{ route('teacher.profile') }}"
            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-violet-300 hover:shadow-md transition-shadow">
            <p class="text-xs text-gray-500">{{ __('teacher.dashboard.my_profile') }}</p>
            <p class="mt-1 text-sm font-medium text-gray-900">{{ $profile?->isApproved() ? __('teacher.dashboard.profile_approved') : __('teacher.dashboard.profile_action') }}</p>
        </a>

        <a href="{{ route('teacher.availability') }}"
            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-violet-300 hover:shadow-md transition-shadow">
            <p class="text-xs text-gray-500">{{ __('teacher.dashboard.availability') }}</p>
            <p class="mt-1 text-sm font-medium text-gray-900">{{ __('teacher.dashboard.manage') }}</p>
        </a>
    </div>
</div>
