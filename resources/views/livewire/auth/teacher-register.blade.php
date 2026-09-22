<div>
    <h1 class="mb-2 text-xl font-semibold text-gray-900">{{ __('auth.teacher_register.title') }}</h1>
    <p class="mb-6 text-sm text-gray-500">{{ __('auth.teacher_register.subtitle') }}</p>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('auth.register.display_name') }}</label>
            <input id="name" type="text" wire:model="form.name" autocomplete="name"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none @error('form.name') border-red-500 @enderror">
            @error('form.name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth.register.email') }}</label>
            <input id="email" type="email" wire:model="form.email" autocomplete="email"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none @error('form.email') border-red-500 @enderror">
            @error('form.email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth.register.password') }}</label>
            <input id="password" type="password" wire:model="form.password" autocomplete="new-password"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none @error('form.password') border-red-500 @enderror">
            @error('form.password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('auth.register.password_confirmation') }}</label>
            <input id="password_confirmation" type="password" wire:model="form.password_confirmation" autocomplete="new-password"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none">
        </div>

        <button type="submit"
            class="mt-2 w-full rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2">
            <span wire:loading.remove>{{ __('auth.teacher_register.submit') }}</span>
            <span wire:loading>{{ __('auth.register.submitting') }}</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        {{ __('auth.register.already_registered') }}
        <a href="{{ route('login') }}" class="font-medium text-violet-600 hover:text-violet-700">{{ __('auth.register.login_link') }}</a>
    </p>
</div>
