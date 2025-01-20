<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Resources\GenreResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\GenreRequest;

class GenreController extends Controller
{
    use AuthorizesRequests;

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Genre::class);
        
        $genres = Genre::latest()->paginate(10);
        return GenreResource::collection($genres);
    }

    public function show(string $slug): GenreResource
    {
        $genre = Genre::findOrFailCustom($slug);

        return new GenreResource($genre);
    }
}
