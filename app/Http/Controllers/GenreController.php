<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Resources\GenreResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\GenreRequest;

class GenreController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $genres = Genre::latest()->paginate(10);
        return GenreResource::collection($genres);
    }

    public function show(Genre $genre): GenreResource
    {
        return new GenreResource($genre);
    }
}
