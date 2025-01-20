<?php

namespace App\Policies;

use App\Models\Genre;
use App\Models\User;
use App\Exceptions\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class GenrePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if (!$user->hasPermissionTo('view genres')) {
            throw new AuthorizationException();
        }
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Genre $genre): bool
    {
        if (!$user->hasPermissionTo('view genres')) {
            throw new AuthorizationException();
        }
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (!$user->hasPermissionTo('create genres')){
            throw new AuthorizationException();
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Genre $genre): bool
    {
        if (!$user->hasAllPermissions('update genres')){}
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Genre $genre): bool
    {
        return false;
    }
}
