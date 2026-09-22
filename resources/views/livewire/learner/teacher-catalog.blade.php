<div>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">{{ __('learner.catalog.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('learner.catalog.subtitle') }}</p>
    </div>

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
            <div class="flex gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm hover:border-violet-200 hover:shadow-md transition-all">
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
        @empty
            <p class="py-12 text-center text-sm text-gray-400">{{ __('learner.catalog.no_results') }}</p>
        @endforelse
    </div>
</div>
