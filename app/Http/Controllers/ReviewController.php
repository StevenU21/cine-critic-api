<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Review::class);
        $reviews = Review::with(['movie', 'user'])->latest()->paginate(10);
        return ReviewResource::collection($reviews);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ReviewResource
    {
        $this->authorize('view', Review::class);
        $review = Review::findOrFailCustom($id);
        return new ReviewResource($review);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request): ReviewResource
    {
        $this->authorize('create', Review::class);

        $review = Review::create($request->validated() + [
            'movie_id' => $request->movie_id,
            'user_id' => Auth::id(),
        ]);

        return new ReviewResource($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewRequest $request, int $id): ReviewResource
    {
        $review = Review::findOrFailCustom($id);
        $this->authorize('update', $review);

        $review->update($request->validated() + [
            'movie_id' => $request->movie_id,
            'user_id' => $request->user_id,
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
