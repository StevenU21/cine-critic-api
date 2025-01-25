<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Resources\MovieResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
    use AuthorizesRequests;
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Movie::class);

        $movies = Movie::with(['genre', 'director', 'ratingAverage', 'reviewsCount'])->get();

        return MovieResource::collection($movies);
    }

    public function show(int $id): MovieResource
    {
        $movie = Movie::findOrFailCustom($id);
        $this->authorize('view', $movie);
        $movie->load(['genre', 'director', 'ratingAverage', 'reviewsCount']);

        return new MovieResource($movie);
    }
}
