@props(['href', 'active' => false])

<a href="{{ $href }}" @class(['thz-nav' => ! $active])
    style="padding:7px 13px;border-radius:999px;font-size:14px;font-weight:600;text-decoration:none;transition:background .15s,color .15s;white-space:nowrap;{{ $active ? 'background:#F1ECE6;color:#1C1917' : 'background:transparent;color:#57534E' }}">
    {{ $slot }}
</a>
