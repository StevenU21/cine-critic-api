<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;

class UserProfileController extends Controller
{
    public function index(): UserProfileResource
    {
        $user = auth()->user()->load(['reviews.movie', 'roles']);

        return new UserProfileResource($user);
    }
}
