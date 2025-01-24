<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FindModelOrFail;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    /** @use HasFactory<\Database\Factories\GenreFactory> */
    use HasFactory, FindModelOrFail;

    protected $fillable = [
        'name',
        'description'
    ];

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }
}
