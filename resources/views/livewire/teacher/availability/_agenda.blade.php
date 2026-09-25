{{-- Agenda list --}}
<div>
    @forelse($agendaGroups as $group)
        <div wire:key="ag-{{ $loop->index }}" style="padding:12px 20px;display:flex;align-items:center;gap:10px;background:#FCFAF8;border-bottom:1px solid #F1ECE6">
            <span style="font-size:13.5px;font-weight:700">{{ $group['title'] }}</span>
            @if($group['isToday'])<span style="font-size:11px;font-weight:700;color:#9A6A00;background:#FDF3D6;padding:2px 8px;border-radius:99px">{{ __('teacher.availability.today') }}</span>@endif
            <span style="font-size:12.5px;color:#A8A29E;margin-left:auto">{{ $group['summary'] }}</span>
        </div>

        @if($group['leave'])
            <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid #F1ECE6;background:repeating-linear-gradient(135deg,#FFFDF8 0,#FFFDF8 8px,#FBF1DE 8px,#FBF1DE 16px);color:#7A4F0C;font-size:14px;font-weight:600"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="10"></circle><path d="M4.9 4.9l14.2 14.2"></path></svg>{{ __('teacher.availability.status_leave') }} · {{ $group['leave'] }}</div>
        @endif

        @foreach($group['rows'] as $row)
            <div wire:key="agrow-{{ $row['id'] }}" wire:click="openBlock({{ $row['id'] }})" style="display:flex;align-items:center;gap:14px;padding:12px 20px;border-bottom:1px solid #F1ECE6;cursor:pointer">
                <div style="width:40px;text-align:center;flex-shrink:0"><div style="font-size:20px;font-weight:800;color:#9A6A00;line-height:1">{{ $row['day'] }}</div><div style="font-size:10.5px;color:#A8A29E;text-transform:uppercase;letter-spacing:.08em;margin-top:4px">{{ $row['mon'] }}</div></div>
                <div style="width:1px;align-self:stretch;background:#EAE4DD"></div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:14.5px;font-weight:600;display:flex;gap:6px;align-items:center">{{ $row['range'] }}@if($row['rec'])<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#A8A29E" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 1l4 4-4 4"></path><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><path d="M7 23l-4-4 4-4"></path><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>@endif</div>
                    <div style="font-size:13px;color:#78716C;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $row['sub'] }}</div>
                </div>
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;background:{{ $row['colors']['bg'] }};color:{{ $row['colors']['fg'] }};border:1px solid {{ $row['colors']['bd'] }};font-size:12px;font-weight:600;white-space:nowrap"><span style="width:6px;height:6px;border-radius:99px;background:{{ $row['colors']['dot'] }}"></span>{{ $row['label'] }}</span>
                @if($row['canDel'])
                    <button wire:click.stop="askDelete({{ $row['id'] }})" title="{{ __('teacher.availability.delete') }}" aria-label="{{ __('teacher.availability.delete') }}" style="width:40px;height:40px;border-radius:10px;border:1px solid transparent;background:transparent;color:#A8A29E;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path></svg></button>
                @else
                    <span title="{{ __('teacher.availability.booked_locked_title') }}" style="width:40px;height:40px;border-radius:10px;color:#4338CA;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg></span>
                @endif
            </div>
        @endforeach
    @empty
        <div style="padding:56px 24px;text-align:center">
            <div style="font-size:15px;font-weight:700">{{ __('teacher.availability.agenda_empty_title') }}</div>
            <div style="font-size:13.5px;color:#78716C;margin-top:4px">{{ __('teacher.availability.agenda_empty_body') }}</div>
        </div>
    @endforelse
</div>
