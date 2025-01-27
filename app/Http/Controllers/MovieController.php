<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Movie::class);

        $query = Movie::with(['genre', 'director', 'ratingAverage', 'reviewsCount']);

        if ($request->has('genre')) {
            $query->where('genre_id', $request->genre);
        }

        if ($request->has('director')) {
            $query->where('director_id', $request->director);
        }

        if ($request->has('year')) {
            $year = Carbon::createFromFormat('Y', $request->year)->year;
            $query->whereYear('release_date', $year);
        }

        $this->applySorting($query, $request);

        $perPage = $request->get('per_page', 10);
        $movies = $query->paginate($perPage);

        return MovieResource::collection($movies);
    }

    private function applySorting($query, Request $request)
    {
        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->get('sort_order', 'asc');

            if ($sortBy === 'rating') {
                $query->select('movies.*')
                    ->leftJoin('reviews', 'movies.id', '=', 'reviews.movie_id')
                    ->groupBy('movies.id')
                    ->orderByRaw('AVG(reviews.rating) ' . $sortOrder);
            } elseif ($sortBy === 'reviews_count') {
                $query->select('movies.*')
                    ->leftJoin('reviews', 'movies.id', '=', 'reviews.movie_id')
                    ->groupBy('movies.id')
                    ->orderByRaw('COUNT(reviews.id) ' . $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }
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
