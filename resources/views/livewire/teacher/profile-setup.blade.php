<div>
    <div style="margin-bottom:28px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px">
        <div>
            <h1 style="font-size:30px;font-weight:800;letter-spacing:-.035em;margin:0;color:#1C1917">{{ __('teacher.profile.title') }}</h1>
        </div>
        @if($profile)
            @php
                $statusStyles = match(true) {
                    $profile->isApproved()    => ['bg' => '#E8F3EC', 'text' => '#2F7D5B', 'dot' => '#3A9A6E', 'border' => '#C5E4CF', 'label' => __('teacher.profile.status.approved')],
                    $profile->isSuspended()   => ['bg' => '#FEF2F2', 'text' => '#DC2626', 'dot' => '#EF4444', 'border' => '#FECACA', 'label' => __('teacher.profile.status.suspended')],
                    !! $profile->submitted_at => ['bg' => '#FBF1DE', 'text' => '#7A4F0C', 'dot' => '#D97706', 'border' => '#F1DDB4', 'label' => __('teacher.profile.status.pending')],
                    default                   => ['bg' => '#F5F5F4', 'text' => '#78716C', 'dot' => '#A8A29E', 'border' => '#EAE4DD', 'label' => __('teacher.profile.status.draft')],
                };
            @endphp
            <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;font-size:12.5px;font-weight:600;border-radius:99px;background:{{ $statusStyles['bg'] }};color:{{ $statusStyles['text'] }};border:1px solid {{ $statusStyles['border'] }}">
                <span style="width:6px;height:6px;border-radius:99px;background:{{ $statusStyles['dot'] }};flex-shrink:0"></span>
                {{ $statusStyles['label'] }}
            </span>
        @endif
    </div>

    @if(session('success'))
        <div style="margin-bottom:24px;border-radius:12px;background:#E8F3EC;border:1px solid #C5E4CF;padding:12px 16px;font-size:14px;font-weight:500;color:#2F7D5B;display:flex;align-items:center;gap:8px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" style="display:flex;flex-direction:column;gap:16px">

        {{-- Bio --}}
        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:24px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <label for="bio" style="display:block;font-size:15px;font-weight:700;color:#1C1917;margin-bottom:4px">
                {{ __('teacher.profile.bio') }}
                <span style="font-weight:400;color:#A8A29E;font-size:13px;margin-left:4px">{{ __('teacher.profile.bio_hint') }}</span>
            </label>
            <textarea id="bio" wire:model="bio" rows="5"
                style="margin-top:10px;display:block;width:100%;border:1px solid {{ $errors->has('bio') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:12px 14px;font:inherit;font-size:14px;background:#fff;outline:none;color:#1C1917;resize:vertical;line-height:1.55"
                placeholder="{{ __('teacher.profile.bio_placeholder') }}"></textarea>
            @error('bio') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        {{-- Levels --}}
        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:24px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="font-size:15px;font-weight:700;color:#1C1917;margin-bottom:14px">{{ __('teacher.profile.levels') }}</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                @foreach(\App\Livewire\Teacher\ProfileSetup::LEVEL_OPTIONS as $level)
                    <label style="cursor:pointer">
                        <input type="checkbox" wire:model="levels" value="{{ $level }}" class="sr-only peer">
                        <span class="peer-checked:bg-amber-500 peer-checked:text-stone-900 peer-checked:border-amber-500"
                            style="display:inline-flex;align-items:center;padding:8px 16px;font-size:13.5px;font-weight:600;border-radius:10px;border:1px solid #E2DBD3;color:#57534E;background:#fff;transition:all .15s;cursor:pointer">
                            {{ __('teacher.levels.' . $level) }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('levels') <p style="margin-top:8px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        {{-- Languages --}}
        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:24px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <div style="font-size:15px;font-weight:700;color:#1C1917;margin-bottom:14px">{{ __('teacher.profile.languages') }}</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                @foreach(\App\Livewire\Teacher\ProfileSetup::LANGUAGE_OPTIONS as $lang)
                    <label style="cursor:pointer">
                        <input type="checkbox" wire:model="languages" value="{{ $lang }}" class="sr-only peer">
                        <span class="peer-checked:bg-amber-500 peer-checked:text-stone-900 peer-checked:border-amber-500"
                            style="display:inline-flex;align-items:center;padding:8px 16px;font-size:13.5px;font-weight:600;border-radius:10px;border:1px solid #E2DBD3;color:#57534E;background:#fff;transition:all .15s;cursor:pointer">
                            {{ __('teacher.languages.' . $lang) }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('languages') <p style="margin-top:8px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        {{-- Meet link --}}
        <div style="background:#fff;border:1px solid #EAE4DD;border-radius:16px;padding:24px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <label for="meetLink" style="display:block;font-size:15px;font-weight:700;color:#1C1917;margin-bottom:10px">{{ __('teacher.profile.meet_link') }}</label>
            <input id="meetLink" type="url" wire:model="meetLink" placeholder="https://meet.google.com/xxx-xxxx-xxx"
                style="display:block;width:100%;border:1px solid {{ $errors->has('meetLink') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:11px 14px;font:inherit;font-size:14px;background:#fff;outline:none;color:#1C1917">
            @error('meetLink') <p style="margin-top:6px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div style="display:flex;align-items:center;gap:10px">
            <button type="submit"
                style="padding:11px 22px;font-size:14px;font-weight:600;background:#fff;border:1px solid #E2DBD3;color:#1C1917;border-radius:12px;cursor:pointer;font:inherit">
                {{ __('teacher.profile.save') }}
            </button>

            @if(!$profile?->isApproved() && !$profile?->submitted_at)
                <button type="button" wire:click="submit"
                    style="padding:11px 22px;font-size:14px;font-weight:700;background:#F2B81D;color:#1C1917;border:none;border-radius:12px;cursor:pointer;font:inherit;box-shadow:0 6px 16px -6px rgba(222,165,0,.5)">
                    {{ __('teacher.profile.submit') }}
                </button>
            @endif
        </div>
    </form>
</div>
