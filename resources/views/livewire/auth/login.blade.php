<div>
    <h1 class="mb-6 text-xl font-semibold text-gray-900">{{ __('auth.login.title') }}</h1>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth.login.email') }}</label>
            <input id="email" type="email" wire:model="email" autocomplete="email"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none @error('email') border-red-500 @enderror">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth.login.password') }}</label>
            <input id="password" type="password" wire:model="password" autocomplete="current-password"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none @error('password') border-red-500 @enderror">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" wire:model="remember" class="rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                {{ __('auth.login.remember') }}
            </label>
        </div>

        <button type="submit"
            class="mt-2 w-full rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2">
            <span wire:loading.remove>{{ __('auth.login.submit') }}</span>
            <span wire:loading>{{ __('auth.login.submitting') }}</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        {{ __('auth.login.no_account') }}
        <a href="{{ route('register') }}" class="font-medium text-violet-600 hover:text-violet-700">{{ __('auth.login.register_link') }}</a>
    </p>
</div>
