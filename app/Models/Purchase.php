<?php

namespace App\Models;

use Database\Factories\PurchaseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'package_key', 'stripe_session_id', 'sessions_total', 'sessions_remaining', 'status'])]
class Purchase extends Model
{
    /** @use HasFactory<PurchaseFactory> */
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lessonSessions(): HasMany
    {
        return $this->hasMany(LessonSession::class);
    }

    /** @return array<string, mixed> */
    public function packageConfig(): array
    {
        return config('packages.'.$this->package_key, []);
    }

    public function hasSessionsRemaining(): bool
    {
        return $this->sessions_remaining > 0;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
