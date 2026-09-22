<div>
    <h1 class="mb-6 text-xl font-semibold text-gray-900">{{ __('teacher.availability.title') }}</h1>

    @if(!$profile?->isApproved())
        <div class="mb-6 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800">
            {{ __('teacher.availability.not_approved') }}
            <a href="{{ route('teacher.profile') }}" class="font-medium underline">{{ __('teacher.availability.complete_profile') }}</a>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Add a slot --}}
    @if($profile?->isApproved())
        <div class="mb-8 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold text-gray-900">{{ __('teacher.availability.add_slot') }}</h2>

            <form wire:submit="addSlot" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600">{{ __('teacher.availability.date') }}</label>
                    <input type="date" wire:model="date" min="{{ now()->addDay()->format('Y-m-d') }}"
                        class="mt-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none @error('date') border-red-500 @enderror">
                    @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600">{{ __('teacher.availability.time') }}</label>
                    <input type="time" wire:model="startTime"
                        class="mt-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none @error('startTime') border-red-500 @enderror">
                    @error('startTime') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600">{{ __('teacher.availability.duration') }}</label>
                    <select wire:model="duration"
                        class="mt-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none">
                        <option value="60">60 min</option>
                        <option value="90">90 min</option>
                        <option value="120">120 min</option>
                    </select>
                </div>

                <button type="submit"
                    class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-medium text-white hover:bg-violet-700 focus:outline-none">
                    {{ __('teacher.availability.add') }}
                </button>
            </form>
        </div>
    @endif

    {{-- Slot list --}}
    <div class="space-y-2">
        @forelse($slots as $slot)
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">
                <div>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $slot->starts_at->format('D d M Y · H:i') }} → {{ $slot->ends_at->format('H:i') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                        {{ $slot->isAvailable() ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $slot->isAvailable() ? __('teacher.availability.status_available') : __('teacher.availability.status_booked') }}
                    </span>

                    @if($slot->isAvailable())
                        <button wire:click="cancelSlot({{ $slot->id }})" wire:confirm="{{ __('teacher.availability.cancel_confirm') }}"
                            class="text-xs text-gray-400 hover:text-red-600">
                            {{ __('teacher.availability.cancel') }}
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <p class="py-8 text-center text-sm text-gray-500">{{ __('teacher.availability.no_slots') }}</p>
        @endforelse
    </div>
</div>
