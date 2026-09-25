{{-- A single day column (shared by the wide grid and the mobile single-day view). $col is required. --}}
<div wire:key="col-{{ $col['index'] }}-{{ $weekStart }}"
    @if($canEdit) x-data @click="$wire.createFromClick({{ $col['index'] }}, Math.max(0, Math.floor((($event.clientY - $el.getBoundingClientRect().top) / 0.93333) / 15) * 15))" @endif
    style="position:relative;height:1344px;border-left:1px solid #F1ECE6;background-color:#fff;background-image:repeating-linear-gradient(to bottom,#F1ECE6 0,#F1ECE6 1px,transparent 1px,transparent 56px);cursor:{{ $canEdit ? 'crosshair' : 'default' }};user-select:none">

    <div style="position:absolute;left:0;right:0;top:0;height:{{ $col['pastHeight'] }}px;background:repeating-linear-gradient(135deg,#F6F2EE 0,#F6F2EE 6px,#F0EBE5 6px,#F0EBE5 12px)"></div>
    @if($col['noticeHeight'] > 0)
        <div style="position:absolute;left:0;right:0;top:{{ $col['noticeTop'] }}px;height:{{ $col['noticeHeight'] }}px;background:repeating-linear-gradient(135deg,rgba(242,184,29,.08) 0,rgba(242,184,29,.08) 6px,transparent 6px,transparent 12px)"></div>
        @if($col['showNotice'])
            <div style="position:absolute;left:5px;top:{{ $col['noticeTop'] + 6 }}px;font-size:10.5px;font-weight:700;color:#9A6A00;background:#FDF3D6;border-radius:6px;padding:2px 6px;z-index:1;white-space:nowrap">{{ __('teacher.availability.notice_24h') }}</div>
        @endif
    @endif

    @if($col['leaveReason'])
        <div style="position:absolute;left:3px;right:3px;top:0;bottom:0;border-radius:10px;background:repeating-linear-gradient(135deg,#FBF1DE 0,#FBF1DE 8px,#F7E8C8 8px,#F7E8C8 16px);border:1px solid #F1DDB4;color:#7A4F0C;padding:10px 8px;z-index:1">
            <div style="position:sticky;top:84px;display:flex;flex-direction:column;gap:2px">
                <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:800"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="10"></circle><path d="M4.9 4.9l14.2 14.2"></path></svg>{{ __('teacher.availability.status_leave') }}</span>
                <span style="font-size:11.5px;font-weight:500">{{ $col['leaveReason'] }}</span>
            </div>
        </div>
    @endif

    @foreach($col['blocks'] as $block)
        <div wire:key="slot-{{ $block['id'] }}" wire:click.stop="openBlock({{ $block['id'] }})"
            style="position:absolute;left:4px;right:4px;top:{{ $block['top'] }}px;height:{{ $block['height'] }}px;z-index:2;background:{{ $block['colors']['bg'] }};border:1px solid {{ $block['colors']['bd'] }};color:{{ $block['colors']['fg'] }};border-radius:10px;padding:5px 8px;overflow:hidden;cursor:pointer;display:flex;flex-direction:column;gap:1px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="display:flex;align-items:center;gap:5px;font-size:11.5px;font-weight:700;white-space:nowrap;min-width:0"><span style="width:6px;height:6px;border-radius:99px;background:{{ $block['colors']['dot'] }};flex-shrink:0"></span><span style="overflow:hidden;text-overflow:ellipsis">{{ $block['time'] }}</span><span style="flex:1"></span>
                @if($block['rec'])<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="M17 1l4 4-4 4"></path><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><path d="M7 23l-4-4 4-4"></path><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>@endif
                @if($block['lock'])<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg>@endif
            </div>
            <div style="font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $block['label'] }}</div>
        </div>
    @endforeach

    @if($col['hasNow'])
        <div style="position:absolute;left:0;right:0;top:{{ $col['nowTop'] }}px;height:2px;background:#DC2626;z-index:5"><div style="position:absolute;left:-5px;top:-4px;width:10px;height:10px;border-radius:99px;background:#DC2626"></div></div>
    @endif
</div>
