<div>
    <div style="margin-bottom:28px">
        <h1 style="font-size:30px;font-weight:800;letter-spacing:-.035em;margin:0;color:#1C1917">{{ __('teacher.dashboard.title') }}, {{ Auth::user()->name }}</h1>
        <p style="font-size:15px;color:#78716C;margin:6px 0 0">{{ __('teacher.dashboard.subtitle') }}</p>
    </div>

    {{-- Status banner --}}
    @if(!$profile?->isApproved())
        @php
            $isPending = (bool) $profile?->submitted_at;
        @endphp
        <div style="margin-bottom:24px;border-radius:14px;border:1px solid {{ $isPending ? '#F1DDB4' : '#DDD1F0' }};background:{{ $isPending ? '#FBF1DE' : '#F3EEFA' }};padding:14px 18px;display:flex;align-items:center;gap:12px">
            <div style="width:32px;height:32px;border-radius:9px;background:{{ $isPending ? '#FDF3D6' : '#E8DDF5' }};color:{{ $isPending ? '#9A6A00' : '#4B3380' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                @if($isPending)
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                @else
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg>
                @endif
            </div>
            <div style="flex:1;min-width:0">
                <p style="font-size:14px;font-weight:500;color:{{ $isPending ? '#7A4F0C' : '#4B3380' }};margin:0">
                    @if($isPending)
                        {{ __('teacher.dashboard.profile_pending') }}
                    @else
                        {{ __('teacher.dashboard.profile_incomplete') }}
                    @endif
                </p>
            </div>
            @if(!$isPending)
                <a href="{{ route('teacher.profile') }}"
                    style="flex-shrink:0;padding:7px 14px;font-size:12.5px;font-weight:700;color:#fff;background:#4B3380;border-radius:9px;text-decoration:none">
                    {{ __('teacher.dashboard.complete_profile') }}
                </a>
            @endif
        </div>
    @endif

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">

        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:20px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div style="font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#78716C">Séances à venir</div>
                <div style="width:36px;height:36px;border-radius:10px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
                </div>
            </div>
            <div style="font-size:32px;font-weight:800;letter-spacing:-.04em;margin-top:2px;color:#1C1917">{{ $upcomingSlots->count() }}</div>
            <div style="font-size:12.5px;color:#A8A29E">réservées par des apprenants</div>
        </div>

        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:20px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div style="font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#78716C">Créneaux libres</div>
                <div style="width:36px;height:36px;border-radius:10px;background:{{ $availableSlotsCount > 0 ? '#E8F3EC' : '#F5F5F4' }};color:{{ $availableSlotsCount > 0 ? '#2F7D5B' : '#A8A29E' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                </div>
            </div>
            <div style="font-size:32px;font-weight:800;letter-spacing:-.04em;margin-top:2px;color:{{ $availableSlotsCount > 0 ? '#2F7D5B' : '#1C1917' }}">{{ $availableSlotsCount }}</div>
            <div style="font-size:12.5px;color:#A8A29E">disponibles à la réservation</div>
        </div>

        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:20px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div style="font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#78716C">Profil</div>
                <div style="width:36px;height:36px;border-radius:10px;background:{{ $profile?->isApproved() ? '#E8F3EC' : '#F5F5F4' }};color:{{ $profile?->isApproved() ? '#2F7D5B' : '#A8A29E' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            @if($profile?->isApproved())
                <div style="font-size:20px;font-weight:800;letter-spacing:-.03em;margin-top:2px;color:#2F7D5B">Approuvé</div>
                <div style="font-size:12.5px;color:#A8A29E">visible dans le catalogue</div>
            @elseif($profile?->submitted_at)
                <div style="font-size:20px;font-weight:800;letter-spacing:-.03em;margin-top:2px;color:#9A6A00">En attente</div>
                <div style="font-size:12.5px;color:#A8A29E">examen sous 48h</div>
            @else
                <div style="font-size:20px;font-weight:800;letter-spacing:-.03em;margin-top:2px;color:#A8A29E">Incomplet</div>
                <div style="font-size:12.5px;color:#A8A29E">à compléter pour publier</div>
            @endif
        </div>
    </div>

    {{-- Quick links --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:24px">
        <a href="{{ route('teacher.profile') }}"
            style="display:flex;align-items:center;gap:16px;background:#fff;border-radius:16px;border:1px solid #EAE4DD;padding:24px;box-shadow:0 1px 2px rgba(28,25,23,.04);text-decoration:none;transition:all .2s">
            <div style="width:40px;height:40px;border-radius:12px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:14px;font-weight:700;color:#1C1917">{{ __('teacher.dashboard.my_profile') }}</div>
                <div style="font-size:13px;color:#A8A29E;margin-top:2px">Bio, niveaux, langues, lien Meet</div>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#A8A29E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"></path></svg>
        </a>

        <a href="{{ route('teacher.availability') }}"
            style="display:flex;align-items:center;gap:16px;background:#fff;border-radius:16px;border:1px solid #EAE4DD;padding:24px;box-shadow:0 1px 2px rgba(28,25,23,.04);text-decoration:none;transition:all .2s">
            <div style="width:40px;height:40px;border-radius:12px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:14px;font-weight:700;color:#1C1917">{{ __('teacher.dashboard.availability') }}</div>
                <div style="font-size:13px;color:#A8A29E;margin-top:2px">Ajouter et gérer vos créneaux</div>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#A8A29E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    {{-- Upcoming sessions list --}}
    @if($upcomingSlots->isNotEmpty())
        <div style="background:#fff;border-radius:16px;border:1px solid #EAE4DD;overflow:hidden;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="padding:18px 20px;border-bottom:1px solid #F1ECE6">
                <div style="font-size:15px;font-weight:700;color:#1C1917">Prochaines séances</div>
            </div>
            @foreach($upcomingSlots as $slot)
                <div style="display:flex;align-items:center;gap:16px;padding:14px 20px;border-bottom:1px solid #F1ECE6">
                    <div style="width:40px;text-align:center;flex-shrink:0">
                        <div style="font-size:20px;font-weight:800;color:#9A6A00;line-height:1">{{ $slot->starts_at->format('d') }}</div>
                        <div style="font-size:10.5px;color:#A8A29E;text-transform:uppercase;letter-spacing:.08em;margin-top:4px">{{ $slot->starts_at->translatedFormat('M') }}</div>
                    </div>
                    <div style="width:1px;height:28px;background:#EAE4DD;flex-shrink:0"></div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:14px;font-weight:600;color:#1C1917">{{ $slot->starts_at->format('H:i') }} – {{ $slot->ends_at->format('H:i') }}</div>
                        @if($slot->lessonSession?->learner)
                            <div style="font-size:12.5px;color:#78716C;margin-top:2px">{{ $slot->lessonSession->learner->first_name }}</div>
                        @endif
                    </div>
                    <span style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:4px 10px;font-size:12px;font-weight:600;background:#EEF2FF;color:#4338CA;border-radius:99px;border:1px solid #C7D2FE">
                        <span style="width:6px;height:6px;border-radius:99px;background:#6366F1;flex-shrink:0"></span>
                        Réservée
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>
