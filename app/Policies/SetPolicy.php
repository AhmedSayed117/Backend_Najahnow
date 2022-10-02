<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Set;
use Illuminate\Auth\Access\HandlesAuthorization;

class SetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any sets.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the set.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Set  $set
     * @return mixed
     */
    public function view(User $user, Set $set)
    {
        return true;
    }

    /**
     * Determine whether the user can create sets.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === 'coach' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the set.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Set  $set
     * @return mixed
     */
    public function update(User $user, Set $set)
    {
        return (($user->role === 'coach')) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the set.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Set  $set
     * @return mixed
     */
    public function delete(User $user, Set $set)
    {
        return (($user->role === 'coach')) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the set.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Set  $set
     * @return mixed
     */
    public function restore(User $user, Set $set)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the set.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Set  $set
     * @return mixed
     */
    public function forceDelete(User $user, Set $set)
    {
        return false;
    }
}
