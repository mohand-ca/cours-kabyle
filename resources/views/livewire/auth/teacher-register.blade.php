<div style="background:#fff;border:1px solid #EAE4DD;border-radius:20px;padding:32px 28px;box-shadow:0 24px 48px -24px rgba(28,25,23,.18)">
    <h1 style="font-size:24px;font-weight:800;letter-spacing:-.03em;margin:0 0 6px;text-align:center;color:#1C1917">{{ __('auth.teacher_register.title') }}</h1>
    <p style="font-size:14.5px;color:#78716C;margin:0 0 24px;text-align:center">{{ __('auth.teacher_register.subtitle') }}</p>

    <div style="display:flex;gap:10px;align-items:flex-start;background:#FBF1DE;border:1px solid #F1DDB4;color:#7A4F0C;border-radius:12px;padding:12px 14px;font-size:13.5px;line-height:1.45;margin-bottom:20px">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg>
        <span>{{ __('auth.teacher_register.info_banner') }}</span>
    </div>

    <form wire:submit="register" style="display:flex;flex-direction:column;gap:16px">
        <div>
            <label for="name" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('auth.register.display_name') }}</label>
            <input id="name" type="text" wire:model="form.name" autocomplete="name"
                style="width:100%;border:1px solid {{ $errors->has('form.name') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:11px 13px;font:inherit;font-size:14.5px;background:#fff;box-shadow:0 1px 2px rgba(28,25,23,.05);outline:none;color:#1C1917">
            @error('form.name') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('auth.register.email') }}</label>
            <input id="email" type="email" wire:model="form.email" autocomplete="email"
                style="width:100%;border:1px solid {{ $errors->has('form.email') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:11px 13px;font:inherit;font-size:14.5px;background:#fff;box-shadow:0 1px 2px rgba(28,25,23,.05);outline:none;color:#1C1917">
            @error('form.email') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="timezone" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('auth.teacher_register.timezone') }}</label>
            <select id="timezone" wire:model="form.timezone"
                style="width:100%;border:1px solid {{ $errors->has('form.timezone') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:11px 13px;font:inherit;font-size:14.5px;background:#fff;box-shadow:0 1px 2px rgba(28,25,23,.05);outline:none;color:#1C1917;appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2378716C' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 13px center">
                <optgroup label="Algérie / Algeria">
                    <option value="Africa/Algiers">Africa/Algiers — UTC+1</option>
                </optgroup>
                <optgroup label="Europe">
                    <option value="Europe/Paris">Europe/Paris — UTC+1/+2</option>
                    <option value="Europe/London">Europe/London — UTC+0/+1</option>
                    <option value="Europe/Brussels">Europe/Brussels — UTC+1/+2</option>
                    <option value="Europe/Zurich">Europe/Zurich — UTC+1/+2</option>
                </optgroup>
                <optgroup label="Canada">
                    <option value="America/Montreal">America/Montreal — UTC-5/-4</option>
                    <option value="America/Toronto">America/Toronto — UTC-5/-4</option>
                    <option value="America/Vancouver">America/Vancouver — UTC-8/-7</option>
                </optgroup>
                <optgroup label="États-Unis / USA">
                    <option value="America/New_York">America/New_York — UTC-5/-4</option>
                    <option value="America/Chicago">America/Chicago — UTC-6/-5</option>
                    <option value="America/Los_Angeles">America/Los_Angeles — UTC-8/-7</option>
                </optgroup>
            </select>
            @error('form.timezone') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('auth.register.password') }}</label>
            <input id="password" type="password" wire:model="form.password" autocomplete="new-password"
                style="width:100%;border:1px solid {{ $errors->has('form.password') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:11px 13px;font:inherit;font-size:14.5px;background:#fff;box-shadow:0 1px 2px rgba(28,25,23,.05);outline:none;color:#1C1917">
            @error('form.password') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('auth.register.password_confirmation') }}</label>
            <input id="password_confirmation" type="password" wire:model="form.password_confirmation" autocomplete="new-password"
                style="width:100%;border:1px solid #E2DBD3;border-radius:12px;padding:11px 13px;font:inherit;font-size:14.5px;background:#fff;box-shadow:0 1px 2px rgba(28,25,23,.05);outline:none;color:#1C1917">
        </div>

        <button type="submit"
            style="width:100%;background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:15px;font-weight:700;padding:13px;border-radius:12px;cursor:pointer;box-shadow:0 8px 20px -8px rgba(222,165,0,.6);margin-top:4px">
            <span wire:loading.remove>{{ __('auth.teacher_register.submit') }}</span>
            <span wire:loading style="opacity:.7">{{ __('auth.register.submitting') }}</span>
        </button>
    </form>

    <div style="border-top:1px solid #F1ECE6;margin-top:24px;padding-top:20px;display:flex;flex-direction:column;gap:8px;align-items:center;font-size:13.5px;color:#78716C">
        <div>{{ __('auth.register.already_registered') }} <a href="{{ route('login') }}" style="font-weight:600;color:#9A6A00">{{ __('auth.register.login_link') }}</a></div>
        <div>{{ __('auth.teacher_register.learner_label') }} <a href="{{ route('register') }}" style="font-weight:600;color:#9A6A00">{{ __('auth.teacher_register.learner_link') }}</a></div>
    </div>
</div>
