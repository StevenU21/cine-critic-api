<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function counts()
    {
        $usersCount = User::count();
        $moviesCount = Movie::count();
        $reviewsCount = Review::count();

        return response()->json([
            'users' => $usersCount,
            'movies' => $moviesCount,
            'reviews' => $reviewsCount,
        ]);
    }

    public function topRatedMovies(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        $movies = Movie::with(['ratingAverage', 'reviewsCount'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereHas('reviews', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('release_date', [$startDate, $endDate]);
                });
            })
            ->get()
            ->sortByDesc(function ($movie) {
                return $movie->ratingAverage->aggregate ?? 0;
            })
            ->take(5)
            ->map(function ($movie) {
                return [
                    'title' => $movie->title,
                    'average_rating' => $movie->ratingAverage->aggregate ?? 0,
                    'reviews_count' => $movie->reviewsCount->count ?? 0,
                ];
            });

        return response()->json($movies);
    }

    public function topUsers()
    {
        $users = User::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'reviews_count' => $user->reviews_count,
                ];
            });

        return response()->json($users);
    }

    public function topGenres()
    {
        $genres = Genre::withCount('movies')
            ->orderBy('movies_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($genre) {
                return [
                    'name' => $genre->name,
                    'movies_count' => $genre->movies_count,
                ];
            });

        return response()->json($genres);
    }

    public function topDirectors()
    {
        $directors = Director::withCount('movies')
            ->orderBy('director_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($director) {
                return [
                    'name' => $director->name,
                    'movies_count' => $director->director_count,
                ];
            });

        return response()->json($directors);
    }

    public function recentMovies()
    {
        $movies = Movie::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($movie) {
                return [
                    'title' => $movie->title,
                    'release_date' => $movie->release_date,
                ];
            });

        return response()->json($movies);
    }

    public function recentReviews()
    {
        $reviews = Review::with('movie', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($review) {
                return [
                    'movie_title' => $review->movie->title,
                    'content' => $review->content,
                    'rating' => $review->rating,
                    'user' => $review->user->name,
                ];
            });

        return response()->json($reviews);
    }

    public function recentUsers()
    {
        $users = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            });

        return response()->json($users);
    }
}
