<?php

namespace App\Policies;

use App\Models\FitnessSummary;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FitnessSummaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any fitness summaries.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the fitness summary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FitnessSummary  $fitnessSummary
     * @return mixed
     */
    public function view(User $user, FitnessSummary $fitnessSummary)
    {
        //
    }

    /**
     * Determine whether the user can create fitness summaries.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === 'nutritionist' || $user->role === 'member';
    }

    /**
     * Determine whether the user can update the fitness summary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FitnessSummary  $fitnessSummary
     * @return mixed
     */
    public function update(User $user, FitnessSummary $fitnessSummary)
    {
        return $user->role === 'nutritionist' || $user->member>id === $fitnessSummary->member_id;
    }

    /**
     * Determine whether the user can delete the fitness summary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FitnessSummary  $fitnessSummary
     * @return mixed
     */
    public function delete(User $user, FitnessSummary $fitnessSummary)
    {
        return $user->role === 'nutritionist' || $user->member>id === $fitnessSummary->member_id;
    }

    /**
     * Determine whether the user can restore the fitness summary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FitnessSummary  $fitnessSummary
     * @return mixed
     */
    public function restore(User $user, FitnessSummary $fitnessSummary)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the fitness summary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FitnessSummary  $fitnessSummary
     * @return mixed
     */
    public function forceDelete(User $user, FitnessSummary $fitnessSummary)
    {
        //
    }
}
