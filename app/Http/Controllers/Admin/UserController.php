<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = User::with(['roles.permissions'])->latest()->paginate(10);

        return UserResource::collection($users);
    }
    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);

        $user->load(['roles.permissions']);

        return new UserResource($user);
    }
}
