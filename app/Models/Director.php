<?php

namespace App\Models;

use App\Traits\FindModelOrFail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Director extends Model
{
    use HasFactory, FindModelOrFail, LogsActivity;

    protected $fillable = [
        'name',
        'biography',
        'image',
        'birth_date',
        'nationality',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'biography', 'image', 'birth_date', 'nationality']);
    }

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }

    public function getAgeAttribute(): int
    {
        return now()->diffInYears($this->birth_date);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
