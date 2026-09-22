<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('auth.teacher_register.title') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('auth.teacher_register.subtitle') }}</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label for="name" class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('auth.register.display_name') }}</label>
            <input id="name" type="text" wire:model="form.name" autocomplete="name"
                class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('form.name') border-red-400 @enderror">
            @error('form.name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('auth.register.email') }}</label>
            <input id="email" type="email" wire:model="form.email" autocomplete="email"
                class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('form.email') border-red-400 @enderror">
            @error('form.email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('auth.register.password') }}</label>
            <input id="password" type="password" wire:model="form.password" autocomplete="new-password"
                class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 @error('form.password') border-red-400 @enderror">
            @error('form.password') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1.5">{{ __('auth.register.password_confirmation') }}</label>
            <input id="password_confirmation" type="password" wire:model="form.password_confirmation" autocomplete="new-password"
                class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100">
        </div>

        {{-- Context note --}}
        <div class="rounded-xl border border-violet-100 bg-violet-50 px-4 py-3 text-xs text-violet-800">
            Votre profil sera examiné par notre équipe avant d'être publié. Vous recevrez une notification sous 48h.
        </div>

        <button type="submit"
            class="w-full rounded-xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition-colors focus:outline-none">
            <span wire:loading.remove>{{ __('auth.teacher_register.submit') }}</span>
            <span wire:loading class="opacity-70">{{ __('auth.register.submitting') }}</span>
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-500">
            {{ __('auth.register.already_registered') }}
            <a href="{{ route('login') }}" class="font-semibold text-violet-600 hover:text-violet-700">{{ __('auth.register.login_link') }}</a>
        </p>
        <p class="mt-2 text-xs text-gray-400">
            Vous êtes apprenant ?
            <a href="{{ route('register') }}" class="font-medium text-gray-600 hover:text-violet-600">Créer un compte apprenant →</a>
        </p>
    </div>
</div>
