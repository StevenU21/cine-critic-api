<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\FindModelOrFail;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Movie extends Model
{
    use HasFactory, FindModelOrFail;

    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'release_date',
        'trailer_url',
        'duration',
        'director_id',
        'genre_id',
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(Director::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function ratingAverage(): HasOne
    {
        return $this->hasOne(Review::class)
            ->selectRaw('movie_id, ROUND(AVG(rating), 2) as aggregate')
            ->groupBy('movie_id');
    }

    public function reviewsCount(): HasOne
    {
        return $this->hasOne(Review::class)
            ->selectRaw('movie_id, COUNT(*) as aggregate')
            ->groupBy('movie_id');
    }

    public function getImageAttribute(): string
    {
        return asset('storage/' . $this->cover_image);
    }
}
