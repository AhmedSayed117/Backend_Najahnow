<?php

namespace App\Policies;

use App\Classes;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassesPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {

    }

    public function view(User $user, Classes $classes)
    {
        //
    }

    public function store(User $user)
    {
        return $user->role == 'coach' || $user->role == 'admin';
    }

    /**
     * Determine whether the user can update the classes.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Classes  $classes
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->role == 'coach' || $user->role == 'admin';
    }

    /**
     * Determine whether the user can delete the classes.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Classes  $classes
     * @return mixed
     */
    public function delete(User $user, Classes $classes)
    {
        return $user->role == 'coach' || $user->role == 'admin';
    }

    /**
     * Determine whether the user can restore the classes.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Classes  $classes
     * @return mixed
     */
    public function restore(User $user, Classes $classes)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the classes.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Classes  $classes
     * @return mixed
     */
    public function forceDelete(User $user, Classes $classes)
    {
        //
    }
}
