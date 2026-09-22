<div>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">{{ __('learner.catalog.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('learner.catalog.subtitle') }}</p>
    </div>

    @if(session('message'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('message') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-6 flex items-center gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('learner.catalog.filter_level') }}</label>
            <select wire:model.live="filterLevel"
                class="rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none">
                <option value="">{{ __('learner.catalog.all') }}</option>
                @foreach(\App\Livewire\Learner\TeacherCatalog::LEVEL_OPTIONS as $level)
                    <option value="{{ $level }}">{{ __('teacher.levels.' . $level) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('learner.catalog.filter_language') }}</label>
            <select wire:model.live="filterLanguage"
                class="rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none">
                <option value="">{{ __('learner.catalog.all') }}</option>
                @foreach(\App\Livewire\Learner\TeacherCatalog::LANGUAGE_OPTIONS as $lang)
                    <option value="{{ $lang }}">{{ __('teacher.languages.' . $lang) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Teacher list --}}
    <div class="space-y-4">
        @forelse($teachers as $teacher)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm transition-all hover:border-violet-200 hover:shadow-md">
                <div class="flex gap-4 p-5">
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-400 to-indigo-500 text-xl font-bold text-white">
                        {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $teacher->user->name }}</h3>
                                <p class="mt-0.5 text-xs text-gray-400">
                                    @if($teacher->available_slots_count > 0)
                                        <span class="text-violet-600 font-medium">
                                            {{ trans_choice('learner.catalog.available_slots', $teacher->available_slots_count, ['count' => $teacher->available_slots_count]) }}
                                        </span>
                                    @else
                                        {{ __('learner.catalog.no_slots') }}
                                    @endif
                                </p>
                            </div>

                            @if($teacher->available_slots_count > 0)
                                <button wire:click="toggleSlots({{ $teacher->id }})"
                                    class="flex-shrink-0 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-violet-700 transition-colors">
                                    {{ $expandedTeacherId === $teacher->id ? __('learner.booking.hide_slots') : __('learner.booking.view_slots') }}
                                </button>
                            @endif
                        </div>

                        @if($teacher->bio)
                            <p class="mt-2 text-xs text-gray-500 line-clamp-2">{{ $teacher->bio }}</p>
                        @endif

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            @foreach($teacher->levels ?? [] as $level)
                                <span class="rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">
                                    {{ __('teacher.levels.' . $level) }}
                                </span>
                            @endforeach
                            @foreach($teacher->languages ?? [] as $lang)
                                <span class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                    {{ __('teacher.languages.' . $lang) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Slots panel --}}
                @if($expandedTeacherId === $teacher->id)
                    <div class="border-t border-gray-100 px-5 pb-5 pt-4">
                        @if($availableSlots->isEmpty())
                            <p class="text-sm text-gray-400">{{ __('learner.booking.no_slots') }}</p>
                        @else
                            <div class="space-y-2">
                                @foreach($availableSlots as $slot)
                                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                                        @if($selectedSlotId === $slot->id)
                                            {{-- Learner picker --}}
                                            <div class="space-y-3">
                                                <p class="text-sm font-medium text-gray-700">
                                                    {{ $slot->starts_at->translatedFormat('D j M Y, H:i') }}
                                                </p>
                                                <p class="text-xs font-medium text-gray-600">{{ __('learner.booking.for_whom') }}</p>

                                                @error('selectedLearnerId')
                                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                                @error('selectedSlotId')
                                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                                @enderror

                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($learners as $learner)
                                                        <button
                                                            wire:click="$set('selectedLearnerId', {{ $learner->id }})"
                                                            class="rounded-lg border px-3 py-1.5 text-xs font-medium transition-colors
                                                                {{ $selectedLearnerId === $learner->id
                                                                    ? 'border-violet-500 bg-violet-50 text-violet-700'
                                                                    : 'border-gray-200 text-gray-600 hover:border-violet-300' }}">
                                                            {{ $learner->first_name }}
                                                        </button>
                                                    @endforeach
                                                </div>

                                                <div class="flex items-center gap-2 pt-1">
                                                    <button wire:click="book"
                                                        @if(! $selectedLearnerId) disabled @endif
                                                        class="rounded-lg bg-violet-600 px-4 py-1.5 text-xs font-medium text-white hover:bg-violet-700 disabled:opacity-40 transition-colors">
                                                        {{ __('learner.booking.confirm') }}
                                                    </button>
                                                    <button wire:click="cancelBooking"
                                                        class="text-xs text-gray-400 hover:text-gray-600">
                                                        {{ __('learner.booking.cancel') }}
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm text-gray-700">
                                                    {{ $slot->starts_at->translatedFormat('D j M Y, H:i') }}
                                                </p>
                                                <button wire:click="selectSlot({{ $slot->id }})"
                                                    class="rounded-lg border border-violet-200 px-3 py-1 text-xs font-medium text-violet-600 hover:bg-violet-50 transition-colors">
                                                    {{ __('learner.booking.book') }}
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <p class="py-12 text-center text-sm text-gray-400">{{ __('learner.catalog.no_results') }}</p>
        @endforelse
    </div>
</div>
