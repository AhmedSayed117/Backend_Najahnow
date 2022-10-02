<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NutritionistSession;
use App\Models\Nutritionist;
use Illuminate\Auth\Access\HandlesAuthorization;

class NutritionistSessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any nutritionist sessions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the nutritionist session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\NutritionistSession  $nutritionistSession
     * @return mixed
     */
    public function view(User $user, NutritionistSession $nutritionistSession)
    {
        //
    }

    /**
     * Determine whether the user can create nutritionist sessions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function store(User $user)
    {
        return $user->role == 'nutritionist' || $user->role == 'admin' ;
    }

    /**
     * Determine whether the user can update the nutritionist session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\NutritionistSession  $nutritionistSession
     * @return mixed
     */
    public function update(User $user, NutritionistSession $nutritionistSession)
    {
        return $user->role == 'nutritionist' || $user->role == 'admin' ;
    }

    /**
     * Determine whether the user can delete the nutritionist session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\NutritionistSession  $nutritionistSession
     * @return mixed
     */
    public function delete(User $user, NutritionistSession $nutritionistSession)
    {
        //
    }

    /**
     * Determine whether the user can restore the nutritionist session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\NutritionistSession  $nutritionistSession
     * @return mixed
     */
    public function restore(User $user, NutritionistSession $nutritionistSession)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the nutritionist session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\NutritionistSession  $nutritionistSession
     * @return mixed
     */
    public function forceDelete(User $user, NutritionistSession $nutritionistSession)
    {
        //
    }
}
