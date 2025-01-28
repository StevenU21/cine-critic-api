<?php

namespace App\Policies;

use App\Exceptions\AuthorizationException;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserProfilePolicy
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
        if (!$user->hasPermissionTo('read user_profiles')) {
            throw new AuthorizationException();
        }
        return true;
    }

    public function view(User $user, User $profile): bool
    {
        if (!$user->hasPermissionTo('read user_profiles')) {
            throw new AuthorizationException();
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('moderator')) {
            if ($profile->hasRole('admin')) {
                throw new AuthorizationException();
            }
            return true;
        }

        if ($user->hasRole('reviewer')) {
            if ($profile->hasRole('admin') || $profile->hasRole('moderator')) {
                throw new AuthorizationException();
            }
            return true;
        }

        return false;
    }
}
