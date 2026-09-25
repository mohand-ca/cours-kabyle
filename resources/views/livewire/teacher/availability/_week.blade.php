{{-- Week grid --}}
<style>
    @media (max-width:760px){ .thz-week-wide{display:none} }
    @media (min-width:761px){ .thz-week-narrow{display:none} }
</style>

{{-- Wide: 7-day grid --}}
<div class="thz-week-wide" style="max-height:680px;overflow:auto;position:relative" x-data x-init="$el.scrollTop = 9 * 56 - 10">
    <div style="min-width:760px">
        <div style="position:sticky;top:0;z-index:8;display:grid;grid-template-columns:60px repeat(7,minmax(0,1fr));background:#fff;border-bottom:1px solid #EAE4DD">
            <div style="font-size:10.5px;font-weight:600;color:#A8A29E;padding:12px 6px;text-align:right;align-self:end">{{ $tzActiveGmt }}</div>
            @foreach($columns as $col)
                <div style="padding:10px 6px;text-align:center;border-left:1px solid #F1ECE6;background:{{ $col['leaveReason'] ? '#FFFDF8' : '#fff' }}">
                    <div style="font-size:11.5px;font-weight:700;color:{{ $col['isToday'] ? '#9A6A00' : '#78716C' }};text-transform:uppercase;letter-spacing:.08em">{{ $col['dow'] }}</div>
                    <div style="margin:4px auto 0;width:34px;height:34px;border-radius:99px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;background:{{ $col['isToday'] ? '#F2B81D' : 'transparent' }};color:#1C1917">{{ $col['num'] }}</div>
                </div>
            @endforeach
        </div>
        <div style="display:grid;grid-template-columns:60px repeat(7,minmax(0,1fr))">
            <div style="position:relative;height:1344px">
                @foreach($hours as $h)
                    <div style="position:absolute;top:{{ $h['top'] }}px;right:8px;font-family:monospace;font-size:11px;color:#A8A29E;transform:translateY(-50%)">{{ $h['label'] }}</div>
                @endforeach
            </div>
            @foreach($columns as $col)
                @include('livewire.teacher.availability._week_column', ['col' => $col])
            @endforeach
        </div>
    </div>
</div>

{{-- Narrow: day chips + single day --}}
<div class="thz-week-narrow">
    <div style="display:flex;gap:6px;overflow-x:auto;padding:12px;border-bottom:1px solid #EAE4DD">
        @foreach($dayChips as $chip)
            <button wire:key="chip-{{ $chip['index'] }}" wire:click="setMobileDay({{ $chip['index'] }})" aria-label="{{ $chip['dow'] }} {{ $chip['num'] }}"
                style="flex:1 0 44px;min-height:62px;border-radius:12px;border:1px solid {{ $chip['active'] ? '#1C1917' : '#EAE4DD' }};background:{{ $chip['active'] ? '#1C1917' : ($chip['isToday'] ? '#FDF3D6' : '#fff') }};color:{{ $chip['active'] ? '#fff' : '#1C1917' }};display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;font:inherit;cursor:pointer">
                <span style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;opacity:.8">{{ $chip['dow'] }}</span>
                <span style="font-size:17px;font-weight:800">{{ $chip['num'] }}</span>
                <span style="display:flex;gap:3px;height:5px">@foreach($chip['dots'] as $dot)<span style="width:5px;height:5px;border-radius:99px;background:{{ $dot }}"></span>@endforeach</span>
            </button>
        @endforeach
    </div>
    <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;border-bottom:1px solid #F1ECE6"><span style="font-size:15px;font-weight:700">{{ $mobileDayTitle }}</span><span style="margin-left:auto;font-size:12px;color:#A8A29E">{{ $tzActiveGmt }}</span></div>
    <div style="max-height:560px;overflow:auto;position:relative" x-data x-init="$el.scrollTop = 9 * 56 - 10">
        <div style="display:grid;grid-template-columns:52px minmax(0,1fr)">
            <div style="position:relative;height:1344px">
                @foreach($hours as $h)
                    <div style="position:absolute;top:{{ $h['top'] }}px;right:8px;font-family:monospace;font-size:11px;color:#A8A29E;transform:translateY(-50%)">{{ $h['label'] }}</div>
                @endforeach
            </div>
            @include('livewire.teacher.availability._week_column', ['col' => $columns[$mobileDay]])
        </div>
    </div>
</div>
