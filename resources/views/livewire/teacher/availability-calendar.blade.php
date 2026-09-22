<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('teacher.availability.title') }}</h1>
    </div>

    @if(!$profile?->isApproved())
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 flex items-center gap-4">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <p class="text-sm text-amber-800">
                {{ __('teacher.availability.not_approved') }}
                <a href="{{ route('teacher.profile') }}" class="font-medium underline ml-1">{{ __('teacher.availability.complete_profile') }}</a>
            </p>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-100 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Add slot form --}}
    @if($profile?->isApproved())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">{{ __('teacher.availability.add_slot') }}</h2>

            <form wire:submit="addSlot" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('teacher.availability.date') }}</label>
                    <input type="date" wire:model="date" min="{{ now()->addDay()->format('Y-m-d') }}"
                        class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('date') border-red-400 @enderror">
                    @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('teacher.availability.time') }}</label>
                    <input type="time" wire:model="startTime"
                        class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('startTime') border-red-400 @enderror">
                    @error('startTime') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('teacher.availability.duration') }}</label>
                    <select wire:model="duration"
                        class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100">
                        <option value="60">60 min</option>
                        <option value="90">90 min</option>
                        <option value="120">120 min</option>
                    </select>
                </div>

                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold bg-violet-600 text-white rounded-xl hover:bg-violet-700 transition-colors">
                    {{ __('teacher.availability.add') }}
                </button>
            </form>
        </div>
    @endif

    {{-- Slot list --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @forelse($slots as $slot)
            <div class="px-6 py-4 flex items-center gap-4 border-b border-gray-50 last:border-b-0">
                <div class="flex-shrink-0 text-center w-10">
                    <p class="text-base font-bold text-violet-600">{{ $slot->starts_at->format('d') }}</p>
                    <p class="text-xs text-gray-400 -mt-0.5">{{ $slot->starts_at->translatedFormat('M') }}</p>
                </div>
                <div class="w-px h-8 bg-gray-100 flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $slot->starts_at->format('H:i') }} → {{ $slot->ends_at->format('H:i') }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $slot->starts_at->translatedFormat('l') }}</p>
                </div>
                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full
                    {{ $slot->isAvailable() ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $slot->isAvailable() ? 'bg-green-500' : 'bg-blue-500' }}"></span>
                    {{ $slot->isAvailable() ? __('teacher.availability.status_available') : __('teacher.availability.status_booked') }}
                </span>

                @if($slot->isAvailable())
                    <button wire:click="cancelSlot({{ $slot->id }})" wire:confirm="{{ __('teacher.availability.cancel_confirm') }}"
                        class="flex-shrink-0 text-xs text-gray-300 hover:text-red-500 transition-colors">
                        ✕
                    </button>
                @endif
            </div>
        @empty
            <div class="px-6 py-12 text-center">
                <p class="text-sm text-gray-400">{{ __('teacher.availability.no_slots') }}</p>
            </div>
        @endforelse
    </div>
</div>
