<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any meals.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the meal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function view(User $user, Meal $meal)
    {
        //
    }

    /**
     * Determine whether the user can create meals.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === 'nutritionist';
    }

    /**
     * Determine whether the user can update the meal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->role === 'nutritionist';
    }

    /**
     * Determine whether the user can delete the meal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->role === 'nutritionist';
    }

    /**
     * Determine whether the user can restore the meal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function restore(User $user, Meal $meal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the meal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function forceDelete(User $user, Meal $meal)
    {
        //
    }
}
