<?php

namespace App\Policies;

use App\User;
use App\Groupmembership;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupmembershipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the group.
     *
     * @param  \App\User  $user
     * @param  \App\Groupmembership  $groupmembership
     * @return mixed
     */
    public function view(User $user, Groupmembership $groupmembership)
    {
        return true;
    }

    /**
     * Determine whether the user can create groups.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param  \App\User  $user
     * @param  \App\Groupmembership  $groupmembership
     * @return mixed
     */
    public function update(User $user, Groupmembership $groupmembership)
    {
        return $groupmembership->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param  \App\User  $user
     * @param  \App\Groupmembership  $groupmembership
     * @return mixed
     */
    public function delete(User $user, Groupmembership $groupmembership)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the group.
     *
     * @param  \App\User  $user
     * @param  \App\Groupmembership  $groupmembership
     * @return mixed
     */
    public function restore(User $user, Groupmembership $groupmembership)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the group.
     *
     * @param  \App\User  $user
     * @param  \App\Groupmembership  $groupmembership
     * @return mixed
     */
    public function forceDelete(User $user, Groupmembership $groupmembership)
    {
        return false;
    }
}
