<?php

namespace App\Policies;

use App\Models\User;
use App\Exceptions\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;


class PermissionPolicy
{
    use HandlesAuthorization;
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
}
