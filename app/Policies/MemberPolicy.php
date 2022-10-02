<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any members.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function view(User $user, Member $member)
    {
        //
    }

    /**
     * Determine whether the user can create members.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function update(User $user, Member $member)
    {
        //
    }
    /**
     * Determine whether the user can update the member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function updatePlan(User $user, Member $member)
    {
        if($user->role === 'nutritionist')
            return $user->id === $member->plan->nutritionist_id;
    }

    public function addPlan(User $user, Member $member)
    {
        if($user->role === 'nutritionist')
            return true;
    }

    public function removePlan(User $user, Member $member)
    {
        if($user->role === 'nutritionist')
            return true;
    }

    public function getActivePlan(User $user, Member $member)
    {
        if($user->role === 'nutritionist')
            return true; //$user->id === $member->plan->nutritionist_id;
        else if($user->role === 'member')
            return $user->member->id === $member->id;
    }

    public function plans(User $user, Member $member)
    {
        if($user->role === 'nutritionist')
            return true; //$user->id === $member->plan->nutritionist_id;
        else if($user->role === 'member')
            return $user->member->id === $member->id; 
    }

    public function storeWorkoutSummary(User $user)
    {
        return $user->role === 'member';
    }

    public function getMyWorkoutSummaries(User $user)
    {
        return $user->role === 'member';
    }

    /**
     * Determine whether the user can delete the member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function delete(User $user, Member $member)
    {
        //
    }

    /**
     * Determine whether the user can restore the member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function restore(User $user, Member $member)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function forceDelete(User $user, Member $member)
    {
        //
    }
}
