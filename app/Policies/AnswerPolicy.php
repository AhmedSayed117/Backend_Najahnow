<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any answers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the answer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Answer  $answer
     * @return mixed
     */
    public function view(User $user, Answer $answer)
    {
        return ture;
    }

    /**
     * Determine whether the user can create answers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the answer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Answer  $answer
     * @return mixed
     */
    public function update(User $user, Answer $answer)
    {
        if($answer->user_id==$user->id)
        {
            return true;
        }
        else 
            return false;
    }

    /**
     * Determine whether the user can delete the answer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Answer  $answer
     * @return mixed
     */
    public function delete(User $user, Answer $answer)
    {
        //dd($user->id,$answer->user_id);
        if($answer->user_id == $user->id)
        {
            return true;
        }
        else 
            return false;
    }

    
}
