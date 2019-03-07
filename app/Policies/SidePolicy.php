<?php

namespace App\Policies;

use App\User;
use App\Side;
use Illuminate\Auth\Access\HandlesAuthorization;

class SidePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the side.
     *
     * @param  \App\User  $user
     * @param  \App\Side  $side
     * @return mixed
     */
    public function view(User $user, Side $side)
    {
        //
    }

    /**
     * Determine whether the user can create sides.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the side.
     *
     * @param  \App\User  $user
     * @param  \App\Side  $side
     * @return mixed
     */
    public function update(User $user, Side $side)
    {
        //
    }

    /**
     * Determine whether the user can delete the side.
     *
     * @param  \App\User  $user
     * @param  \App\Side  $side
     * @return mixed
     */
    public function delete(User $user, Side $side)
    {
        //
    }

    /**
     * Determine whether the user can restore the side.
     *
     * @param  \App\User  $user
     * @param  \App\Side  $side
     * @return mixed
     */
    public function restore(User $user, Side $side)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the side.
     *
     * @param  \App\User  $user
     * @param  \App\Side  $side
     * @return mixed
     */
    public function forceDelete(User $user, Side $side)
    {
        //
    }
}
