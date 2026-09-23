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

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px">
        @foreach($packages as $package)
            <div style="display:flex;flex-direction:column;background:#fff;border-radius:16px;border:1px solid #EAE4DD;padding:24px;box-shadow:0 1px 2px rgba(28,25,23,.04);transition:all .2s">
                <div style="flex:1">
                    <div style="font-size:15px;font-weight:700;color:#1C1917;margin-bottom:8px">{{ $package['name'] }}</div>
                    <div style="font-size:36px;font-weight:800;letter-spacing:-.04em;color:#9A6A00;line-height:1">
                        ${{ number_format($package['price_cents'] / 100, 2) }}
                    </div>
                    <div style="font-size:13px;color:#A8A29E;margin-top:4px">
                        ${{ number_format($package['price_cents'] / $package['sessions_count'] / 100, 2) }} {{ __('learner.packages.per_session') }}
                    </div>
                    <div style="font-size:14px;color:#57534E;margin-top:16px">
                        {{ trans_choice('learner.packages.sessions', $package['sessions_count'], ['count' => $package['sessions_count']]) }}
                    </div>
                </div>

                <a href="{{ route('learner.checkout', $package['key']) }}"
                    style="margin-top:20px;display:block;text-align:center;background:#F2B81D;color:#1C1917;font-size:14.5px;font-weight:700;padding:12px;border-radius:12px;box-shadow:0 6px 16px -6px rgba(222,165,0,.5);text-decoration:none">
                    {{ __('learner.packages.buy') }}
                </a>
            </div>
        @endforeach
    </div>
</div>
