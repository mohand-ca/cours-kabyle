<?php

namespace App\Models;

use Database\Factories\AvailabilityPatternFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['teacher_profile_id', 'day_of_week', 'start_time', 'end_time', 'slot_duration', 'buffer', 'starts_on', 'until', 'is_active'])]
class AvailabilityPattern extends Model
{
    /** @use HasFactory<AvailabilityPatternFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'slot_duration' => 'integer',
            'buffer' => 'integer',
            'starts_on' => 'date',
            'until' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function teacherProfile(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class);
    }

    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    /**
     * Translated day name. `day_of_week` follows 0 = Monday … 6 = Sunday.
     */
    public function dayName(): string
    {
        return __('teacher.availability.days.'.$this->day_of_week);
    }
}
