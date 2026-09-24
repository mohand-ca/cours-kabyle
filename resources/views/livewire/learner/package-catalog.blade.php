<div>
    <div style="margin-bottom:28px">
        <h1 style="font-size:30px;font-weight:800;letter-spacing:-.035em;margin:0;color:#1C1917">{{ __('learner.packages.title') }}</h1>
        <p style="font-size:15px;color:#78716C;margin:6px 0 0">{{ __('learner.packages.subtitle') }}</p>
    </div>

    @if(session('message'))
        <div style="margin-bottom:24px;border-radius:12px;background:#E8F3EC;border:1px solid #C5E4CF;padding:12px 16px;font-size:14px;font-weight:500;color:#2F7D5B;display:flex;align-items:center;gap:8px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    @if($sessionsRemaining > 0)
        <div style="margin-bottom:24px;border-radius:14px;border:1px solid #EBD69A;background:#FFFCF0;padding:16px 20px;display:flex;align-items:center;gap:12px">
            <div style="width:32px;height:32px;border-radius:9px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
            </div>
            <p style="font-size:14px;font-weight:500;color:#7A4F0C;margin:0">
                {{ trans_choice('learner.packages.remaining_sessions', $sessionsRemaining, ['count' => $sessionsRemaining]) }}
            </p>
        </div>
    @endif

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;align-items:stretch;max-width:960px">
        @foreach($packages as $key => $package)
            @php
                $featured = $key === 'standard';
                $price = $package['price_cents'] / 100;
                $perSession = $package['price_cents'] / 100 / $package['sessions_count'];
                $priceLabel = '$'.rtrim(rtrim(number_format($price, 2, '.', ''), '0'), '.');
                $perSessionLabel = '$'.number_format($perSession, 2, '.', '');
            @endphp
            <div class="thz-card" style="position:relative;display:flex;flex-direction:column;background:{{ $featured ? '#FFFDF8' : '#fff' }};border:{{ $featured ? '2px solid #F2B81D' : '1px solid #EAE4DD' }};border-radius:20px;padding:{{ $featured ? '30px 26px' : '28px 26px' }};box-shadow:{{ $featured ? '0 24px 48px -22px rgba(222,165,0,.45)' : '0 1px 2px rgba(28,25,23,.04)' }}">
                @if($featured)
                    <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:#F2B81D;color:#1C1917;font-size:11px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;padding:5px 14px;border-radius:999px;box-shadow:0 8px 18px -6px rgba(222,165,0,.7);white-space:nowrap">{{ __('welcome.pricing.popular_badge') }}</div>
                @endif
                <div style="flex:1">
                    <div style="font-size:18px;font-weight:800;letter-spacing:-.02em;color:#1C1917">{{ __('welcome.pricing.packs.'.$key.'.name') }}</div>
                    <div style="font-size:13.5px;color:#78716C;line-height:1.45;margin-top:6px;min-height:38px">{{ __('welcome.pricing.packs.'.$key.'.tagline') }}</div>

                    <div style="margin:20px 0 4px">
                        <span style="font-size:44px;font-weight:800;letter-spacing:-.045em;line-height:1;color:#9A6A00">{{ $priceLabel }}</span>
                    </div>
                    <div style="font-size:13px;color:#9A6A00;font-weight:600">{{ __('welcome.pricing.sessions_count', ['count' => $package['sessions_count']]) }} · {{ __('welcome.pricing.per_session', ['price' => $perSessionLabel]) }}</div>
                </div>

                <a href="{{ route('learner.checkout', $package['key']) }}"
                    style="margin-top:24px;display:block;text-align:center;{{ $featured ? 'background:#F2B81D;color:#1C1917;box-shadow:0 6px 16px -6px rgba(222,165,0,.5)' : 'background:#fff;border:1px solid #E2DBD3;color:#1C1917' }};font-size:14.5px;font-weight:700;padding:12px;border-radius:12px;text-decoration:none">
                    {{ __('learner.packages.buy') }}
                </a>
            </div>
        @endforeach
    </div>

    {{-- Included in every pack --}}
    <div style="max-width:960px;margin:28px 0 0;background:#FCFAF8;border:1px solid #F1ECE6;border-radius:16px;padding:24px 26px">
        <div style="font-size:12px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#9A6A00;margin-bottom:16px">{{ __('welcome.pricing.included_title') }}</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px">
            @foreach(__('welcome.pricing.included') as $item)
            <div style="display:flex;align-items:flex-start;gap:10px">
                <div style="width:20px;height:20px;border-radius:99px;background:#E8F3EC;color:#2F7D5B;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
                </div>
                <span style="font-size:14px;color:#44403C;line-height:1.45">{{ $item }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
