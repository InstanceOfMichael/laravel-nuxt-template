<?php

namespace App\Policies;

use App\User;
use App\Linkdomain;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkdomainPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the linkdomain.
     *
     * @param  \App\User  $user
     * @param  \App\Linkdomain  $linkdomain
     * @return mixed
     */
    public function view(User $user, Linkdomain $linkdomain)
    {
        //
    }

    /**
     * Determine whether the user can create linkdomains.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the linkdomain.
     *
     * @param  \App\User  $user
     * @param  \App\Linkdomain  $linkdomain
     * @return mixed
     */
    public function update(User $user, Linkdomain $linkdomain)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the linkdomain.
     *
     * @param  \App\User  $user
     * @param  \App\Linkdomain  $linkdomain
     * @return mixed
     */
    public function delete(User $user, Linkdomain $linkdomain)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the linkdomain.
     *
     * @param  \App\User  $user
     * @param  \App\Linkdomain  $linkdomain
     * @return mixed
     */
    public function restore(User $user, Linkdomain $linkdomain)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the linkdomain.
     *
     * @param  \App\User  $user
     * @param  \App\Linkdomain  $linkdomain
     * @return mixed
     */
    public function forceDelete(User $user, Linkdomain $linkdomain)
    {
        return false;
    }
}
