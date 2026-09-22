<?php

namespace App\Models;

use Database\Factories\LearnerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'first_name', 'last_name', 'date_of_birth', 'relationship', 'notification_email', 'points'])]
class Learner extends Model
{
    /** @use HasFactory<LearnerFactory> */
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lessonSessions(): HasMany
    {
        return $this->hasMany(LessonSession::class);
    }

    /**
     * Returns all email addresses that should receive notifications for this learner.
     *
     * @return array<int, string>
     */
    public function notificationAddresses(): array
    {
        $addresses = [$this->user->email];

        if ($this->notification_email) {
            $addresses[] = $this->notification_email;
        }

        return $addresses;
    }
}
