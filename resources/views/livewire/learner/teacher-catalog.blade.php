<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('learner.catalog.title') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('learner.catalog.subtitle') }}</p>
    </div>

    @if(session('message'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-100 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('message') }}
        </div>
    @endif

    @if($errors->has('selectedLearnerId') || $errors->has('selectedSlotId'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm font-medium text-red-700">
            {{ $errors->first('selectedLearnerId') ?: $errors->first('selectedSlotId') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-6 flex items-center gap-3">
        <div class="flex items-center gap-2">
            <label class="text-xs font-medium text-gray-500">{{ __('learner.catalog.filter_level') }}</label>
            <select wire:model.live="filterLevel"
                class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100">
                <option value="">{{ __('learner.catalog.all') }}</option>
                @foreach(\App\Livewire\Learner\TeacherCatalog::LEVEL_OPTIONS as $level)
                    <option value="{{ $level }}">{{ __('teacher.levels.' . $level) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-xs font-medium text-gray-500">{{ __('learner.catalog.filter_language') }}</label>
            <select wire:model.live="filterLanguage"
                class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100">
                <option value="">{{ __('learner.catalog.all') }}</option>
                @foreach(\App\Livewire\Learner\TeacherCatalog::LANGUAGE_OPTIONS as $lang)
                    <option value="{{ $lang }}">{{ __('teacher.languages.' . $lang) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Teacher list --}}
    <div class="space-y-3">
        @forelse($teachers as $teacher)
            <div class="bg-white rounded-2xl border transition-all
                {{ $expandedTeacherId === $teacher->id ? 'border-violet-300 shadow-md shadow-violet-50' : 'border-gray-100 shadow-sm hover:border-violet-200 hover:shadow-md' }}">

                {{-- Teacher row --}}
                <div class="p-5 flex gap-4">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $teacher->user->name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Algérie · UTC+1</p>
                            </div>
                            @if($teacher->available_slots_count > 0)
                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-green-50 text-green-700 border border-green-200 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    {{ trans_choice('learner.catalog.available_slots', $teacher->available_slots_count, ['count' => $teacher->available_slots_count]) }}
                                </span>
                            @else
                                <span class="flex-shrink-0 px-2 py-0.5 text-xs font-medium text-gray-400 bg-gray-50 border border-gray-200 rounded-full">
                                    {{ __('learner.catalog.no_slots') }}
                                </span>
                            @endif
                        </div>

                        @if($teacher->bio)
                            <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $teacher->bio }}</p>
                        @endif

                        <div class="flex items-center gap-2 mt-3 flex-wrap">
                            @foreach($teacher->languages ?? [] as $lang)
                                <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-md">
                                    {{ __('teacher.languages.' . $lang) }}
                                </span>
                            @endforeach
                            @foreach($teacher->levels ?? [] as $level)
                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 rounded-md">
                                    {{ __('teacher.levels.' . $level) }}
                                </span>
                            @endforeach

                            @if($teacher->available_slots_count > 0)
                                <button wire:click="toggleSlots({{ $teacher->id }})"
                                    class="ml-auto flex-shrink-0 px-3 py-1 text-xs font-medium
                                        {{ $expandedTeacherId === $teacher->id
                                            ? 'text-violet-700 bg-violet-50 rounded-lg'
                                            : 'text-violet-600 hover:text-violet-700' }}">
                                    {{ $expandedTeacherId === $teacher->id ? __('learner.booking.hide_slots') : __('learner.booking.view_slots') . ' →' }}
                                </button>
                            @endif
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
                                    <div class="rounded-xl border transition-all
                                        {{ $selectedSlotId === $slot->id ? 'border-violet-200 bg-violet-50/50' : 'border-gray-100 bg-white hover:border-violet-200' }}">

                                        @if($selectedSlotId === $slot->id)
                                            {{-- Learner picker --}}
                                            <div class="p-4 space-y-3">
                                                <div class="flex items-center gap-4">
                                                    <div class="flex-shrink-0 text-center w-12">
                                                        <p class="text-lg font-bold text-violet-600">{{ $slot->starts_at->format('d') }}</p>
                                                        <p class="text-xs text-gray-400 -mt-0.5">{{ $slot->starts_at->translatedFormat('M') }}</p>
                                                        <p class="text-xs text-gray-400">{{ $slot->starts_at->translatedFormat('D') }}</p>
                                                    </div>
                                                    <div class="w-px h-10 bg-gray-200 flex-shrink-0"></div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $slot->starts_at->format('H:i') }} → {{ $slot->ends_at->format('H:i') }}</p>
                                                        <p class="text-xs text-gray-500 mt-0.5">60 min</p>
                                                    </div>
                                                </div>

                                                <p class="text-xs font-medium text-gray-700">{{ __('learner.booking.for_whom') }}</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($learners as $learner)
                                                        <button wire:click="$set('selectedLearnerId', {{ $learner->id }})"
                                                            class="rounded-lg border px-3 py-1.5 text-xs font-medium transition-colors
                                                                {{ $selectedLearnerId === $learner->id
                                                                    ? 'border-violet-500 bg-violet-600 text-white'
                                                                    : 'border-gray-200 text-gray-600 hover:border-violet-300 bg-white' }}">
                                                            {{ $learner->first_name }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <button wire:click="book" @if(!$selectedLearnerId) disabled @endif
                                                        class="px-4 py-1.5 text-xs font-medium bg-violet-600 text-white rounded-lg hover:bg-violet-700 disabled:opacity-40 transition-colors">
                                                        {{ __('learner.booking.confirm') }}
                                                    </button>
                                                    <button wire:click="cancelBooking" class="text-xs text-gray-400 hover:text-gray-600">
                                                        {{ __('learner.booking.cancel') }}
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-4 flex items-center gap-4">
                                                <div class="flex-shrink-0 text-center w-12">
                                                    <p class="text-lg font-bold text-violet-600">{{ $slot->starts_at->format('d') }}</p>
                                                    <p class="text-xs text-gray-400 -mt-0.5">{{ $slot->starts_at->translatedFormat('M') }}</p>
                                                    <p class="text-xs text-gray-400">{{ $slot->starts_at->translatedFormat('D') }}</p>
                                                </div>
                                                <div class="w-px h-10 bg-gray-100 flex-shrink-0"></div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $slot->starts_at->format('H:i') }} → {{ $slot->ends_at->format('H:i') }}</p>
                                                    <p class="text-xs text-gray-500 mt-0.5">60 min</p>
                                                </div>
                                                <button wire:click="selectSlot({{ $slot->id }})"
                                                    class="flex-shrink-0 px-3 py-1.5 text-xs font-medium bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
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
            <div class="py-16 text-center">
                <p class="text-sm text-gray-400">{{ __('learner.catalog.no_results') }}</p>
            </div>
        @endforelse
    </div>
</div>
