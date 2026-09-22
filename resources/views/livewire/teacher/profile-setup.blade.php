<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900">{{ __('teacher.profile.title') }}</h1>

        @if($profile)
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                {{ $profile->isApproved() ? 'bg-green-100 text-green-800' : ($profile->isPending() && $profile->submitted_at ? 'bg-yellow-100 text-yellow-800' : ($profile->isSuspended() ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                {{ $profile->isApproved() ? __('teacher.profile.status.approved') : ($profile->isPending() && $profile->submitted_at ? __('teacher.profile.status.pending') : ($profile->isSuspended() ? __('teacher.profile.status.suspended') : __('teacher.profile.status.draft'))) }}
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="space-y-5">
        <div>
            <label for="bio" class="block text-sm font-medium text-gray-700">
                {{ __('teacher.profile.bio') }} <span class="text-gray-400">{{ __('teacher.profile.bio_hint') }}</span>
            </label>
            <textarea id="bio" wire:model="bio" rows="5"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none @error('bio') border-red-500 @enderror"
                placeholder="{{ __('teacher.profile.bio_placeholder') }}"></textarea>
            @error('bio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <p class="block text-sm font-medium text-gray-700">{{ __('teacher.profile.levels') }}</p>
            <div class="mt-2 flex flex-wrap gap-3">
                @foreach(\App\Livewire\Teacher\ProfileSetup::LEVEL_OPTIONS as $level)
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" wire:model="levels" value="{{ $level }}"
                            class="rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                        {{ __('teacher.levels.' . $level) }}
                    </label>
                @endforeach
            </div>
            @error('levels') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <p class="block text-sm font-medium text-gray-700">{{ __('teacher.profile.languages') }}</p>
            <div class="mt-2 flex flex-wrap gap-3">
                @foreach(\App\Livewire\Teacher\ProfileSetup::LANGUAGE_OPTIONS as $lang)
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" wire:model="languages" value="{{ $lang }}"
                            class="rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                        {{ __('teacher.languages.' . $lang) }}
                    </label>
                @endforeach
            </div>
            @error('languages') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="meetLink" class="block text-sm font-medium text-gray-700">{{ __('teacher.profile.meet_link') }}</label>
            <input id="meetLink" type="url" wire:model="meetLink" placeholder="https://meet.google.com/xxx-xxxx-xxx"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none @error('meetLink') border-red-500 @enderror">
            @error('meetLink') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none">
                {{ __('teacher.profile.save') }}
            </button>

            @if(!$profile?->isApproved() && !$profile?->submitted_at)
                <button type="button" wire:click="submit"
                    class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none">
                    {{ __('teacher.profile.submit') }}
                </button>
            @endif
        </div>
    </form>
</div>
