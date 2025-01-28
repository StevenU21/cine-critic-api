<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
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

        $movies = Movie::with('reviews')
            ->whereHas('reviews', function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            })
            ->get()
            ->sortByDesc(function ($movie) {
                return $movie->reviews->avg('rating');
            })
            ->take(5);

        return response()->json($movies);
    }

    public function topUsers()
    {
        $users = User::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->limit(5)
            ->get();

        return response()->json($users);
    }

    public function topGenres()
    {
        $genres = Movie::select('genre', \DB::raw('count(*) as total'))
            ->groupBy('genre')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return response()->json($genres);
    }

    public function topDirectors()
    {
        $directors = Movie::select('director', \DB::raw('count(*) as total'))
            ->groupBy('director')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return response()->json($directors);
    }

    public function recentMovies()
    {
        $movies = Movie::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($movies);
    }

    public function recentReviews()
    {
        $reviews = Review::with('movie')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($reviews);
    }

    public function recentUsers()
    {
        $users = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($users);
    }
}
