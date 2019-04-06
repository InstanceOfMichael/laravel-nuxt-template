<?php

namespace App\Policies;

use App\User;
use App\Claimtopic;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClaimtopicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the claimtopic.
     *
     * @param  \App\User  $user
     * @param  \App\Claimtopic  $claimtopic
     * @return mixed
     */
    public function view(User $user, Claimtopic $claimtopic)
    {
        return true;
    }

    /**
     * Determine whether the user can create claimtopics.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the claimtopic.
     *
     * @param  \App\User  $user
     * @param  \App\Claimtopic  $claimtopic
     * @return mixed
     */
    public function update(User $user, Claimtopic $claimtopic)
    {
        return $claimtopic->op_id === $user->id;
    }

    /**
     * Determine whether the user can delete the claimtopic.
     *
     * @param  \App\User  $user
     * @param  \App\Claimtopic  $claimtopic
     * @return mixed
     */
    public function delete(User $user, Claimtopic $claimtopic)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the claimtopic.
     *
     * @param  \App\User  $user
     * @param  \App\Claimtopic  $claimtopic
     * @return mixed
     */
    public function restore(User $user, Claimtopic $claimtopic)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the claimtopic.
     *
     * @param  \App\User  $user
     * @param  \App\Claimtopic  $claimtopic
     * @return mixed
     */
    public function forceDelete(User $user, Claimtopic $claimtopic)
    {
        return false;
    }
}
