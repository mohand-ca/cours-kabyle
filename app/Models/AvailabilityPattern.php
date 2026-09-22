<?php

namespace App\Models;

use Database\Factories\AvailabilityPatternFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['teacher_profile_id', 'day_of_week', 'start_time', 'end_time', 'is_active'])]
class AvailabilityPattern extends Model
{
    /** @use HasFactory<AvailabilityPatternFactory> */
    use HasFactory;

    public function teacherProfile(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class);
    }

    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    public function dayName(): string
    {
        return ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'][$this->day_of_week];
    }
}
