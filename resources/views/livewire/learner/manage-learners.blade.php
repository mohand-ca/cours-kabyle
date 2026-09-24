<div>
    <div style="margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;gap:16px">
        <div>
            <h1 style="font-size:30px;font-weight:800;letter-spacing:-.035em;margin:0;color:#1C1917">{{ __('learner.learners.title') }}</h1>
        </div>
        @if(!$showForm)
            <button wire:click="startAdd"
                style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:14px;font-weight:700;padding:10px 18px;border-radius:12px;cursor:pointer;box-shadow:0 6px 16px -6px rgba(222,165,0,.5);flex-shrink:0">
                {{ __('learner.learners.add') }}
            </button>
        @endif
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px;border-radius:12px;background:#E8F3EC;border:1px solid #C5E4CF;padding:12px 16px;font-size:14px;color:#2F7D5B">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="margin-bottom:16px;border-radius:12px;background:#FEF2F2;border:1px solid #FECACA;padding:12px 16px;font-size:14px;color:#DC2626">{{ session('error') }}</div>
    @endif

    {{-- Add / Edit form --}}
    @if($showForm)
        <div style="margin-bottom:24px;background:#fff;border-radius:16px;border:1px solid #EAE4DD;padding:24px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
            <h2 style="font-size:15px;font-weight:700;color:#1C1917;margin:0 0 20px">
                {{ $editingId ? __('learner.learners.edit') : __('learner.learners.add') }}
            </h2>

            <form wire:submit="save" style="display:flex;flex-direction:column;gap:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('learner.learners.first_name') }}</label>
                        <input type="text" wire:model="form.firstName"
                            style="width:100%;border:1px solid {{ $errors->has('form.firstName') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:10px 13px;font:inherit;font-size:14px;background:#fff;outline:none;color:#1C1917">
                        @error('form.firstName') <p style="margin-top:5px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('learner.learners.last_name') }}</label>
                        <input type="text" wire:model="form.lastName"
                            style="width:100%;border:1px solid {{ $errors->has('form.lastName') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:10px 13px;font:inherit;font-size:14px;background:#fff;outline:none;color:#1C1917">
                        @error('form.lastName') <p style="margin-top:5px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('learner.learners.relationship') }}</label>
                        <select wire:model="form.relationship"
                            style="width:100%;border:1px solid #E2DBD3;border-radius:12px;padding:10px 13px;font:inherit;font-size:14px;background:#fff;outline:none;color:#1C1917;cursor:pointer">
                            @foreach(['self', 'child', 'spouse', 'other'] as $rel)
                                <option value="{{ $rel }}">{{ __('learner.learners.relationships.' . $rel) }}</option>
                            @endforeach
                        </select>
                        @error('form.relationship') <p style="margin-top:5px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">
                            {{ __('learner.learners.date_of_birth') }}
                            <span style="color:#A8A29E;font-weight:400">{{ __('learner.learners.date_of_birth_optional') }}</span>
                        </label>
                        <input type="date" wire:model="form.dateOfBirth"
                            style="width:100%;border:1px solid {{ $errors->has('form.dateOfBirth') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:10px 13px;font:inherit;font-size:14px;background:#fff;outline:none;color:#1C1917">
                        @error('form.dateOfBirth') <p style="margin-top:5px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#1C1917">{{ __('learner.learners.notification_email') }}</label>
                    <input type="email" wire:model="form.notificationEmail"
                        style="width:100%;border:1px solid {{ $errors->has('form.notificationEmail') ? '#EF4444' : '#E2DBD3' }};border-radius:12px;padding:10px 13px;font:inherit;font-size:14px;background:#fff;outline:none;color:#1C1917">
                    <p style="margin-top:6px;font-size:12px;color:#A8A29E">{{ __('learner.learners.notification_email_hint') }}</p>
                    @error('form.notificationEmail') <p style="margin-top:5px;font-size:12px;color:#DC2626">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex;align-items:center;gap:10px;padding-top:4px">
                    <button type="submit"
                        style="background:#F2B81D;color:#1C1917;border:none;font:inherit;font-size:14px;font-weight:700;padding:10px 20px;border-radius:10px;cursor:pointer;box-shadow:0 4px 12px -4px rgba(222,165,0,.5)">
                        {{ __('learner.learners.save') }}
                    </button>
                    <button type="button" wire:click="cancelForm"
                        style="background:transparent;border:1px solid #E2DBD3;color:#57534E;font:inherit;font-size:14px;font-weight:500;padding:9px 20px;border-radius:10px;cursor:pointer">
                        {{ __('learner.learners.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Learner list --}}
    <div style="display:flex;flex-direction:column;gap:8px">
        @forelse($learners as $learner)
            <div class="thz-row" style="display:flex;align-items:center;justify-content:space-between;background:#fff;border-radius:14px;border:1px solid #EAE4DD;padding:16px 20px;box-shadow:0 1px 2px rgba(28,25,23,.04)">
                <div style="display:flex;align-items:center;gap:14px">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#F2B81D,#F9D55C);color:#1C1917;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        {{ strtoupper(substr($learner->first_name, 0, 1) . substr($learner->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1C1917">{{ $learner->first_name }} {{ $learner->last_name }}</div>
                        <div style="font-size:12.5px;color:#A8A29E;margin-top:2px">{{ __('learner.learners.relationships.' . $learner->relationship) }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px">
                    <button wire:click="startEdit({{ $learner->id }})"
                        style="font-size:13px;font-weight:600;color:#9A6A00;background:none;border:none;cursor:pointer;font:inherit">
                        {{ __('learner.learners.edit') }}
                    </button>
                    @if($learner->relationship !== 'self')
                        <button wire:click="delete({{ $learner->id }})"
                            wire:confirm="{{ __('learner.learners.delete_confirm') }}"
                            style="font-size:13px;font-weight:500;color:#A8A29E;background:none;border:none;cursor:pointer;font:inherit">
                            {{ __('learner.learners.delete') }}
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding:48px 24px;text-align:center">
                <p style="font-size:14px;color:#A8A29E">{{ __('learner.learners.no_learners') }}</p>
            </div>
        @endforelse
    </div>
</div>
