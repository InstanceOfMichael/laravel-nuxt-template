<?php

namespace App\Policies;

use App\User;
use App\Allowedquestionside;
use Illuminate\Auth\Access\HandlesAuthorization;

class AllowedquestionsidePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the allowedquestionside.
     *
     * @param  \App\User  $user
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return mixed
     */
    public function view(User $user, Allowedquestionside $allowedquestionside)
    {
        return true;
    }

    /**
     * Determine whether the user can create allowedquestionsides.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the allowedquestionside.
     *
     * @param  \App\User  $user
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return mixed
     */
    public function update(User $user, Allowedquestionside $allowedquestionside)
    {
        return $allowedquestionside->op_id === $user->id;
    }

    /**
     * Determine whether the user can delete the allowedquestionside.
     *
     * @param  \App\User  $user
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return mixed
     */
    public function delete(User $user, Allowedquestionside $allowedquestionside)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the allowedquestionside.
     *
     * @param  \App\User  $user
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return mixed
     */
    public function restore(User $user, Allowedquestionside $allowedquestionside)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the allowedquestionside.
     *
     * @param  \App\User  $user
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return mixed
     */
    public function forceDelete(User $user, Allowedquestionside $allowedquestionside)
    {
        return false;
    }
}
