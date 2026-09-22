<?php

namespace App\Models;

use Database\Factories\LessonSessionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['availability_slot_id', 'learner_id', 'teacher_profile_id', 'purchase_id', 'status'])]
class LessonSession extends Model
{
    /** @use HasFactory<LessonSessionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function availabilitySlot(): BelongsTo
    {
        return $this->belongsTo(AvailabilitySlot::class);
    }

    public function learner(): BelongsTo
    {
        return $this->belongsTo(Learner::class);
    }

    public function teacherProfile(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->where('status', 'confirmed')
            ->whereHas('availabilitySlot', fn ($q) => $q->where('starts_at', '>', now()))
            ->with(['availabilitySlot', 'teacherProfile.user', 'learner']);
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
        $this->availabilitySlot->update(['status' => 'available']);
    }
}
