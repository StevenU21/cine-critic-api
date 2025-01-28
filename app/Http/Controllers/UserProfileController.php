<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserProfileController extends Controller
{
    use AuthorizesRequests;

    public function index(): UserProfileResource
    {
        $this->authorize('viewAny', User::class);
        $user = auth()->user()->load(['reviews.movie', 'roles']);

        return new UserProfileResource($user);
    }

    public function show(int $id): UserProfileResource
    {
        $user = User::findOrFailCustom($id)->load(['reviews.movie', 'roles']);
        $this->authorize('view', $user);

        return new UserProfileResource($user);
    }
}
