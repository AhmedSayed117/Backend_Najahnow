<?php

namespace App\Policies;

use App\Models\User;
use App\Supplementary;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplementaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any supplementaries.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the supplementary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Supplementary  $supplementary
     * @return mixed
     */
    public function view(User $user, Supplementary $supplementary)
    {
        return true;
    }

    /**
     * Determine whether the user can create supplementaries.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->role == "admin")
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can update the supplementary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Supplementary  $supplementary
     * @return mixed
     */
    public function update(User $user, Supplementary $supplementary)
    {
        if($user->role == "admin")
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can delete the supplementary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Supplementary  $supplementary
     * @return mixed
     */
    public function delete(User $user, Supplementary $supplementary)
    {
        if($user->role == "admin")
            {
                return true ;
            }
        else
            return false;
    }

    
}
