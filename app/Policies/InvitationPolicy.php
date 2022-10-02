<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any invitations.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->role == "admin")
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can view the invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function view(User $user, Invitation $invitation)
    {
        if($user->role == "admin")
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can create invitations.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->role == "member")
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can update the invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function update(User $user, Invitation $invitation)
    {
        if($invitation->user_id==$user->id)
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can delete the invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function delete(User $user, Invitation $invitation)
    {
        if($invitation->user_id==$user->id)
            {
                return true ;
            }
        else
            return false;
    }

    
}
