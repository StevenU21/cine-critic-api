<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Director extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'biography',
        'image',
        'birth_date',
        'nationality',
    ];

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }

    public function getAgeAttribute(): int
    {
        return now()->diffInYears($this->birth_date);
    }
}
