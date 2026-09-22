<div>
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('teacher.profile.title') }}</h1>
        </div>
        @if($profile)
            @php
                $statusColor = match(true) {
                    $profile->isApproved()    => 'bg-green-50 text-green-700 border-green-200',
                    $profile->isSuspended()   => 'bg-red-50 text-red-700 border-red-200',
                    !! $profile->submitted_at => 'bg-amber-50 text-amber-700 border-amber-200',
                    default                   => 'bg-gray-50 text-gray-600 border-gray-200',
                };
                $statusDot = match(true) {
                    $profile->isApproved()    => 'bg-green-500',
                    $profile->isSuspended()   => 'bg-red-500',
                    !! $profile->submitted_at => 'bg-amber-500',
                    default                   => 'bg-gray-400',
                };
                $statusLabel = match(true) {
                    $profile->isApproved()    => __('teacher.profile.status.approved'),
                    $profile->isSuspended()   => __('teacher.profile.status.suspended'),
                    !! $profile->submitted_at => __('teacher.profile.status.pending'),
                    default                   => __('teacher.profile.status.draft'),
                };
            @endphp
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium border rounded-full {{ $statusColor }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                {{ $statusLabel }}
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-100 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Bio --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <label for="bio" class="block text-sm font-semibold text-gray-900 mb-1">
                {{ __('teacher.profile.bio') }}
                <span class="font-normal text-gray-400 text-xs ml-1">{{ __('teacher.profile.bio_hint') }}</span>
            </label>
            <textarea id="bio" wire:model="bio" rows="5"
                class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('bio') border-red-400 @enderror"
                placeholder="{{ __('teacher.profile.bio_placeholder') }}"></textarea>
            @error('bio') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Levels --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm font-semibold text-gray-900 mb-3">{{ __('teacher.profile.levels') }}</p>
            <div class="flex flex-wrap gap-2">
                @foreach(\App\Livewire\Teacher\ProfileSetup::LEVEL_OPTIONS as $level)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model="levels" value="{{ $level }}" class="sr-only peer">
                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors
                            peer-checked:bg-violet-600 peer-checked:text-white peer-checked:border-violet-600
                            border-gray-200 text-gray-600 hover:border-violet-300">
                            {{ __('teacher.levels.' . $level) }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('levels') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Languages --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm font-semibold text-gray-900 mb-3">{{ __('teacher.profile.languages') }}</p>
            <div class="flex flex-wrap gap-2">
                @foreach(\App\Livewire\Teacher\ProfileSetup::LANGUAGE_OPTIONS as $lang)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model="languages" value="{{ $lang }}" class="sr-only peer">
                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors
                            peer-checked:bg-violet-600 peer-checked:text-white peer-checked:border-violet-600
                            border-gray-200 text-gray-600 hover:border-violet-300">
                            {{ __('teacher.languages.' . $lang) }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('languages') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Meet link --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <label for="meetLink" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('teacher.profile.meet_link') }}</label>
            <input id="meetLink" type="url" wire:model="meetLink" placeholder="https://meet.google.com/xxx-xxxx-xxx"
                class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('meetLink') border-red-400 @enderror">
            @error('meetLink') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                class="px-5 py-2 text-sm font-medium bg-white border border-gray-200 text-gray-700 rounded-xl shadow-sm hover:bg-gray-50 transition-colors">
                {{ __('teacher.profile.save') }}
            </button>

            @if(!$profile?->isApproved() && !$profile?->submitted_at)
                <button type="button" wire:click="submit"
                    class="px-5 py-2 text-sm font-semibold bg-violet-600 text-white rounded-xl hover:bg-violet-700 transition-colors">
                    {{ __('teacher.profile.submit') }}
                </button>
            @endif
        </div>
    </form>
</div>
