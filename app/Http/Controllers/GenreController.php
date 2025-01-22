<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use App\Http\Resources\GenreResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GenreController extends Controller
{
    use AuthorizesRequests;

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Genre::class);
        $genres = Genre::latest()->paginate(10);

        return GenreResource::collection($genres);
    }

    public function show(int $id): GenreResource
    {
        $genre = Genre::findOrFailCustom($id);
        $this->authorize('view', $genre);

        return new GenreResource($genre);
    }

    public function store(GenreRequest $request): GenreResource
    {
        $this->authorize('create', Genre::class);
        $genre = Genre::create($request->validated());

        return new GenreResource($genre);
    }

    public function update(GenreRequest $request, int $id): GenreResource
    {
        $genre = Genre::findOrFailCustom($id);
        $this->authorize('update', $genre);
        $genre->update($request->validated());

        return new GenreResource($genre);
    }

    public function destroy(int $id): JsonResponse
    {
        $genre = Genre::findOrFailCustom($id);
        $this->authorize('delete', $genre);
        $genre->delete();

        return response()->json([
            'message' => 'Resource deleted successfully',
        ], 200);
    }
}
