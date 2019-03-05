<?php

namespace App\Policies;

use App\User;
use App\Claimrelation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Carbon;

class ClaimrelationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the claimrelation.
     *
     * @param  \App\User  $user
     * @param  \App\Claimrelation  $claimrelation
     * @return mixed
     */
    public function view(User $user, Claimrelation $claimrelation)
    {
        return true;
    }

    /**
     * Determine whether the user can create claimrelations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the claimrelation.
     *
     * @param  \App\User  $user
     * @param  \App\Claimrelation  $claimrelation
     * @return mixed
     */
    public function update(User $user, Claimrelation $claimrelation)
    {
        return $claimrelation->op_id === $user->id;
    }

    /**
     * Determine whether the user can delete the claimrelation.
     *
     * @param  \App\User  $user
     * @param  \App\Claimrelation  $claimrelation
     * @return mixed
     */
    public function delete(User $user, Claimrelation $claimrelation)
    {
        if ($claimrelation->op_id === $user->id) {
            return Carbon::now()->addMinutes(5)->gte($claimrelation->created_at);
        }
        return false;
    }

    /**
     * Determine whether the user can restore the claimrelation.
     *
     * @param  \App\User  $user
     * @param  \App\Claimrelation  $claimrelation
     * @return mixed
     */
    public function restore(User $user, Claimrelation $claimrelation)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the claimrelation.
     *
     * @param  \App\User  $user
     * @param  \App\Claimrelation  $claimrelation
     * @return mixed
     */
    public function forceDelete(User $user, Claimrelation $claimrelation)
    {
        return false;
    }
}
