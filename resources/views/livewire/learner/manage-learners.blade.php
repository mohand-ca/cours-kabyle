<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900">{{ __('learner.learners.title') }}</h1>
        @if(!$showForm)
            <button wire:click="startAdd"
                class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-medium text-white hover:bg-violet-700 focus:outline-none">
                {{ __('learner.learners.add') }}
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Add / Edit form --}}
    @if($showForm)
        <div class="mb-6 rounded-2xl border border-violet-100 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-sm font-semibold text-gray-900">
                {{ $editingId ? __('learner.learners.edit') : __('learner.learners.add') }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('learner.learners.first_name') }}</label>
                        <input type="text" wire:model="form.firstName"
                            class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('form.firstName') border-red-400 @enderror">
                        @error('form.firstName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('learner.learners.last_name') }}</label>
                        <input type="text" wire:model="form.lastName"
                            class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('form.lastName') border-red-400 @enderror">
                        @error('form.lastName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('learner.learners.relationship') }}</label>
                        <select wire:model="form.relationship"
                            class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100">
                            @foreach(['self', 'child', 'spouse', 'other'] as $rel)
                                <option value="{{ $rel }}">{{ __('learner.learners.relationships.' . $rel) }}</option>
                            @endforeach
                        </select>
                        @error('form.relationship') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('learner.learners.date_of_birth') }}
                            <span class="text-gray-400 font-normal">{{ __('learner.learners.date_of_birth_optional') }}</span>
                        </label>
                        <input type="date" wire:model="form.dateOfBirth"
                            class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('form.dateOfBirth') border-red-400 @enderror">
                        @error('form.dateOfBirth') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('learner.learners.notification_email') }}</label>
                    <input type="email" wire:model="form.notificationEmail"
                        class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('form.notificationEmail') border-red-400 @enderror">
                    <p class="mt-1.5 text-xs text-gray-400">{{ __('learner.learners.notification_email_hint') }}</p>
                    @error('form.notificationEmail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-medium text-white hover:bg-violet-700 focus:outline-none">
                        {{ __('learner.learners.save') }}
                    </button>
                    <button type="button" wire:click="cancelForm"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 focus:outline-none">
                        {{ __('learner.learners.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Learner list --}}
    <div class="space-y-3">
        @forelse($learners as $learner)
            <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white px-5 py-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-violet-100 text-sm font-semibold text-violet-700">
                        {{ strtoupper(substr($learner->first_name, 0, 1) . substr($learner->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $learner->first_name }} {{ $learner->last_name }}</p>
                        <p class="text-xs text-gray-400">{{ __('learner.learners.relationships.' . $learner->relationship) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="startEdit({{ $learner->id }})"
                        class="text-xs font-medium text-violet-600 hover:text-violet-700">
                        {{ __('learner.learners.edit') }}
                    </button>
                    @if($learner->relationship !== 'self')
                        <button wire:click="delete({{ $learner->id }})"
                            wire:confirm="{{ __('learner.learners.delete_confirm') }}"
                            class="text-xs font-medium text-gray-400 hover:text-red-600">
                            {{ __('learner.learners.delete') }}
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <p class="py-8 text-center text-sm text-gray-400">{{ __('learner.learners.no_learners') }}</p>
        @endforelse
    </div>
</div>
