<?php

namespace App\Policies;

use App\User;
use App\Claimside;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClaimsidePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the claimside.
     *
     * @param  \App\User  $user
     * @param  \App\Claimside  $claimside
     * @return mixed
     */
    public function view(User $user, Claimside $claimside)
    {
        return true;
    }

    /**
     * Determine whether the user can create claimsides.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the claimside.
     *
     * @param  \App\User  $user
     * @param  \App\Claimside  $claimside
     * @return mixed
     */
    public function update(User $user, Claimside $claimside)
    {
        return $claimside->op_id === $user->id;
    }

    /**
     * Determine whether the user can delete the claimside.
     *
     * @param  \App\User  $user
     * @param  \App\Claimside  $claimside
     * @return mixed
     */
    public function delete(User $user, Claimside $claimside)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the claimside.
     *
     * @param  \App\User  $user
     * @param  \App\Claimside  $claimside
     * @return mixed
     */
    public function restore(User $user, Claimside $claimside)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the claimside.
     *
     * @param  \App\User  $user
     * @param  \App\Claimside  $claimside
     * @return mixed
     */
    public function forceDelete(User $user, Claimside $claimside)
    {
        return false;
    }
}
