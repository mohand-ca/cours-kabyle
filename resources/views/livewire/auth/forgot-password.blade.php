<div style="background:#fff;border:1px solid #EAE4DD;border-radius:20px;padding:32px 28px;box-shadow:0 24px 48px -24px rgba(28,25,23,.18)">
    <h1 style="font-size:24px;font-weight:800;letter-spacing:-.03em;margin:0 0 6px;text-align:center;color:#1C1917">{{ __('auth.forgot_password.title') }}</h1>
    <p style="font-size:14px;color:#78716C;margin:0 0 24px;text-align:center;line-height:1.55">{{ __('auth.forgot_password.subtitle') }}</p>

    @if ($sent)
        <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:14px 16px;font-size:13.5px;color:#166534;line-height:1.5;margin-bottom:20px">
            {{ __('auth.forgot_password.sent') }}
        </div>
    @endif

    <form wire:submit="sendLink" style="display:flex;flex-direction:column;gap:16px">
        <div>
            <label for="email" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('auth.forgot_password.email') }}</label>
            <input id="email" type="email" wire:model="email" autocomplete="email"
                style="width:100%;border:1px solid {{ $errors->has('email') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:11px 13px;font:inherit;font-size:14.5px;background:#fff;box-shadow:0 1px 2px rgba(28,25,23,.05);outline:none;color:#1C1917">
            @error('email') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
            style="width:100%;background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:15px;font-weight:700;padding:13px;border-radius:12px;cursor:pointer;box-shadow:0 8px 20px -8px rgba(222,165,0,.6);margin-top:4px">
            <span wire:loading.remove>{{ __('auth.forgot_password.submit') }}</span>
            <span wire:loading style="opacity:.7">{{ __('auth.forgot_password.submitting') }}</span>
        </button>
    </form>

    <div style="border-top:1px solid #F1ECE6;margin-top:24px;padding-top:20px;text-align:center;font-size:13.5px">
        <a href="{{ route('login') }}" style="color:#9A6A00;font-weight:600;text-decoration:none">← {{ __('auth.forgot_password.back_to_login') }}</a>
    </div>
</div>
