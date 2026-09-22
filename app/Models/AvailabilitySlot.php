<?php

namespace App\Models;

use Database\Factories\AvailabilitySlotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['teacher_profile_id', 'availability_pattern_id', 'starts_at', 'ends_at', 'status'])]
class AvailabilitySlot extends Model
{
    /** @use HasFactory<AvailabilitySlotFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function teacherProfile(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class);
    }

    public function availabilityPattern(): BelongsTo
    {
        return $this->belongsTo(AvailabilityPattern::class);
    }

    public function lessonSession(): HasOne
    {
        return $this->hasOne(LessonSession::class);
    }

    public function book(): void
    {
        $this->update(['status' => 'booked']);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available')->where('starts_at', '>', now());
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isBooked(): bool
    {
        return $this->status === 'booked';
    }
}
