@php
    $mType = $modal ? ($modal['type'] === 'add' ? 'add-'.$modal['tab'] : $modal['type']) : null;
    $titles = [
        'add-one' => [__('teacher.availability.modal.add_title'), __('teacher.availability.modal.one_sub')],
        'add-rec' => [__('teacher.availability.modal.add_title'), __('teacher.availability.modal.rec_sub')],
        'booked'  => [__('teacher.availability.modal.booked_title'), ''],
        'slot'    => [__('teacher.availability.modal.slot_title'), ''],
        'leave'   => [__('teacher.availability.modal.leave_title'), __('teacher.availability.modal.leave_sub')],
        'del'     => [__('teacher.availability.modal.del_title'), __('teacher.availability.modal.del_sub')],
        'pub'     => [__('teacher.availability.modal.pub_title'), __('teacher.availability.modal.pub_sub')],
    ];
    $pill = fn($on) => $on ? 'background:#FDF3D6;color:#7A5300;border:1px solid #F2B81D' : 'background:#fff;color:#57534E;border:1px solid #E2DBD3';
@endphp

@if($mType)
<div wire:key="modal-{{ $mType }}" x-data x-on:keydown.escape.window="$wire.closeModal()" wire:click="closeModal"
    style="position:fixed;inset:0;z-index:70;background:rgba(28,25,23,.42);backdrop-filter:blur(3px);display:flex;align-items:center;justify-content:center;padding:24px">
    <div wire:click.stop role="dialog" aria-modal="true"
        style="background:#fff;width:100%;max-width:{{ $mType === 'add-rec' ? '640px' : '540px' }};max-height:90vh;border-radius:20px;box-shadow:0 30px 80px -20px rgba(28,25,23,.45);display:flex;flex-direction:column;overflow:hidden">

        {{-- Header --}}
        <div style="display:flex;align-items:flex-start;gap:12px;padding:20px 22px 14px">
            <div style="flex:1;min-width:0"><div style="font-size:19px;font-weight:800;letter-spacing:-.03em">{{ $titles[$mType][0] }}</div><div style="font-size:13.5px;color:#78716C;margin-top:3px">{{ $titles[$mType][1] }}</div></div>
            <button wire:click="closeModal" aria-label="{{ __('teacher.availability.modal.close') }}" style="width:40px;height:40px;border-radius:10px;border:1px solid #EAE4DD;background:#fff;color:#57534E;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"></path></svg></button>
        </div>

        <div style="flex:1;overflow:auto;padding:4px 22px 22px">

            {{-- Add: tab switcher --}}
            @if($modal['type'] === 'add')
                <div role="tablist" style="display:flex;background:#F1ECE6;border-radius:12px;padding:4px;gap:2px;margin-bottom:20px">
                    @foreach(['one' => __('teacher.availability.tab_one'), 'rec' => __('teacher.availability.tab_rec')] as $tab => $label)
                        <button wire:click="openAdd('{{ $tab }}')" style="flex:1;{{ $modal['tab'] === $tab ? 'background:#fff;color:#1C1917;box-shadow:0 1px 3px rgba(28,25,23,.12)' : 'background:transparent;color:#78716C' }};border:none;font:inherit;font-size:14px;font-weight:700;min-height:40px;border-radius:9px;cursor:pointer">{{ $label }}</button>
                    @endforeach
                </div>
            @endif

            {{-- A. One-off --}}
            @if($mType === 'add-one')
                <div style="display:flex;flex-direction:column;gap:16px">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px">
                        <div><label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.field_date') }}</label><input type="date" wire:model.live="oneOff.date" style="width:100%;height:44px;border:1px solid #E2DBD3;border-radius:12px;padding:0 12px;font:inherit;font-size:14.5px;background:#fff;color:#1C1917"></div>
                        <div><label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.field_start') }} <span style="font-weight:400;color:#A8A29E">({{ $tzActiveLabel }})</span></label><input type="time" step="900" wire:model.live="oneOff.start" style="width:100%;height:44px;border:1px solid #E2DBD3;border-radius:12px;padding:0 12px;font:inherit;font-size:14.5px;background:#fff;color:#1C1917"></div>
                    </div>
                    <div><label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.field_duration') }}</label><div style="display:flex;gap:8px">
                        @foreach([60,90,120] as $v)<button wire:click="$set('oneOff.duration', {{ $v }})" style="flex:1;{{ $pill($oneOff->duration === $v) }};font:inherit;font-size:14px;font-weight:700;min-height:44px;border-radius:12px;cursor:pointer">{{ $v }} min</button>@endforeach
                    </div></div>
                    @error('oneOff.start')<div role="alert" style="display:flex;gap:10px;align-items:center;background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;border-radius:12px;padding:12px 14px;font-size:14px;font-weight:700"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v4M12 16h.01"></path></svg>{{ $message }}</div>@enderror
                    @if($oneSummary && !$errors->has('oneOff.start'))
                        <div style="display:flex;gap:10px;align-items:center;background:#E8F3EC;border:1px solid #C5E4CF;color:#2F7D5B;border-radius:12px;padding:12px 14px;font-size:14px;font-weight:600"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>{{ $oneSummary }}</div>
                    @endif
                    @if($onePreview)
                        <div style="background:#FCFAF8;border:1px solid #F1ECE6;border-radius:14px;padding:14px 16px">
                            <div style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#A8A29E;margin-bottom:8px">{{ __('teacher.availability.learners_will_see') }}</div>
                            @foreach($onePreview as $p)<div style="display:flex;justify-content:space-between;gap:12px;padding:7px 0;border-top:1px solid #F1ECE6;font-size:14px"><span style="color:#57534E">{{ $p['city'] }} <span style="color:#A8A29E">· {{ $p['gmt'] }}</span></span><span style="font-weight:700">= {{ $p['when'] }}</span></div>@endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- B. Recurring --}}
            @if($mType === 'add-rec')
                <div style="display:flex;flex-direction:column;gap:18px">
                    <div><div style="font-size:13px;font-weight:600;margin-bottom:8px">{{ __('teacher.availability.field_days') }}</div><div style="display:flex;gap:8px;flex-wrap:wrap">
                        @foreach(range(0,6) as $dow)
                            @php($on = isset($recDays[$dow]))
                            <button wire:click="toggleRecDay({{ $dow }})" aria-pressed="{{ $on ? 'true' : 'false' }}" title="{{ __('teacher.availability.days.'.$dow) }}" style="width:44px;height:44px;border-radius:99px;border:1px solid {{ $on ? '#F2B81D' : '#E2DBD3' }};background:{{ $on ? '#F2B81D' : '#fff' }};color:#1C1917;font:inherit;font-size:14.5px;font-weight:800;cursor:pointer">{{ __('teacher.availability.days_letter.'.$dow) }}</button>
                        @endforeach
                    </div></div>
                    @if(empty($recDays))
                        <div style="font-size:13.5px;color:#A8A29E">{{ __('teacher.availability.rec_pick_day') }}</div>
                    @endif
                    @foreach($recDays as $dow => $ranges)
                        <div wire:key="recday-{{ $dow }}" style="border:1px solid #EAE4DD;border-radius:14px;padding:14px">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;flex-wrap:wrap">
                                <span style="font-size:14.5px;font-weight:700">{{ __('teacher.availability.every', ['day' => mb_strtolower(__('teacher.availability.days.'.$dow))]) }}</span>
                                <span style="flex:1"></span>
                                <button wire:click="startCopy({{ $dow }})" style="background:transparent;border:none;font:inherit;font-size:13px;font-weight:600;color:#9A6A00;cursor:pointer;min-height:36px;display:flex;align-items:center;gap:6px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>{{ __('teacher.availability.copy_to_days') }}</button>
                            </div>
                            @if($copyFrom === $dow)
                                <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;background:#FFFDF8;border:1px solid #F1DDB4;border-radius:12px;padding:10px;margin-bottom:10px">
                                    @foreach(range(0,6) as $target)
                                        @if($target !== $dow)
                                            @php($ton = $copyTo[$target] ?? false)
                                            <button wire:click="toggleCopyTarget({{ $target }})" title="{{ __('teacher.availability.days.'.$target) }}" style="width:40px;height:40px;border-radius:99px;border:1px solid {{ $ton ? '#F2B81D' : '#E2DBD3' }};background:{{ $ton ? '#F2B81D' : '#fff' }};color:#1C1917;font:inherit;font-size:13.5px;font-weight:800;cursor:pointer">{{ __('teacher.availability.days_letter.'.$target) }}</button>
                                        @endif
                                    @endforeach
                                    <button wire:click="applyCopy" style="margin-left:auto;background:#1C1917;color:#fff;border:none;font:inherit;font-size:13px;font-weight:700;min-height:40px;padding:0 14px;border-radius:10px;cursor:pointer">{{ __('teacher.availability.copy_apply') }}</button>
                                </div>
                            @endif
                            <div style="display:flex;flex-direction:column;gap:8px">
                                @foreach($ranges as $ri => $range)
                                    @php($from = (int)explode(':',$range[0])[0]*60 + (int)explode(':',$range[0])[1])
                                    @php($to = (int)explode(':',$range[1])[0]*60 + (int)explode(':',$range[1])[1])
                                    @php($bad = $to <= $from)
                                    <div wire:key="recrange-{{ $dow }}-{{ $ri }}">
                                        <div style="display:flex;align-items:center;gap:8px">
                                            <input type="time" step="900" wire:model.live="recDays.{{ $dow }}.{{ $ri }}.0" style="flex:1;min-width:0;height:44px;border:1px solid {{ $bad ? '#FECACA' : '#E2DBD3' }};border-radius:12px;padding:0 10px;font:inherit;font-size:14.5px;background:#fff;color:#1C1917">
                                            <span style="color:#A8A29E">–</span>
                                            <input type="time" step="900" wire:model.live="recDays.{{ $dow }}.{{ $ri }}.1" style="flex:1;min-width:0;height:44px;border:1px solid {{ $bad ? '#FECACA' : '#E2DBD3' }};border-radius:12px;padding:0 10px;font:inherit;font-size:14.5px;background:#fff;color:#1C1917">
                                            <button wire:click="removeRecRange({{ $dow }}, {{ $ri }})" aria-label="{{ __('teacher.availability.rec_remove_range') }}" style="width:40px;height:40px;border-radius:10px;border:1px solid transparent;background:transparent;color:#A8A29E;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"></path></svg></button>
                                        </div>
                                        @if($bad)<div style="font-size:12.5px;font-weight:600;color:#DC2626;margin-top:6px">{{ __('teacher.availability.err_range_order') }}</div>@endif
                                    </div>
                                @endforeach
                                <button wire:click="addRecRange({{ $dow }})" style="align-self:flex-start;background:transparent;border:1px dashed #E2DBD3;color:#57534E;font:inherit;font-size:13px;font-weight:600;min-height:40px;padding:0 12px;border-radius:10px;cursor:pointer">{{ __('teacher.availability.rec_add_range') }}</button>
                            </div>
                        </div>
                    @endforeach
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:14px">
                        <div><div style="font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.field_slot_duration') }}</div><div style="display:flex;gap:6px">@foreach([60,90,120] as $v)<button wire:click="setRecDuration({{ $v }})" style="flex:1;{{ $pill($recDuration === $v) }};font:inherit;font-size:13px;font-weight:700;min-height:40px;border-radius:10px;cursor:pointer">{{ $v }} min</button>@endforeach</div></div>
                        <div><div style="font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.buffer') }}</div><div style="display:flex;gap:6px">@foreach([0,15,30] as $v)<button wire:click="setRecBuffer({{ $v }})" style="flex:1;{{ $pill($recBuffer === $v) }};font:inherit;font-size:13px;font-weight:700;min-height:40px;border-radius:10px;cursor:pointer">{{ $v ? $v.' min' : __('teacher.availability.buffer_none') }}</button>@endforeach</div></div>
                        <div><div style="font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.horizon') }}</div><div style="display:flex;gap:6px">@foreach([4,8,12] as $v)<button wire:click="setRecHorizon({{ $v }})" style="flex:1;{{ $pill($recHorizon === $v) }};font:inherit;font-size:13px;font-weight:700;min-height:40px;border-radius:10px;cursor:pointer">{{ $v }} {{ __('teacher.availability.weeks_short') }}</button>@endforeach</div></div>
                    </div>
                    <div style="background:#FFFDF8;border:1px solid #F1DDB4;border-radius:14px;padding:16px">
                        <div style="font-size:20px;font-weight:800;letter-spacing:-.03em">{{ trans_choice('teacher.availability.rec_total', $recErrors['total'], ['count' => $recErrors['total']]) }}</div>
                        @foreach($recErrors['summary'] as $line)<div style="font-size:13.5px;color:#57534E;margin-top:6px">{{ $line }}</div>@endforeach
                    </div>
                </div>
            @endif

            {{-- C. Booked --}}
            @if($mType === 'booked' && $bookedData)
                <div style="display:flex;flex-direction:column;gap:14px">
                    <div style="display:flex;align-items:center;gap:14px"><div style="width:52px;height:52px;border-radius:14px;background:#EEF2FF;color:#4338CA;font-size:17px;font-weight:800;display:flex;align-items:center;justify-content:center">{{ $bookedData['initials'] }}</div><div style="flex:1;min-width:0"><div style="font-size:17px;font-weight:700">{{ $bookedData['name'] }}</div><div style="font-size:13px;color:#78716C">{{ __('teacher.availability.learner') }} · {{ $bookedData['gmt'] }}</div></div><span style="display:inline-flex;align-items:center;gap:6px;padding:5px 11px;border-radius:999px;background:#EEF2FF;color:#4338CA;border:1px solid #C7D2FE;font-size:12.5px;font-weight:700"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg>{{ __('teacher.availability.status_booked') }}</span></div>
                    <div style="border:1px solid #EAE4DD;border-radius:14px;overflow:hidden">
                        <div style="display:flex;justify-content:space-between;gap:12px;padding:12px 14px;font-size:14px"><span style="color:#78716C">{{ $tzActiveLabel }} ({{ $tzActiveGmt }})</span><span style="font-weight:700;text-align:right">{{ $bookedData['teacherWhen'] }}</span></div>
                        <div style="display:flex;justify-content:space-between;gap:12px;padding:12px 14px;font-size:14px;border-top:1px solid #F1ECE6"><span style="color:#78716C">{{ __('teacher.availability.learner_time') }} ({{ $bookedData['gmt'] }})</span><span style="font-weight:700;text-align:right">{{ $bookedData['learnerWhen'] }}</span></div>
                        <div style="display:flex;justify-content:space-between;gap:12px;padding:12px 14px;font-size:14px;border-top:1px solid #F1ECE6"><span style="color:#78716C">{{ __('teacher.availability.field_duration') }}</span><span style="font-weight:700">{{ $bookedData['duration'] }} min</span></div>
                        @if($bookedData['meetLink'])<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;padding:10px 14px;font-size:14px;border-top:1px solid #F1ECE6"><span style="color:#78716C">Google Meet</span><span style="display:flex;align-items:center;gap:8px;min-width:0"><span style="font-family:monospace;font-size:13px;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $bookedData['meetLink'] }}</span><button type="button" x-data @click="navigator.clipboard?.writeText('{{ $bookedData['meetLink'] }}'); $wire.copyMeet()" style="height:34px;border-radius:9px;border:1px solid #E2DBD3;background:#fff;font:inherit;font-size:12.5px;font-weight:600;padding:0 10px;cursor:pointer;flex-shrink:0">{{ __('teacher.availability.booked_copy') }}</button></span></div>@endif
                    </div>
                    <div role="alert" style="display:flex;gap:10px;align-items:flex-start;background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;border-radius:12px;padding:12px 14px"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg><div><div style="font-size:14px;font-weight:700">{{ __('teacher.availability.booked_locked_title') }}</div><div style="font-size:13px;color:#B91C1C;margin-top:2px">{{ __('teacher.availability.booked_locked_body') }}</div></div></div>
                </div>
            @endif

            {{-- Slot detail --}}
            @if($mType === 'slot' && $slotData)
                <div style="display:flex;flex-direction:column;gap:14px">
                    <div style="display:flex;gap:8px;flex-wrap:wrap"><span style="display:inline-flex;align-items:center;gap:6px;padding:5px 11px;border-radius:999px;background:#E8F3EC;color:#2F7D5B;border:1px solid #C5E4CF;font-size:12.5px;font-weight:700"><span style="width:7px;height:7px;border-radius:99px;background:#3A9A6E"></span>{{ __('teacher.availability.status_available') }}</span>@if($slotData['rec'])<span style="display:inline-flex;align-items:center;gap:6px;padding:5px 11px;border-radius:999px;background:#fff;color:#57534E;border:1px solid #E2DBD3;font-size:12.5px;font-weight:600">{{ $slotData['recLabel'] }}</span>@endif</div>
                    <div style="border:1px solid #EAE4DD;border-radius:14px;overflow:hidden">
                        <div style="display:flex;justify-content:space-between;gap:12px;padding:12px 14px;font-size:14px"><span style="color:#78716C">{{ $tzActiveLabel }}</span><span style="font-weight:700;text-align:right">{{ $slotData['when'] }}</span></div>
                        @foreach($slotData['preview'] as $p)<div style="display:flex;justify-content:space-between;gap:12px;padding:12px 14px;font-size:14px;border-top:1px solid #F1ECE6"><span style="color:#78716C">{{ $p['city'] }} · {{ $p['gmt'] }}</span><span style="font-weight:600;text-align:right">{{ $p['when'] }}</span></div>@endforeach
                    </div>
                </div>
            @endif

            {{-- D. Leave --}}
            @if($mType === 'leave')
                <div style="display:flex;flex-direction:column;gap:16px">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px">
                        <div><label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.field_from') }}</label><input type="date" wire:model="timeOff.from" style="width:100%;height:44px;border:1px solid #E2DBD3;border-radius:12px;padding:0 12px;font:inherit;font-size:14.5px;background:#fff;color:#1C1917"></div>
                        <div><label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.field_to') }}</label><input type="date" wire:model="timeOff.to" style="width:100%;height:44px;border:1px solid #E2DBD3;border-radius:12px;padding:0 12px;font:inherit;font-size:14.5px;background:#fff;color:#1C1917"></div>
                    </div>
                    <div><label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">{{ __('teacher.availability.field_reason') }} <span style="font-weight:400;color:#A8A29E">{{ __('teacher.availability.field_reason_hint') }}</span></label><input wire:model="timeOff.reason" placeholder="{{ __('teacher.availability.reason_placeholder') }}" style="width:100%;height:44px;border:1px solid #E2DBD3;border-radius:12px;padding:0 12px;font:inherit;font-size:14.5px;background:#fff;color:#1C1917"></div>
                    @error('timeOff.to')<div role="alert" style="display:flex;gap:10px;align-items:center;background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;border-radius:12px;padding:12px 14px;font-size:14px;font-weight:700">{{ $message }}</div>@enderror
                    <div style="display:flex;gap:10px;align-items:center;background:#FCFAF8;border:1px solid #F1ECE6;border-radius:12px;padding:12px 14px;font-size:14px;color:#57534E"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M4.9 4.9l14.2 14.2"></path></svg>{{ __('teacher.availability.leave_explainer') }}</div>
                </div>
            @endif

            {{-- E. Delete --}}
            @if($mType === 'del' && $deleteData)
                <div style="display:flex;flex-direction:column;gap:12px">
                    <div style="border:1px solid #EAE4DD;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:12px"><span style="width:8px;height:8px;border-radius:99px;background:#3A9A6E"></span><span style="font-size:14.5px;font-weight:700;flex:1">{{ $deleteData['when'] }}</span></div>
                    @if($deleteData['rec'])
                        @foreach(['one' => __('teacher.availability.del_one'), 'series' => __('teacher.availability.del_series')] as $mode => $label)
                            @php($on = $delMode === $mode)
                            <button wire:click="$set('delMode', '{{ $mode }}')" role="radio" aria-checked="{{ $on ? 'true' : 'false' }}" style="display:flex;gap:12px;align-items:flex-start;text-align:left;background:{{ $on ? '#FFFDF8' : '#fff' }};border:1px solid {{ $on ? '#F2B81D' : '#E2DBD3' }};border-radius:14px;padding:14px;font:inherit;cursor:pointer;width:100%"><span style="width:20px;height:20px;border-radius:99px;border:2px solid {{ $on ? '#F2B81D' : '#D6D3D1' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px"><span style="width:10px;height:10px;border-radius:99px;background:{{ $on ? '#F2B81D' : 'transparent' }}"></span></span><span><span style="display:block;font-size:14.5px;font-weight:700;color:#1C1917">{{ $label }}</span><span style="display:block;font-size:13px;color:#78716C;margin-top:2px">{{ __('teacher.availability.del_'.$mode.'_sub') }}</span></span></button>
                        @endforeach
                    @else
                        <div style="font-size:14px;color:#57534E">{{ __('teacher.availability.del_single_explainer') }}</div>
                    @endif
                </div>
            @endif

            {{-- Publish confirm --}}
            @if($mType === 'pub')
                <div style="display:flex;flex-direction:column;gap:14px">
                    <div style="background:#FFFDF8;border:1px solid #F1DDB4;border-radius:14px;padding:18px;text-align:center"><div style="font-size:34px;font-weight:800;letter-spacing:-.04em">{{ $recErrors['total'] }}</div><div style="font-size:14px;color:#9A6A00;font-weight:600">{{ trans_choice('teacher.availability.pub_over', $recHorizon, ['count' => $recHorizon]) }}</div></div>
                    @foreach($recErrors['summary'] as $line)<div style="font-size:14px;color:#57534E;display:flex;align-items:center;gap:8px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2F7D5B" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>{{ $line }}</div>@endforeach
                    <div style="font-size:13.5px;color:#78716C">{{ __('teacher.availability.pub_note') }}</div>
                </div>
            @endif
        </div>

        {{-- Footer actions --}}
        <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;padding:14px 22px;border-top:1px solid #F1ECE6;background:#fff">
            @php($cancel = '<button wire:click="closeModal" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font:inherit;font-size:14.5px;font-weight:600;min-height:46px;padding:0 18px;border-radius:12px;cursor:pointer">')
            @if($mType === 'add-one')
                <button wire:click="closeModal" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font:inherit;font-size:14.5px;font-weight:600;min-height:46px;padding:0 18px;border-radius:12px;cursor:pointer">{{ __('teacher.availability.cancel') }}</button>
                <button wire:click="addOneOff" style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:14.5px;font-weight:700;min-height:46px;padding:0 20px;border-radius:12px;cursor:pointer;box-shadow:0 10px 24px -8px rgba(222,165,0,.6)">{{ __('teacher.availability.add') }}</button>
            @elseif($mType === 'add-rec')
                <button wire:click="closeModal" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font:inherit;font-size:14.5px;font-weight:600;min-height:46px;padding:0 18px;border-radius:12px;cursor:pointer">{{ __('teacher.availability.cancel') }}</button>
                <button wire:click="askPublish" @disabled($recErrors['blocked']) style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:14.5px;font-weight:700;min-height:46px;padding:0 20px;border-radius:12px;cursor:pointer;opacity:{{ $recErrors['blocked'] ? '.45' : '1' }};box-shadow:0 10px 24px -8px rgba(222,165,0,.6)">{{ __('teacher.availability.publish_recurrence') }}</button>
            @elseif($mType === 'booked')
                <button wire:click="supportCancel" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font:inherit;font-size:14.5px;font-weight:600;min-height:46px;padding:0 18px;border-radius:12px;cursor:pointer">{{ __('teacher.availability.booked_support') }}</button>
                <button wire:click="contactLearner" style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:14.5px;font-weight:700;min-height:46px;padding:0 20px;border-radius:12px;cursor:pointer;box-shadow:0 10px 24px -8px rgba(222,165,0,.6)">{{ __('teacher.availability.booked_contact') }}</button>
            @elseif($mType === 'slot')
                <button wire:click="askDelete({{ $modal['id'] }})" style="background:#fff;border:1px solid #FECACA;color:#DC2626;font:inherit;font-size:14.5px;font-weight:700;min-height:46px;padding:0 18px;border-radius:12px;cursor:pointer">{{ __('teacher.availability.delete') }}</button>
                <button wire:click="closeModal" style="background:#1C1917;color:#fff;border:none;font:inherit;font-size:14.5px;font-weight:700;min-height:46px;padding:0 20px;border-radius:12px;cursor:pointer">{{ __('teacher.availability.modal.close') }}</button>
            @elseif($mType === 'leave')
                <button wire:click="closeModal" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font:inherit;font-size:14.5px;font-weight:600;min-height:46px;padding:0 18px;border-radius:12px;cursor:pointer">{{ __('teacher.availability.cancel') }}</button>
                <button wire:click="addTimeOff" style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:14.5px;font-weight:700;min-height:46px;padding:0 20px;border-radius:12px;cursor:pointer;box-shadow:0 10px 24px -8px rgba(222,165,0,.6)">{{ __('teacher.availability.block_period') }}</button>
            @elseif($mType === 'del')
                <button wire:click="closeModal" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font:inherit;font-size:14.5px;font-weight:600;min-height:46px;padding:0 18px;border-radius:12px;cursor:pointer">{{ __('teacher.availability.cancel') }}</button>
                <button wire:click="deleteSlot" style="background:#DC2626;color:#fff;border:none;font:inherit;font-size:14.5px;font-weight:700;min-height:46px;padding:0 20px;border-radius:12px;cursor:pointer">{{ $deleteData['rec'] && $delMode === 'series' ? __('teacher.availability.delete_series') : __('teacher.availability.delete') }}</button>
            @elseif($mType === 'pub')
                <button wire:click="backToRecurrence" style="background:#fff;border:1px solid #E2DBD3;color:#1C1917;font:inherit;font-size:14.5px;font-weight:600;min-height:46px;padding:0 18px;border-radius:12px;cursor:pointer">{{ __('teacher.availability.back') }}</button>
                <button wire:click="publishRecurrence" style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:14.5px;font-weight:700;min-height:46px;padding:0 20px;border-radius:12px;cursor:pointer;box-shadow:0 10px 24px -8px rgba(222,165,0,.6)">{{ __('teacher.availability.publish') }}</button>
            @endif
        </div>
    </div>
</div>
@endif
