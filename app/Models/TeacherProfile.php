<?php

namespace App\Models;

use Database\Factories\TeacherProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'bio', 'levels', 'languages', 'meet_link', 'status', 'submitted_at', 'default_slot_duration', 'slot_buffer', 'booking_horizon_weeks'])]
class TeacherProfile extends Model
{
    /** @use HasFactory<TeacherProfileFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'levels' => 'array',
            'languages' => 'array',
            'submitted_at' => 'datetime',
            'default_slot_duration' => 'integer',
            'slot_buffer' => 'integer',
            'booking_horizon_weeks' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function timeOffs(): HasMany
    {
        return $this->hasMany(TimeOff::class);
    }

    /**
     * The teacher's own timezone (e.g. Africa/Algiers). Patterns are expressed in this timezone.
     */
    public function timezone(): string
    {
        return $this->user->timezone ?? config('app.timezone');
    }

    public function availabilityPatterns(): HasMany
    {
        return $this->hasMany(AvailabilityPattern::class);
    }

    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function submit(): void
    {
        $this->update([
            'status' => 'pending',
            'submitted_at' => now(),
        ]);
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }
}
