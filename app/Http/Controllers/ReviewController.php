<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function general_index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Review::class);
        $reviews = Review::with(['movie', 'user'])->latest()->paginate(10);
        return ReviewResource::collection($reviews);
    }

    /**
     * Display the specified resource.
     */
    public function general_show(int $id): ReviewResource
    {
        $this->authorize('view', Review::class);
        $review = Review::findOrFailCustom($id);
        return new ReviewResource($review);
    }

    public function index(int $movieId): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Review::class);
        $reviews = Review::where('movie_id', $movieId)->with(['movie', 'user'])->latest()->paginate(10);
        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request, $movieId): ReviewResource
    {
        $this->authorize('create', Review::class);

        $movie = Movie::findOrFailCustom($movieId);

        $review = Review::create($request->validated() + [
            'movie_id' => $movie->id,
            'user_id' => Auth::id(),
        ]);

        return new ReviewResource($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewRequest $request, int $movieId, int $reviewId): ReviewResource
    {
        $review = Review::findOrFailCustom($reviewId);
        $this->authorize('update', $review);

        $movie = Movie::findOrFailCustom($movieId);

        $review->update($request->validated() + [
            'movie_id' => $movie->id,
            'user_id' => Auth::id(),
        ]);

        return new ReviewResource($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $review = Review::findOrFailCustom($id);
        $this->authorize('delete', $review);
        $review->delete();

        return response()->json(null, 204);
    }
}
