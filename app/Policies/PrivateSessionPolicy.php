<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PrivateSession;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrivateSessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any private sessions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the private session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\PrivateSession  $privateSession
     * @return mixed
     */
    public function view(User $user, PrivateSession $privateSession)
    {
        return true;
    }

    /**
     * Determine whether the user can create private sessions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === 'coach' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the private session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\PrivateSession  $privateSession
     * @return mixed
     */
    public function update(User $user, PrivateSession $privateSession)
    {
        return (($user->role === 'coach')) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the private session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\PrivateSession  $privateSession
     * @return mixed
     */
    public function delete(User $user, PrivateSession $privateSession)
    {
        return (($user->role === 'coach')) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the private session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\PrivateSession  $privateSession
     * @return mixed
     */
    public function restore(User $user, PrivateSession $privateSession)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the private session.
     *
     * @param  \App\Models\User  $user
     * @param  \App\PrivateSession  $privateSession
     * @return mixed
     */
    public function forceDelete(User $user, PrivateSession $privateSession)
    {
        return false;
    }

    public function requestSession(User $user, PrivateSession $privateSession)
    {
        return ($user->role === 'member');
    }

    public function cancelSession(User $user, PrivateSession $privateSession)
    {
        return ($user->role === 'member') && ($privateSession->members->contains($user->member));
    }
    public function rejectSession(User $user, PrivateSession $privateSession)
    {
        return ($user->role === 'admin');
    }
    public function acceptSession(User $user, PrivateSession $privateSession)
    {
        return ($user->role === 'admin');
    }
}
