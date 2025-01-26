<?php

namespace App\Policies;

use App\Exceptions\AuthorizationException;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        if (!$user->hasPermissionTo('read reviews')) {
            throw new AuthorizationException();
        }
        return true;
    }

    public function view(User $user): bool
    {
        if (!$user->hasPermissionTo('read reviews')) {
            throw new AuthorizationException();
        }
        return true;
    }

    public function create(User $user): bool
    {
        if (!$user->hasPermissionTo('create reviews')) {
            throw new AuthorizationException();
        }
        return true;
    }

    public function update(User $user, Review $review): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('moderator')) {
            return true;
        }

        if ($user->hasRole('reviewer') && $user->id === $review->user_id) {
            return true;
        }

        throw new AuthorizationException();
    }

    public function delete(User $user, Review $review): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('moderator')) {
            return true;
        }

        if ($user->hasRole('reviewer') && $user->id === $review->user_id) {
            return true;
        }

        throw new AuthorizationException();
    }
}
