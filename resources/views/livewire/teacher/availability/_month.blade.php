{{-- Month grid --}}
<div>
    <div style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));border-bottom:1px solid #EAE4DD">
        @foreach($monthHead as $label)
            <div style="padding:10px 8px;font-size:11.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#78716C;text-align:center">{{ $label }}</div>
        @endforeach
    </div>
    <div style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr))">
        @foreach($monthCells as $cell)
            <button wire:key="mcell-{{ $cell['date'] }}" wire:click="jumpToDay('{{ $cell['date'] }}')" aria-label="{{ $cell['date'] }}"
                style="min-height:112px;border:none;border-right:1px solid #F1ECE6;border-bottom:1px solid #F1ECE6;padding:8px 10px;text-align:left;font:inherit;color:#1C1917;cursor:pointer;display:flex;flex-direction:column;align-items:flex-start;gap:5px;min-width:0;opacity:{{ $cell['inMonth'] ? '1' : '.45' }};background:{{ $cell['isLeave'] ? 'repeating-linear-gradient(135deg,#FFFDF8 0,#FFFDF8 8px,#FBF1DE 8px,#FBF1DE 16px)' : ($cell['isPast'] ? '#FCFAF8' : '#fff') }}">
                <span style="width:28px;height:28px;border-radius:99px;display:flex;align-items:center;justify-content:center;font-size:13.5px;font-weight:700;background:{{ $cell['isToday'] ? '#F2B81D' : 'transparent' }};flex-shrink:0">{{ $cell['num'] }}</span>
                @if($cell['isLeave'])
                    <span style="font-size:11.5px;font-weight:700;color:#7A4F0C;background:#FBF1DE;border:1px solid #F1DDB4;padding:2px 8px;border-radius:99px">{{ __('teacher.availability.status_leave') }}</span>
                @endif
                @if($cell['open'] > 0)
                    <span style="display:flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#2F7D5B"><span style="width:6px;height:6px;border-radius:99px;background:#3A9A6E"></span>{{ $cell['openLabel'] }}</span>
                @endif
                @if($cell['booked'] > 0)
                    <span style="display:flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#4338CA"><span style="width:6px;height:6px;border-radius:99px;background:#6366F1"></span>{{ $cell['bookedLabel'] }}</span>
                @endif
                @if($cell['past'] > 0 && !$cell['open'] && !$cell['booked'])
                    <span style="display:flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:#A8A29E"><span style="width:6px;height:6px;border-radius:99px;background:#C9C2BA"></span>{{ $cell['pastLabel'] }}</span>
                @endif
            </button>
        @endforeach
    </div>
</div>
