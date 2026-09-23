<div>
    <div style="margin-bottom:28px">
        <h1 style="font-size:30px;font-weight:800;letter-spacing:-.035em;margin:0;color:#1C1917">{{ __('teacher.availability.title') }}</h1>
    </div>

    @if(!$profile?->isApproved())
        <div style="margin-bottom:24px;border-radius:14px;border:1px solid #F1DDB4;background:#FBF1DE;padding:14px 18px;display:flex;align-items:center;gap:12px">
            <div style="color:#9A6A00;flex-shrink:0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <p style="font-size:14px;color:#7A4F0C;margin:0">
                {{ __('teacher.availability.not_approved') }}
                <a href="{{ route('teacher.profile') }}" style="font-weight:600;color:#9A6A00;margin-left:4px">{{ __('teacher.availability.complete_profile') }}</a>
            </p>
        </div>
    @endif

    @if(session('success'))
        <div style="margin-bottom:24px;border-radius:12px;background:#E8F3EC;border:1px solid #C5E4CF;padding:12px 16px;font-size:14px;font-weight:500;color:#2F7D5B;display:flex;align-items:center;gap:8px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="margin-bottom:24px;border-radius:12px;background:#FEF2F2;border:1px solid #FECACA;padding:12px 16px;font-size:14px;font-weight:500;color:#DC2626">{{ session('error') }}</div>
    @endif

    {{-- Add slot form --}}
    @if($profile?->isApproved())
        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:24px;margin-bottom:16px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="font-size:15px;font-weight:700;color:#1C1917;margin-bottom:16px">{{ __('teacher.availability.add_slot') }}</div>

            <form wire:submit="addSlot" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;color:#78716C;margin-bottom:6px">{{ __('teacher.availability.date') }}</label>
                    <input type="date" wire:model="date" min="{{ now()->addDay()->format('Y-m-d') }}"
                        style="border:1px solid {{ $errors->has('date') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:10px 13px;font:inherit;font-size:13.5px;background:#fff;outline:none;color:#1C1917">
                    @error('date') <p style="margin-top:5px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;color:#78716C;margin-bottom:6px">{{ __('teacher.availability.time') }}</label>
                    <input type="time" wire:model="startTime"
                        style="border:1px solid {{ $errors->has('startTime') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:10px 13px;font:inherit;font-size:13.5px;background:#fff;outline:none;color:#1C1917">
                    @error('startTime') <p style="margin-top:5px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;color:#78716C;margin-bottom:6px">{{ __('teacher.availability.duration') }}</label>
                    <select wire:model="duration"
                        style="border:1px solid #E2DBD3;border-radius:12px;padding:10px 13px;font:inherit;font-size:13.5px;background:#fff;outline:none;color:#1C1917;cursor:pointer">
                        <option value="60">60 min</option>
                        <option value="90">90 min</option>
                        <option value="120">120 min</option>
                    </select>
                </div>

                <button type="submit"
                    style="padding:11px 22px;font-size:14px;font-weight:700;background:#F2B81D;color:#1C1917;border:none;border-radius:12px;cursor:pointer;font:inherit;box-shadow:0 6px 16px -6px rgba(222,165,0,.5)">
                    {{ __('teacher.availability.add') }}
                </button>
            </form>
        </div>
    @endif

    {{-- Slot list --}}
    <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;overflow:hidden;box-shadow:0 1px 2px rgba(28,25,23,.04)">
        @forelse($slots as $slot)
            <div style="display:flex;align-items:center;gap:16px;padding:14px 20px;border-bottom:1px solid #F1ECE6">
                <div style="width:40px;text-align:center;flex-shrink:0">
                    <div style="font-size:20px;font-weight:800;color:#9A6A00;line-height:1">{{ $slot->starts_at->format('d') }}</div>
                    <div style="font-size:10.5px;color:#A8A29E;text-transform:uppercase;letter-spacing:.08em;margin-top:4px">{{ $slot->starts_at->translatedFormat('M') }}</div>
                </div>
                <div style="width:1px;height:28px;background:#EAE4DD;flex-shrink:0"></div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:14px;font-weight:600;color:#1C1917">{{ $slot->starts_at->format('H:i') }} – {{ $slot->ends_at->format('H:i') }}</div>
                    <div style="font-size:12px;color:#A8A29E;margin-top:2px">{{ $slot->starts_at->translatedFormat('l') }}</div>
                </div>
                <span style="flex-shrink:0;display:inline-flex;align-items:center;gap:5px;padding:4px 10px;font-size:12px;font-weight:600;border-radius:99px;background:{{ $slot->isAvailable() ? '#E8F3EC' : '#EEF2FF' }};color:{{ $slot->isAvailable() ? '#2F7D5B' : '#4338CA' }};border:1px solid {{ $slot->isAvailable() ? '#C5E4CF' : '#C7D2FE' }}">
                    <span style="width:6px;height:6px;border-radius:99px;background:{{ $slot->isAvailable() ? '#3A9A6E' : '#6366F1' }};flex-shrink:0"></span>
                    {{ $slot->isAvailable() ? __('teacher.availability.status_available') : __('teacher.availability.status_booked') }}
                </span>

                @if($slot->isAvailable())
                    <button wire:click="cancelSlot({{ $slot->id }})" wire:confirm="{{ __('teacher.availability.cancel_confirm') }}"
                        style="flex-shrink:0;font-size:14px;color:#D1C9C0;background:none;border:none;cursor:pointer;padding:0;line-height:1">✕</button>
                @endif
            </div>
        @empty
            <div style="padding:48px 24px;text-align:center">
                <div style="width:48px;height:48px;border-radius:14px;background:#F1ECE6;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#A8A29E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
                </div>
                <p style="font-size:14px;color:#A8A29E">{{ __('teacher.availability.no_slots') }}</p>
            </div>
        @endforelse
    </div>
</div>
