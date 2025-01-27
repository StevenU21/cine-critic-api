<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Http\Resources\MovieResource;
use App\Models\User;
use App\Notifications\CreatedMovieNotification;
use App\Services\ImageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
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

    public function store(MovieRequest $request, ImageService $imageService): MovieResource
    {
        $this->authorize('create', Movie::class);

        $movie = Movie::create($request->validated() +
            [
                'director_id' => $request->director_id,
                'genre_id' => $request->genre_id,
            ]);

        if ($request->hasFile('cover_image')) {
            $movie->cover_image = $imageService->storeImage($request->file('cover_image'), $movie->title, $movie->id, 'movies_images');
            $movie->save();
        }

        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new CreatedMovieNotification($movie, $user));
        }

        return new MovieResource($movie);
    }

    public function update(MovieRequest $request, int $id, ImageService $imageService): MovieResource
    {
        $movie = Movie::findOrFailCustom($id);
        $this->authorize('update', $movie);

        $movie->update($request->validated() + [
            'director_id' => $request->director_id,
            'genre_id' => $request->genre_id,
        ]);

        if ($request->hasFile('cover_image')) {
            if ($movie->cover_image) {
                $imageService->deleteImage($movie->cover_image);
            }
            $movie->cover_image = $imageService->storeImage($request->file('cover_image'), $movie->title, $movie->id, 'movies_images');
            $movie->save();
        }

        return new MovieResource($movie);
    }

    public function destroy(int $id, ImageService $imageService): JsonResponse
    {
        $movie = Movie::findOrFailCustom($id);
        $this->authorize('delete', $movie);

        if ($movie->cover_image) {
            $imageService->deleteImage($movie->cover_image);
        }

        $movie->delete();

        return response()->json(null, 204);
    }
}
