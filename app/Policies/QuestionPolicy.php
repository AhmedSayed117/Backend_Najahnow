<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any questions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the question.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Question  $question
     * @return mixed
     */
    public function view(User $user, Question $question)
    {
        return true;
    }

    /**
     * Determine whether the user can create questions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the question.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Question  $question
     * @return mixed
     */
    public function update(User $user, Question $question)
    {
        //dd($question->user_id,$user->id);
        if($question->user_id==$user->id)
        {
            return true;
        }
        else
            return false;
    }

    /**
     * Determine whether the user can delete the question.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Question  $question
     * @return mixed
     */
    public function delete(User $user, Question $question)
    {
        if($question->user_id==$user->id)
        {
            return true;
        }
        else
            return false;
    }


}
