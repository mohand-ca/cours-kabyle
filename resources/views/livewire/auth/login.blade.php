<div style="background:#fff;border:1px solid #EAE4DD;border-radius:20px;padding:32px 28px;box-shadow:0 24px 48px -24px rgba(28,25,23,.18)">
    <h1 style="font-size:24px;font-weight:800;letter-spacing:-.03em;margin:0 0 6px;text-align:center;color:#1C1917">{{ __('auth.login.title') }}</h1>
    <p style="font-size:14.5px;color:#78716C;margin:0 0 28px;text-align:center">{{ __('auth.login.subtitle') }}</p>

    <form wire:submit="login" style="display:flex;flex-direction:column;gap:16px">
        <div>
            <label for="email" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('auth.login.email') }}</label>
            <input id="email" type="email" wire:model="email" autocomplete="email"
                style="width:100%;border:1px solid {{ $errors->has('email') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:11px 13px;font:inherit;font-size:14.5px;background:#fff;box-shadow:0 1px 2px rgba(28,25,23,.05);outline:none;color:#1C1917"
                class="focus-amber">
            @error('email') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('auth.login.password') }}</label>
            <input id="password" type="password" wire:model="password" autocomplete="current-password"
                style="width:100%;border:1px solid {{ $errors->has('password') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:11px 13px;font:inherit;font-size:14.5px;background:#fff;box-shadow:0 1px 2px rgba(28,25,23,.05);outline:none;color:#1C1917">
            @error('password') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between">
            <label style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#57534E;cursor:pointer">
                <input type="checkbox" wire:model="remember" style="width:16px;height:16px;accent-color:#9A6A00;margin:0">
                {{ __('auth.login.remember') }}
            </label>
            <a href="{{ route('password.request') }}" style="font-size:13px;color:#9A6A00;font-weight:600;text-decoration:none">{{ __('auth.login.forgot_password') }}</a>
        </div>

        <button type="submit"
            style="width:100%;background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:15px;font-weight:700;padding:13px;border-radius:12px;cursor:pointer;box-shadow:0 8px 20px -8px rgba(222,165,0,.6);margin-top:4px">
            <span wire:loading.remove>{{ __('auth.login.submit') }}</span>
            <span wire:loading style="opacity:.7">{{ __('auth.login.submitting') }}</span>
        </button>
    </form>

    <div style="border-top:1px solid #F1ECE6;margin-top:24px;padding-top:20px;display:flex;flex-direction:column;gap:8px;align-items:center;font-size:13.5px;color:#78716C">
        <div>{{ __('auth.login.no_account') }} <a href="{{ route('register') }}" style="font-weight:600;color:#9A6A00">{{ __('auth.login.register_link') }}</a></div>
        <div>{{ __('auth.login.teacher_label') }} <a href="{{ route('teacher.register') }}" style="font-weight:600;color:#9A6A00">{{ __('auth.login.teacher_link') }}</a></div>
    </div>
</div>
