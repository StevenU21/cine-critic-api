<?php

namespace App\Policies;

use App\Exceptions\AuthorizationException;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DirectorPolicy
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
        if (!$user->hasPermissionTo('read directors')) {
            throw new AuthorizationException();
        }
        return true;
    }

    public function view(User $user): bool
    {
        if (!$user->hasPermissionTo('read directors')) {
            throw new AuthorizationException();
        }
        return true;
    }

    public function create(User $user): bool
    {
        if (!$user->hasPermissionTo('create directors')) {
            throw new AuthorizationException();
        }
        return true;
    }

    public function update(User $user): bool
    {
        if (!$user->hasPermissionTo('update directors')) {
            throw new AuthorizationException();
        }
        return true;
    }

    public function delete(User $user): bool
    {
        if (!$user->hasPermissionTo('delete directors')) {
            throw new AuthorizationException();
        }
        return true;
    }
}
