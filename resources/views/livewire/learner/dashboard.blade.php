<div>
    <div style="margin-bottom:28px">
        <h1 style="font-size:30px;font-weight:800;letter-spacing:-.035em;margin:0;color:#1C1917">{{ __('learner.dashboard.greeting') }}, {{ Auth::user()->name }} 👋</h1>
        <p style="font-size:15px;color:#78716C;margin:6px 0 0">{{ __('learner.dashboard.subtitle') }}</p>
    </div>

    @if(session('message'))
        <div style="margin-bottom:24px;border-radius:12px;background:#E8F3EC;border:1px solid #C5E4CF;padding:12px 16px;font-size:14px;font-weight:500;color:#2F7D5B;display:flex;align-items:center;gap:8px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:24px">

        <div class="thz-card" style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:20px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div style="font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#78716C">{{ __('learner.dashboard.upcoming_sessions') }}</div>
                <div style="width:36px;height:36px;border-radius:10px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
                </div>
            </div>
            <div style="font-size:32px;font-weight:800;letter-spacing:-.04em;margin-top:2px;color:#1C1917">{{ $upcomingSessions->count() }}</div>
            <div style="font-size:12.5px;color:#A8A29E">{{ __('learner.dashboard.stat_sessions_planned') }}</div>
        </div>

        <div class="thz-card" style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:20px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div style="font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#78716C">{{ __('learner.dashboard.sessions_remaining') }}</div>
                <div style="width:36px;height:36px;border-radius:10px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                </div>
            </div>
            @if($sessionsRemaining > 0)
                <div style="font-size:32px;font-weight:800;letter-spacing:-.04em;margin-top:2px;color:#9A6A00">{{ $sessionsRemaining }}</div>
                <div style="font-size:12.5px;color:#A8A29E">{{ __('learner.dashboard.stat_sessions_balance') }}</div>
            @else
                <div style="font-size:32px;font-weight:800;letter-spacing:-.04em;margin-top:2px;color:#1C1917">0</div>
                <a href="{{ route('learner.packages') }}" style="font-size:12.5px;font-weight:600;color:#9A6A00">{{ __('learner.dashboard.buy_sessions') }} →</a>
            @endif
        </div>

        <div class="thz-card" style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:20px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div style="font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#78716C">{{ __('learner.dashboard.points') }}</div>
                <div style="width:36px;height:36px;border-radius:10px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"></path></svg>
                </div>
            </div>
            <div style="font-size:32px;font-weight:800;letter-spacing:-.04em;margin-top:2px;color:#1C1917">{{ $totalPoints }}</div>
            <div style="font-size:12.5px;color:#A8A29E">{{ __('learner.dashboard.stat_points_earned') }}</div>
        </div>

        <div class="thz-card" style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:20px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div style="font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#78716C">{{ __('learner.dashboard.learners') }}</div>
                <div style="width:36px;height:36px;border-radius:10px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
            <div style="font-size:32px;font-weight:800;letter-spacing:-.04em;margin-top:2px;color:#1C1917">{{ $learners->count() }}</div>
            <div style="font-size:12.5px;color:#A8A29E">{{ __('learner.dashboard.stat_learner_profiles') }}</div>
        </div>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">

        {{-- Upcoming sessions --}}
        <div style="flex:3 1 480px;min-width:0;background:#fff;border:1px solid #EAE4DD;border-radius:16px;overflow:hidden;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid #F1ECE6">
                <div style="font-size:15px;font-weight:700;color:#1C1917">{{ __('learner.dashboard.upcoming_sessions') }}</div>
                <a href="{{ route('learner.teachers') }}" style="font-size:13px;font-weight:600;color:#9A6A00">{{ __('learner.dashboard.find_teacher') }} →</a>
            </div>

            @forelse($upcomingSessions as $session)
                <div style="display:flex;align-items:center;gap:16px;padding:14px 20px;border-bottom:1px solid #F1ECE6">
                    <div style="width:40px;text-align:center;flex-shrink:0">
                        <div style="font-size:20px;font-weight:800;color:#9A6A00;line-height:1">{{ $session->availabilitySlot->starts_at->format('d') }}</div>
                        <div style="font-size:10.5px;color:#A8A29E;text-transform:uppercase;letter-spacing:.08em;margin-top:4px">{{ $session->availabilitySlot->starts_at->translatedFormat('M') }}</div>
                    </div>
                    <div style="width:1px;height:28px;background:#EAE4DD;flex-shrink:0"></div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:14px;font-weight:600;color:#1C1917">{{ $session->availabilitySlot->starts_at->format('H:i') }} – {{ $session->availabilitySlot->ends_at->format('H:i') }}</div>
                        <div style="font-size:12.5px;color:#78716C;margin-top:2px">{{ __('learner.booking.with') }} {{ $session->teacherProfile->user->name }} · {{ $session->learner->first_name }}</div>
                    </div>
                    <span style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:4px 10px;font-size:12px;font-weight:600;background:#E8F3EC;color:#2F7D5B;border-radius:99px;border:1px solid #C5E4CF">
                        <span style="width:6px;height:6px;border-radius:99px;background:#3A9A6E;flex-shrink:0"></span>
                        {{ __('learner.booking.confirmed') }}
                    </span>

                    @if($cancelSessionId === $session->id)
                        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                            <button wire:click="cancelSession" style="padding:4px 12px;font-size:12px;font-weight:600;background:#DC2626;color:#fff;border:none;border-radius:8px;cursor:pointer;font:inherit">
                                {{ __('learner.booking.cancel_session') }}
                            </button>
                            <button wire:click="dismissCancel" style="font-size:12px;color:#A8A29E;background:none;border:none;cursor:pointer;font:inherit">
                                {{ __('learner.booking.cancel') }}
                            </button>
                        </div>
                    @else
                        <button wire:click="confirmCancel({{ $session->id }})" style="flex-shrink:0;font-size:12px;color:#D1C9C0;background:none;border:none;cursor:pointer;font:inherit;padding:0">✕</button>
                    @endif
                </div>
            @empty
                <div style="padding:48px 20px;text-align:center">
                    <div style="width:48px;height:48px;border-radius:14px;background:#F1ECE6;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#A8A29E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
                    </div>
                    <p style="font-size:14px;color:#A8A29E">{{ __('learner.dashboard.no_sessions') }}</p>
                    <a href="{{ route('learner.teachers') }}" style="margin-top:12px;display:inline-block;font-size:13px;font-weight:600;color:#9A6A00">{{ __('learner.dashboard.find_teacher') }} →</a>
                </div>
            @endforelse
        </div>

        {{-- Learner profiles --}}
        <div style="flex:2 1 280px;min-width:0;background:#fff;border:1px solid #EAE4DD;border-radius:16px;overflow:hidden;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid #F1ECE6">
                <div style="font-size:15px;font-weight:700;color:#1C1917">{{ __('learner.dashboard.learners') }}</div>
                <a href="{{ route('learner.learners') }}" style="font-size:13px;font-weight:600;color:#9A6A00">+ {{ __('learner.dashboard.add_learner') }}</a>
            </div>
            @forelse($learners as $learner)
                <div style="padding:14px 20px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #F1ECE6">
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#F2B81D,#F9D55C);color:#1C1917;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        {{ strtoupper(substr($learner->first_name, 0, 1) . substr($learner->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1C1917">{{ $learner->first_name }}</div>
                        <div style="font-size:12px;color:#A8A29E;margin-top:2px">{{ __('learner.learners.relationships.' . $learner->relationship) }}</div>
                    </div>
                </div>
            @empty
                <div style="padding:32px 20px;text-align:center">
                    <p style="font-size:14px;color:#A8A29E">{{ __('learner.learners.no_learners') }}</p>
                    <a href="{{ route('learner.learners') }}" style="margin-top:8px;display:inline-block;font-size:13px;font-weight:600;color:#9A6A00">{{ __('learner.learners.add') }} →</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
