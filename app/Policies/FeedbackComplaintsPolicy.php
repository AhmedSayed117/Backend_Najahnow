<?php

namespace App\Policies;

use App\Models\FeedbackComplaints;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackComplaintsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any feedback complaints.
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
     * Determine whether the user can view the feedback complaints.
     *
     * @param  \App\Models\User  $user
     * @param  \App\FeedbackComplaints  $feedbackComplaints
     * @return mixed
     */
    public function view(User $user, FeedbackComplaints $feedbackComplaints)
    {
        if($user->role == "admin")
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can create feedback complaints.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the feedback complaints.
     *
     * @param  \App\Models\User  $user
     * @param  \App\FeedbackComplaints  $feedbackComplaints
     * @return mixed
     */
    public function update(User $user, FeedbackComplaints $feedbackComplaints)
    {
        if($feedbackComplaints->user_id==$user->id)
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can delete the feedback complaints.
     *
     * @param  \App\Models\User  $user
     * @param  \App\FeedbackComplaints  $feedbackComplaints
     * @return mixed
     */
    public function delete(User $user, FeedbackComplaints $feedbackComplaints)
    {
        if($feedbackComplaints->user_id==$user->id)
            {
                return true ;
            }
        else
            return false;
    }

    
}
