<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load(['reviews.movie', 'roles']);

        return new UserProfileResource($user);
    }
}
