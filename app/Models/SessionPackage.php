<?php

namespace App\Models;

use Database\Factories\SessionPackageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'sessions_count', 'price_cents', 'stripe_price_id', 'is_active'])]
class SessionPackage extends Model
{
    /** @use HasFactory<SessionPackageFactory> */
    use HasFactory;

    protected $table = 'packages';

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'package_id');
    }

    public function priceInDollars(): string
    {
        return number_format($this->price_cents / 100, 2);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
