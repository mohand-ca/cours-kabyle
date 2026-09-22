<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('auth.login.title') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('auth.login.subtitle') }}</p>
    </div>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('auth.login.email') }}</label>
            <input id="email" type="email" wire:model="email" autocomplete="email"
                class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('email') border-red-400 @enderror">
            @error('email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('auth.login.password') }}</label>
            <input id="password" type="password" wire:model="password" autocomplete="current-password"
                class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('password') border-red-400 @enderror">
            @error('password') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-xs text-gray-500 cursor-pointer">
                <input type="checkbox" wire:model="remember" class="rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                {{ __('auth.login.remember') }}
            </label>
        </div>

        <button type="submit"
            class="w-full rounded-xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition-colors focus:outline-none">
            <span wire:loading.remove>{{ __('auth.login.submit') }}</span>
            <span wire:loading class="opacity-70">{{ __('auth.login.submitting') }}</span>
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-500">
            {{ __('auth.login.no_account') }}
            <a href="{{ route('register') }}" class="font-semibold text-violet-600 hover:text-violet-700">{{ __('auth.login.register_link') }}</a>
        </p>
        <p class="mt-2 text-xs text-gray-400">
            Vous êtes enseignant ?
            <a href="{{ route('teacher.register') }}" class="font-medium text-gray-600 hover:text-violet-600">Créer un compte enseignant →</a>
        </p>
    </div>
</div>
