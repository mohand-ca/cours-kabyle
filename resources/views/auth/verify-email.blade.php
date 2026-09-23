<x-layouts.auth>
    <div style="background:#fff;border:1px solid #EAE4DD;border-radius:20px;padding:32px 28px;box-shadow:0 24px 48px -24px rgba(28,25,23,.18)">
        <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#FEF3C7,#FDE68A);border:1px solid #EAE4DD;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px">
            ✉️
        </div>

        <h1 style="font-size:22px;font-weight:800;letter-spacing:-.03em;margin:0 0 8px;text-align:center;color:#1C1917">{{ __('auth.verify_email.title') }}</h1>
        <p style="font-size:14px;color:#78716C;margin:0 0 24px;text-align:center;line-height:1.55">{{ __('auth.verify_email.subtitle') }}</p>

        @if (session('status') === 'verification-link-sent')
            <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:12px 14px;margin-bottom:20px;font-size:13.5px;color:#166534;text-align:center">
                {{ __('auth.verify_email.sent') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                style="width:100%;background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:15px;font-weight:700;padding:13px;border-radius:12px;cursor:pointer;box-shadow:0 8px 20px -8px rgba(222,165,0,.6)">
                {{ __('auth.verify_email.resend') }}
            </button>
        </form>

        <div style="border-top:1px solid #F1ECE6;margin-top:24px;padding-top:20px;text-align:center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    style="background:none;border:none;font:inherit;font-size:13.5px;color:#78716C;cursor:pointer;text-decoration:underline;text-underline-offset:2px">
                    {{ __('auth.verify_email.logout') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>
