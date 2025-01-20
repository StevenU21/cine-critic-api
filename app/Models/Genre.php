<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FindModelOrFail;

class Genre extends Model
{
    /** @use HasFactory<\Database\Factories\GenreFactory> */
    use HasFactory, FindModelOrFail;

    protected $fillable = [
        'name',
        'description'
    ];
}
