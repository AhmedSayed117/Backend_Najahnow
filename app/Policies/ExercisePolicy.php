<?php

namespace App\Policies;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExercisePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any exercises.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the exercise.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Exercise  $exercise
     * @return mixed
     */
    public function view(User $user, Exercise $exercise)
    {
        return true;
    }

    /**
     * Determine whether the user can create exercises.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === 'coach' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the exercise.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Exercise  $exercise
     * @return mixed
     */
    public function update(User $user, Exercise $exercise)
    {
        return (($user->role === 'coach')) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the exercise.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Exercise  $exercise
     * @return mixed
     */
    public function delete(User $user, Exercise $exercise)
    {
        return (($user->role === 'coach')) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the exercise.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Exercise  $exercise
     * @return mixed
     */
    public function restore(User $user, Exercise $exercise)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the exercise.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Exercise  $exercise
     * @return mixed
     */
    public function forceDelete(User $user, Exercise $exercise)
    {
        return false;
    }
}
