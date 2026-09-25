<div>
    {{-- Toast --}}
    @if(session('toast'))
        @php($toast = session('toast'))
        <div role="status" wire:key="toast-{{ md5($toast['message']) }}"
            style="position:fixed;top:78px;left:50%;transform:translateX(-50%);z-index:90;max-width:calc(100% - 32px);background:{{ $toast['kind'] === 'err' ? '#FEF2F2' : '#1C1917' }};color:{{ $toast['kind'] === 'err' ? '#DC2626' : '#FAF8F5' }};border:1px solid {{ $toast['kind'] === 'err' ? '#FECACA' : '#1C1917' }};padding:11px 16px;border-radius:12px;font-size:14px;font-weight:600;box-shadow:0 14px 34px -10px rgba(28,25,23,.35);display:flex;align-items:center;gap:10px"
            x-data x-init="setTimeout(() => $el.remove(), 3600)">
            @if($toast['kind'] === 'err')
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v4M12 16h.01"></path></svg>
            @else
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#F2B81D" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
            @endif
            <span>{{ $toast['message'] }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between;margin-bottom:20px">
        <div style="min-width:0">
            <h1 style="font-size:30px;font-weight:800;letter-spacing:-.035em;margin:0;line-height:1.05">{{ __('teacher.availability.title') }}</h1>
            <p style="font-size:15px;color:#78716C;margin:6px 0 0">{{ __('teacher.availability.subtitle') }}</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <button wire:click="openLeave" @style(['opacity:.45;cursor:not-allowed' => !$canEdit]) @disabled(!$canEdit)
                style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font:inherit;font-size:14px;font-weight:600;min-height:44px;padding:0 15px;border-radius:12px;cursor:pointer;display:inline-flex;align-items:center;gap:8px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M4.9 4.9l14.2 14.2"></path></svg>
                {{ __('teacher.availability.block_leave') }}
            </button>
            <button wire:click="openAdd('one')" @style(['opacity:.45;cursor:not-allowed' => !$canEdit]) @disabled(!$canEdit)
                style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:14px;font-weight:700;min-height:44px;padding:0 18px;border-radius:12px;cursor:pointer;box-shadow:0 10px 24px -8px rgba(222,165,0,.6);display:inline-flex;align-items:center;gap:8px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"></path></svg>
                {{ __('teacher.availability.add_availability') }}
            </button>
        </div>
    </div>

    {{-- Not approved banner --}}
    @unless($canEdit)
        <div style="display:flex;flex-wrap:wrap;gap:12px 16px;align-items:center;background:#FBF1DE;border:1px solid #F1DDB4;color:#7A4F0C;border-radius:16px;padding:16px 18px;margin-bottom:16px">
            <div style="width:40px;height:40px;border-radius:12px;background:#F6E3BC;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg></div>
            <div style="flex:1 1 280px"><div style="font-size:15px;font-weight:700">{{ __('teacher.availability.not_approved_title') }}</div><div style="font-size:13.5px;margin-top:2px">{{ __('teacher.availability.not_approved_body') }}</div></div>
            <a href="{{ route('teacher.profile') }}" style="background:#1C1917;color:#FAF8F5;font-size:14px;font-weight:700;min-height:44px;padding:0 16px;border-radius:12px;display:inline-flex;align-items:center">{{ __('teacher.availability.complete_profile') }}</a>
        </div>
    @endunless

    {{-- Learner-timezone preview banner --}}
    @if($previewLabel)
        <div style="display:flex;flex-wrap:wrap;gap:10px 14px;align-items:center;background:#FDF3D6;border:1px solid #F1DDB4;color:#7A4F0C;border-radius:14px;padding:12px 16px;margin-bottom:16px;font-size:14px">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <span style="flex:1 1 260px">{!! __('teacher.availability.preview_banner', ['tz' => '<b>'.$previewLabel.' ('.$previewGmt.')</b>', 'diff' => $previewDiff]) !!}</span>
            <button wire:click="setPreviewTz('home')" style="background:#fff;border:1px solid #F1DDB4;color:#7A4F0C;font:inherit;font-size:13.5px;font-weight:700;min-height:40px;padding:0 14px;border-radius:10px;cursor:pointer">{{ __('teacher.availability.preview_reset') }}</button>
        </div>
    @endif

    {{-- Onboarding empty state --}}
    @if($isEmpty && $canEdit)
        <div style="background:#FFFDF8;border:1px solid #F1DDB4;border-radius:20px;padding:clamp(22px,4vw,36px);margin-bottom:16px">
            <div style="font-size:12px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9A6A00">{{ __('teacher.availability.onboarding_eyebrow') }}</div>
            <h2 style="font-size:clamp(22px,3.2vw,30px);font-weight:800;letter-spacing:-.035em;line-height:1.1;margin:10px 0 10px">{{ __('teacher.availability.onboarding_title') }}</h2>
            <p style="font-size:15px;color:#78716C;line-height:1.55;margin:0 0 20px;max-width:560px">{{ __('teacher.availability.onboarding_body') }}</p>
            <div style="font-size:13px;font-weight:600;color:#78716C;margin-bottom:8px">{{ __('teacher.availability.onboarding_preset') }}</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px">
                @foreach(['weeknights','mornings','weekend'] as $preset)
                    <button wire:click="applyPreset('{{ $preset }}')" style="background:#fff;border:1px solid #E2DBD3;border-radius:12px;padding:9px 14px;min-height:44px;font:inherit;text-align:left;cursor:pointer">
                        <div style="font-size:13.5px;font-weight:700">{{ __('teacher.availability.presets.'.$preset.'.label') }}</div>
                        <div style="font-size:12px;color:#78716C">{{ __('teacher.availability.presets.'.$preset.'.sub') }}</div>
                    </button>
                @endforeach
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center">
                <button wire:click="openAdd('rec')" style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:15px;font-weight:700;min-height:48px;padding:0 20px;border-radius:12px;cursor:pointer;box-shadow:0 10px 24px -8px rgba(222,165,0,.6)">{{ __('teacher.availability.onboarding_cta') }}</button>
                <button wire:click="openAdd('one')" style="background:none;border:none;font:inherit;font-size:14px;font-weight:600;color:#9A6A00;cursor:pointer">{{ __('teacher.availability.onboarding_alt') }}</button>
            </div>
        </div>
    @endif

    {{-- Timezone + stats bar --}}
    <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;box-shadow:0 1px 2px rgba(28,25,23,.04);padding:14px 16px;display:flex;flex-wrap:wrap;gap:14px 24px;align-items:center;margin-bottom:14px">
        <div style="display:flex;align-items:center;gap:12px;min-width:0">
            <div style="width:42px;height:42px;border-radius:12px;background:#FDF3D6;color:#9A6A00;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg></div>
            <div style="min-width:0"><div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#A8A29E">{{ __('teacher.availability.active_tz') }}</div><div style="font-size:15px;font-weight:700;white-space:nowrap">{{ $tzActiveLabel }} ({{ $tzActiveGmt }})</div><div style="font-family:monospace;font-size:11.5px;color:#78716C">{{ $tzActiveIana }}</div></div>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;flex:0 1 250px;min-width:200px">
            <label style="font-size:12px;font-weight:600;color:#78716C">{{ __('teacher.availability.preview_label') }}</label>
            <select wire:model.live="previewTz" style="height:40px;border:1px solid {{ $previewLabel ? '#F2B81D' : '#E2DBD3' }};border-radius:10px;padding:0 10px;font:inherit;font-size:14px;font-weight:600;background:{{ $previewLabel ? '#FDF3D6' : '#fff' }};color:#1C1917;cursor:pointer">
                @foreach($tzOptions as $opt)
                    <option value="{{ $opt['key'] === 'home' ? '' : $opt['key'] }}">{{ $opt['label'] }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex:1"></div>
        <div style="display:flex;gap:0;flex-wrap:wrap">
            <div style="padding:4px 18px;border-left:1px solid #F1ECE6"><div style="font-size:24px;font-weight:800;letter-spacing:-.04em;line-height:1.1">{{ $statHours }} h</div><div style="font-size:12px;color:#78716C">{{ __('teacher.availability.stat_hours') }}</div></div>
            <div style="padding:4px 18px;border-left:1px solid #F1ECE6"><div style="font-size:24px;font-weight:800;letter-spacing:-.04em;line-height:1.1;color:#2F7D5B">{{ $statOpen }}</div><div style="font-size:12px;color:#78716C">{{ __('teacher.availability.stat_open') }}</div></div>
            <div style="padding:4px 18px;border-left:1px solid #F1ECE6"><div style="font-size:24px;font-weight:800;letter-spacing:-.04em;line-height:1.1;color:#4338CA">{{ $statBooked }}</div><div style="font-size:12px;color:#78716C">{{ __('teacher.availability.stat_booked') }}</div></div>
        </div>
    </div>

    {{-- View toggle + navigation + legend --}}
    <div style="display:flex;flex-wrap:wrap;gap:10px 14px;align-items:center;margin-bottom:12px">
        <div role="tablist" style="display:flex;background:#F1ECE6;border-radius:12px;padding:4px;gap:2px">
            @foreach(['week' => __('teacher.availability.view_week'), 'month' => __('teacher.availability.view_month'), 'agenda' => __('teacher.availability.view_agenda')] as $v => $label)
                <button wire:click="setView('{{ $v }}')" role="tab" style="{{ $view === $v ? 'background:#fff;color:#1C1917;box-shadow:0 1px 3px rgba(28,25,23,.12)' : 'background:transparent;color:#78716C' }};border:none;font:inherit;font-size:14px;font-weight:600;min-height:38px;padding:0 15px;border-radius:9px;cursor:pointer">{{ $label }}</button>
            @endforeach
        </div>
        @if($showNav)
        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;min-width:0">
            <button wire:click="navPrev" aria-label="{{ __('teacher.availability.prev') }}" style="width:40px;height:40px;border-radius:10px;border:1px solid #E2DBD3;background:#fff;color:#1C1917;cursor:pointer;display:flex;align-items:center;justify-content:center"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"></path></svg></button>
            <button wire:click="navNext" aria-label="{{ __('teacher.availability.next') }}" style="width:40px;height:40px;border-radius:10px;border:1px solid #E2DBD3;background:#fff;color:#1C1917;cursor:pointer;display:flex;align-items:center;justify-content:center"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"></path></svg></button>
            <button wire:click="goToday" style="height:40px;border-radius:10px;border:1px solid #E2DBD3;background:#fff;color:#1C1917;font:inherit;font-size:13.5px;font-weight:600;padding:0 13px;cursor:pointer">{{ __('teacher.availability.today') }}</button>
            <span style="font-size:15px;font-weight:700;letter-spacing:-.01em;margin-left:6px;white-space:nowrap">{{ $weekRangeLabel }}</span>
            @if($view === 'week')<input type="date" value="{{ $weekStart }}" wire:change="goToDate($event.target.value)" aria-label="{{ __('teacher.availability.go_to_date') }}" style="height:40px;border:1px solid #E2DBD3;border-radius:10px;padding:0 8px;font:inherit;font-size:13px;background:#fff;color:#78716C;cursor:pointer">@endif
        </div>
        @endif
        <div style="flex:1"></div>
        @if($view === 'agenda')
            <div style="display:flex;gap:6px">
                @foreach(['all' => __('teacher.availability.agenda_filter_all'), 'open' => __('teacher.availability.agenda_filter_open'), 'booked' => __('teacher.availability.agenda_filter_booked')] as $f => $label)
                    @php($on = $agendaFilter === $f)
                    <button wire:click="setAgendaFilter('{{ $f }}')" style="background:{{ $on ? '#1C1917' : '#fff' }};color:{{ $on ? '#fff' : '#57534E' }};border:1px solid {{ $on ? '#1C1917' : '#E2DBD3' }};font:inherit;font-size:13.5px;font-weight:600;min-height:40px;padding:0 14px;border-radius:999px;cursor:pointer">{{ $label }}</button>
                @endforeach
            </div>
        @endif
        @if($view === 'week')
        <div style="display:flex;flex-wrap:wrap;gap:6px;font-size:12px;font-weight:600">
            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;background:#E8F3EC;color:#2F7D5B;border:1px solid #C5E4CF"><span style="width:7px;height:7px;border-radius:99px;background:#3A9A6E"></span>{{ __('teacher.availability.status_available') }}</span>
            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;background:#EEF2FF;color:#4338CA;border:1px solid #C7D2FE"><span style="width:7px;height:7px;border-radius:99px;background:#6366F1"></span>{{ __('teacher.availability.status_booked') }}</span>
            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;background:#FBF1DE;color:#7A4F0C;border:1px solid #F1DDB4"><span style="width:7px;height:7px;border-radius:99px;background:#D69E2E"></span>{{ __('teacher.availability.status_leave') }}</span>
            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;background:#F1ECE6;color:#78716C;border:1px solid #E2DBD3"><span style="width:7px;height:7px;border-radius:99px;background:#A8A29E"></span>{{ __('teacher.availability.status_past') }}</span>
        </div>
        @endif
    </div>

    {{-- Calendar card --}}
    <div style="background:#fff;border:1px solid #EAE4DD;border-radius:18px;overflow:hidden;box-shadow:0 1px 2px rgba(28,25,23,.04);position:relative">
        @unless($canEdit)
            <div style="position:absolute;top:12px;right:12px;z-index:20;display:inline-flex;align-items:center;gap:6px;background:#1C1917;color:#FAF8F5;font-size:12.5px;font-weight:700;padding:6px 11px;border-radius:999px"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg>{{ __('teacher.availability.read_only') }}</div>
        @endunless
        <div style="opacity:{{ $canEdit ? '1' : '.5' }};filter:{{ $canEdit ? 'none' : 'grayscale(.4)' }};pointer-events:{{ $canEdit ? 'auto' : 'none' }}">
            @if($view === 'week')
                @include('livewire.teacher.availability._week')
            @elseif($view === 'month')
                @include('livewire.teacher.availability._month')
            @else
                @include('livewire.teacher.availability._agenda')
            @endif
        </div>
    </div>

    {{-- Settings footer --}}
    <div style="display:flex;flex-wrap:wrap;gap:12px 26px;align-items:center;padding:14px 18px;margin-top:14px;border:1px solid #EAE4DD;border-radius:16px;background:#FCFAF8">
        @php($seg = fn($active) => $active ? 'background:#fff;color:#1C1917;box-shadow:0 1px 3px rgba(28,25,23,.12)' : 'background:transparent;color:#78716C')
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap"><span style="font-size:12.5px;font-weight:600;color:#78716C">{{ __('teacher.availability.default_duration') }}</span><div style="display:flex;background:#F1ECE6;border-radius:10px;padding:3px;gap:2px">
            @foreach([60,90,120] as $v)<button wire:click="setDefaultDuration({{ $v }})" style="{{ $seg($defaultDuration === $v) }};border:none;font:inherit;font-size:13px;font-weight:600;min-height:34px;padding:0 11px;border-radius:8px;cursor:pointer">{{ $v }} min</button>@endforeach
        </div></div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap"><span style="font-size:12.5px;font-weight:600;color:#78716C">{{ __('teacher.availability.buffer') }}</span><div style="display:flex;background:#F1ECE6;border-radius:10px;padding:3px;gap:2px">
            @foreach([0,15,30] as $v)<button wire:click="setBuffer({{ $v }})" style="{{ $seg($buffer === $v) }};border:none;font:inherit;font-size:13px;font-weight:600;min-height:34px;padding:0 11px;border-radius:8px;cursor:pointer">{{ $v ? $v.' min' : __('teacher.availability.buffer_none') }}</button>@endforeach
        </div></div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap"><span style="font-size:12.5px;font-weight:600;color:#78716C">{{ __('teacher.availability.horizon') }}</span><div style="display:flex;background:#F1ECE6;border-radius:10px;padding:3px;gap:2px">
            @foreach([4,8,12] as $v)<button wire:click="setHorizon({{ $v }})" style="{{ $seg($horizonWeeks === $v) }};border:none;font:inherit;font-size:13px;font-weight:600;min-height:34px;padding:0 11px;border-radius:8px;cursor:pointer">{{ $v }} {{ __('teacher.availability.weeks_short') }}</button>@endforeach
        </div></div>
        <span style="flex:1"></span>
        <span style="font-size:12.5px;color:#A8A29E">{{ __('teacher.availability.generated_until', ['date' => $horizonEnd]) }}</span>
    </div>

    @include('livewire.teacher.availability._modals')
</div>
