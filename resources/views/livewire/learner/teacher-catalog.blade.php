<div>
    <div style="margin-bottom:28px">
        <h1 style="font-size:30px;font-weight:800;letter-spacing:-.035em;margin:0;color:#1C1917">{{ __('learner.catalog.title') }}</h1>
        <p style="font-size:15px;color:#78716C;margin:6px 0 0">{{ __('learner.catalog.subtitle') }}</p>
    </div>

    @if(session('message'))
        <div style="margin-bottom:24px;border-radius:12px;background:#E8F3EC;border:1px solid #C5E4CF;padding:12px 16px;font-size:14px;font-weight:500;color:#2F7D5B;display:flex;align-items:center;gap:8px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    @if($errors->has('selectedLearnerId') || $errors->has('selectedSlotId'))
        <div style="margin-bottom:24px;border-radius:12px;background:#FEF2F2;border:1px solid #FECACA;padding:12px 16px;font-size:14px;font-weight:500;color:#DC2626">
            {{ $errors->first('selectedLearnerId') ?: $errors->first('selectedSlotId') }}
        </div>
    @endif

    {{-- Filters --}}
    <div style="margin-bottom:24px;display:flex;align-items:center;gap:12px;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:8px">
            <label style="font-size:13px;font-weight:500;color:#78716C">{{ __('learner.catalog.filter_level') }}</label>
            <select wire:model.live="filterLevel"
                style="border:1px solid #E2DBD3;border-radius:10px;background:#fff;padding:8px 12px;font-size:13.5px;color:#1C1917;outline:none;cursor:pointer">
                <option value="">{{ __('learner.catalog.all') }}</option>
                @foreach(\App\Livewire\Learner\TeacherCatalog::LEVEL_OPTIONS as $level)
                    <option value="{{ $level }}">{{ __('teacher.levels.' . $level) }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <label style="font-size:13px;font-weight:500;color:#78716C">{{ __('learner.catalog.filter_language') }}</label>
            <select wire:model.live="filterLanguage"
                style="border:1px solid #E2DBD3;border-radius:10px;background:#fff;padding:8px 12px;font-size:13.5px;color:#1C1917;outline:none;cursor:pointer">
                <option value="">{{ __('learner.catalog.all') }}</option>
                @foreach(\App\Livewire\Learner\TeacherCatalog::LANGUAGE_OPTIONS as $lang)
                    <option value="{{ $lang }}">{{ __('teacher.languages.' . $lang) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Teacher list --}}
    <div style="display:flex;flex-direction:column;gap:12px">
        @forelse($teachers as $teacher)
            <div @class(['thz-row' => $expandedTeacherId !== $teacher->id]) style="background:#fff;border-radius:16px;border:1px solid {{ $expandedTeacherId === $teacher->id ? '#EBD69A' : '#EAE4DD' }};box-shadow:{{ $expandedTeacherId === $teacher->id ? '0 8px 24px -8px rgba(28,25,23,.12)' : '0 1px 2px rgba(28,25,23,.04)' }};overflow:hidden;transition:all .2s">

                {{-- Teacher row --}}
                <div style="padding:20px;display:flex;gap:16px">
                    <div style="flex-shrink:0;width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#F2B81D,#F9D55C);display:flex;align-items:center;justify-content:center;color:#1C1917;font-weight:700;font-size:20px">
                        {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                    </div>

                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px">
                            <div>
                                <div style="font-size:15px;font-weight:700;color:#1C1917">{{ $teacher->user->name }}</div>
                                <div style="font-size:12.5px;color:#78716C;margin-top:2px">{{ __('learner.catalog.location') }}</div>
                            </div>
                            @if($teacher->available_slots_count > 0)
                                <span style="flex-shrink:0;display:inline-flex;align-items:center;gap:5px;padding:3px 10px;font-size:12px;font-weight:600;background:#E8F3EC;color:#2F7D5B;border-radius:99px;border:1px solid #C5E4CF">
                                    <span style="width:6px;height:6px;border-radius:99px;background:#3A9A6E;flex-shrink:0"></span>
                                    {{ trans_choice('learner.catalog.available_slots', $teacher->available_slots_count, ['count' => $teacher->available_slots_count]) }}
                                </span>
                            @else
                                <span style="flex-shrink:0;padding:3px 10px;font-size:12px;font-weight:500;color:#A8A29E;background:#F5F5F4;border-radius:99px;border:1px solid #EAE4DD">
                                    {{ __('learner.catalog.no_slots') }}
                                </span>
                            @endif
                        </div>

                        @if($teacher->bio)
                            <p style="font-size:13.5px;color:#78716C;margin-top:10px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">{{ $teacher->bio }}</p>
                        @endif

                        <div style="display:flex;align-items:center;gap:6px;margin-top:12px;flex-wrap:wrap">
                            @foreach($teacher->languages ?? [] as $lang)
                                <span style="padding:3px 10px;font-size:12px;font-weight:500;background:#F5F5F4;color:#57534E;border-radius:8px;border:1px solid #EAE4DD">{{ __('teacher.languages.' . $lang) }}</span>
                            @endforeach
                            @foreach($teacher->levels ?? [] as $level)
                                <span style="padding:3px 10px;font-size:12px;font-weight:500;background:#FDF3D6;color:#9A6A00;border-radius:8px;border:1px solid #F0DC9A">{{ __('teacher.levels.' . $level) }}</span>
                            @endforeach

                            @if($teacher->available_slots_count > 0)
                                <button wire:click="toggleSlots({{ $teacher->id }})"
                                    style="margin-left:auto;padding:6px 12px;font-size:12.5px;font-weight:600;background:{{ $expandedTeacherId === $teacher->id ? '#FDF3D6' : 'transparent' }};color:#9A6A00;border:1px solid {{ $expandedTeacherId === $teacher->id ? '#EBD69A' : 'transparent' }};border-radius:8px;cursor:pointer;font:inherit">
                                    {{ $expandedTeacherId === $teacher->id ? __('learner.booking.hide_slots') : __('learner.booking.view_slots') . ' →' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Slots panel --}}
                @if($expandedTeacherId === $teacher->id)
                    <div style="border-top:1px solid #F1ECE6;padding:16px 20px 20px">
                        @if($availableSlots->isEmpty())
                            <p style="font-size:14px;color:#A8A29E">{{ __('learner.booking.no_slots') }}</p>
                        @else
                            <div style="display:flex;flex-direction:column;gap:8px">
                                @foreach($availableSlots as $slot)
                                    <div style="border-radius:12px;border:1px solid {{ $selectedSlotId === $slot->id ? '#EBD69A' : '#EAE4DD' }};background:{{ $selectedSlotId === $slot->id ? '#FFFCF0' : '#FCFAF8' }};overflow:hidden;transition:all .15s">

                                        @if($selectedSlotId === $slot->id)
                                            <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
                                                <div style="display:flex;align-items:center;gap:16px">
                                                    <div style="text-align:center;width:40px;flex-shrink:0">
                                                        <div style="font-size:18px;font-weight:800;color:#9A6A00;line-height:1">{{ $slot->starts_at->format('d') }}</div>
                                                        <div style="font-size:10px;color:#A8A29E;text-transform:uppercase;margin-top:2px">{{ $slot->starts_at->translatedFormat('M') }}</div>
                                                        <div style="font-size:10px;color:#A8A29E">{{ $slot->starts_at->translatedFormat('D') }}</div>
                                                    </div>
                                                    <div style="width:1px;height:28px;background:#EAE4DD;flex-shrink:0"></div>
                                                    <div>
                                                        <div style="font-size:14px;font-weight:600;color:#1C1917">{{ $slot->starts_at->format('H:i') }} – {{ $slot->ends_at->format('H:i') }}</div>
                                                        <div style="font-size:12px;color:#A8A29E;margin-top:2px">60 min</div>
                                                    </div>
                                                </div>

                                                <div style="font-size:13px;font-weight:600;color:#1C1917">{{ __('learner.booking.for_whom') }}</div>
                                                <div style="display:flex;flex-wrap:wrap;gap:8px">
                                                    @foreach($learners as $learner)
                                                        <button wire:click="$set('selectedLearnerId', {{ $learner->id }})"
                                                            style="padding:6px 14px;font-size:13px;font-weight:600;border-radius:8px;cursor:pointer;font:inherit;transition:all .15s;border:1px solid {{ $selectedLearnerId === $learner->id ? '#9A6A00' : '#E2DBD3' }};background:{{ $selectedLearnerId === $learner->id ? '#9A6A00' : '#fff' }};color:{{ $selectedLearnerId === $learner->id ? '#fff' : '#57534E' }}">
                                                            {{ $learner->first_name }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                                <div style="display:flex;align-items:center;gap:10px">
                                                    <button wire:click="book" @if(!$selectedLearnerId) disabled @endif
                                                        style="padding:9px 18px;font-size:13.5px;font-weight:700;background:#F2B81D;color:#1C1917;border:none;border-radius:10px;cursor:pointer;font:inherit;opacity:{{ $selectedLearnerId ? '1' : '.4' }}">
                                                        {{ __('learner.booking.confirm') }}
                                                    </button>
                                                    <button wire:click="cancelBooking" style="font-size:13px;color:#A8A29E;background:none;border:none;cursor:pointer;font:inherit">
                                                        {{ __('learner.booking.cancel') }}
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div style="padding:12px 16px;display:flex;align-items:center;gap:16px">
                                                <div style="text-align:center;width:32px;flex-shrink:0">
                                                    <div style="font-size:16px;font-weight:800;color:#9A6A00;line-height:1">{{ $slot->starts_at->format('d') }}</div>
                                                    <div style="font-size:9px;color:#A8A29E;text-transform:uppercase;margin-top:2px">{{ $slot->starts_at->translatedFormat('M') }}</div>
                                                </div>
                                                <div style="width:1px;height:22px;background:#EAE4DD;flex-shrink:0"></div>
                                                <div style="flex:1">
                                                    <div style="font-size:13.5px;font-weight:600;color:#1C1917">{{ $slot->starts_at->format('H:i') }} – {{ $slot->ends_at->format('H:i') }}</div>
                                                    <div style="font-size:11.5px;color:#A8A29E;margin-top:2px">{{ $slot->starts_at->translatedFormat('D') }} · 60 min</div>
                                                </div>
                                                <button wire:click="selectSlot({{ $slot->id }})"
                                                    style="flex-shrink:0;padding:7px 14px;font-size:12.5px;font-weight:700;background:#F2B81D;color:#1C1917;border:none;border-radius:8px;cursor:pointer;font:inherit">
                                                    {{ __('learner.booking.book') }}
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div style="padding:64px 24px;text-align:center">
                <div style="width:48px;height:48px;border-radius:14px;background:#F1ECE6;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#A8A29E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <p style="font-size:14px;color:#A8A29E">{{ __('learner.catalog.no_results') }}</p>
            </div>
        @endforelse
    </div>
</div>
