<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any announcements.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the announcement.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Announcement  $announcement
     * @return mixed
     */
    public function view(User $user, Announcement $announcement)
    {
        return true;
    }

    /**
     * Determine whether the user can create announcements.
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
     * Determine whether the user can update the announcement.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Announcement  $announcement
     * @return mixed
     */
    public function update(User $user, Announcement $announcement)
    {
        if($user->role == "admin")
            {
                return true ;
            }
        else
            return false;
    }

    /**
     * Determine whether the user can delete the announcement.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Announcement  $announcement
     * @return mixed
     */
    public function delete(User $user, Announcement $announcement)
    {
        if($user->role == "admin")
            {
                return true ;
            }
        else
            return false;
    }

   
}
